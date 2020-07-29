<?php

namespace App\Models\ORM;

use App\Models\AutentificadorJWT;
use App\Models\ORM\evento;
use ArrayObject;
include_once __DIR__ . '/eventos.php';
include_once __DIR__ . '../../modelAPI/IApiControler.php';
include_once __DIR__ . '../../modelAPI/AutentificadorJWT.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class eventosController
{
    public function agregarEvento($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $token = $request->getHeader('token');
        
        if($token==null)
        {
            return $response->withJson("token no ingresado", 200);
        }
        if(array_key_exists("fecha",  $parametros)==null)
        {
            return $response->withJson("No ingresa fecha del evento", 200);
        }
        
        if(array_key_exists("descripcion",  $parametros)==null)
        {
            return $response->withJson("No ingresa descripcion del evento", 200);
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
            if($usuario->tipo==2)
            {
                $var = $parametros["fecha"];
                $fecha = date("Y-m-d H:i", strtotime($var));
                $evento=new evento;
                $evento->usuario_id=$usuario->id;
                $evento->fecha=$fecha;
                $evento->descripcion=$parametros["descripcion"];
                $evento->save();
                return $response->withJson("Evento guardado con exito", 200);
            }
            return $response->withJson("No puede crear eventos como administrador", 200);
        }else{
            return $response->withJson("No se pudo verificar el usuario", 200);
        }


    }
    public function traerEventos($request, $response, $args)
    {
        $token = $request->getHeader('token');

        if($token==null)
        {
            return $response->withJson("token no ingresado", 200);
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
            if($usuario->tipo==2)
            {
                $eventos = evento::select()
                ->where('eventos.usuario_id', '=', $usuario->id)
                ->get()
                ->toArray();

                return $response->withJson($eventos, 200);
            }
            else if($usuario->tipo==1)
            {
                $eventos = evento::all();
                //usort($arrayToSort, "cmp");
                return $response->withJson($eventos, 200);
            }
            
        }
        else
        {
            return $response->withJson("No se pudo verificar el usuario", 200);
        }
    }
    public function modificarEvento($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        if($parametros== null)
        {
            return $response->withJson("No ingreso parametros", 200);
        }
        $token = $request->getHeader('token');
        if($token==null)
        {
            return $response->withJson("fallo auth", 200);
        }

        $token = AutentificadorJWT::ObtenerData($token[0]);
        if($token==null)
        {
            return $response->withJson("fallo auth", 200);
        }

        $usuario=usuario::find($token->idUsuario);

        if($args["id"]==null)
        {
            return $response->withJson("Error al obtener id", 200);
        }
        if(array_key_exists("fecha",  $parametros)==null)
        {
            return $response->withJson("No ingresa fecha del evento", 200);
        }
        if($usuario!=null)
        {

            $eventos = evento::select()
            ->where('eventos.id', '=', $args["id"])
            ->get()
            ->toArray();
            if(count($eventos)==0)
            {
                return $response->withJson("No existe evento con este id", 200);
            }
            else
            {
                if($eventos[0]["usuario_id"]!=$usuario->id)
                {
                    return $response->withJson("Usted nop puede modificar este id", 200);
                }
                else
                {
                    $evento=evento::find($eventos[0]["id"]);
                    $var = $parametros["fecha"];
                    $fecha = date("Y-m-d H:i", strtotime($var));
                    $evento->fecha=$fecha;
                    $evento->save();
                }
            }
            return $response->withJson($evento, 200);
        }
        else
        {
            return $response->withJson("No se pudo verificar el usuario", 200);
        }
    }
    public function cargarImagen($request, $response, $args){
        $token = $request->getHeader('token');
        if($token==null)
        {
            return $response->withJson("fallo auth", 200);
        }

        $token = AutentificadorJWT::ObtenerData($token[0]);
        if($token==null)
        {
            return $response->withJson("fallo auth", 200);
        }
        
        $usuario=usuario::find($token->idUsuario);
        if($usuario!=null)
        {
            $imagen=$this->guardarArchivo($request,$usuario->id);
            return $response->withJson("Archivo guardado ".$imagen, 200);
        } 
        else
        {
            return $response->withJson("No se encontraron usuarios.", 200);
        }   

    }


    private function guardarArchivo($request,$titulo)
    {
        $archivos = $request->getUploadedFiles();
        $destino="./imagenes/";
        if(!is_dir($destino))
        {
            mkdir($destino);
        }

        if(isset($archivos['imagen']))
        {
            
            $idAnterior=$archivos['imagen']->getClientFilename();
            $extension= explode(".", $idAnterior)  ;
            $extension=array_reverse($extension);
            $archivos['imagen']->moveTo($destino.$titulo.".".$extension[0]);
            $img = $destino.$titulo.".".$extension[0];

    
            //$resultado = imagepng($im, $img);
            return $img;
        }
        return null;
    }
}