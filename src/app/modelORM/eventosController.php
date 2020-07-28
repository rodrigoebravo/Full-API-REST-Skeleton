<?php

namespace App\Models\ORM;

use App\Models\AutentificadorJWT;
use App\Models\ORM\evento;

include_once __DIR__ . '/eventos.php';
include_once __DIR__ . '../../modelAPI/IApiControler.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class eventosController
{
    public function agregarEvento($request, $response, $args)
    {
        
        $parametros = $request->getParsedBody();
        $var = $parametros["fecha"];
        $fecha = date("Y-m-d H:i", strtotime($var));
        $evento=new evento;
        $evento->usuario_id=1;
        $evento->fecha=$fecha;
        $evento->save();
        return $response->withJson("Evento guardado con exito", 200);
    }
}