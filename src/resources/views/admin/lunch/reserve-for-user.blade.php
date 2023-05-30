@extends('template.master-admin')

@section('title', 'افزودن غذا برای کاربر')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            @livewire('tahdig::components.reserve-food', ['booking_date' => $booking_date])
        </div>
    </div>
@endsection

