@extends('template.master-admin')

@section('title', 'آمار رزرو غذا')
@section('content')

    <div class="card">
        <div class="card-header font-bold p-2">آمار کلی - <small>{{ jdfw_name($booking_date) }}</small></div>
        <div class="card-body p-3">
            @foreach($foods as $food2)
                @foreach($food2 as $food)
                    {{ $food->first()->food->name }} : <b>{{ $food->sum('quantity') }}</b> <br>
                @endforeach
            @endforeach
        </div>
    </div>
    @foreach($tahdigSalons as $salon)
        <div class="card">
            <div class="card-header font-bold">{{ $salon->name }}</div>
            <div class="card-body p-3">
                @foreach($bookings as $booking)
                    @foreach(($booking->where('salon_id', $salon->id))->groupBy('food_id') as $food)
                        {{ $food->first()->food->name }} : <b class="text-secondary">{{ $food->sum('quantity') }}</b>
                        --- گرفته شده: (<b class="text-success">{{ ($food->where('received_at'))->sum('quantity') }}</b>) <br>
                    @endforeach
                @endforeach
            </div>
        </div>
    @endforeach
@endsection
