<?php

namespace Bslm\Tahdig\Http\Controllers\User;

use App\Enums\UserContractTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OptionController;
use App\Models\Comment;
use App\Models\Day;
use App\Models\Salon;
use App\Notifications\foodAddedByAdmin;
use Bslm\Tahdig\Http\Models\Food;
use Bslm\Tahdig\Http\Models\TahdigBooking;
use Bslm\Tahdig\Http\Models\TahdigLogs;
use Bslm\Tahdig\Http\Models\TahdigReservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LunchController extends Controller
{
    public function reserve()
    {
        $bookings = TahdigBooking::with(['reservationsForUser', 'foods.restaurant', 'meal'])
            ->where('booking_date', '>', Carbon::now()->addHours(config('salam.gap_hours')))
            ->orderBy('booking_date', 'asc')->get();

       $data = [];
        foreach ($bookings as $booking) {
            $dayOfWeek = Carbon::parse($booking->booking_date)->format('l');
            if (!isset($data[$dayOfWeek])) {
                $data[$dayOfWeek] = [];
            }

            $data[$dayOfWeek][] = $booking;
        }

        $is_temporary_disabled = (int)OptionController::get('disable-tahdig') === 1;
        $salons = Salon::where('is_active', true)->get();

        return view('tahdig::user.lunch.reserve', compact('data', 'is_temporary_disabled', 'salons'));
    }

    public function reserveSubmit(Request $request)
    {
        $data = $request->except('_token');

        if ((int)OptionController::get('disable-tahdig') === 1) {
            return redirect('lunch/reserve');
        }

        foreach ($data['booking'] as $booking_id => $booking) {
            foreach ($booking as $food_id => $quantity) {
                if ($quantity == 0) {
                    TahdigReservation::where('user_id', auth()->id())
                        ->where('booking_id', $booking_id)
                        ->where('food_id', $food_id)
                        ->delete();
                } else {
                    $food = Food::find($food_id);

                    TahdigReservation::updateOrCreate(
                        [
                            'user_id' => auth()->id(),
                            'booking_id' => $booking_id,
                            'food_id' => $food_id,
                        ],
                        [
                            'quantity' => $quantity,
                            'price' => $food->price,
                            'price_default' => 0,
                            'salon_id' => $data['salon'][$booking_id],
                        ]
                    );
                }
            }
        }

        return redirect('lunch/history');
    }

    public function reserveSubmitAjax(Request $request)
    {
//        $data = $request->except('_token');

        if ((int)OptionController::get('disable-tahdig') === 1) {
            return response('متاسفانه رزرو غذا غیرفعاله.', 406);
        }

//        foreach ($data['booking'] as $booking_id => $booking) {
//            foreach ($booking as $food_id => $quantity) {
        $quantity = $request->qty;
        $booking_id = $request->bookingId;
        $food_id = $request->foodId;
        $salon_id = $request->salonId;

        if ($quantity == 0) {
            TahdigReservation::where('user_id', auth()->id())
                ->where('booking_id', $booking_id)
                ->where('food_id', $food_id)
                ->delete();
        } else {
            $food = Food::find($food_id);

            TahdigReservation::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'booking_id' => $booking_id,
                    'food_id' => $food_id,
                ],
                [
                    'quantity' => $quantity,
                    'price' => $food->price,
                    'price_default' => 0,
                    'salon_id' => $salon_id,
                ]
            );
        }

        return redirect('lunch/history');
    }

    public function history()
    {
        markReadNotification(foodAddedByAdmin::class);

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
            ->where('user_id', auth()->id());

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
            ->where('user_id', auth()->id())
            ->union($logs)
            ->orderBy('date', 'desc')
            ->paginate(30);

        $totalCost = TahdigReservation::with(['booking'])
            ->whereHas('booking', function ($query) {
                $query
                    ->where('booking_date', '>=', auth()->user()->settlement_at);
            })
            ->where('user_id', auth()->id())
            ->sum(DB::raw('price * quantity'));

        $credits = 0;
        if (auth()->user()->contract_type == UserContractTypesEnum::ACTIVE ||
            auth()->user()->contract_type == UserContractTypesEnum::INTERN) {

            $credits = Day::where('day', '>=', (new Carbon(auth()->user()->settlement_at))->subDay())
                ->where('user_id', auth()->user()->id)
                ->sum('charge_amount');

            $credits += Day::where('day', '>=', (new Carbon(auth()->user()->settlement_at))->subDay())
                ->whereNull('user_id')
                ->sum('charge_amount');
        }

        $data['sum'] = $credits - $totalCost;
        $data['is_temporary_disabled'] = (int)OptionController::get('disable-tahdig') === 1;

        return view('tahdig::user.lunch.history', $data);
    }

    public function deleteReservation(TahdigReservation $reservation)
    {
        if ((int)OptionController::get('disable-tahdig') === 1)
            return redirect('lunch/history');

        if (
            (auth()->id() === (int)$reservation->user_id)
            &&
            ($reservation->booking->booking_date > now()->addHours(config('salam.gap_hour'))->startOfDay())
        ) {
            $reservation->delete();
        }

        return back()->with('msg-ok', __('msg.change_ok'));
    }

    public function rateForFood(TahdigReservation $reservation, Request $request)
    {
        $data['reservation'] = $reservation;

        return view('tahdig::user.lunch.rate', $data);
    }

    public function rateForFoodSubmit(TahdigReservation $reservation, Request $request)
    {

        if ((int)OptionController::get('disable-tahdig') === 1)
            return redirect('lunch/history');

        if ($reservation->user_id != auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rate' => 'required|integer|min:1|max:5',
        ]);

        $comment = new Comment();
        $comment->user_id = auth()->id();
        $comment->commentable_id = $reservation->id;
        $comment->commentable_type = get_class($reservation);
        $comment->score = $request->get('rate');
        $comment->comment = $request->get('comment');
        $comment->save();

        return redirect('lunch/history')->with('msg-ok', __('msg.change_ok'));
    }
}
