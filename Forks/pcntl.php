<?php
$pid = pcntl_fork();
if ($pid == -1) {
    // Falha na criação do fork
    echo 'Falha na criação do fork' . PHP_EOL;

} elseif ($pid > 0) {
    // Sou o processo pai
    echo 'Fork criado com sucesso sob o PID ' . $pid . PHP_EOL;

} else {
    // Sou o processo filho, em background
    mail('vagrant@localhost', 'Lorem ipsum', 'Dolor sit amet');
}
