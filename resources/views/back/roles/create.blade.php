@extends('back.layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-airplay text-success"></i>
                </div>
                <div>
                    Agregar Rol
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

    <div class="mbg-3 alert alert-info alert-dismissible fade show" role="alert">
    <span class="pe-2">
        <i class="fa fa-question-circle"></i>
    </span>
        This dashboard example was created using only the available elements and components, no additional SCSS was
        written!
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="post" class="">
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
                </div>
                <button type="submit" class="mt-2 mb-4 btn btn-primary">Guardar</button>
                <hr>
                <p>Asignar Permisos</p>
                <div class="row">
                    @foreach($permissions as $id => $permission)
                        <div class="col-lg-3 col-md-4 col-sm-6 py-1">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" name="permissions[]" id="checkbox_{{ $permission }}" value="{{ $id }}">
                                    <label for="checkbox_{{ $permission }}">{{ $permission }}</label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
    </div>

{{--    <div class="main-card mb-3 card">--}}
{{--        <div class="card-body">--}}
{{--            <div class="row">--}}
{{--                <div class="col-sm-6 py-1">--}}
{{--                    <div class="form-group clearfix">--}}
{{--                        <div class="icheck-primary d-inline">--}}
{{--                            <input type="checkbox" id="checkboxPrimary1" checked>--}}
{{--                            <label for="checkboxPrimary1">--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="icheck-primary d-inline">--}}
{{--                            <input type="checkbox" id="checkboxPrimary2">--}}
{{--                            <label for="checkboxPrimary2">--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="icheck-primary d-inline">--}}
{{--                            <input type="checkbox" id="checkboxPrimary3" disabled>--}}
{{--                            <label for="checkboxPrimary3">--}}
{{--                                Primary checkbox--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-sm-6 py-1">--}}
{{--                    <div class="form-group clearfix">--}}
{{--                        <div class="icheck-primary d-inline">--}}
{{--                            <input type="radio" id="radioPrimary1" name="r1" checked>--}}
{{--                            <label for="radioPrimary1">--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="icheck-primary d-inline">--}}
{{--                            <input type="radio" id="radioPrimary2" name="r1">--}}
{{--                            <label for="radioPrimary2">--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="icheck-primary d-inline">--}}
{{--                            <input type="radio" id="radioPrimary3" name="r1" disabled>--}}
{{--                            <label for="radioPrimary3">--}}
{{--                                Primary radio--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="row">--}}
{{--                <div class="col-sm-6 py-1">--}}
{{--                    <div class="form-group clearfix">--}}
{{--                        <div class="icheck-danger d-inline">--}}
{{--                            <input type="checkbox" checked id="checkboxDanger1">--}}
{{--                            <label for="checkboxDanger1">--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="icheck-danger d-inline">--}}
{{--                            <input type="checkbox" id="checkboxDanger2">--}}
{{--                            <label for="checkboxDanger2">--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="icheck-danger d-inline">--}}
{{--                            <input type="checkbox" disabled id="checkboxDanger3">--}}
{{--                            <label for="checkboxDanger3">--}}
{{--                                Danger checkbox--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-sm-6 py-1">--}}
{{--                    <div class="form-group clearfix">--}}
{{--                        <div class="icheck-danger d-inline">--}}
{{--                            <input type="radio" name="r2" checked id="radioDanger1">--}}
{{--                            <label for="radioDanger1">--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="icheck-danger d-inline">--}}
{{--                            <input type="radio" name="r2" id="radioDanger2">--}}
{{--                            <label for="radioDanger2">--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="icheck-danger d-inline">--}}
{{--                            <input type="radio" name="r2" disabled id="radioDanger3">--}}
{{--                            <label for="radioDanger3">--}}
{{--                                Danger radio--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="row">--}}
{{--                <div class="col-sm-6 py-1">--}}
{{--                    <div class="form-group clearfix">--}}
{{--                        <div class="icheck-success d-inline">--}}
{{--                            <input type="checkbox" checked id="checkboxSuccess1">--}}
{{--                            <label for="checkboxSuccess1">--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="icheck-success d-inline">--}}
{{--                            <input type="checkbox" id="checkboxSuccess2">--}}
{{--                            <label for="checkboxSuccess2">--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="icheck-success d-inline">--}}
{{--                            <input type="checkbox" disabled id="checkboxSuccess3">--}}
{{--                            <label for="checkboxSuccess3">--}}
{{--                                Success checkbox--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-sm-6 py-1">--}}
{{--                    <div class="form-group clearfix">--}}
{{--                        <div class="icheck-success d-inline">--}}
{{--                            <input type="radio" name="r3" checked id="radioSuccess1">--}}
{{--                            <label for="radioSuccess1">--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="icheck-success d-inline">--}}
{{--                            <input type="radio" name="r3" id="radioSuccess2">--}}
{{--                            <label for="radioSuccess2">--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="icheck-success d-inline">--}}
{{--                            <input type="radio" name="r3" disabled id="radioSuccess3">--}}
{{--                            <label for="radioSuccess3">--}}
{{--                                Success radio--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
