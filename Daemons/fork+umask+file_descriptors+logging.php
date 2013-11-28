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

openlog('PSD', LOG_PID | LOG_CONS, LOG_LOCAL0);
while (true) {
    syslog(LOG_DEBUG, 'Envio de email iniciando');
    $sent = mail('vagrant@localhost', 'Lorem ipsum', 'Lorem ipsum dolor sit amet');
    if (true === $sent) {
        syslog(LOG_DEBUG, 'Envio de email terminado sucesso');
        continue;
    }
    syslog(LOG_ERR, 'Falha ao enviar email');
    sleep(2);
}
closelog();
