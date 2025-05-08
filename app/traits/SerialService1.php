<?php

namespace app\trait;

trait SerialService
{
    public function read(): array
    {
        $port     = '/dev/ttyUSB0';   // ajuste conforme mapeamento do seu dispositivo
        $baudRate = 9600;
        // Abre a porta em modo leitura/escrita, sem bloqueio no open
        $fd = @dio_open($port, O_RDWR | O_NOCTTY | O_NONBLOCK);
        if ($fd === false) {
            throw new \RuntimeException("Não foi possível abrir a porta serial: {$port}");
        }
        // Configura parâmetros seriais
        dio_tcsetattr($fd, [
            'baud'   => $baudRate, // 9600, 19200, 115200...
            'bits'   => 8,         // 5,6,7,8
            'stop'   => 1,         // 1 ou 2
            'parity' => 0,         // 0 = nenhuma, 1 = ímpar, 2 = par
        ]);
        // Modo bloqueante para leitura completa
        dio_fcntl($fd, F_SETFL, 0);
        // Laço de leitura até obter dados
        $data = '';
        $data = @dio_read($fd, 128); // Lê até 4KB de dados
        var_dump($data); // Debug: mostra os dados recebidos
        die;
        usleep(100_000); // 0.1s de espera se sem dados

        // Fecha a porta serial
        dio_close($fd);
        // Remove espaços e quebras de linha
        $payload = trim($data);
        // Decodifica JSON recebido
        $decoded = json_decode($payload, true);
        return $decoded;
    }
}