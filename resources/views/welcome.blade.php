@extends('layouts.app')

@section('content')
    <div class="container mt-4" >
        <!-- Example content sections -->
        <section id="banner" style=" background-size: cover; background-position: center;">
            <center><img src="{{ asset('imagenes/Fondo.jpg') }}" alt="Banner Image" style="width: 700px; height: 350px;"> </center>
        </section>
        <section id="bienvenida" style="background-color: #ff63dd; padding: 10px; color: #0c0c0c;">
            <center><h1>Bienvenidos a Acceso UTSC</h1> </center>
            <h2>SAIIUT es un acronimo para Sistema Automatizado Integral de Información de las Universidades Tecnológicas., es decir,<br> Un sistema de información para universidades basadas en tecnologia y 
                <br> en este caso una herramienta para mantener el control de los estudiantes,empleados y visitantes para el plantel <br>para este proyecto seria el registro de personas que entren a la universidad<br> 
                para eso creamos estas tablas para hacer el registro manual de ellas.
            </h2>
        </section>
        <section id="botones" style="background-color: #b075f3; padding: 10px;">
            <center><h1>Escoje a cual tabla de registros deseas ir: </h1></center>
            <div class="text-center mt-5">
                <a href="{{ route('estudiantes.index') }}" style="background-color: #1571da; padding: 20px; color: #0c0c0c;" class="btn btn-primary btn-lg mr-3">Tabla de Estudiantes</a>
                <a href="{{ route('empleados.index') }}" style="background-color: #25b7e4; padding: 20px; color: #0c0c0c;" class="btn btn-primary btn-lg mr-3">Tabla de Empleados</a>
                <a href="{{ route('visitantes.index') }}" style="background-color: #1f33e6; padding: 20px; color: #0c0c0c;" class="btn btn-primary btn-lg">Tabla de Visitantes</a>
            </div>
            <center><h1>Ir a la pagina de Escaneo: </h1></center>
            <div class="text-center mt-5">
                <a href="{{ route('scanner') }}" style="background-color: #1571da; padding: 20px; color: #0c0c0c;" class="btn btn-primary btn-lg mr-3">Escanear Codigo QR</a>
            </div>
        </section>
        <!--
        <section id="MISION">
            
        </section>

        <section id="VISION">
            
        </section>

        <section id="VALORES">
            
        </section>

        <section id="AVANCES">
            <!-- Avances content 
        </section>

        <section id="SOBRENOSOTROS">
            <!-- Sobre nosotros content 
        </section>
        -->
    </div>
@endsection
