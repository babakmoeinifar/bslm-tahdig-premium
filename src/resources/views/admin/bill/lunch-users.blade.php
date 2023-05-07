@extends('template.master-admin')

@section('title', 'حساب غذای کاربران')

@section('content')
    <div class="page-title mb-3 d-print-none">
        <div class="row">
                <div class="col-md-3">
                    <h3>
                        مدیریت کاربران
                        <a href="{{ url('/admin/bills/lunch-users-export?'. http_build_query(request()->all())) }}"
                        class="btn btn-light-primary">
                            خروجی اکسل
                        </a>
                    </h3>

                </div>

                <div class="col-md-3 text-center">
                    <a href="{{ url('admin/bills/reset-tahdig') }}" class="btn btn-light-primary btn-reset-tahdig">صفر شدن حساب</a>
                    <a href="{{ url('admin/bills/tahdig-logs') }}" class="btn btn-light-primary">تاریخچه</a>
                </div>

                <div class="col-md-3 text-end">
                    <div class="col-md-12">
                        غیرفعال سازی موقت رزرو
                        <label class="switch">
                            <input type="checkbox" name="chk-toggle-tahdig" {{ $is_temporary_disabled ? 'checked=checked' : '' }} >
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

            <div class="col-md-3 text-end">
                    <form action="{{ url('/admin/bills/lunch-users') }}">
                        <div class="form-switch">
                            <label class="form-check-label" for="active-switch">کاربران غیرفعال</label>
                            <input class="form-check-input m-0"
                                   onchange="this.form.submit()"
                                   value="1" type="checkbox" id="active-switch"
                                   @if(request('deactiveUsers')) checked @endif name="deactiveUsers">
                        </div>
                    </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-content">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">پرسنلی</th>
                        <th scope="col">نام</th>
                        <th scope="col">تاریخ تسویه</th>
                        <th scope="col">تاریخ خروج</th>
                        <th scope="col">جمع حساب</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($usersBill as $userBill)
                        <tr class="@if(!is_null($userBill->deactivated_at)) text-muted @endif">
                            <td>{{ $userBill->employee_id }}</td>
                            <td>{{ $userBill->name }}</td>
                            <td>{{ jdf($userBill->settlement_at) }}</td>
                            <td>@if($userBill->deactivated_at){{ jdf($userBill->deactivated_at) }} @endif</td>
                            <td dir="ltr">
                                {{ number_format($userBill->balance*10,0,".",",") }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
