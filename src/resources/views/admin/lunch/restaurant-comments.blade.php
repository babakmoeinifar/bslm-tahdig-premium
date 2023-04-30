@extends('template.master-admin')

@section('title', 'نظرات رستوران')

@section('content')

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                نظرات برای رستوران {{ $restaurant->name }}
            </h4>
        </div>
        <div class="card-content">
                <div class="table-responsive ss02">
                    <table class="table mb-0 table-lg">
                        <thead>
                        <tr>
                            <th scope="col">کاربر</th>
                            <th scope="col">امتیاز</th>
                            <th scope="col">نظر</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reservations as $comment)
                            <tr>
                                <td>{{ $comment->user->name }}</td>
                                <td><i class="bi bi-star-fill text-warning"></i> {{ $comment->comments->score }}</td>
                                <td class="small">{{ $comment->comments->comment }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
    <div class="mt-5 ss02">
        {!! $reservations->onEachSide(0)->links() !!}
    </div>
@endsection
