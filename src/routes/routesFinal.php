<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\ORM\usuariosController;
use App\Models\ORM\eventosController;


include_once __DIR__ . '/../../src/app/modelORM/eventosController.php';
include_once __DIR__ . '/../../src/app/modelORM/usuariosController.php';

return function (App $app) {

    $app->group('',function(){
        $this->post('/users', usuariosController::class . ':registrar');
        $this->post('/login', usuariosController::class . ':login');

        $this->post('/eventos', eventosController::class . ':agregarEvento');//->add(Middleware::class . ":validarToken");

        // $this->get('/eventos', eventosController::class . ':login')->add(Middleware::class . ":validarToken");
        // $this->put('/eventos/{id}', eventosController::class . ':login')->add(Middleware::class . ":validarToken");

        // $this->put('/users', eventosController::class . ':login')->add(Middleware::class . ":validarToken");
        // $this->post('/login', eventosController::class . ':login')->add(Middleware::class . ":validarToken");
        // $this->post('/login', eventosController::class . ':login')->add(Middleware::class . ":validarToken");
    });
};
