<?php

namespace App\Models\ORM;

use App\Models\AutentificadorJWT;
use App\Models\ORM\usuario;
use App\Models\ORM\tipoMascota;

include_once __DIR__ . '/usuarios.php';
include_once __DIR__ . '/tipoMascotas.php';
include_once __DIR__ . '../../modelAPI/IApiControler.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class tipoMascotasController
{
    public function cargaTipoMascota($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $token = $token=$request->getHeader('token');
        if($token==null)
        {
            return $response->withJson("token no ingresado", 200);
        }
        if(array_key_exists("tipo",  $parametros)==null)
        {
            return $response->withJson("No ingresa tipo mascota", 200);
        }
        $token = AutentificadorJWT::ObtenerData($token[0]);
        if($token==null)
        {
            $mensaje="Falló autenticacion";
            return false;
        }
        $usuario=usuario::find($token->idUsuario);
        if($usuario!=null)
        {
            if($usuario->tipo==3)
            {
                $tipoMascota=new tipoMascota;
                $tipoMascota->tipo=$parametros["tipo"];
                $tipoMascota->save();
                return $response->withJson("El tipo de mascota fue creado con exito", 200);
            }
            return $response->withJson("No puede crear tipos de mascota", 200);
        }else{
            return $response->withJson("No se pudo verificar el usuario", 200);   
        }
    }
}
