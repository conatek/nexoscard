@extends('auth.layouts.app')

@section('content')
<div class="app-container app-theme-white body-tabs-shadow bg-white">
    <div class="app-container">
        <div class="h-100">
        {{-- <div> --}}
            <div class="h-100 g-0 row">
                <div class="d-none d-lg-block col-lg-4 bg-dark" style="background-image: url('images/sidebar/city1.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                {{-- @include('auth.partials.auth-slider') --}}
                @include('auth.partials.auth-form-login')
            </div>
        </div>
    </div>
</div>
@endsection