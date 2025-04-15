<?php

namespace app\traits;

use Slim\Views\Twig;

trait Template
{
    public function getTwig()
    {
        try {
            # Criamos o Twig e passamos por parâmetro, o diretório padrão das views
            $twig = Twig::create(DIR_VIEWS);
            # Adicionamos uma variável global, que pode ser acessada em qualquer view
            $twig->getEnvironment()->addGlobal('nome_variavel', 'valor_variavel');
            return $twig;
        } catch (\Exception $e) {
            throw new \Exception("Restrição: " . $e->getMessage(), 1);
        }
    }
    public function setView($name)
    {
        return $name . EXT_VIEWS;
    }
    public function Send($response, array $data, int $statusCode = 200)
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response
            ->withStatus($statusCode)
            ->withHeader('Content-type', 'application/json');
    }
}
