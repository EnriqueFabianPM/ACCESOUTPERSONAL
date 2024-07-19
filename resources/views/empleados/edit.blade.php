@extends('empleados.layout')
@section('content')
<div class="card">
    <div class="card-header">Editar Información de Estudiante</div>
    <div class="card-body">
        <form action="{{ route('empleados.update', $empleado->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="identificador">Identificador:</label>
                <input type="text" class="form-control" id="identificador" name="identificador" value="{{ $empleado->identificador }}" required>
            </div>
            <div class="form-group">
                <label for="Fotoqr">Imagen de Codigo QR</label>
                <div>
                    @if($visitante->Fotoqr)
                        <img src="{{ asset($empleado->Fotoqr) }}" alt="Imagen QR" style="max-width: 200px; max-height: 200px;">
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="Foto">Foto de Empleado</label>
                <div>
                    @if($empleado->Foto)
                        <img src="{{ asset($empleado->Foto) }}" alt="Foto del Empleado" style="max-width: 200px; max-height: 200px;">
                    @endif
                </div>
                <input type="file" class="form-control" id="Foto" name="Foto">
            </div>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $empleado->nombre }}" required>
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="{{ $empleado->apellidos }}" required>
            </div>
            <div class="form-group">
                <label for="areatrabajo">Area de Trabajo:</label>
                <input type="text" class="form-control" id="areatrabajo" name="areatrabajo" value="{{ $empleado->areatrabajo }}" required>
            </div>
            <div class="form-group">
                <label for="telefono">Telefono:</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $empleado->telefono }}" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $empleado->email }}" required>
            </div>
            <div class="form-group">
                <label for="entrada">Entrada:</label>
                <input type="date" class="form-control" id="entrada" name="entrada" value="{{ $empleado->entrada }}">
            </div>
            <div class="form-group">
                <label for="salida">Salida:</label>
                <input type="date" class="form-control" id="salida" name="salida" value="{{ $empleado->salida }}">
            </div>
            <div class="form-group">
                <label for="Fotoqr">Generar nuevo Codigo QR</label>
                <button type="button" id="generateQR" class="btn btn-primary">Generar QR</button>

                <!-- QR Code Display Area -->
                <h1> Codigo QR Nuevo</h1>
                <div id="qrCodeDisplay" class="mb-3"></div>            
                <input type="hidden" name="qrCodeData" id="qrCodeData">
            </div>

            <button type="submit" class="btn btn-success">Actualizar Registro</button>
        </form>
    </div>
</div>

<!-- Include qrcode-generator library -->
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const generateQR = document.getElementById('generateQR');
    const qrCodeDisplay = document.getElementById('qrCodeDisplay');
    const qrCodeDataInput = document.getElementById('qrCodeData');

    fetch('/get-ip')
        .then(response => response.json())
        .then(data => {
            const ip = data.ip;

            generateQR.addEventListener('click', function() {
                const identificadorValue = document.getElementById('identificador').value;

                if (identificadorValue) {
                    const baseURL = `http://${ip}:8000`; // Dynamically set IP address
                    const redirectURL = `${baseURL}/empleados/show/${identificadorValue}`;
                    console.log('Redirect URL:', redirectURL);

                    const typeNumber = 4;
                    const errorCorrectionLevel = 'L';
                    const qr = qrcode(typeNumber, errorCorrectionLevel);
                    qr.addData(redirectURL);
                    qr.make();

                    qrCodeDisplay.innerHTML = qr.createImgTag(10);

                    qrCodeDataInput.value = qr.createDataURL(10);
                } else {
                    alert('Please enter the identifier before generating QR code.');
                }
            });
        });
});
</script>
@endsection
