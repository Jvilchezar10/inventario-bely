@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Gestión de roles</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            {{-- <div class="pull-left">
                <h2>Gestión de roles</h2>
            </div> --}}
            <div class="pull-right">
                @can('role-create')
                    <a class="btn btn-success" href="{{ route('roles.create') }}"> Crear nuevo rol</a>
                @endcan
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif


    <table class="table table-bordered">
        <tr>
            <th>N</th>
            <th>Nombre</th>
            <th width="280px">Acciones</th>
        </tr>
        @foreach ($roles as $key => $role)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $role->name }}</td>
                <td>
                    <a class="btn btn-info" href="{{ route('roles.show', $role->id) }}">Ver</a>
                    @can('role-edit')
                        <a class="btn btn-primary" href="{{ route('roles.edit', $role->id) }}">Editar</a>
                    @endcan
                    @can('role-delete')
                        {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                        {!! Form::submit('Eliminar', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    @endcan
                </td>
            </tr>
        @endforeach
    </table>


    {!! $roles->render() !!}

@endsection
