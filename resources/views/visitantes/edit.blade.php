@extends('visitantes.layout')
@section('content')
<div class="card">
    <div class="card-header">Editar Información de Visitante</div>
    <div class="card-body">
        <form action="{{ route('visitantes.update' , $visitante->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="identificador">Identificador:</label>
                <input type="text" class="form-control" id="identificador" name="identificador" value="{{ $visitante->identificador }}" required>
            </div>
            <div class="form-group">
                <label for="Fotoqr">Imagen de Codigo QR</label>
                <div>
                    @if($visitante->Fotoqr)
                        <img src="{{ asset($visitante->Fotoqr) }}" alt="Imagen QR" style="max-width: 200px; max-height: 200px;">
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $visitante->nombre }}" required>
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="{{ $visitante->apellidos }}" required>
            </div>
            <div class="form-group">
                <label for="motivo">Motivo de Visita:</label>
                <input type="text" class="form-control" id="motivo" name="motivo" value="{{ $visitante->motivo }}" required>
            </div>
            <div class="form-group">
                <label for="telefono">Telefono:</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $visitante->telefono }}" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $visitante->email }}" required>
            </div>
            <div class="form-group">
                <label for="entrada">Entrada:</label>
                <input type="date" class="form-control" id="entrada" name="entrada" value="{{ $visitante->entrada }}">
            </div>
            <div class="form-group">
                <label for="salida">Salida:</label>
                <input type="date" class="form-control" id="salida" name="salida" value="{{ $visitante->salida }}">
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

    generateQR.addEventListener('click', function() {
        const identificadorValue = document.getElementById('identificador').value; // Assuming 'identificador' is the identifier field

        if (identificadorValue) {
            // Construct URL dynamically
            const baseURL = 'http://192.168.100.67:8000'; // Replace '192.168.1.100' with your actual IPv4 address
            const redirectURL = `${baseURL}/visitantes/show/${identificadorValue}`;
            console.log('Redirect URL:', redirectURL); // Debugging line

            // Create QR code instance
            const typeNumber = 4; // Example: adjust as needed
            const errorCorrectionLevel = 'L'; // Example: adjust as needed
            const qr = qrcode(typeNumber, errorCorrectionLevel);
            qr.addData(redirectURL);
            qr.make();

            // Display QR code
            qrCodeDisplay.innerHTML = qr.createImgTag(10); // Example: adjust size

            // Store QR code data in hidden input field
            qrCodeDataInput.value = qr.createDataURL(10); // Store as data URL
        } else {
            alert('Please enter data before generating QR code.');
        }
    });
});
</script>
@endsection
