@extends('layouts.gestor')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Editar Usuario</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('gestor.users.update', $user) }}">
                @csrf
                @method('PATCH')
                
                @foreach($editableFields as $field)
                <div class="form-group">
                    <label for="{{ $field }}">{{ ucfirst($field) }}</label>
                    <input type="text" class="form-control" id="{{ $field }}" 
                           name="{{ $field }}" value="{{ old($field, $user->$field) }}">
                </div>
                @endforeach
                
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('gestor.users.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection