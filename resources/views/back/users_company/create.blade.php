@extends('back.layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users text-success"></i>
                </div>
                <div>
                    Agregar Usuario
                </div>
            </div>
            <div class="page-title-actions">
                <a href="{{ route('users.index') }}" type="button" class="btn-shadow me-3 btn btn-info">
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
            <form action="{{ route('users_company.store') }}" method="post" class="">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input name="name" id="name" value="{{ old('name')  }}" placeholder="Ingresar nombre" type="text" class="form-control" autofocus>
                            @if($errors->has('name'))
                                <span class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input name="email" id="email" value="{{ old('email')  }}" placeholder="Ingresar email" type="email" class="form-control">
                            @if($errors->has('email'))
                                <span class="error text-danger" for="input-email">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" name="company_id" value="{{ $company->id }}">
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input name="password" id="password" placeholder="Ingresar contraseña" type="password" class="form-control">
                            @if($errors->has('password'))
                                <span class="error text-danger" for="input-password">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <button type="submit" class="mt-2 btn btn-primary">Guardar</button>
                <hr>
                <p>Asignar Roles</p>
                <div class="row">
                    @foreach($roles as $id => $role)
                        @if($role != 'Master' && $role != 'Super' && $role != 'Guest')
                            <div class="col-lg-3 col-md-4 col-sm-6 py-1">
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" name="roles[]" id="checkbox_{{ $role }}" value="{{ $id }}">
                                        <label for="checkbox_{{ $role }}">{{ $role }}</label>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </form>
        </div>
    </div>
@endsection
