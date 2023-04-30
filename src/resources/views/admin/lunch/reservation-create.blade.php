@extends('template.master-admin')

@section('title', 'افزودن غذا')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            @include('template.messages')
            <div class="card my-4">
                <div class="card-header">
                    <h4 class="card-title">افزودن روز غذا</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('admin/lunch/reservation/create') }}">
                        @csrf
                        <div class="form-group">
                            <label>تاریخ</label>
                            <input type="text" class="form-control" name="date" id="date">
                            <input type="hidden" class="form-control" name="date_alt" id="date_alt">
                        </div>
                        <div class="form-group">
                            <label>وعده</label>
                            <select class="form-control form-select" name="meal">
                                <option value="">----</option>
                                @foreach($meals as $meal)
                                    <option value="{{ $meal->id }}">{{ $meal->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>غذا اول</label>
                            <select class="form-control form-select food-list" name="food_1">
                                <option value="">----</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">{{ $food->name }}
                                        - {{ $food->restaurant->name }} - {{ $food->price }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>غذا دوم</label>
                            <select class="form-control form-select food-list" name="food_2">
                                <option value="">----</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">{{ $food->name }}
                                        - {{ $food->restaurant->name }} - {{ $food->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>غذا سوم</label>
                            <select class="form-control food-list" name="food_3">
                                <option value="">----</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">{{ $food->name }}
                                        - {{ $food->restaurant->name }} - {{ $food->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>غذا چهارم</label>
                            <select class="form-control food-list" name="food_4">
                                <option value="">----</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">{{ $food->name }}
                                        - {{ $food->restaurant->name }} - {{ $food->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>غذا پنجم</label>
                            <select class="form-control food-list" name="food_5">
                                <option value="">----</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">{{ $food->name }}
                                        - {{ $food->restaurant->name }} - {{ $food->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>غذا ششم</label>
                            <select class="form-control food-list" name="food_6">
                                <option value="">----</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">{{ $food->name }}
                                        - {{ $food->restaurant->name }} - {{ $food->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>غذا هفتم</label>
                            <select class="form-control food-list" name="food_7">
                                <option value="">----</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">{{ $food->name }}
                                        - {{ $food->restaurant->name }} - {{ $food->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>غذا هشتم</label>
                            <select class="form-control food-list" name="food_8">
                                <option value="">----</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">{{ $food->name }}
                                        - {{ $food->restaurant->name }} - {{ $food->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>غذا نهم</label>
                            <select class="form-control food-list" name="food_9">
                                <option value="">----</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">{{ $food->name }}
                                        - {{ $food->restaurant->name }} - {{ $food->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>غذا دهم</label>
                            <select class="form-control food-list" name="food_10">
                                <option value="">----</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">{{ $food->name }}
                                        - {{ $food->restaurant->name }} - {{ $food->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>غذا یازدهم</label>
                            <select class="form-control food-list" name="food_11">
                                <option value="">----</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">{{ $food->name }}
                                        - {{ $food->restaurant->name }} - {{ $food->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>غذا دوازدهم</label>
                            <select class="form-control food-list" name="food_12">
                                <option value="">----</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">{{ $food->name }}
                                        - {{ $food->restaurant->name }} - {{ $food->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>غذا سیزدهم</label>
                            <select class="form-control food-list" name="food_13">
                                <option value="">----</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">{{ $food->name }}
                                        - {{ $food->restaurant->name }} - {{ $food->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>غذا چهاردهم</label>
                            <select class="form-control food-list" name="food_14">
                                <option value="">----</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">{{ $food->name }}
                                        - {{ $food->restaurant->name }} - {{ $food->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>غذا پانزدهم</label>
                            <select class="form-control food-list" name="food_15">
                                <option value="">----</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">{{ $food->name }}
                                        - {{ $food->restaurant->name }} - {{ $food->price }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">افزودن</button>
                    </form>
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
            $("#date").pDatepicker({
                altField: '#date_alt',
                altFormat: 'X'
            });

            $('.food-list').selectpicker({
                liveSearch: true
            });
        });
    </script>
@endpush
