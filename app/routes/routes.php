    <?php

    use Slim\Routing\RouteCollectorProxy;
    use app\controllers\ControllerUser;
    use app\controllers\ControllerHome;
    use app\controllers\ControllerEmpresa;
    use app\controllers\ControllerFornecedor;
    use app\controllers\ControllerLogin;
    use app\middlewares\Auth;

    // Página inicial (home)
    $app->get('/', ControllerHome::class . ':home')->add(Auth::authenticate());
    $app->get('/home', ControllerHome::class . ':home')->add(Auth::authenticate());

    // Logout
    $app->post('/logout', ControllerLogin::class . ':sair');
    $app->group('/home', function (RouteCollectorProxy $group) {
        $group->post('/poweron', ControllerHome::class . ':poweron');
    });

    // Login
    $app->get('/login', ControllerLogin::class . ':login')->add(Auth::authenticate());
    $app->post('/login/authenticate', ControllerLogin::class . ':authenticate');

    // Usuário
    $app->group('/usuario', function (RouteCollectorProxy $group) {
        $group->get('/lista', ControllerUser::class . ':lista')->add(Auth::authenticate());
        $group->get('/cadastro', ControllerUser::class . ':cadastro');
        $group->post('/insert', ControllerUser::class . ':insert');
        $group->get('/alterar/{id}', ControllerUser::class . ':alterar')->add(Auth::authenticate());
        $group->post('/deletar', ControllerUser::class . ':deletar');
    });

    // Empresa
    $app->group('/empresa', function (RouteCollectorProxy $group) {
        $group->get('/lista', ControllerEmpresa::class . ':lista')->add(Auth::authenticate());
        $group->get('/cadastro', ControllerEmpresa::class . ':cadastro')->add(Auth::authenticate());
        $group->post('/insert', ControllerEmpresa::class . ':insert');
        $group->get('/alterar/{id}', ControllerEmpresa::class . ':alterar')->add(Auth::authenticate());
        $group->post('/deletar', ControllerEmpresa::class . ':deletar');
    });

    // Fornecedor
    $app->group('/fornecedor', function (RouteCollectorProxy $group) {
        $group->get('/lista', ControllerFornecedor::class . ':lista')->add(Auth::authenticate());
        $group->get('/cadastro', ControllerFornecedor::class . ':cadastro')->add(Auth::authenticate());
        $group->post('/insert', ControllerFornecedor::class . ':insert');
        $group->get('/alterar/{id}', ControllerFornecedor::class . ':alterar')->add(Auth::authenticate());
        $group->post('/deletar', ControllerFornecedor::class . ':deletar');
    });
