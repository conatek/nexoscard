@extends('back.layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users text-success"></i>
                </div>
                <div>
                    Editar Usuario
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

    <div class="col-md-12">
        <div class="card-shadow-primary card-border text-white mb-3 card bg-primary">
            <div class="card-body mx-auto">
                <div class="avatar-icon-wrapper avatar-icon-xl d-flex mb-3 justify-content-center">
                    <div class="avatar-icon">
                        @if ($user->image_url)
                            <img src="{{ $user->image_url }}" alt="{{ $user->name }}">
                        @else
                            <img src="{{ asset('images/default-profile.jpeg') }}" alt="{{ $user->name }}">
                        @endif
                        <img src="{{ $user->image_url }}" alt="{{ $user->name }}">
                    </div>
                </div>
                <div>
                    <h5 class="menu-header-title text-center">{{ $user->name }}</h5>
                    <h6 class="menu-header-subtitle text-center">{{ $user->email }}</h6>
                </div>
                {{-- <div class="menu-header-btn-pane pt-1">
                    <button class="btn-icon btn btn-dark btn-sm">
                        <i class="pe-7s-config btn-icon-wrapper"></i>
                        View Complete Profile
                    </button>
                </div> --}}
            </div>
            {{-- <div class="text-center d-block card-footer">
                <button class="btn-shadow-dark btn-wider btn btn-dark">Send Message</button>
            </div> --}}
        </div>
    </div>
    <div class="main-card mb-3 card">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input name="name" id="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" autofocus>
                            @if($errors->has('name'))
                                <span class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input name="email" id="email" type="email" class="form-control" value="{{ old('email', $user->email) }}">
                            @if($errors->has('email'))
                                <span class="error text-danger" for="input-email">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="company" class="form-label">Empresa</label>
                            <select type="select" id="company"
                                    name="company_id" class="form-select">
                                <option value="">-- Seleccionar empresa --</option>
                                @foreach($companies as $id => $company)
                                    <option value="{{ $id }}" {{ old('company', $user->company_id) == $id ? 'selected' : '' }}>{{ $company }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('company'))
                                <span class="error text-danger" for="input-company">{{ $errors->first('company') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="image" class="form-label">Imagen</label>
                            <div class="input-group">
                                <input type="file" name="image" id="image" class="form-control">
                                @if($errors->has('image'))
                                    <span class="error text-danger">{{ $errors->first('image') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input name="password" id="password" type="password" class="form-control" placeholder="Ingrese la contraseña solo si desea modificar">
                            @if($errors->has('password'))
                                <span class="error text-danger" for="input-password">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <button type="submit" class="mt-2 btn btn-primary">Actualizar</button>
                <hr>
                <p>Asignar Roles</p>
                <div class="row">
                    @foreach($roles as $id => $role)
                        <div class="col-lg-3 col-md-4 col-sm-6 py-1">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" name="roles[]" id="checkbox_{{ $role }}"
                                           value="{{ $id }}" {{ $user->roles->contains($id) ? 'checked' : '' }}>
                                    <label for="checkbox_{{ $role }}">{{ $role }}</label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
    </div>
@endsection
