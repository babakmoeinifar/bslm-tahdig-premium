@extends('template.master-admin')

@section('title', 'نظرات غذا')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>
                نظرات غذا
            </h4>
        </div>
        <div class="card-body ss02">
            <table class="table table-striped" id="table">
                <thead>
                <tr>
                    <th scope="col">کاربر</th>
                    <th scope="col">امتیاز</th>
                    <th scope="col">متن نظر</th>
                </tr>
                </thead>
                <tbody>
                @foreach($comments as $comment)
                    <tr>
                        <td>{{ $comment->user->name }}</td>
                        <td>{{ $comment->score }}</td>
                        <td>{{ $comment->comment ?? 'نظری ثبت نشده' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </div>
        <div class="mt-5 ss02">
            {!! $comments->onEachSide(0)->links() !!}
        </div>
@endsection
