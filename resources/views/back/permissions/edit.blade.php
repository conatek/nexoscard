@extends('back.layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-key text-success"></i>
                </div>
                <div>
                    Editar Permiso
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('permissions.index') }}" type="button" class="btn-shadow me-3 btn btn-info">
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
            <form action="{{ route('permissions.update', $permission->id) }}" method="post" class="">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input name="name" id="name" value="{{ old('name', $permission->name)  }}" placeholder="Ingresar nombre" type="text" class="form-control" autofocus>
                            @if($errors->has('name'))
                                <span class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <button type="submit" class="mt-2 btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
@endsection
