<?php
$pid = pcntl_fork();
if ($pid == -1) {
    echo 'Falha na criação do fork' . PHP_EOL;
    exit(2);

} elseif ($pid > 0) {
    echo 'Daemon inicializado (PID: ' . $pid . ').' . PHP_EOL;
    exit();
}

umask(0);

while (true) {
    mail('vagrant@localhost', 'Lorem ipsum', 'Dolor sit amet');
    sleep(2);
}
