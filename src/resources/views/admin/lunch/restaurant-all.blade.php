@extends('template.master-admin')

@section('title', 'لیست رستوران')

@section('content')

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                رستوران‌ها
                <a href="{{ url('admin/lunch/restaurants/create') }}" class="btn btn-light-primary">افزودن</a>
            </h4>
        </div>
        <div class="card-content">
            <div class="table-responsive ss02">
                <table class="table mb-0 table-lg">
                    <thead>
                    <tr>
                        <th scope="col">شناسه</th>
                        <th scope="col">اسم</th>
                        <th scope="col">امتیاز</th>
                        <th scope="col">نظرات</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($restaurants as $restaurant)
                        <tr>
                            <td>{{ $restaurant->id }}</td>
                            <td>{{ $restaurant->name }}</td>
                            <td><i class="bi bi-star-fill text-warning"></i>
                                @foreach($restaurantScores as $resScore)
                                    @if($restaurant->id === $resScore->restaurant_id)
                                        {{ (int)$resScore->score }}
                                    @endif
                                @endforeach
                            </td>
                            <td><a href="{{ url('admin/lunch/restaurants/comments/'. $restaurant->id) }}"
                                   class="btn btn-primary btn-sm"><i class="bi bi-eye"></i></a></td>
                            <td><a href="{{ url('admin/lunch/restaurants/'.$restaurant->id) }}" class="btn btn-primary">ویرایش</a> </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
