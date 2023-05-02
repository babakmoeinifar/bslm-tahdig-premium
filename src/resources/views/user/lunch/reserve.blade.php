@extends('template.master-user')

@section('title', 'رزرو')

@section('content')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>رزرو غذا</h3>
            </div>
        </div>
    </div>
    @include('template.messages')

    @if($is_temporary_disabled)
        <div class="card ss02">
            <div class="p-5 text-center">
                <h5>متاسفانه رزرو غذا غیرفعاله.</h5>
            </div>
        </div>

    @else
        @php
            $daysOfWeek = [
               'Saturday' => 'شنبه',
               'Sunday' => 'یکشنبه',
               'Monday' => 'دوشنبه',
               'Tuesday' => 'سشنبه',
               'Wednesday' => 'چهارشنبه',
               'Thursday' => 'پنجشنبه',
               'Friday' => 'جمعه',
           ];
        @endphp

        <form action="{{ url('lunch/reserve') }}" method="post">
            @csrf
            <div class="card mt-3 border rounded">
                <nav class="card-header">
                    <ul class="nav nav-tabs border-bottom">
                        @foreach($data as $dayOfWeek => $bookings)
                            @php $dayOfWeekPersian = $daysOfWeek[$dayOfWeek]; @endphp

                            <li class="nav-item">
                                <a class="nav-link @if($loop->first) active @endif"
                                   data-bs-toggle="tab" href="#{{ $dayOfWeek }}"
                                >{{ $dayOfWeekPersian }}
                                </a>
                            </li>
                        @endforeach

                    </ul>
                </nav>

                <div class="tab-content card-body" id="nav-tabContent">

                    @foreach($data as $dayOfWeek => $bookings)
                        <div class="tab-pane fade @if($loop->first) show active @endif"
                             id="{{ $dayOfWeek }}">
                            <h5 class="pt-3">{{ jdfw($bookings[0]->booking_date) }}</h5>
                            <div class="row row-cols-1 row-cols-md-3 mt-4">
                                @foreach($bookings as $booking)
                                    <div class="col mb-4">
                                        <div class="card h-100 border rounded">
                                            @if($bookings[0]->booking_date != $booking->booking_date)
                                                <h6 class="pt-3">{{ jdfw($booking->booking_date) }}</h6>
                                            @endif
                                            <div class="card-body mb-0 pb-0">
                                                <h4 class="card-title ss02 mb-3"
                                                    style="font-size: 1.4rem">{{ $booking->meal->name }}</h4>
                                                <div class="card-text">
                                                    @foreach($booking->foods as $food)
                                                        @if($food->restaurant->is_active)
                                                            @php
                                                                $value = $booking->reservationsForUser()->where('food_id', $food->id)->first();
                                                            @endphp

                                                            <div class="d-flex row" style="align-items: baseline">
                                                                <h6 class="mt-2 col-auto"
                                                                    style="@if(isset($value->quantity) && $value->quantity > 0) color:#ff4501;font-weight:900; @else font-weight: 500 @endif"
                                                                    id="foodName-{{ $booking->id }}{{ $food->id }}">{{ $food->name }}
                                                                </h6>
                                                                <small class="mx-2 text-muted col-auto">/ {{ $food->restaurant->name }}</small>

                                                            </div>
                                                            <div class="row">

                                                                <div class="col-6">
                                                                    <input type="number"
                                                                           class="form-control form-control-sm"
                                                                           name="booking[{{ $booking->id }}][ {{ $food->id }}]"
                                                                           min="0" max="3"
                                                                           value="{{ $value->quantity ?? 0 }}"
                                                                           id="{{ $booking->id }}{{ $food->id }}"
                                                                           onchange="updateFoodValue({{ $food->id }}, {{ $booking->id }}{{ $food->id }}, {{ $value->quantity ?? 0 }}, {{ $booking->id }})">
                                                                </div>
                                                                <div class="col-6"
                                                                     style="align-self: center;text-align: end">
                                                                    <span class="ss02">{{ $food->price }} تومان </span>
                                                                </div>
                                                                <hr class="mt-2">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="d-flex justify-content-center m-0">
                                                    <label for="quantity" class="col-sm-3 col-form-label"
                                                           style="font-weight: 400;font-size: 13px">ساختمان</label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control form-select" id="salonId-{{$booking->id}}"
                                                                name="salon[{{ $booking->id }}]" style="font-size: 12px">
                                                            <option value="1">---</option>
                                                            @foreach($salons as $salon)
                                                                <option value="{{ $salon->id }}"
                                                                        @if($booking->reservationsForUser()->first())
                                                                            @if($salon->id == $booking->reservationsForUser()->first()->salon_id)
                                                                                selected
                                                                        @endif
                                                                        @else
                                                                            @if($salon->id == auth()->user()->default_salon_id)
                                                                                selected
                                                                        @endif
                                                                        @endif
                                                                >
                                                                    {{ $salon->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        </form>
    @endif

@endsection

@push('css')
    <style>
        .input-group.input-group-sm {
            direction: ltr;
        }

        .nav-tabs {
            position: relative;
        }

        .nav-tabs.fixed {
            position: fixed;
            top: 0;
            z-index: 9999;
            width: 100%;
            background-color: white;
        }
        .btn-decrement{
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            color: #495057;
            background-color: #e9ecef;
            border-right: 0;
        }
        .btn-increment{
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            color: #495057;
            background-color: #e9ecef;
            border-left: 0;
        }
        .swal2-popup{
            width: 11.2em !important;
            font-size: 0.5rem !important;
        }

    </style>
@endpush
@push('js')
    <script src="{{ asset('js/input-spinner.js') }}"></script>
    <script>
        $("input[type='number']").inputSpinner();

        function updateFoodValue(foodId, elementId, qty, bookingId) {
            let inputValue = $('#' + elementId).val();
            let salonId = $('#salonId-' + bookingId ).val();
            fetch('/lunch/reserve', {
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json, text-plain, */*",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": $('input[name="_token"]').val()
                },
                method: 'post',
                credentials: "same-origin",
                body: JSON.stringify({
                    foodId: foodId,
                    qty: inputValue,
                    bookingId: bookingId,
                    salonId: salonId,
                })
            })
                .then((response) => {
                    if(response.status === 200){
                        if (inputValue > 0) {
                            $('#foodName-' + elementId).css({"color": "#ff4501", "font-weight": "900"})
                        }else {
                            $('#foodName-' + elementId).css({"color": "", "font-weight": "500"})
                        }
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: '',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                })
                .catch(function (error) {
                    console.log(error);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: '',
                        showConfirmButton: false,
                        timer: 1500
                    })
                });
        }

        $(document).ready(function () {
            var navTabsOffset = $('.nav-tabs').offset().top;

            $(window).scroll(function () {
                var scrollPos = $(window).scrollTop();

                if (scrollPos >= navTabsOffset) {
                    $('.nav-tabs').addClass('fixed');
                } else {
                    $('.nav-tabs').removeClass('fixed');
                }
            });

            $('.nav-tabs.fixed').click(function () {
                $('html, body').animate({scrollTop: 0}, 500);
            });
        });


    </script>
@endpush
