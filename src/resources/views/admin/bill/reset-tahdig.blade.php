@extends('template.master-admin')

@section('title', 'صفر شدن حساب ته دیگ')

@section('content')
    <div class="page-title mb-3 d-print-none">
        <div class="row">
            <div class="col-md-4">
                <h3>
                    صفر شدن حساب ته دیگ
                </h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form method="post" action="{{ url('admin/bills/reset-tahdig/' ) }}">
                            @csrf
                            
                            <div class="form-group">
                                <label>انتخاب کاربر</label>
                                <select class="form-control form-select users-list" name="users">
                                    <option value="">انتخاب کاربر</option>
                                    <option value="all">همه</option>
                                    @foreach($data['users'] as $user)
                                        <option value="{{$user['id']}}">{{$user['name'] . '(' . $user['employeeId'] . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>تاریخ تسویه</label>
                                <input type="text" class="form-control" name="" id="settlement_at" value="">
                                <input type="hidden" class="form-control" name="settlement_at_alt" id="settlement_at_alt">
                            </div>
                            
                            <button type="submit" class="btn btn-primary">تسویه</button>
                            <a class="btn btn-secondary" href="{{ url('/admin/bills/lunch-users/') }}">بازگشت</a>

                        </form>
                    </div>
                </div>
            </div>
        </div>
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
            
            $("#settlement_at").pDatepicker({
                altField: '#settlement_at_alt',
                altFormat: 'X',
                format: 'YYYY/MM/DD',
                observer: true
            })

            $('.users-list').selectpicker({
                liveSearch: true
            })
        });
    </script>
@endpush
