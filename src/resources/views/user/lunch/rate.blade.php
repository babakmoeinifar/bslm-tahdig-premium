@extends('resources.views.template.master-user')

@section('title', 'امتیاز غذا')

@section('content')
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="card ss02">
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title mb-4">نظر برای: {{ $reservation->food->name }}</h4>
                        <p>رستوران: {{ $reservation->food->restaurant->name }}</p>
                        <p>{{ jdfw_name($reservation->booking->booking_date) }}</p>
                    </div>
                    <div class="card-footer">
                        <form action="{{ url('/lunch/reserve/'.$reservation->id.'/rate')}}" method="POST">
                            @csrf
                            <div style="direction:ltr; text-align:center;">
                                <input type="text" name="rate" id="rate" value="">
                            </div>
                            <label for="comment">توضیحات:</label>
                            <textarea name="comment" rows="3" class="form-control"></textarea>
                            <button type="submit" class="btn btn-primary mt-2">ثبت</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="{{ asset('vendor/bss/css/star-rating.min.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@push('js')
    <script src="{{ asset('vendor/bss/js/star-rating.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/bss/js/locales/fa.js') }}/"></script>
    <script>
        $("#rate").rating({
            min: 0,
            max: 5,
            step: 1,
            showCaption: false,
            emptyStar: '<i class="bi bi-star"></i>',
            filledStar: '<i class="bi bi-star-fill"></i>',
        });
    </script>
@endpush
