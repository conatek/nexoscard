@extends('back.layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-culture text-success"></i>
                </div>
                <div>
                    Información de la Empresa
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('companies.index') }}" type="button" class="btn-shadow me-3 btn btn-info">
                    <i class="fa fa-list"></i>
                    Ver Listado
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mbg-3 alert alert-success alert-dismissible fade show" role="alert">
        <span class="pe-2">
            <i class="fa fa-star"></i>
            {{-- <i class="pe-7s-star"></i> --}}
        </span>
            {{ session('success') }}
        </div>
    @endif

    <company-detail 
        :company="{{ $company }}"
        :company_type="{{ $company_type }}"
        :industry_type="{{ $industry_type }}"
        :identification_type="{{ $identification_type }}"
        :province="{{ $province }}"
        :city="{{ $city }}"
    ></company-detail>

@endsection
