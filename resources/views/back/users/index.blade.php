@extends('back.layouts.app')

@section('content')

{{-- {{ dd($companies) }} --}}

<user-index
    :companies="{{ $companies }}"
    :users="{{ $users }}"
    :roles="{{ $roles }}"
></user-index>

@endsection
