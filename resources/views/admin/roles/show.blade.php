@extends('adminlte::page')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Mostrar rol</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('roles.index') }}"> Atrás</a>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Nombre:</strong>
                {{ $role->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Permisos:</strong>
                <br />
                <div class="row">
                    @php $count = 0; @endphp
                    @foreach ($rolePermissions as $v)
                        <div class="col-lg-3">
                            <label class="label label-success">{{ $v->name }}</label>
                        </div>
                        @php $count++; @endphp
                        @if ($count % 4 == 0)
                </div>
                <div class="row">
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@endsection
