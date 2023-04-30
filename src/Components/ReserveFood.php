<?php

namespace Bslm\Tahdig\Components;

use App\Models\Salon;
use App\Models\User;
use App\Notifications\foodAddedByAdmin;
use Bslm\Tahdig\Http\Models\Food;
use Bslm\Tahdig\Http\Models\TahdigBooking;
use Bslm\Tahdig\Http\Models\TahdigReservation;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ReserveFood extends Component
{
    public $booking;
    public $salons = [];
    public $users = [];
    public $query;

    public $userId;
    public $foodId;
    public $salonId;
    public $quantity = 1;

    public function mount($id)
    {
        $this->booking = TahdigBooking::with(['reservationsForUser', 'foods.restaurant', 'meal'])
            ->findOrFail($id);

        $this->salons = Salon::where('is_active', true)->get();
    }

    protected $rules = [
        'userId' => 'required',
        'foodId' => 'required',
        'salonId' => 'required',
    ];


    public function searchUser()
    {
        $this->users = User::where('name', 'like', '%' . $this->query . '%')
            ->where('deactivated_at', null)
            ->take(10)
            ->get();
    }

    public function submitReserve()
    {
        $this->validate();

        $food = Food::find($this->foodId);
        TahdigReservation::updateOrCreate(
            [
                'user_id' => $this->userId,
                'booking_id' => $this->booking->id,
                'food_id' => $food->id,
                'added_by' => auth()->id(),
            ],
            [
                'quantity' => $this->quantity,
                'price' => $food->price,
                'price_default' => 0,
                'salon_id' => $this->salonId,
                'received_at' => now(),
            ]
        );

        $salonName = Salon::where('id', $this->salonId)->value('name');
        Notification::send([User::find($this->userId)], new foodAddedByAdmin($this->quantity, $food->name, $salonName));

        return redirect()->to('/admin/lunch/reservation/for-user/' . $this->booking->id)->with('msg-ok', __('msg.food_reserved_by_admin'));
    }

    public function render()
    {
        return view('tahdig::components.reserve-food');
    }
}
