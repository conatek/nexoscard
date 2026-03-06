@extends('back.layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-culture text-success"></i>
                </div>
                <div>
                    Agregar Empresa
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

    {{-- <div class="mbg-3 alert alert-info alert-dismissible fade show" role="alert">
        <span class="pe-2">
            <i class="fa fa-question-circle"></i>
        </span>
        This dashboard example was created using only the available elements and components, no additional SCSS was
        written!
    </div> --}}

    <company-create 
        :company_types="{{ $company_types }}"
        :document_types="{{ $document_types }}"
        :provinces="{{ $provinces }}"
        :industry_types="{{ $industry_types }}"
    ></company-create>
    
@endsection
