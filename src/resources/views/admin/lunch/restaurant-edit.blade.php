@extends('template.master-admin')

@section('title', 'ویرایش رستوران')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6 mx-auto">
            @include('template.messages')
            <div class="card my-4">
                <div class="card-body">
                    <form method="post" action="{{ url('admin/lunch/restaurants/edit') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $restaurant->id }}">
                        <div class="form-group">
                            <label>اسم</label>
                            <input type="text" name="name" class="form-control" value="{{ $restaurant->name }}">
                        </div>
                        <div class="form-group">
                            <b>وضعیت</b>
                            <div class="row">
                                <div class="col-6">
                                    <label for="inActive" class="form-check-label">غیرفعال</label>
                                    <input type="radio" id="inActive" class="form-check"
                                           value="0" name="is_active" @if(!$restaurant->is_active) checked @endif>
                                </div>
                                <div class="col-6">
                                    <label for="active" class="form-check-label">فعال</label>
                                    <input type="radio" id="active" class="form-check"
                                           value="1" name="is_active" @if($restaurant->is_active) checked @endif>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">ثبت</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
