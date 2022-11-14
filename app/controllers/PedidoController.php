<?php
require_once './models/Pedido.php';

class PedidoController extends Pedido
{
    public function AltaPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        if(isset($parametros['aliasCliente']) && isset($parametros['idMesa']))
        {
            $aliasCliente = $parametros['aliasCliente'];
            $idMesa = $parametros['idMesa'];
            
            try 
            {
                $pedido = new Pedido();
                $pedido->aliasCliente = $aliasCliente;
                $pedido->idMesa = $idMesa;
                $pedido->tiempoPedidoTomado = date('Y-m-d H:i:s');
                $pedido->codigoDePedido = Pedido::generarCodigoAlfanumerico();
                $pedido->foto = Pedido::obtenerPathDeLaFoto($request, $aliasCliente);

                $pedido->id = $pedido->crearPedido();

                if($pedido->id > 0)
                {
                    $payload = json_encode(array("mensaje" => "El pedido fue creado exitosamente"));
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "Ocurrio un error al crear el pedido"));
                }
            } 
            catch (Exception $e) 
            {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Hubo un problema con los parametros enviados para crear el pedido"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPedidoPorAliasDeCliente($request, $response, $args)
    {
        if(isset($args['aliasCliente']))
        {
            $pedido = $args['aliasCliente'];
            try 
            {
                $pedidoTraido = Pedido::obtenerPedidoPorAliasDeCliente($pedido);
                $payload = json_encode($pedidoTraido);
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

    public function TraerTodosLosPedidos($request, $response, $args)
    {
        try 
        {
            $listaDePedidos = Pedido::obtenerTodosLosPedidos();
            $payload = json_encode(array("listaDePedidos" => $listaDePedidos));
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
