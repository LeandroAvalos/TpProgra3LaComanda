<?php
require_once './models/Producto.php';

class ProductoController extends Producto
{
    public function AltaProducto($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        if(isset($parametros['descripcionProducto']) && isset($parametros['tipoProducto']) && isset($parametros['cantidad']) && isset($parametros['precio']) && isset($parametros['codigoDePedido']))
        {
            $descripcionProducto = $parametros['descripcionProducto'];
            $tipoProducto = $parametros['tipoProducto'];
            $cantidad = $parametros['cantidad'];
            $precio = $parametros['precio'];
            $estado = "Pendiente";
            $codigoDePedido = $parametros['codigoDePedido'];
            
            try 
            {
                $producto = new Producto();
                $producto->descripcionProducto = $descripcionProducto;
                $producto->tipoProducto = $tipoProducto;
                $producto->cantidad = $cantidad;
                $producto->precio = $precio;
                $producto->estado = $estado;
                $producto->codigoDePedido = $codigoDePedido;

                $producto->id = $producto->crearProducto();

                if($producto->id > 0)
                {
                    $payload = json_encode(array("mensaje" => "El producto fue creado exitosamente"));
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "Ocurrio un error al crear el producto"));
                }
            } 
            catch (Exception $e) 
            {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Hubo un problema con los parametros enviados para crear el producto"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerProducto($request, $response, $args)
    {
        if(isset($args['id']))
        {
            $producto = $args['id'];
            try 
            {
                $productoTraido = Producto::obtenerProducto($producto);
                $payload = json_encode($productoTraido);
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

    public function TraerTodosLosProductos($request, $response, $args)
    {
        try 
        {
            $listaDeProductos = Producto::obtenerTodosLosProductos();
            $payload = json_encode(array("listaDeProductos" => $listaDeProductos));
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
