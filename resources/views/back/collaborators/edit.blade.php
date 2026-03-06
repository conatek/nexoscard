@extends('back.layouts.app')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-users text-success"></i>
            </div>
            <div>
                Editar Colaborador
            </div>
        </div>
        <div class="page-title-actions">
            <a href="{{ route('collaborators.index') }}" type="button" class="btn-shadow me-3 btn btn-info">
                <i class="fa fa-list"></i>
                Ver Listado
            </a>

            {{-- @can('user_create') --}}
            {{-- <a href="{{ route('collaborators.create') }}" type="button" class="btn-shadow me-3 btn btn-info">
                <i class="fa fa-plus"></i>
                Carga Masiva
            </a> --}}
            {{-- @endcan --}}

            
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

<collaborator-edit 
    :collaborator="{{ $collaborator }}"
    :document_types="{{ $document_types }}"
    :sex_types="{{ $sex_types }}"
    :civil_status_types="{{ $civil_status_types }}"
    :rh_types="{{ $rh_types }}"
    :stratum_types="{{ $stratum_types }}"
    :housing_tenure_types="{{ $housing_tenure_types }}"
    :provinces="{{ $provinces }}"
    :position_types="{{ $position_types }}"
    :contract_types="{{ $contract_types }}"
    :bank_types="{{ $bank_types }}"
    :eps_types="{{ $eps_types }}"
    :afp_types="{{ $afp_types }}"
    :arl_types="{{ $arl_types }}"
    :ccf_types="{{ $ccf_types }}"
></collaborator-edit>

@endsection
