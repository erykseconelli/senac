<?php

namespace app\middlewares;

class Auth
{
    public static function authenticate()
    {
        $auth = function ($request, $handler) {
            #Middleware executa a lógica de autenticação
            #e caso seja auticado ou autorizado, retorna a rota final
            $response = $handler->handle($request);
            #CAPTURAMOS O METODOS DE REQUISIÇÃO.
            $method = $request->getMethod();
            #CAPTURAMOS A PAGINA SOLICITADA PELO USUÁRIO
            $pagina = $request->getRequestTarget();
            #CASO METODOS SEJA GET VALIDAMOS O NIVEL DE ACESSO.
            if ($method == 'GET') {
                #Verifica se a pagina que esta sendo acessada é a de Login.
                if ($pagina == '/login') {
                    #Verifica se o usuário esta autenticado
                    if (isset($_SESSION['usuario']) and boolval($_SESSION['usuario']['logado'])) {
                        #Caso o usuário já esteja autenticado redireciona para a pagina inicial
                        return $response->withHeader('Location', HOME)->withStatus(302);
                        #Encerra a execução do middlewares
                        die;
                    }
                    #destrói a variavel de sessão
                    session_destroy();
                    #Retorna a reposta direcionado para a rota
                    return $response;
                    #Encerra a execução do middlewares
                }
                #CASO USUÁRIO NÃO ESTEJA LOGADO, DIRECIONAMOS PARA AUTENTICAÇÃO
                if ((empty($_SESSION['usuario']) or !boolval($_SESSION['usuario']['logado'])) and ($pagina !== '/login')) {
                    return $response->withHeader('Location', HOME . '/login')->withStatus(302);
                    session_destroy();
                    die();
                }
                return $response;
            }
        };
        return $auth;
    }
}
