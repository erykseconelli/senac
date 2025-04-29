<?php

namespace app\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use app\database\builder\SelectQuery;

class ControllerLogin extends Base
{
    public function login($request, $response)
    {
        try {
            $TemplateData = [
                'titulo' => 'Autenticação'
            ];

            return $this->getTwig()
                ->render($response, $this->setView('login'), $TemplateData)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 1);
        }
    }

    public function authenticate($request, $response)
    {
        try {


            $form = $request->getParsedBody();
            $login = $form['cpf'];
            $senha = $form['senha'];

            // Buscar usuário pelo CPF ou Email
            $user = (array) SelectQuery::select()
                ->table('users')
                ->where('cpf', '=', $login, 'OR')
                ->where('email', '=', $login)
                ->fetch();
            if (!$user || $user[0] === false) {
                return $this->Send($response, [
                    'status' => false,
                    'msg' => 'Usuário não encontrado'
                ]);
            }
            if (!password_verify($senha, $user['senha'])) {
                return $this->Send($response, [
                    'status' => false,
                    'msg' => 'Usuário não encontrado'
                ]);
            }
            // Criar sessão
            session_regenerate_id(true);
            $_SESSION['usuario'] = [
                'id' => $user['id'],
                'nome' => $user['nome'],
                'email' => $user['email'],
                'logado' => true,
            ];
            return $this->Send($response, [
                'status' => true,
                'msg' => 'Seja bem-vindo ' . $user['nome'],
            ]);
        } catch (\Exception $e) {
            return $response->withJson([
                'status' => false,
                'msg' => 'Erro interno: ' . $e->getMessage()
            ]);
        }
    }



    // .. seus outros métodos

    public function sair(Request $request, Response $response, array $args): Response
    {
        session_start();

        // Destroi a sessão
        session_destroy();

        // Limpa o cookie da sessão se necessário
        setcookie(session_name(), '', time() - 3600, '/');

        // Retorna resposta JSON
        $response->getBody()->write(json_encode([
            'status' => true,
            'msg' => 'Logout realizado com sucesso'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
