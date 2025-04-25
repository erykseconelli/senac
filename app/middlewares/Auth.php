<?php

namespace app\middlewares;

class Auth
{
    public static function authenticate()
    {
        $auth = function ($request, $handler) {
            # Middleware executa a lógica de autenticação
            # E caso seja autenticado ou autorizado, retorna a rota final
            $response = $handler->handle($request);
            # Capturamos o metodos de requisição
            $method = $request->getMethod();
            # Capturamos a pagina solicitada pelo usuário
            $pagina = $request->getRequestTarget();
        };
        return $auth; 
    }
}