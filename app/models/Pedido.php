<?php

class Pedido
{
    public $id;
    public $codigoDePedido;
    public $foto;
    public $tiempoPedidoTomado;
    public $aliasCliente;
    public $idMesa;
    public $tiempoFinalizacion;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (codigoDePedido, foto, tiempoPedidoTomado, aliasCliente, idMesa) VALUES 
        (:codigoDePedido, :foto, :tiempoPedidoTomado, :aliasCliente, :idMesa)");
        $consulta->bindValue(':codigoDePedido', $this->codigoDePedido, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoPedidoTomado', $this->tiempoPedidoTomado, PDO::PARAM_STR);
        $consulta->bindValue(':aliasCliente', $this->aliasCliente, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodosLosPedidos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedidoPorAliasDeCliente($pedidoAlias)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE aliasCliente = :aliasCliente");
        $consulta->bindValue(':aliasCliente', $pedidoAlias, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function generarCodigoAlfanumerico()
    {
        return substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
    }

    public static function obtenerPathDeLaFoto($request, $aliasCliente)
    {
        $archivoSubido = $request->getUploadedFiles()['foto'];
        if ($archivoSubido->getError() === UPLOAD_ERR_OK) 
        {
          $nombreDelArchivo = $aliasCliente.'.jpg';
          if (!file_exists('../app/fotos/pedidos/'))
          {
              mkdir('../app/fotos/pedidos/', 0777, true);
          }
          $ruta = "../app/fotos/pedidos/".$nombreDelArchivo;
          $archivoSubido->moveTo($ruta);
        }
        else
        {
          $ruta = "";
        }
  
        return $ruta;
    }
   
}
