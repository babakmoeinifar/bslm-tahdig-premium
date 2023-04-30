@extends('template.master-admin')

@section('title', 'لیست رزروها')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">لیست روز غذا</h4>
        </div>
        <div class="card-content">
            <div class="table-responsive">
                <table class="table table-striped ss02" id="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">تاریخ</th>
                        <th scope="col">وعده</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ jdfw($booking->booking_date) }}</td>
                            <td>{{ $booking->meal->name }}</td>
                            <td>
                                @foreach($saloons as $saloon)
                                    <a href="{{ url('admin/lunch/reservation/'. $booking->id. '/'. $saloon->id) }}"
                                       class="btn btn-outline-primary">
                                        {{ $saloon->name }}
                                    </a>
                                @endforeach
                                <a href="{{ url('admin/lunch/reservation/report/'. $booking->id) }}"
                                   class="btn btn-outline-primary">آمار</a>
                                <a href="{{ url('admin/lunch/reservation/for-user/'. $booking->id) }}"
                                   class="btn btn-outline-primary">افزودن</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
