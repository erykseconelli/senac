<?php

use Slim\Routing\RouteCollectorProxy;
use app\controllers\ControllerUser;
use app\controllers\ControllerHome;
use app\controllers\ControllerEmpresa;
use app\controllers\ControllerFornecedor;


$app->get('/', ControllerHome::class . ':home');
// Define app routes. (Usuarios)
$app->group('/usuario', function (RouteCollectorProxy $group) {
    $group->get('/lista', ControllerUser::class . ':lista');
    $group->get('/cadastro', ControllerUser::class . ':cadastro');
    $group->post('/insert', ControllerUser::class . ':insert');
    $group->get('/alterar/{id}', ControllerUser::class . ':alterar');
});

// Define rotas da Empresa
$app->group('/empresa', function (RouteCollectorProxy $group) {
    $group->get('/lista', ControllerEmpresa::class . ':lista');
    $group->get('/cadastro', ControllerEmpresa::class . ':cadastro');
    $group->post('/insert', ControllerEmpresa::class . ':insert');
});

// Define rotas do Fornecedor
$app->group('/fornecedor', function (RouteCollectorProxy $group) {
    $group->get('/lista', ControllerFornecedor::class . ':lista');
    $group->get('/cadastro', ControllerFornecedor::class . ':cadastro');
    $group->post('/insert', ControllerFornecedor::class . ':insert');
});
