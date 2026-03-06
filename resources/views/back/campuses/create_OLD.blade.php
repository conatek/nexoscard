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
            <form action="{{ route('campuses.store') }}" method="post" class="">
                @csrf

                <input id="company_id" name="company_id" type="hidden" value="{{ auth()->user()->company_id }}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input name="name" id="name" value="{{ old('name')  }}" placeholder="Ingresar nombre" type="text" class="form-control" autofocus>
                            @if($errors->has('name'))
                                <span class="error text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="address" class="form-label">Dirección</label>
                            <input name="address" id="address" value="{{ old('address')  }}" placeholder="Ingresar dirección" type="text" class="form-control">
                            @if($errors->has('address'))
                                <span class="error text-danger">{{ $errors->first('address') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="province" class="form-label">Departamento</label>
                            <input name="province" id="province" value="{{ old('province')  }}" placeholder="Ingresar departamento" type="text" class="form-control">
                            @if($errors->has('province'))
                                <span class="error text-danger">{{ $errors->first('province') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="city" class="form-label">Municipio</label>
                            <input name="city" id="city" value="{{ old('city')  }}" placeholder="Ingresar ciudad" type="text" class="form-control">
                            @if($errors->has('city'))
                                <span class="error text-danger">{{ $errors->first('city') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input name="phone" id="phone" value="{{ old('phone')  }}" placeholder="Ingresar teléfono" type="text" class="form-control">
                            @if($errors->has('phone'))
                                <span class="error text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input name="email" id="email" value="{{ old('email')  }}" placeholder="Ingresar email" type="text" class="form-control">
                            @if($errors->has('email'))
                                <span class="error text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <button type="submit" class="mt-2 btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
@endsection
