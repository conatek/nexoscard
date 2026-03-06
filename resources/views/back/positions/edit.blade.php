@extends('back.layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-helm text-success"></i>
                </div>
                <div>
                    Editar Cargo
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('positions.index') }}" type="button" class="btn-shadow me-3 btn btn-info">
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
            <form action="{{ route('positions.update', $position->id) }}" method="post" class="">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input name="name" id="name" value="{{ old('name', $position->name)  }}" placeholder="Ingresar nombre" type="text" class="form-control" autofocus>
                            @if($errors->has('name'))
                                <span class="error text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="area_id" class="form-label">Área</label>
                            <select class="multiselect-dropdown form-control" name="area_id" id="area_select">
                                <option value="">-- Seleccionar Área --</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}" {{ $area->id == $position->area_id ? 'selected' : '' }}> {{ $area->name }} </option>
                                @endforeach
                            </select>
                            @if($errors->has('area_id'))
                                <span class="error text-danger">{{ $errors->first('area_id') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="name" class="form-label">Salario Estimado</label>
                            <input name="estimated_salary" id="estimated_salary" value="{{ old('estimated_salary', $position->estimated_salary)  }}" placeholder="Ingresar salario estimado" type="text" class="form-control">
                            @if($errors->has('estimated_salary'))
                                <span class="error text-danger">{{ $errors->first('estimated_salary') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea name="description" rows="1" class="form-control autosize-input" style="height: 100px;">{{ (old('description', $position->description)) }}</textarea>
                            @if($errors->has('description'))
                                <span class="error text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <button type="submit" class="mt-2 btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
@endsection
