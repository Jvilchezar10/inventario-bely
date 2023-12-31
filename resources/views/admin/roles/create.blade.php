@extends('adminlte::page')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Crear nuevo rol</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('roles.index') }}"> Atrás</a>
                    </div>
                </div>
            </div>


            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Ups!</strong> Hubo algunos problemas con tu entrada.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {!! Form::open(['route' => 'roles.store', 'method' => 'POST']) !!}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4">
                    <div class="form-group">
                        <strong>Nombre:</strong>
                        {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Permisos:</strong>
                        <br />
                        <div class="row">
                            @foreach ($permission as $value)
                                <div class="col-md-3">
                                    <label>{{ Form::checkbox('permission[]', $value->id, false, ['class' => 'name']) }}
                                        {{ $value->name }}</label>
                                </div>
                                <br />
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
