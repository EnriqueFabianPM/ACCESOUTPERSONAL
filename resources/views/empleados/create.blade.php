@extends('empleados.layout')
@section('content')
<div class="card">
    <div class="card-header">Registrar Nuevo Empleado</div>
    <div class="card-body">
        <form id="empleadoForm" action="{{ route('empleados.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="Foto">Foto del Empleado: </label>
                <input type="file" class="form-control-file" id="Foto" name="Foto">
            </div>
            <div class="form-group">
                <label for="identificador">Identificador: <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="identificador" name="identificador" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre: <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos: <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
            </div>
            <div class="form-group">
                <label for="areatrabajo">Area de Trabajo: <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="areatrabajo" name="areatrabajo" required>
            </div>
            <div class="form-group">
                <label for="telefono">Telefono: <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail: <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="entrada">Entrada</label>
                <input type="date" class="form-control" id="entrada" name="entrada">
            </div>
            <div class="form-group">
                <label for="salida">Salida</label>
                <input type="date" class="form-control" id="salida" name="salida">
            </div>
            <div class="form-group">
                <h1> Codigo QR</h1>
                <button type="button" id="generateQR" class="btn btn-primary">Generar QR Code</button>
            </div>
            <!-- QR Code Display Area -->
            <div id="qrCodeDisplay" class="mb-3"></div>            
            <input type="hidden" name="qrCodeData" id="qrCodeData">
            
            <button type="submit" class="btn btn-success">Registrar</button>
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
            const baseURL = 'http://192.168.0.115:8000'; // Replace '192.168.1.100' with your actual IPv4 address
            const redirectURL = `${baseURL}/empleados/show/${identificadorValue}`;
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
