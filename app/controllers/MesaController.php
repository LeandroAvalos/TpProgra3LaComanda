<?php
require_once './models/Mesa.php';

class MesaController extends Mesa
{
    public function AltaMesa($request, $response, $args)
    {
        try 
        {
            $mesa = new Mesa();
            $mesa->codigoMesa = rand(10000,99999);
            $mesa->estado = "Libre";

            $mesa->id = $mesa->crearMesa();

            if($mesa->id > 0)
            {
                $payload = json_encode(array("mensaje" => "La mesa fue creada exitosamente"));
            }
            else
            {
                $payload = json_encode(array("mensaje" => "Ocurrio un error al crear la mesa"));
            }
        } 
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
        }
       

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodasLasMesas($request, $response, $args)
    {
        try 
        {
            $listaDeMesas = Mesa::obtenerTodasLasMesas();
            $payload = json_encode(array("listaDeMesas" => $listaDeMesas));
        } 
        catch (Exception $e) 
        {
            $payload = json_encode(array("mensaje" => $e->getMessage()));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerMesaPorCodigo($request, $response, $args)
    {
        if(isset($args['codigoMesa']))
        {
            $mesa = $args['codigoMesa'];
            try 
            {
                $mesaTraida = Mesa::obtenerMesaPorCodigo($mesa);
                $payload = json_encode($mesaTraida);
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
}
