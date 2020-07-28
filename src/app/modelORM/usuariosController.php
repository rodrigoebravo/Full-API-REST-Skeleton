<?php

namespace App\Models\ORM;

use App\Models\AutentificadorJWT;
use App\Models\ORM\usuario;

include_once __DIR__ . '/usuarios.php';
include_once __DIR__ . '../../modelAPI/IApiControler.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class usuariosController
{
    public function registrar($request, $response, $args)
    {
        //$usuario=usuario::all();
        $parametros = $request->getParsedBody();
        if(array_key_exists("usuario",  $parametros)==null)
        {
            return $response->withJson("No ingresa usuario", 200);
        }
        if(array_key_exists("email",  $parametros)==null)
        {
            return $response->withJson("No ingresa email", 200);
        }
        if(array_key_exists("tipo",  $parametros)==null)
        {
            return $response->withJson("No ingresa tipo usuario", 200);
        }
        if(array_key_exists("clave",  $parametros)==null)
        {
            return $response->withJson("No ingresa clave", 200);
        }
        
        $usuarioExiste=usuario::select()
        ->where('usuarios.email', '=', $parametros["email"])
        ->get()
        ->toArray();
        if(count($usuarioExiste)>0)
        {
            return $response->withJson("El mail ya se ecuentra registrado.", 200);
        }
        $tipo=1;
        if(strtoupper($parametros["tipo"])=="VETERINARIO")
        {
            $tipo=2;
        }
        if(strtoupper($parametros["tipo"])=="ADMIN")
        {
            $tipo=3;
        }
        $usuarioNuevo= new usuario;
        $usuarioNuevo->email=$parametros["email"];
        $usuarioNuevo->usuario=$parametros["usuario"];
        $usuarioNuevo->tipo=$tipo;
        $usuarioNuevo->clave=base64_encode($parametros["clave"]);
        $usuarioNuevo->save();
        
        return $response->withJson("Usuario registrado con exito", 200);
    }
    public function login($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        if(array_key_exists("email",  $parametros)==null)
        {
            return $response->withJson("No ingresa email", 200);
        }
        if(array_key_exists("clave",  $parametros)==null)
        {
            return $response->withJson("No ingresa clave", 200);
        }

        $usuarioExiste=usuario::select()
        ->where('usuarios.email', '=', $parametros["email"])
        ->get()
        ->toArray();
        if(count($usuarioExiste)==0)
        {
            return $response->withJson("El mail no se ecuentra registrado.", 200);
        }
        $usuarioExiste=$usuarioExiste[0];
        if(base64_decode($usuarioExiste["clave"])==$parametros["clave"])
        {
            $u=array("idUsuario"=>$usuarioExiste["id"]);
            $token = AutentificadorJWT::CrearToken($u);
            return $response->withJson($token, 200);
        }
        return $response->withJson("Clave incorrecta.", 200);
        
    }
}