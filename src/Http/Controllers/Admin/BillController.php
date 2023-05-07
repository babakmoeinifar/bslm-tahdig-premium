<?php

namespace Bslm\Tahdig\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\BillExport;
use App\Http\Controllers\OptionController;
use App\Models\User;
use Bslm\Tahdig\Http\Models\TahdigLogs;
use Bslm\Tahdig\Http\Models\TahdigReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BillController extends Controller
{
    public function lunchUsers()
    {
        is_allowed('billing_view');

        if (request('deactiveUsers')) {
            $data['usersBill'] = DB::select('
                SELECT u.id, u.employee_id, u.deactivated_at, u.settlement_at, u.name, ff.cost, uu.credits,
        
                (ifnull(uu.credits, 0) - Ifnull(ff.cost, 0)) balance
                
                FROM users u
                
                JOIN (SELECT u.id, Sum(d.charge_amount) credits,
                
                Count(*) cday
                
                FROM users u
                
                LEFT JOIN days d ON ( Date_sub(u.settlement_at, INTERVAL 1 day) ) <= d.day
                
                AND (d.user_id = u.id OR d.user_id is null)
                
                AND (u.deactivated_at IS NOT NULL)
                
                GROUP BY u.id, u.started_at) uu
                
                ON u.id = uu.id
                
                
                LEFT JOIN (SELECT u.id,
                
                Sum(tr.price * tr.quantity) cost
                
                FROM users u
                
                LEFT JOIN tahdig_reservations tr
                
                ON u.id = tr.user_id
                
                LEFT JOIN tahdig_bookings tb
                
                ON tr.booking_id = tb.id
                
                WHERE tb.booking_date >= u.settlement_at
                
                GROUP BY u.id) ff
                
                ON u.id = ff.id
                
                WHERE 1
                AND deactivated_at IS NOT NULL
                ORDER BY employee_id asc');
        } else {
            $data['usersBill'] = DB::select('
                SELECT u.id, u.employee_id, u.deactivated_at, u.settlement_at, u.name, ff.cost, uu.credits,
        
                (ifnull(uu.credits, 0) - Ifnull(ff.cost, 0)) balance
                
                FROM users u
                
                JOIN (SELECT u.id, Sum(d.charge_amount) credits,
                
                Count(*) cday
                
                FROM users u
                
                LEFT JOIN days d ON ( Date_sub(u.settlement_at, INTERVAL 1 day) ) <= d.day
                
                AND (d.user_id = u.id OR d.user_id is null)
                
                AND (u.deactivated_at > d.day OR u.deactivated_at IS NULL)
                
                GROUP BY u.id, u.started_at) uu
                
                ON u.id = uu.id
                
                
                LEFT JOIN (SELECT u.id,
                
                Sum(tr.price * tr.quantity) cost
                
                FROM users u
                
                LEFT JOIN tahdig_reservations tr
                
                ON u.id = tr.user_id
                
                LEFT JOIN tahdig_bookings tb
                
                ON tr.booking_id = tb.id
                
                WHERE tb.booking_date >= u.settlement_at
                
                GROUP BY u.id) ff
                
                ON u.id = ff.id
                
                WHERE 1
                AND deactivated_at IS NULL
                ORDER BY employee_id asc');
        }
        // u.contract_type IN ( 12, 13 )


        $data['is_temporary_disabled'] = (int)OptionController::get('disable-tahdig') === 1;
        return view('tahdig::admin.bill.lunch-users', $data);
    }

    public function lunchUserExport()
    {
        is_allowed('billing_view');

        return Excel::download(new BillExport(), 'lunch-users-bills.xlsx');
    }

    public static function getResult()
    {

        is_allowed('billing_view');

        $res = DB::select('
            SELECT u.id,
            u.employee_id,
            u.deactivated_at,
            u.settlement_at,
            u.name,
            ff.cost,
            uu.credits,
            ( uu.credits - Ifnull(ff.cost, 0) ) balance
            FROM   users u
            JOIN(SELECT u.id,
                        Sum(d.charge_amount) credits,
                        Count(*) cday
                    FROM   users u
                        LEFT JOIN days d
                                ON ( Date_sub(u.settlement_at, INTERVAL 1 day) ) <= d.day
                                AND (
                                    d.user_id = u.id
                                    OR
                                    d.user_id is null
                                )
                                AND ( u.deactivated_at > d.day
                                OR u.deactivated_at IS NULL 
                                )
                                
                    GROUP  BY u.id, u.started_at) uu
                ON u.id = uu.id
            LEFT JOIN (SELECT u.id,
                                Sum(tr.price * tr.quantity) cost
                        FROM   `users` u
                                LEFT JOIN tahdig_reservations tr
                                        ON u.id = tr.user_id
                                LEFT JOIN tahdig_bookings tb
                                        ON tr.booking_id = tb.id
                        WHERE  tb.booking_date >= u.settlement_at
                        GROUP  BY u.id) ff
                    ON u.id = ff.id
        WHERE 1
        ORDER BY employee_id asc');
        // u.contract_type IN ( 12, 13 )
        // return $res;
        $return = [];
        foreach ($res as $item) {
            $return[$item->id] = $item;
        }
        return $return;
    }

    public function resetTahdig()
    {

        $data['users'] = $data['users'] = UserController::getList();

        return view('tahdig::admin.bill.reset-tahdig', ['data' => $data]);
    }

    public function resetTahdigSubmit(Request $request)
    {

        is_allowed('billing_view');

        $request->validate([
            'users' => ['required', 'string'],
            'settlement_at' => ['string']
        ]);
        $result = [];

        // get current balance of all users
        $users = self::getResult();
        foreach ($users as $user) {
            $result[$user->id] = [
                'user_id' => $user->id,
                'year' => (int)jdate2("Y", time(), null, null, 'en'),
                'month' => (int)jdate2("m", time(), null, null, 'en'),
                'balance' => $user->balance
            ];
        }

        $settlement_at = Carbon::createFromTimestamp($request->get('settlement_at_alt'))->toDateTimeString();
        $selected_users = $request->get('users') === 'all' ? array_keys($users) : explode(',', $request->get('users'));

        foreach ($selected_users as $user_id) {
            $item = $result[$user_id];

            $row = new TahdigLogs();
            $row->user_id = $item['user_id'];
            $row->year = $item['year'];
            $row->month = $item['month'];
            $row->charge = (double)$item['balance'];
            $row->settlement_at = $settlement_at;
            $row->save();

            // update settlement date
            User::where('id', $user_id)->update(['settlement_at' => $settlement_at]);

        }

        // User::whereNull('deactivated_at')->update(['settlement_at' => Carbon::now()]);

        return redirect("/admin/bills/lunch-users")->with('msg-ok', __('msg.edit_ok'));

        return response()->json([
            'data' => [],
            'code' => 1,
            'message' => 'عملیات با موفقیت انجام شد',
        ]);
    }

    public function restaurants()
    {
        is_allowed('billing_view');

        $data['restaurantsBill'] = TahdigReservation::query()
            ->with('food.restaurant')
            ->join('tahdig_bookings', 'tahdig_reservations.booking_id', 'tahdig_bookings.id')
            ->whereBetween('tahdig_bookings.booking_date', [$data['firstDayOfMonth'], $data['lastDayOfMonth']])
            ->get()
            ->groupBy('food.restaurant_id');

        return view('tahdig::admin.bill.restaurants', $data);
    }
}
