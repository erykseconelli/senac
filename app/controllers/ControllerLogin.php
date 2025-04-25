<?php

namespace app\controllers;

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
            throw new \Exception(  "Restrição: " . $e->getMessage(), 1);

        };
    }

    public function autheticate ($request, $response)
    {
        try {
            $form = $request->getParsedBody();
            # Recuperamos o login do usário
            $login = $form['login'];
            # Recupera a senha do usuário
            $senha = $form['senha'];
            # selecionamos o usuário pelo email ou pelo CPF
            $user = (array) SelectQuery::select()
                ->table('users')
                ->where('cpf', '=', $login, 'OR')
                ->where('email', '=', $login)
                ->fetch();
        } catch (\Exception $e) {
        };
    }
}