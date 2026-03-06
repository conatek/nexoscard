@extends('back.layouts.app')

@section('content')

    @can('home_master')
        @include('back.home.home-master')
    @elsecan('home_super')
        @include('back.home.home-super')
    @elsecan('home_admin')
        @include('back.home.home-admin')
    @else
        @include('back.home.home-generic')
    @endcan

@endsection
