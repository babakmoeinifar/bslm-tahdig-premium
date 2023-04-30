@extends('template.master-admin')

@section('title', 'لیست روز')

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-striped responsive ss02" id="table">
                <thead>
                <tr>
                    <th scope="col">پرسنلی</th>
                    <th scope="col">اسم</th>
                    <th scope="col">غذا</th>
                    <th scope="col">رستوران</th>
                    <th scope="col">دریافت</th>
                </tr>
                </thead>
                <tbody>
                @foreach($foods as $food)
                    @php
                        $food = $food->sortBy('user.id')
                    @endphp
                    @foreach($food as $reservation)
                        <tr class="@if(!is_null($reservation->received_at)) text-black-50 text-decoration-line-through @endif">
                            <td>{{ $reservation->user->employee_id }}</td>
                            <td>{{ $reservation->user->name }}</td>
                            <td>
                                @if($reservation->quantity > 1)
                                    ({{ $reservation->quantity }})
                                @endif
                                {{ $reservation->food->name }}
                            </td>
                            <td>{{ $reservation->food->restaurant->name }}</td>
                            <td>
                                <button class="btn btn-info received @if(!is_null($reservation->received_at)) disabled @endif"
                                        data-id="{{ $reservation->id }}">
                                    دریافت
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
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
                paging: false,
                responsive: true
            });

            $('.received').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ url('admin/ajax/tahdig/received') }}/" + $(this).data('id')
                }).done(function () {});
                $(this).prop('disabled', true);
                $(this).parent().parent().addClass('text-black-50 text-decoration-line-through');
            })
        });
    </script>
@endpush
