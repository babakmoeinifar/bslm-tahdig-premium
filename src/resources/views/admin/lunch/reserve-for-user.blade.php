@extends('template.master-admin')

@section('title', 'افزودن غذا برای کاربر')

@section('content')
    <div class="row">
        <div class="col-12 col-md-4">
            @livewire('tahdig::components.reserve-food', ['id' => $id])
        </div>
    </div>
@endsection

