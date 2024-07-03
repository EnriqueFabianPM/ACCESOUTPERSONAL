@extends('visitantes.layout')
@section('content')
<div class="card">
    <div class="card-header">Registrar Nuevo Visitante</div>
    <div class="card-body">
        <form id="visitanteForm" action="{{ route('visitantes.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <!-- QR Code Display Area -->
            <div id="qrCodeDisplay" class="mb-3"></div>

            <div class="form-group">
                <label for="Fotoqr">Imagen de Codigo QR</label>
                <input type="file" class="form-control-file" id="Fotoqr" name="Fotoqr">
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
                <label for="motivo">Motivo de Visita: <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="motivo" name="motivo" required>
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

            <button type="button" id="generateQR" class="btn btn-primary">Generar QR Code</button>
            <button type="submit" class="btn btn-success">Registrar</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('estudianteForm');
        const qrCodeDisplay = document.getElementById('qrCodeDisplay');

        // Function to generate QR code
        function generateQRCode(text) {
            qrCodeDisplay.innerHTML = ''; // Clear previous QR code if any
            const qr = new QRCode(qrCodeDisplay, {
                text: text,
                width: 200,
                height: 200
            });
        }

        // Event listener for Generate QR Code button
        document.getElementById('generateQR').addEventListener('click', function () {
            const identificador = document.getElementById('identificador').value.trim();
            if (identificador) {
                generateQRCode(identificador);
            } else {
                alert('Por favor, ingrese un identificador válido antes de generar el código QR.');
            }
        });

        // Submit form listener (optional, you can handle form submission in Laravel)
        form.addEventListener('submit', function (event) {
            // Optionally, you can do additional form validation or processing here
            // before allowing the form submission to proceed.
        });
    });
</script>
@endsection
