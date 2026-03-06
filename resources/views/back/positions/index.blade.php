@extends('back.layouts.app')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-helm text-success"></i>
                </div>
                <div>
                    Listado de Cargos
                </div>
            </div>
            <div class="page-title-actions">
                @can('area_create')
                    <a href="{{ route('positions.create') }}" type="button" class="btn-shadow me-3 btn btn-info">
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
                    <th>Área</th>
                    <th>Salario Estimado</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($positions as $position)
                    <tr>
                        <td>{{ $position->name }}</td>
                        <td>{{ $position->area->name }}</td>
                        <td>{{ $position->estimated_salary != null ? '$' . number_format($position->estimated_salary, 0) : 'Sin Asignar' }}</td>
                        <td class="td-actions">
                            @can('position_show')
                                <a href="{{ route('positions.show', $position->id) }}" class="mb-2 me-2 btn-icon btn btn-sm btn-success">
                                    <i class="pe-7s-look btn-icon-wrapper"></i>Mostrar
                                </a>
                            @endcan

                            @can('position_edit')
                                <a href="{{ route('positions.edit', $position->id) }}" class="mb-2 me-2 btn-icon btn btn-sm btn-primary">
                                    <i class="pe-7s-pen btn-icon-wrapper"></i>Editar
                                </a>
                            @endcan

                            @can('position_destroy')
                                <form action="{{ route('positions.destroy', $position->id) }}" method="POST">
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
                    <th>Área</th>
                    <th>Salario Estimado</th>
                    <th>Acciones</th>
                </tr>
                </tfoot>
            </table>

            {{-- {{ $users->onEachSide(2)->links() }} --}}
        </div>
    </div>

    {{--<div class="components">--}}
    {{--    <div class="hello">--}}
    {{--        <h2>Hello.vue</h2>--}}
    {{--        <hello-component />--}}
    {{--    </div>--}}

    {{--    <div class="world">--}}
    {{--        <h2>World.vue</h2>--}}
    {{--        <world-component />--}}
    {{--    </div>--}}
    {{--</div>--}}
@endsection
