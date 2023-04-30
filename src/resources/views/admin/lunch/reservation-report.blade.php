@extends('template.master-admin')

@section('title', 'آمار رزرو غذا')
@section('content')

    <div class="card">
        <div class="card-header font-bold">آمار کلی</div>
        <div class="card-body p-3">
            @foreach($foods as $food)
                {{ $food->first()->food->name }} : {{ $food->sum('quantity') }} <br>
            @endforeach
        </div>
    </div>
    @foreach($tahdigSalons as $salon)
        <div class="card">
            <div class="card-header font-bold">{{ $salon->name }}</div>
            <div class="card-body p-3">
                @foreach(($booking->where('salon_id', $salon->id))->groupBy('food_id') as $food)
                    {{ $food->first()->food->name }} : {{ $food->sum('quantity') }}
                     --- گرفته شده: ({{ ($food->where('received_at'))->sum('quantity') }}) <br>
                @endforeach
            </div>
        </div>
    @endforeach
@endsection
