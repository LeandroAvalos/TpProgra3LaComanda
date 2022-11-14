<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';

require_once './controllers/MesaController.php';
require_once './controllers/EmpleadoController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoController.php';
require_once './middlewares/AutenticadorJWT.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->post('/crearMesa', \MesaController::class . ':AltaMesa');
    $group->get('[/]', \MesaController::class . ':TraerTodasLasMesas');
    $group->get('/{codigoMesa}', \MesaController::class . ':TraerMesaPorCodigo');
  });

$app->group('/empleados', function (RouteCollectorProxy $group) {
    $group->post('/crearEmpleado', \EmpleadoController::class . ':AltaEmpleado');
    $group->get('[/]', \EmpleadoController::class . ':TraerTodosLosEmpleados');
    $group->get('/{alias}', \EmpleadoController::class . ':TraerEmpleadoPorAlias');
  });

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->post('/crearProducto', \ProductoController::class . ':AltaProducto');
    $group->get('[/]', \ProductoController::class . ':TraerTodosLosProductos');
    $group->get('/{id}', \ProductoController::class . ':TraerProducto');
  });

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->post('/crearPedido', \PedidoController::class . ':AltaPedido');
    $group->get('[/]', \PedidoController::class . ':TraerTodosLosPedidos');
    $group->get('/{aliasCliente}', \PedidoController::class . ':TraerPedidoPorAliasDeCliente');
  });

$app->get('[/]', function (Request $request, Response $response) {    
    $payload = json_encode(array("mensaje" => "Slim Framework 4 PHP"));
    
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
