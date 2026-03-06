@extends('back.layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users text-success"></i>
                </div>
                <div>
                    Listado de Usuarios
                </div>
            </div>
            <div class="page-title-actions">
                @can('user_company_create')
                    <a href="{{ route('users_company.create') }}" type="button" class="btn-shadow me-3 btn btn-info">
                        <i class="fa fa-plus"></i>
                        Agregar
                    </a>
                @endcan
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

    <div class="main-card mb-3 card">
        <div class="card-body">
            {{-- <table style="width: 100%;" id="example" class="table table-cntk table-hover table-striped table-bordered"> --}}
            <table style="width: 100%;" id="dt_users" class="table table-cntk table-hover table-bordered">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @forelse($user->roles as $role)
                                <span class="badge bg-success">{{ $role->name }}</span>
                            @empty
                                <span class="badge bg-danger">No roles added</span>
                            @endforelse
                        </td>
                        <td class="td-actions">
                            @can('user_company_show')
                                <a href="{{ route('users_company.show', $user->id) }}" class="mb-2 me-2 btn-icon btn btn-sm btn-success">
                                    <i class="pe-7s-look btn-icon-wrapper"></i>Mostrar
                                </a>
                            @endcan

                            @can('user_company_edit')
                                <a href="{{ route('users_company.edit', $user->id) }}" class="mb-2 me-2 btn-icon btn btn-sm btn-primary">
                                    <i class="pe-7s-pen btn-icon-wrapper"></i>Editar
                                </a>
                            @endcan

                            @can('user_company_destroy')
                                <form action="{{ route('users_company.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="mb-2 me-2 btn-icon btn btn-sm btn-danger" type="submit">
                                        <i class="pe-7s-trash btn-icon-wrapper"></i>Eliminar
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Acciones</th>
                </tr>
                </tfoot>
            </table>

            {{-- {{ $users->onEachSide(2)->links() }} --}}
        </div>
    </div>
@endsection
