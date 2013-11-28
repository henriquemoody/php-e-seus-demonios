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

fclose(STDIN);
fclose(STDOUT);
fclose(STDERR);

$fd0 = fopen('/dev/null', 'r');
$fd1 = fopen('/tmp/psd.log', 'a');
$fd2 = fopen('php://stdout', 'a');

while (true) {
    mail('vagrant@localhost', 'Lorem ipsum', 'Dolor sit amet');
    sleep(2);
}
