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
