<?php

namespace app\controllers;

use app\database\builder\DeleteQuery;
use app\database\builder\InsertQuery;
use app\database\builder\UpdateQuery;

class ControllerUser extends Base
{
    public function lista($request, $response)
    {
        try {
            $TemplateData = [
                'titulo' => 'Lista de Usuários',
            ];
            return $this->getTwig()
                ->render($response, $this->setView('listuser'), $TemplateData)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (\Exception $e) {
            throw new \Exception("Restrição: " . $e->getMessage(), 1);
        };
    }
    public function cadastro($request, $response)
    {
        try {
            $TemplateData = [
                'titulo' => 'Cadastro de Usuários',
            ];
            return $this->getTwig()
                ->render($response, $this->setView('users'), $TemplateData)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (\Exception $e) {
            throw new \Exception("" . $e->getMessage(), 1);
        }
    }
    public function insert($request, $response)
    {
        try {
            $form = $request->getParsedBody();
            $FieldsAndValues = [
                'nome_completo' => $form['nome_completo'],
                'cpf' => $form['cpf'],
                'email' => $form['email'],
                'senha' => password_hash($form['senha'], PASSWORD_DEFAULT)
            ];
            $IsSave = InsertQuery::table('users')->save($FieldsAndValues);
            if (! $IsSave) {
                return $this->Send($response, [
                    'status' => false,
                    'msg' => 'Restrição ao cadastrar usuário',
                ], 500);
            }
            return $this->Send($response, [
                'status' => true,
                'msg' => 'Usuário cadastrado com sucesso',
            ], 200);
        } catch (\Exception $e) {
            throw new \Exception("Restrição: " . $e->getMessage(), 1);
        }
    }
    public function deletar($request, $response)
    {
        try {
            $form = $request->getParsedBody();
            $IsDelete = DeleteQuery::table("users")->where("id", '=', $form['id'])->delete();
            if ($IsDelete) {
                return $this->Send($response, [
                    'status' => true,
                    'msg' => 'Usuário foi deletado',
                ], 200);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 1);
        }
    }
    public function Update($request, $response, $args)
    {
        try {
            $id = $args['id'];
            $TemplateData = [
                'titulo' => 'Lista de Usuários',
                'id' => $id
            ];
            $form = $request->getParsedBody();
            $IsUpdate = UpdateQuery::table('users')->where('id', '=', $id)->update($form);
            if ($IsUpdate) {
                return $this->Send($response, [
                    'status' => false,
                    'msg' => 'Usuário atualizado com sucesso',
                ], 200);
            }
            return $this->Send($response, [
                'status' => true,
                'msg' => 'Restrição ao atualizar usuário',
            ], 500);
        } catch (\Exception $e) {
            throw new \Exception("Restrição: " . $e->getMessage(), 1);
        }
    }
}
