<?php

namespace Bslm\Tahdig\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OptionController;
use App\Models\Comment;
use App\Models\Salon;
use App\Models\User;
use Bslm\Tahdig\Http\Models\Food;
use Bslm\Tahdig\Http\Models\Meal;
use Bslm\Tahdig\Http\Models\Restaurant;
use Bslm\Tahdig\Http\Models\TahdigBooking;
use Bslm\Tahdig\Http\Models\TahdigReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LunchController extends Controller
{

    public function charge(Request $request, $year, $month)
    {

        exit('disabled');
        $star = "$year/$month/1";
        $end = circleSearch($year, $month, 1);
        $en = "$end[0]/$end[1]/1";

        $ts = periodDayByDay($star, $en);

        // get active users
        $users = User::select()
            ->whereNull('deactivated_at')
            ->get();

        foreach ($ts as $timestamp) {
            $is_holiday = file_get_contents("https://api.pendarino.com/holidays/isholiday/$timestamp");
            $is_holiday = json_decode($is_holiday, true);
            if ($is_holiday['data']['isholiday']) {
                echo "<p>skipped: " . $is_holiday['data']['date'] . "-" . $is_holiday['data']['text'] . "</p>";
                continue;
            }
            if ($is_holiday['data']['week'] === 'پنجشنبه') {
                echo "<p>skipped: " . $is_holiday['data']['date'] . "-" . $is_holiday['data']['week'] . "</p>";
                continue;
            }

            foreach ($users as $user) {
                $date = Carbon::createFromTimestamp($timestamp)->toDateString();
                DB::table('days')->updateOrInsert(
                    [
                        'user_id' => $user->id,
                        'day' => $date
                    ],
                    [
                        'user_id' => $user->id,
                        'day' => $date,
                        'charge_amount' => 25000
                    ]
                );
            }
        }

    }

    public static function temporaryToggleReserve(Request $request)
    {
        is_allowed('reservation_management');

        $request->validate([
            'toggle' => 'required|string',
        ]);

        $cur = $request['toggle'];
        OptionController::set('disable-tahdig', $cur);

        return response()->json([
            'data' => [],
            'code' => 1,
            'message' => 'عملیات با موفقیت انجام شد',
        ]);

    }

    public function reservationCreate(Request $request)
    {
        is_allowed('reservation_management');

        $data['meals'] = Meal::all();
        $data['foods'] = Food::with(['restaurant'])
            ->whereHas('restaurant', function ($q) {
                $q->where('is_active', '=', 1);
            })->get();

        return view('tahdig::admin.lunch.reservation-create', $data);
    }

    public function reservationCreateSubmit(Request $request)
    {
        is_allowed('reservation_management');

        $request->validate([
            'meal' => 'required|exists:meals,id',
            'foods' => 'array',
            'foods.*' => 'nullable|distinct|exists:foods,id|different:food_main',
        ]);

        $booking = new TahdigBooking();
        $booking->booking_date = Carbon::createFromTimestamp($request->get('date_alt'))->toDateString();
        $booking->meal_id = $request->get('meal');
        $booking->default_food_id = 0;
        $booking->save();

        $booking->foods()->attach($request->get('food_1'), ['for_inter' => $request->has('food_inter_1')]);
        $booking->foods()->attach($request->get('food_2'), ['for_inter' => $request->has('food_inter_2')]);
        $booking->foods()->attach($request->get('food_3'), ['for_inter' => $request->has('food_inter_3')]);
        $booking->foods()->attach($request->get('food_4'), ['for_inter' => $request->has('food_inter_4')]);
        $booking->foods()->attach($request->get('food_5'), ['for_inter' => $request->has('food_inter_5')]);
        $booking->foods()->attach($request->get('food_6'), ['for_inter' => $request->has('food_inter_6')]);
        $booking->foods()->attach($request->get('food_7'), ['for_inter' => $request->has('food_inter_7')]);
        $booking->foods()->attach($request->get('food_8'), ['for_inter' => $request->has('food_inter_8')]);
        $booking->foods()->attach($request->get('food_9'), ['for_inter' => $request->has('food_inter_9')]);
        $booking->foods()->attach($request->get('food_10'), ['for_inter' => $request->has('food_inter_10')]);
        $booking->foods()->attach($request->get('food_11'), ['for_inter' => $request->has('food_inter_11')]);
        $booking->foods()->attach($request->get('food_12'), ['for_inter' => $request->has('food_inter_12')]);
        $booking->foods()->attach($request->get('food_13'), ['for_inter' => $request->has('food_inter_13')]);
        $booking->foods()->attach($request->get('food_14'), ['for_inter' => $request->has('food_inter_14')]);
        $booking->foods()->attach($request->get('food_15'), ['for_inter' => $request->has('food_inter_15')]);

        return redirect()->back()->with('msg-ok', __('msg.change_ok'));
    }

    public function reservation(Request $request)
    {
        is_allowed('reservation_view');

        $data['bookings'] = TahdigBooking::with('meal')
            ->orderBy('booking_date', 'desc')
            ->paginate(20);

        $data['saloons'] = Salon::where('is_active', true)->get();
        $data['is_temporary_disabled'] = (int)OptionController::get('disable-tahdig') === 1;

        return view('tahdig::admin.lunch.reservation', $data);
    }

    public function reservationDetail(Request $request, $booking_id, $saloon_id)
    {
        is_allowed('reservation_view');

        $booking = TahdigBooking::with(['reservations', 'reservations.user', 'reservations.food.restaurant'])
            ->findOrFail($booking_id);

        $data['booking'] = $booking->reservations->where('salon_id', $saloon_id);
        $data['foods'] = $data['booking']->groupBy('food_id');

        return view('tahdig::admin.lunch.reservation-detail', $data);
    }

    public function restaurantCreate(Request $request)
    {
        is_allowed('food_management');

        return view('tahdig::admin.lunch.restaurant-create');
    }

    public function restaurantCreateSubmit(Request $request)
    {
        is_allowed('food_management');

        $request->validate([
            'name' => 'required|string|unique:restaurants,name',
        ]);

        $restaurant = new Restaurant();
        $restaurant->name = $request->get('name');
        $restaurant->save();

        return redirect()->back()->with('msg-ok', __('msg.add_ok', ['name' => $request->get('name')]));
    }

    public function restaurants()
    {
        is_allowed('food_view');

        $data['restaurants'] = Restaurant::all();

        $data['restaurantScores'] = TahdigReservation::join('foods', 'foods.id', 'tahdig_reservations.food_id')
            ->join('restaurants', 'restaurants.id', 'foods.restaurant_id')
            ->join('comments', 'comments.commentable_id', 'tahdig_reservations.id')->groupBy('restaurant_id')
            ->where('comments.commentable_type', \Bslm\Tahdig\Http\Models\TahdigReservation::class)
            ->selectRaw('avg(comments.score) as score, foods.restaurant_id')
            ->get();

        return view('tahdig::admin.lunch.restaurant-all', $data);
    }

    public function restaurantEdit(Request $request, $restId)
    {
        is_allowed('food_management');

        $data['restaurant'] = Restaurant::findOrFail($restId);

        return view('tahdig::admin.lunch.restaurant-edit', $data);
    }

    public function restaurantEditSubmit(Request $request)
    {
        is_allowed('food_management');

        $restaurant = Restaurant::find($request->get('id'));
        $request->validate([
            'name' => ['required', 'string', Rule::unique('restaurants')->ignore($restaurant->id)],
            'is_active' => 'required',
        ]);

        $restaurant->name = $request->get('name');
        $restaurant->is_active = $request->get('is_active');
        $restaurant->save();

        return redirect()->back()->with('msg-ok', __('msg.edit_ok'));
    }

    public function restaurantComments(Restaurant $restaurant)
    {
        is_allowed('food_view');

        $data['restaurant'] = $restaurant;
        $data['reservations'] = TahdigReservation::with(['food', 'comments'])
            ->whereHas('comments')
            ->whereHas('food', function ($q) use ($restaurant) {
                $q->where('restaurant_id', $restaurant->id);
            })->paginate(40);

        return view('tahdig::admin.lunch.restaurant-comments', $data);
    }

    public function foodCreate(Request $request)
    {
        is_allowed('food_management');

        $data['restaurants'] = Restaurant::where('is_active', '=', 1)->get();

        return view('tahdig::admin.lunch.food-create', $data);
    }

    public function foodCreateSubmit(Request $request)
    {
        is_allowed('food_management');

        $request->validate([
            'name' => 'required|string',
            'restaurant' => 'required|exists:restaurants,id',
            'price' => 'required|numeric',
        ]);

        $food = new Food();
        $food->name = $request->get('name');
        $food->restaurant_id = $request->get('restaurant');
        $food->price = to_en($request->get('price')); //todo convert number
        $food->save();

        return redirect()->back()->with('msg-ok', __('msg.add_ok', ['name' => $request->get('name')]));
    }

    public function foodEdit(Request $request, $foodId)
    {
        is_allowed('food_management');

        $data['food'] = Food::findOrFail($foodId);

        return view('tahdig::admin.lunch.food-edit', $data);
    }

    public function foodEditSubmit(Request $request)
    {
        is_allowed('food_management');

        $request->validate([
            'id' => 'required|exists:foods,id',
            'price' => 'required|numeric',
        ]);

        $food = Food::find($request->get('id'));
        $food->price = to_en($request->get('price')); //todo convert number
        $food->save();

        return redirect()->back()->with('msg-ok', __('msg.add_ok', ['name' => $request->get('name')]));
    }

    public function foodComments($id)
    {
        is_allowed('food_view');

        $data['comments'] = Comment::where(['commentable_type' => 'Bslm\Tahdig\Http\Models\TahdigReservation'])
            ->leftJoin('tahdig_reservations', 'tahdig_reservations.id', 'comments.commentable_id')
            ->where('tahdig_reservations.food_id', $id)
            ->paginate(40);
        return view('tahdig::admin.lunch.food-comments', $data);
    }

    public function foods()
    {
        is_allowed('food_view');

        $data['foods'] = Food::query()
            ->with(['reservations', 'restaurant'])
            ->whereHas('restaurant', function ($q) {
                $q->where('is_active', '=', 1);
            })
            ->withSum('reservations', 'score_avg')
            ->withSum('reservations', 'score_count')
            ->orderBy('restaurant_id', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return view('tahdig::admin.lunch.food-all', $data);
    }

    public function reserveForUser(Request $request, $id)
    {
        is_allowed('reservation_management');

        return view('tahdig::admin.lunch.reserve-for-user', ['id' => $id]);
    }

    public function reservationReport(Request $request, $booking_id)
    {
        is_allowed('reservation_view');

        $booking = TahdigBooking::with(['reservations', 'reservations.user', 'reservations.food.restaurant'])
            ->findOrFail($booking_id);

        $data['booking'] = $booking->reservations;
        $data['foods'] = $data['booking']->groupBy('food_id');
        $data['tahdigSalons'] = Salon::get();

        return view('tahdig::admin.lunch.reservation-report', $data);
    }
}
