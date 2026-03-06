@extends('auth.layouts.app')

@section('content')
<div class="app-container app-theme-white body-tabs-shadow bg-white">
    <div class="app-container">
        <div class="h-100">
            <div class="h-100 g-0 row">
                @include('auth.partials.auth-form-register')
                {{-- @include('auth.partials.auth-slider') --}}
                {{-- <div class="d-none d-lg-block col-lg-4 bg-dark"></div> --}}
                <div class="d-none d-lg-block col-lg-4 bg-dark" style="background-image: url('images/sidebar/city2.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
            </div>
        </div>
    </div>
</div>
@endsection