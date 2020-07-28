<?php

namespace App\Models\ORM;

use App\Models\AutentificadorJWT;
use App\Models\ORM\usuario;
use App\Models\ORM\mascota;
use App\Models\ORM\tipoMascota;

include_once __DIR__ . '/usuarios.php';
include_once __DIR__ . '/mascotas.php';
include_once __DIR__ . '/tipoMascotas.php';
include_once __DIR__ . '../../modelAPI/IApiControler.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class mascotasController
{
    public function cargaMascota($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $token = $token=$request->getHeader('token');
        if($token==null)
        {
            return $response->withJson("token no ingresado", 200);
        }
        if(array_key_exists("cliente_id",  $parametros)==null)
        {
            return $response->withJson("No ingresa id del cliente", 200);
        }

        if(array_key_exists("nombre",  $parametros)==null)
        {
            return $response->withJson("No ingresa nombre mascota", 200);
        }

        if(array_key_exists("fecha",  $parametros)==null)
        {
            return $response->withJson("No ingresa fecha nacimiento", 200);
        }
        if(array_key_exists("tipo",  $parametros)==null)
        {
            return $response->withJson("No ingresa tipo mascota", 200);
        }
        //$token = AutentificadorJWT::ObtenerData($token[0]); 
        $idTipoMascota=tipoMascota::select()
        ->where('tipo_mascotas.tipo', '=', $parametros["tipo"])
        ->get()
        ->toArray();
        if(count($idTipoMascota)==0)
        {
            return $response->withJson("No se pudo encontrar el tipo de mascota", 200);
        }
        $idTipoMascota=$idTipoMascota[0]["id"];
        $user=usuario::select()
        ->where('usuarios.tipo', '=', 1)
        ->where('usuarios.id', '=', intval($parametros["cliente_id"]))
        ->get()
        ->toArray();
        if(count($user)==0)
        {
            return $response->withJson("No se pudo encontrar el cliente", 200);
        }
        $mascota = new mascota();
        $mascota->nombre=$parametros["nombre"];
        $mascota->fecha_nacimiento=$parametros["fecha"];
        $mascota->cliente_id=$user[0]["id"];
        $mascota->tipo_mascotas_id=$idTipoMascota;
        $var = $parametros["fecha"]. " 20:20";
        $fecha = date("Y-m-d", strtotime($var));
        $mascota->fecha_nacimiento=$fecha;
        $mascota->save();
        return $response->withJson("Mascota creada con exito", 200);
    }
}


