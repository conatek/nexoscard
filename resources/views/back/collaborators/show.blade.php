@extends('back.layouts.app')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-user text-success"></i>
            </div>
            <div>
                Información del Colaborador
            </div>
        </div>
        <div class="page-title-actions">
            <a href="{{ route('collaborators.index') }}" type="button" class="btn-shadow me-3 btn btn-info">
                <i class="fa fa-list"></i>
                Ver Listado
            </a>
        </div>
    </div>
</div>

<collaborator-show
    :collaborator="{{ $collaborator }}"
    :document_type="{{ $document_type }}"
    :document_province="{{ $document_province }}"
    :document_city="{{ $document_city }}"
    :birth_province="{{ $birth_province }}"
    :birth_city="{{ $birth_city }}"
    :residence_province="{{ $residence_province }}"
    :residence_city="{{ $residence_city }}"
    :civil_status="{{ $civil_status }}"
    :sex_type="{{ $sex_type }}"
    :rh_type="{{ $rh_type }}"
    :stratum_type="{{ $stratum_type }}"
    :highest_academic_achievement="{{ $highest_academic_achievement }}"
    :housing_tenure="{{ $housing_tenure }}"
    :relationship_types="{{ $relationship_types }}"
    :occupation_types="{{ $occupation_types }}"
    :sex_types="{{ $sex_types }}"
    :achievement_types="{{ $achievement_types }}"
    :examination_types="{{ $examination_types }}"
    :home_visit_types="{{ $home_visit_types }}"
    :contractual_documents_types="{{ $contractual_documents_types }}"
></collaborator-show>

@endsection
