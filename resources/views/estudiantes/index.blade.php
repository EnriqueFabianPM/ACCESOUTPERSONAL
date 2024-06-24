@extends('estudiantes.layout')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h2>Sistema de Registro de Estudiantes UTSC</h2>
                </div>
                <div class="card-body">
                    <a href="{{ route('estudiantes.create') }}" class="btn btn-success btn-sm" title="Add New Student">
                        <i class="fa fa-plus" aria-hidden="true"></i> Registrar Nuevo Estudiante
                    </a>
                    <br />
                    <br />
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Codigo QR</th>
                                    <th>Foto</th>
                                    <th>Identificador</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Semestre</th>
                                    <th>Grupo</th>
                                    <th>Email</th>
                                    <th>Entrada</th>
                                    <th>Salida</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($estudiantes as $estudiante)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><img src="/ImagenesQREstudiantes/{{ $estudiante->Fotoqr }}" width="100px"></td>
                                    <td><img src="/FotosEstudiantes/{{ $estudiante->Foto }}<" width="100px"></td>
                                    <td>{{ $estudiante->identificador }}</td>
                                    <td>{{ $estudiante->nombre }}</td>
                                    <td>{{ $estudiante->apellidos }}</td>
                                    <td>{{ $estudiante->semestre }}</td>
                                    <td>{{ $estudiante->grupo }}</td>
                                    <td>{{ $estudiante->email }}</td>
                                    <td>{{ $estudiante->entrada }}</td>
                                    <td>{{ $estudiante->salida }}</td>
                                    <td>
                                        <a href="{{ route('estudiantes.show', $estudiante->id) }}"
                                            title="View Student" class="btn btn-info btn-sm">
                                            <i class="fa fa-eye" aria-hidden="true"></i> View
                                        </a>
                                        <a href="{{ route('estudiantes.edit', $estudiante->id) }}"
                                            title="Edit Student" class="btn btn-primary btn-sm">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                        </a>

                                        <form method="POST"
                                            action="{{ route('estudiantes.destroy', $estudiante->id) }}"
                                            accept-charset="UTF-8" style="display: inline">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                title="Delete Student"
                                                onclick="return confirm('Are you sure you want to delete this student?')">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
