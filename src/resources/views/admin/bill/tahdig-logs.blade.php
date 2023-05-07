@extends('template.master-admin')

@section('title', 'تاریخچه')

@section('content')
    <div class="card">

        <div class="card-header">

            <div class="row">
                <div class="col-9 col-md-6 col-sm-12">
                    <h3 class="mb-4">تاریخچه</h3>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">

                <form method="post" action="{{ url('admin/bills/tahdig-logs') }}">
                    @csrf
                    <div class="d-flex justify-content-end">

                        <div class="mx-1">
                            <div class="form-group text-start">
                                <label>انتخاب کاربر</label>
                                <select class="form-control form-select users-list" name="select_tahdig_logs_user">
                                    <option value=""></option>
                                    @foreach($data['users'] as $user)
                                        <option {{$user['id'] == $data['userid'] ? 'selected="selected"' : ''}} value="{{$user['id']}}">{{$user['name'] . '(' . $user['employeeId'] . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mx-1">
                            <div class="form-group">
                                <label>تاریخ شروع</label>
                                <input type="text" class="form-control" name="" id="date_start" value="">
                                <input type="hidden" class="form-control" name="date_start_alt" id="date_start_alt">
                            </div>
                        </div>

                        <div class="mx-1">
                            <div class="form-group">
                                <label>تاریخ پایان</label>
                                <input type="text" class="form-control" name="" id="date_end" value="">
                                <input type="hidden" class="form-control" name="date_end_alt" id="date_end_alt">
                            </div>
                        </div>

                        <div class="mx-1">
                            <div class="form-group">
                                <label></label>
                                <p>
                                    <button type="submit" class="btn btn-primary">جستجو</button>
                                </p>
                            </div>
                        </div>

                    </div>
                </form>
                @if(count(explode('/',request()->url())) > 8)
                    <a href="{{ url('/admin/bills/tahdig-logs-export?userId='.(explode('/',request()->url()))[6].'&date_start='.(explode('/',request()->url()))[7].'&date_end='.(explode('/',request()->url()))[8]) }}"
                       class="btn btn-light-primary">
                        خروجی اکسل
                    </a>
                @endif

            </div>
        </div>

        @if(@$data['reservations'])
            <div class="card-content">
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
                            <th scope="col">قیمت(ریال)</th>
                            <th scope="col">عملیات</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($data['reservations'] as $reservation)
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
                                <td>{{ number_format($reservation->price*10) }}</td>
                                <td>
                                    @if($reservation->date > now()->addHours(config('nahar.gap_hour'))->startOfDay())
                                        <a href="{{ url('/lunch/reserve/'.$reservation->id.'/delete')}}"
                                           class="btn-danger btn btn-sm">
                                            حذف
                                        </a>
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
                {{ $data['reservations']->onEachSide(0)->links() }}
            </div>
        @endif

    </div>
@endsection


@push('css')
    <link href="{{ asset('css/persian-datepicker.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
@endpush

@push('js')
    <script src="{{ asset('js/persian-date.min.js') }}"></script>
    <script src="{{ asset('js/persian-datepicker.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function () {

            $('.users-list').selectpicker({
                liveSearch: true
            })

            $("#date_start").pDatepicker({
                altField: '#date_start_alt',
                altFormat: 'X',
                format: 'YYYY/MM/DD',
                observer: true
            })

            $("#date_end").pDatepicker({
                altField: '#date_end_alt',
                altFormat: 'X',
                format: 'YYYY/MM/DD',
                observer: true
            })
        });
    </script>
@endpush
