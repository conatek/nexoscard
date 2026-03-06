@extends('back.layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-helm text-success"></i>
                </div>
                <div>
                    Información de la Sede
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

    @if (session('success'))
        <div class="mbg-3 alert alert-success alert-dismissible fade show" role="alert">
        <span class="pe-2">
            <i class="fa fa-star"></i>
            {{-- <i class="pe-7s-star"></i> --}}
        </span>
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card-shadow-primary border mb-3 card card-body border-primary">
                <h5 class="card-title">{{ $campus->name  }}</h5>
                En este espacio colocaremos la información de la sede.
            </div>
        </div>
    </div>
@endsection
