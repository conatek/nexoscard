@extends('auth.layouts.app')

@section('content')
<div class="app-container app-theme-white body-tabs-shadow bg-white">
    <div class="app-container">
        <div class="h-100">
            <div class="h-100 g-0 row">
                <div class="d-none d-lg-block col-lg-4 bg-dark"></div>
                {{-- @include('auth.partials.auth-slider') --}}
                @include('auth.partials.auth-form-password-email')
            </div>
        </div>
    </div>
</div>
@endsection