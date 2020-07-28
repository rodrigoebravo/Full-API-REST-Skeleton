<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\ORM\usuariosController;


include_once __DIR__ . '/../../src/app/modelORM/usuariosController.php';

return function (App $app) {

    $app->group('',function(){
        $this->post('/users', usuariosController::class . ':registrar');
        $this->post('/login', usuariosController::class . ':login');

        $this->post('/eventos', usuariosController::class . ':login')->add(Middleware::class . ":validarToken");
        $this->get('/eventos', usuariosController::class . ':login')->add(Middleware::class . ":validarToken");
        $this->put('/eventos/{id}', usuariosController::class . ':login')->add(Middleware::class . ":validarToken");

        $this->put('/users', usuariosController::class . ':login')->add(Middleware::class . ":validarToken");
        $this->post('/login', usuariosController::class . ':login')->add(Middleware::class . ":validarToken");
        $this->post('/login', usuariosController::class . ':login')->add(Middleware::class . ":validarToken");
    });
};
