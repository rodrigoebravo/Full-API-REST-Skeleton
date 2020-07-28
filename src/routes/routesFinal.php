<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\ORM\usuariosController;
use App\Models\ORM\tipoMascotasController;
use App\Models\ORM\mascotasController;
use App\Models\ORM\turnosController;

include_once __DIR__ . '/../../src/app/modelORM/usuariosController.php';
include_once __DIR__ . '/../../src/app/modelORM/tipoMascotasController.php';
include_once __DIR__ . '/../../src/app/modelORM/mascotasController.php';
include_once __DIR__ . '/../../src/app/modelORM/turnosController.php';

return function (App $app) {

    $app->group('',function(){
        $this->post('/registro', usuariosController::class . ':registrar');
        $this->post('/login', usuariosController::class . ':login');
        $this->post('/tipo_mascota', tipoMascotasController::class . ':cargaTipoMascota')->add(Middleware::class . ":validarToken");;
        $this->post('/mascotas', mascotasController::class . ':cargaMascota')->add(Middleware::class . ":validarToken");;
    });
    $app->group('/turnos',function(){
        $this->post('/mascota', turnosController::class . ':crearTurno');
        $this->get('/mascota/{id_mascota}', turnosController::class . ':verTurno');
        $this->get('/{id_usuario}', turnosController::class . ':login');
    });
};
