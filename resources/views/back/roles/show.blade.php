@extends('back.layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-airplay text-success"></i>
                </div>
                <div>
                    Información del Rol
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('roles.index') }}" type="button" class="btn-shadow me-3 btn btn-info">
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
        <div class="col-md-6">
            <div class="card-shadow-primary border mb-3 card card-body border-success">
                <h5 class="card-title">{{ $role->name  }}</h5>
                <hr>
                En este espacio colocaremos la información del permiso.
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card-shadow-primary border mb-3 card card-body border-success">
                <h5 class="card-title">Permisos Asignados</h5>
                <hr>
                <div>
                    @forelse($role->permissions as $permission)
                        <span class="badge bg-success mt-2">{{ $permission->name }}</span>
                    @empty
                        <span class="badge bg-danger">No permission added</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
