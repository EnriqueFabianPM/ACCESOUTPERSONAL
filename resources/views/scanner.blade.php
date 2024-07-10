@extends('layouts.app')

@section('title', 'Escaneo de Codigo QR')

@section('content')
    <div class="container mt-4">
        <center><h1>Escanear Codigo QR para entrar a la universidad</h1></center>
        <div class="form-group mt-5">
            <label for="qrInput">Coloque el cursor en el campo de abajo y escanee el Código QR:</label>
            <input type="text" id="qrInput" class="form-control" autofocus>
        </div>
        <p id="loadingMessage" class="text-center mt-3">Iniciando escáner de QR...</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const qrInput = document.getElementById('qrInput');
            let scanTimeout;

            qrInput.addEventListener('input', function () {
                clearTimeout(scanTimeout);

                scanTimeout = setTimeout(function() {
                    let scannedData = qrInput.value.trim();
                    console.log('Scanned Data:', scannedData); // For debugging purposes

                    // Adjust the scanned data format if necessary
                    scannedData = scannedData.replace(/>/g, ':').replace(/&/g, '/').replace(/^http:\/\//, 'http://').replace(/^http>/, 'http://').replace(/^http>&&/, 'http://');

                    console.log('Processed Data:', scannedData); // For debugging purposes

                    if (scannedData.includes('estudiantes/show/')) {
                        window.location.href = scannedData;
                    } else if (scannedData.includes('empleados/show/')) {
                        window.location.href = scannedData;
                    } else if (scannedData.includes('visitantes/show/')) {
                        window.location.href = scannedData;
                    } else {
                        alert('Error: Código QR inválido, por favor ingrese nuevamente.');
                        qrInput.value = '';
                    }
                }, 500); // Delay to ensure the full QR code is captured
            });
        });
    </script>
@endsection
