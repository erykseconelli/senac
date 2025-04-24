<?php

namespace app\controllers;


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
}