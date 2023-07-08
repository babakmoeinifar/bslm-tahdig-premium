@extends('template.master-user')

@section('title', 'تاریخچه')

@section('content')
    @include('template.messages')

    <div class="card ss02">
        <div class="card-header">
            <h3>تاریخچه رزرو غذا</h3>
        </div>

        @if($is_temporary_disabled)
            <div class="p-5 text-center">
                <h5>متاسفانه رزرو غذا غیرفعاله.</h5>
            </div>
        @else
            <div class="card-content">
                <div class="card-body">
                    <div class="alert alert-info">
                        تراز حساب:
                        <strong>{{ number_format($sum,0,".",",") }}</strong>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 table-lg">
                        <thead>
                        <tr>
                            <th scope="col">تاریخ</th>
                            <th scope="col">وعده</th>
                            <th scope="col">غذا</th>
                            <th scope="col">تعداد</th>
                            <th scope="col">سالن</th>
                            <th scope="col">رستوران</th>
                            <th scope="col">قیمت</th>
                            <th scope="col">عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reservations as $reservation)
                            <tr @if(!@$reservation->food->name) style='background:#f1faee' @endif>
                                <td>{{ jdfw($reservation->date) }}</td>
                                <td>{{ @$reservation->booking->meal->name }}</td>
                                <td>
                                    {{ @$reservation->food->name ? @$reservation->food->name : 'تسویه' }}
                                    @if($reservation->added_by)
                                        <i class="bi bi-bandaid text-muted" title="رزرو توسط سازمان"></i>
                                    @endif
                                </td>
                                <td>{{ $reservation->quantity }}</td>
                                <td>{{ @$reservation->salon->name }}</td>
                                <td>{{ @$reservation->food->restaurant->name }}</td>
                                <td>{{ number_format($reservation->price) }}</td>
                                <td>
                                    @if($reservation->date > now()->addHours(config('nahar.gap_hour'))->startOfDay())
                                        @if(@$reservation->food->name)
                                            <a href="{{ url('/lunch/reserve/'.$reservation->id.'/delete')}}"
                                               class="btn-danger btn btn-sm">
                                                حذف
                                            </a>
                                        @endif
                                    @else
                                        @if(!$reservation->comments && @$reservation->food->name)
                                            <a href="{{ url('/lunch/reserve/'.$reservation->id.'/rate')}}"
                                               class="btn-warning btn btn-sm">
                                                امتیاز دادن
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                {{ $reservations->onEachSide(0)->links() }}
            </div>
        @endif


    </div>
@endsection
