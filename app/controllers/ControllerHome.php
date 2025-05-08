<?php

namespace app\controllers;

class ControllerHome extends Base
{
    public function home($request, $response)
    {
        try {
            $TemplateData = [
                'titulo' => 'Página Inicial',
            ];
            return $this->getTwig()
                ->render($response, $this->setView('home'), $TemplateData)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (\Exception $e) {
            throw new \Exception("Restrição: " . $e->getMessage(), 1);
        }
    }
    public function poweron($request, $response)
    {
        try {
           
        } catch (\Exception $e) {
        }
    }
}
