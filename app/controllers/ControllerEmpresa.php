<?php

namespace app\controllers;

use app\database\builder\InsertQuery;
use app\database\builder\DeleteQuery;

class ControllerEmpresa extends Base
{
    public function lista($request, $response)
    {
        try {
            $TemplateData = [
                'titulo' => 'Lista de Empresas',
            ];
            return $this->getTwig()
                ->render($response, $this->setView('listempresa'), $TemplateData)
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
                'titulo' => 'Cadastro de Empresas',
            ];
            return $this->getTwig()
                ->render($response, $this->setView('empresa'), $TemplateData)
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
                'empresa' => $form['empresa'],
                'cnpj' => $form['cnpj'],
                'endereco' => $form['endereco']
            ];
            $IsSave = InsertQuery::table('empresa')->save($FieldsAndValues);
            if (! $IsSave) {
                return $this->Send($response, [
                    'status' => false,
                    'msg' => 'Restrição ao cadastrar empresa',
                ], 500);
            }
            return $this->Send($response, [
                'status' => true,
                'msg' => 'Empresa cadastrada com sucesso',
            ], 200);
        } catch (\Exception $e) {
            throw new \Exception("Restrição: " . $e->getMessage(), 1);
        }
    }
    public function deletar($request, $response)
    {
        try {
            $form = $request->getParsedBody();
            $IsDelete = DeleteQuery::table("empresa")->where("id", '=', $form['id'])->delete();
            if ($IsDelete) {
                return $this->Send($response, [
                    'status' => true,
                    'msg' => 'Empresa foi deletado',
                ], 200);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 1);
        }
    }
    public function alterar($request, $response, $args)
    {
        try {
            $id = $args['id'];
            $TemplateData = [
                'titulo' => 'Lista de Empresas',
                'id' => $id
            ];
            return $this->getTwig()
                ->render($response, $this->setView('empresa'), $TemplateData)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (\Exception $e) {
            throw new \Exception("Restrição: " . $e->getMessage(), );
        };
    }
}

