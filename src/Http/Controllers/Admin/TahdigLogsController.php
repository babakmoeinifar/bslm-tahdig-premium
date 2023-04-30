<?php

namespace Bslm\Tahdig\Http\Controllers\Admin;

use App\Exports\TahdigLogsByUserExport;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Controller;
use Bslm\TahdigBasic\Http\Models\TahdigLogs;
use Bslm\TahdigBasic\Http\Models\TahdigReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TahdigLogsController extends Controller
{

    public static function index(Request $request, $userid = null)
    {

        is_allowed('billing_view');

        $raw = [];
        $data['users'] = $data['users'] = UserController::getList();
        $data['userid'] = $userid;
        $data['logs'] = TahdigLogs::select('tahdig_logs.*', 'users.name as user_name')
            ->join('users', 'users.id', '=', 'user_id')
            ->where('user_id', $userid)
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('tahdig::admin.bill.tahdig-logs', ['data' => $data]);

    }

    public static function logsSubmit(Request $request, $userid = null, $date_start = null, $date_end = null)
    {

        is_allowed('billing_view');

        $request->validate([
            'select_tahdig_logs_user' => 'string',
            'date_start_alt' => 'string',
            'date_end_alt' => 'string',
        ]);

        $ds = $date_start ? $date_start : date("Y-m-d", $request->get('date_start_alt'));
        $de = $date_end ? $date_end : date("Y-m-d", $request->get('date_end_alt'));
        $userid = $userid ? $userid : $request->get('select_tahdig_logs_user');

        $logs = TahdigLogs::select([
            DB::raw('null as id'),
            DB::raw('null as user_id'),
            DB::raw('null as booking_id'),
            DB::raw('null as food_id'),
            DB::raw('tahdig_logs.charge as price'),
            DB::raw('null as quantity'),
            DB::raw('null as salon_id'),
            DB::raw('null as price_default'),
            DB::raw('null as received_at'),
            DB::raw('null as created_at'),
            DB::raw('null as updated_at'),
            DB::raw('null as added_by'),
            DB::raw('null as score_count'),
            DB::raw('null as score_avg'),
            DB::raw('settlement_at as date'),
        ])
            ->where('user_id', $userid)
            ->where('settlement_at', '>', $ds)
            ->where('settlement_at', '<', $de);

        $data['reservations'] = TahdigReservation::with([
            'booking',
            'booking.meal',
            'food.restaurant',
            'salon',
        ])
            ->select([
                DB::raw('tahdig_reservations.id'),
                DB::raw('user_id'),
                DB::raw('booking_id'),
                DB::raw('food_id'),
                DB::raw('price'),
                DB::raw('quantity'),
                DB::raw('salon_id'),
                DB::raw('price_default'),
                DB::raw('received_at'),
                DB::raw('tahdig_reservations.created_at'),
                DB::raw('tahdig_reservations.updated_at'),
                DB::raw('added_by'),
                DB::raw('score_count'),
                DB::raw('score_avg'),
                'tahdig_bookings.booking_date as date',
            ])
            ->join('tahdig_bookings', 'tahdig_bookings.id', '=', 'booking_id')
            ->where('user_id', $userid)
            ->where('tahdig_bookings.booking_date', '>', $ds)
            ->where('tahdig_bookings.booking_date', '<', $de)
            ->union($logs)
            ->orderBy('date', 'desc')
            ->paginate(30);

        $data['users'] = $data['users'] = UserController::getList();
        $data['userid'] = $userid;

        // $url = "admin/bills/tahdig-logs/$userid/$ds/$de";
        // return redirect($url);
        return $date_start ? view('tahdig::admin.bill.tahdig-logs', ['data' => $data]) : redirect("admin/bills/tahdig-logs/$userid/$ds/$de");
        // return view('admin.bill.tahdig-logs', ['data' => $data]);

    }

    public function tahdigLogUserExcelExport()
    {
        is_allowed('billing_view');
        return Excel::download(new TahdigLogsByUserExport(request('userId'), request('date_start'), request('date_end')),
            'tahdig-log-user-'.request('userId').'.xlsx');
    }
}
