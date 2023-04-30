@extends('template.master-admin')

@section('title', 'لیست غذا ها')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>
                لیست غذاها
                <a href="{{ url('admin/lunch/foods/create') }}" class="btn btn-light-primary">افزودن</a>
            </h4>
        </div>
        <div class="card-body ss02">
            <table class="table table-striped" id="table">
                <thead>
                <tr>
                    <th scope="col">شناسه</th>
                    <th scope="col">نام غذا</th>
                    <th scope="col">قیمت</th>
                    <th scope="col">رستوران</th>
                    <th scope="col">امتیاز</th>
                    <th scope="col">نظرات</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($foods as $food)
                    <tr>
                        <td>{{ $food->id }}</td>
                        <td>{{ $food->name }}</td>
                        <td>{{ $food->price }}</td>
                        <td>{{ $food->Restaurant->name }}</td>
                        <td>
                            @if($food->reservations_sum_score_count > 0)
                                {{ $food->reservations_sum_score_avg / $food->reservations_sum_score_count }}
                            @else
                                بدون امتیاز
                            @endif
                        </td>
                        <td><a href="{{ url('admin/lunch/foods/comments/'.$food->id) }}"
                               class="btn btn-secondary">نظرات</a></td>
                        <td><a href="{{ url('admin/lunch/foods/'.$food->id) }}" class="btn btn-primary">ویرایش</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endsection

        @push('css')
            <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
        @endpush

        @push('js')
            <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
            <script>
                $(document).ready(function () {
                    $('#table').DataTable({
                        paging: false
                    });
                });
            </script>
    @endpush
