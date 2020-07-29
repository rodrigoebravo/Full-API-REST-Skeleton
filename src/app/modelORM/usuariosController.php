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
        if(array_key_exists("nombre",  $parametros)==null)
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
        $arr1 = str_split($parametros["nombre"]);
        for ($i=0; $i < count($arr1) ; $i++) { 
            if($arr1[$i]==" ")
            {
                return $response->withJson("El nombre debe ir sin espacios", 200);
            }
        }
        if(strlen($parametros["clave"])<4)
        {
            return $response->withJson("La clave debe tener al menos 4 caracteres", 200);
        }

        $usuarioExiste=usuario::select()
        ->where('usuarios.email', '=', $parametros["email"])
        ->get()
        ->toArray();

        if(count($usuarioExiste)>0)
        {
            return $response->withJson("El mail ya se ecuentra registrado.", 200);
        }

        $usuarioExiste=usuario::select()
        ->where('usuarios.nombre', '=', $parametros["nombre"])
        ->get()
        ->toArray();

        if(count($usuarioExiste)>0)
        {
            return $response->withJson("El nombre ya se ecuentra registrado.", 200);
        }
        
        $tipo=0;
        if(strtoupper($parametros["tipo"])=="ADMIN")
        {
            $tipo=1;
        }
        if(strtoupper($parametros["tipo"])=="USER")
        {
            $tipo=2;
        }
        if($tipo==0)
        {
            return $response->withJson("El tipo de usuario es incorrecto.", 200);
        }

        $usuarioNuevo= new usuario;
        $usuarioNuevo->email=strtoupper($parametros["email"]);
        $usuarioNuevo->nombre=strtoupper($parametros["nombre"]);
        $usuarioNuevo->tipo=$tipo;
        $usuarioNuevo->clave=base64_encode($parametros["clave"]);
        $usuarioNuevo->save();
        
        return $response->withJson("Usuario registrado con exito", 200);
    }

    public function login($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $nombre="";
        $email="";
        if(!isset($parametros))
        {
            return $response->withJson("No ingresó parametros", 200);
        }
        if(array_key_exists("email",  $parametros)==null)
        {
            if(array_key_exists("nombre",  $parametros)==null)
            {
                return $response->withJson("Debe ingresar nombre o email", 200);
            }
            else
            {
                $nombre=$parametros["nombre"];
            }
        }
        else
        {
            $email=$parametros["email"];
        }
        if(array_key_exists("nombre",  $parametros)==null)
        {
            if(array_key_exists("email",  $parametros)==null)
            {
                return $response->withJson("Debe ingresar nombre o email", 200);
            }
            else
            {
                $email=$parametros["email"];
            }
        }
        else
        {
            $nombre=$parametros["nombre"];
        }
        
        if(array_key_exists("clave",  $parametros)==null)
        {
            return $response->withJson("No ingresa clave", 200);
        }
        
        if($email!="")
        {
            $usuarioExiste=usuario::select()
            ->where('usuarios.email', '=', $email)
            ->get()
            ->toArray();
        }else if($nombre!="")
        {
            $usuarioExiste=usuario::select()
            ->where('usuarios.nombre', '=', $nombre)
            ->get()
            ->toArray();
        }

        if(count($usuarioExiste)==0)
        {
            return $response->withJson("El mail no se ecuentra registrado.", 200);
        }

        $usuarioExiste=$usuarioExiste[0];
        if(base64_decode($usuarioExiste["clave"])==$parametros["clave"])
        {
            $u=array("idUsuario"=>$usuarioExiste["id"]);
            $token = AutentificadorJWT::CrearToken($u);
            return $response->withJson(array("token"=>$token), 200);
        }
        return $response->withJson("Clave incorrecta.", 200);
        
    }
}