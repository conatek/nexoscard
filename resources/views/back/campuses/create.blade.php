@extends('back.layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-helm text-success"></i>
                </div>
                <div>
                    Agregar Sede
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('campuses.index') }}" type="button" class="btn-shadow me-3 btn btn-info">
                    <i class="fa fa-list"></i>
                    Ver Listado
                </a>
            </div>
        </div>
    </div>

    <div class="mbg-3 alert alert-info alert-dismissible fade show" role="alert">
        <span class="pe-2">
            <i class="fa fa-question-circle"></i>
        </span>
        This dashboard example was created using only the available elements and components, no additional SCSS was
        written!
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            @php
                $company_id = auth()->user()->company_id;
            @endphp

            <campus-create :company_id="{{ $company_id }}" :provinces="{{ $provinces }}"></campus-create>

        </div>
    </div>
@endsection
