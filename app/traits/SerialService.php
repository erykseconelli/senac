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


    #Inicializa as configurações básicas de comunicação serial. Deve-se atribuir valores a $port e $timeout
    public function __construct()
    {
        $this->port     =SERIAL_CONFIG['port'];     // Ex: '/dev/ttyUSB0
        $this->timeout  =SERIAL_CONFIG['timeout'];   // Ex: 2s
    }
    
    # 1. Recupera configurações completas para serial.
    $cfg = SERIAL_CONFIG['serial'];
    $cmd = '';
    # 2. Monta o comando de configuração da porta
    # Usa stty no Linux/WSL para configurar baud, bits e paridade
    $cmd = sprintf(
        'stty -F %s %d cs%d -cstopb -parenb raw',
        escapeshellarg($cfg['port']),
        $cfg['baud'],
        $cfg['data']
    );
    # 3. Execiuta comando de configuração
    exec ($cmd, $output, $ret);
    if ($ret !== 0) {
        throw new \RuntimeException('Restrição ao configurar porta serial: ' . implode("\n", $output));        
    }
    # 4. Abre fluxo de leitura/escrita na porta serial
    $stream = @fopen($this->port, 'r+');
    if (!$stream) {
        throw new \RuntimeException(
            "Não foi possível abrir a porta serial {$this->port}"
        );
    }
    # 5. Ajusta bloqueio e timeout na stream
    stream_set_blocking($stream, true);           # Modo bloqueante até receber dados.
    stream_set_timeout($stream, $this->timeout);  # Timeout em segundos.
    # 6 Lê linha JSON enviada pelo Arduino
    $line = fgets($stream);
    # 7. Coleta metadados para verificar se houve timeout
    $meta = stream_get_meta_data($stream);
    fclose($stream); # Fecha imediatamente a comunicação
    # 8. Verifica se ocorreu timeout
    if ($meta['timed_out']) {
        throw new \RuntimeExeception(
            "Leitura da porta serial excedeu {$this->timeout}s"
        );
    }
    # 9. Remove espaços e quebras de linha
    $line = trim($line);
    # 10. Decodifica JSON e valida erros
    $data = json_decode($line, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \RuntimeExeception(
            "JSON inválido recebido: $line"
        );
    }
    # 11. Retorna dados estruturados ao consumidor
    return $data;





    # 1 Carrega configurações da porta serial 
    $cfg = SERIAL_CONFIG['serial'];
    # 2. Monta comando de configuração de porta conforme o sistema
    # No Linux/WSL, configuramos via 'stty'
    $cmd = sprintf(
        'stty -F %s %d cs%d -cstopb -parenb raw',
        escapeshellarg($cfg['port']),
        $cfg['baud'],
        $cfg['data']
    );
    # 3. Execiuta comando de configuração da porta
    exec ($cmd, $out, $ret);
    if ($ret !== 0) {
        throw new \RuntimeException('Restrição ao configurar porta: ' . implode("\n", $out));        
    }
    # 4. Garante que a escrita não bloqueie indefinidiamente
    stream_set_blocking($fp, true);
    stream_set_timeout($fp, $this->timeout);
    # 5. Envia o comando "OFF" seguido de nova linha
    $bytes = fwrite($fp, "OFF\n");
    # 6. Fecha o recurso após envio
    fclose($fp);
    # Verifica se algum byte foi transmitido
    if ($bytes === false || $bytes === 0) {
        throw new \RuntimeExeception('Restrição ao enviar comando OFF');        
    }
    # 8. Sucesso no envio do comando
    return true;



    # 1. Carrega configurações da porta serial
    $cfg = SERIAL_CONFIG('serial');
    # 2. Monta comando de configuração de porta conforme sistema
    # No Linux/WSL, configuramos via 'stty'
    $cmd = sprintf(
        'stty -F %s %d cs%d -cstopb -parenb raw',
        escapeshellarg($cfg['port']),
        $cfg['baud'],
        $cfg['data']
    );
    # 3. Execiuta comando de configuração da porta
    exec ($cmd, $out, $ret);
    if ($ret !== 0) {
        throw new \RuntimeException('Restrição ao configurar porta: ' . implode("\n", $out));        
    }
    # 4. Abre conexão de leitura/escrita na porta serial
    $fp = @fopen($this->port, 'r+');
    if (!$fp) {
        throw new \RuntimeExeception(
            "Não foi possível abrir a porta {$this->port}"
        );
    }
    # 5. Define fluxo bloqueante e tempo de timeout
    stream_set_blocking($fp, true);
    stream_set_timeout($fp, $this->timeout);
    # 6. Envia o comando "ON" seguido de nova linha
    $byte = fwrite($fp, "ON\n");
    # 7. Fecha imediatamente a porta após escrita
    fclose($fp);
    # 8. Verifica se alguma byte foi escrito com sucesso
    if ($bytes === false || $bytes === 0) {
        throw new \RuntimeExeception('Falaha ao enviar comando ON');
    }
    # 9. Retorna true indicando sucesso no envio
    return true;

    
};