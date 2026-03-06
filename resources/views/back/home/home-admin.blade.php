@extends('back.layouts.app')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-graph2 text-success"></i>
            </div>
            <div>
                Panel de Control
            </div>
        </div>
        <div class="page-title-actions">
            {{-- <a href="{{ route('collaborators.create') }}" type="button" class="btn-shadow me-3 btn btn-info">
                <i class="fa fa-plus"></i>
                Agregar
            </a> --}}
        </div>
    </div>
</div>

@if (session('success'))
    <div class="mbg-3 alert alert-success alert-dismissible fade show" role="alert">
        <span class="pe-2">
            <i class="fa fa-star"></i>
            <i class="pe-7s-star"></i>
        </span>
        {{ session('success') }}
    </div>
@endif

@php
    $company_id = auth()->user()->company_id;
@endphp

<control-panel :company_id="{{ $company_id }}"></control-panel>

@endsection
