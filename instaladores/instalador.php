<?php

function copyEnvFile($version) {
    $source = __DIR__ . "/.env.version{$version}";
    $destination = __DIR__ . '/../.env';

    if (!file_exists($source)) {
        echo "Archivo de Entorno (.env) para la version {$version} No encontrada.\n";
        return;
    }

    if (!copy($source, $destination)) {
        echo "Copia de archivo de entorno (.env) fallido.\n";
        return;
    }

    echo "Archivo de Entorno (.env) para la version {$version} copiado exitosamente.\n";
}

function runComposerInstall() {
    echo "Ejecutando composer install...\n";
    shell_exec('composer install');
}

function runNpmInstall() {
    echo "Ejecutando npm install...\n";
    shell_exec('npm install');
}

function runMigration() {
    echo "Ejecutando migrations...\n";
    shell_exec('php artisan migrate');
}

echo "Ingresa la version que quieres instalar(por ejemplo: 1.0.0 o 2.0.0): ";
$version = trim(fgets(STDIN));

copyEnvFile($version);
runComposerInstall();
runNpmInstall();
runMigration();

echo "Instalacion completada.\n";
