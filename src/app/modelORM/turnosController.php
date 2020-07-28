<?php

namespace App\Models\ORM;

use App\Models\AutentificadorJWT;
use App\Models\ORM\usuario;
use App\Models\ORM\turno;

include_once __DIR__ . '/usuarios.php';
include_once __DIR__ . '/turnos.php';
include_once __DIR__ . '../../modelAPI/IApiControler.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class turnosController
{
    public function crearTurno($request, $response, $args)
    {
        $turno=new turno;
        $turno
    }    
}


