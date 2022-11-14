<?php
require_once './models/Empleado.php';

class EmpleadoController extends Empleado
{
    public function AltaEmpleado($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        if(isset($parametros['nombre']) && isset($parametros['alias']) && isset($parametros['clave']) && isset($parametros['tipoUsuario']))
        {
            $nombre = $parametros['nombre'];
            $alias = $parametros['alias'];
            $clave = $parametros['clave'];
            $tipoUsuario = $parametros['tipoUsuario'];
            $fechaAlta = date('Y-m-d H:i:s');

            try 
            {
                $empleado = new Empleado();
                $empleado->nombre = $nombre;
                $empleado->alias = $alias;
                $empleado->clave = $clave;
                $empleado->tipoUsuario = $tipoUsuario;
                $empleado->fechaAlta = $fechaAlta;

                $empleado->id = $empleado->crearEmpleado();

                if($empleado->id > 0)
                {
                    $payload = json_encode(array("mensaje" => "El perfil del empleado fue creado exitosamente"));
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "Ocurrio un error al crear el perfil del empleado"));
                }
            } 
            catch (Exception $e) 
            {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Hubo un problema con los parametros enviados para crear el empleado"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerEmpleadoPorAlias($request, $response, $args)
    {
        if(isset($args['alias']))
        {
            $empleado = $args['alias'];
            try 
            {
                $empleadoTraido = Empleado::obtenerEmpleadoPorAlias($empleado);
                $payload = json_encode($empleadoTraido);
            } 
            catch (Exception $e) 
            {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosLosEmpleados($request, $response, $args)
    {
        try 
        {
            $listaDeEmpleados = Empleado::obtenerTodosLosEmpleados();
            $payload = json_encode(array("listaDeEmpleados" => $listaDeEmpleados));
        } 
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}
