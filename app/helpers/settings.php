<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
# Temos que definir o caminho base do projeto.
define('ROOT', dirname(__FILE__, 3));
# Definindo o caminho do diretório de templates.
define('DIR_VIEWS', ROOT . '/app/view/');
# Definindo a extenssão dos templates.
define('EXT_VIEWS', '.html');

define('HOME', 'http://' . $_SERVER['HTTP_HOST']);


# Define uma constante SERIAL_CONFIG que agrupa todas as configurações
# necessárias para comunicação serial com o Arduino.
define ('SERIAL_CONFIG', [
    'serial' => [                          #Chave principal identificada o grupo de configurações seriais
        'port'  =>'/dev/ttyUSB0',          # Portal serial a ser usada:
        # '\\\\.\\' é o prefixo obrigatório no Windows para acesso a portas COM acima de COM9
        # e garante que o PHP abra corretamente a COM3 como "\\.\COM3".
        'baud'  => 9600,                   # Taxa de transmissão em bits por segundo:
        # 9600 é a velocidade de comunicação serial padrão para muitos sensores e módulos Arduino.
        'parity'    => 'N',                # Paridade:
        # 'N' indica sem paridade (None), significando que não haverá bit extra de verificação de paridade.
        'data'  =>8,                       # Bits de dados:
        # Define quantos bits compõem cada quadro de dados; 8 bits é o padrão mais comum.
        'stop'  =>1,                       # Bits de parada:
        # Número de bits de parada após cada byte de dados; 1 bit é o valor usual.
        'timeout' => 2,                    # Tempo máximo de espera na leitura:
        # Em segundos -- se o Arduino não enviar dados dentro desse período, a leitura retorna nula ou falha.         
    ],
]);