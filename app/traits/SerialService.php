<?php

namespace app\traits;

trait SerialService
{
    # Porta ou dispositivo serial para comunicação com o Arduino. Linux: '/dev/ttyUSB0' ou '/dev/ttyACM0' Windows: '\\\\.\\COM3'
    private string $port;
    # Tempo máximo (em segundos) para aguarda a leitura de dados do Arduino, Define o tmepo limite de bloqueio na operação de leitura.
    private int  $timeout;
    # Inicializa as configurações básicas de comunicação serial. Deve-se atribuir valores a $port e #timeout amtes de usar os métodos.
    public function __construct() {}
    # Abre a portal serial, lê uma linha JSON enviada pelo Arduino e retorna os dados decodificados como array associativo.
    public function read(): array {}
    # Envia comando para desligar o registro de água no Arduino bool|null Retorna true em sucesso, false em falha ou null se não suportados.
    public function TurnOffRegistration (): ?bool {}
    # Envia comando para ligar o registro de água no Arduino. bool|null Retorna true em sucesso, false em falha ou null se não suportados.
    public function TurnOnRegistration (): ?bool {} 
};