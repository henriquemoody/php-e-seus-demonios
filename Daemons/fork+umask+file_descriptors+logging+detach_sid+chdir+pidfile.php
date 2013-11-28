<?php

$pidfile = '/var/run/psd/daemon.pid';
if (file_exists($pidfile)) {
    $daemonPid = (int) file_get_contents($pidfile);
    if (true === posix_kill($daemonPid, 0)) {
        echo 'Daemon já em execução (PID ' . $daemonPid . ').' . PHP_EOL;
        exit(2);
    }
    unlink($pidfile);
}

$pidfileHandler = fopen($pidfile, 'w+');

if (! flock($pidfileHandler, LOCK_EX | LOCK_NB)) {
    echo 'Falha ao bloquear acesso externo ao pidfile' . PHP_EOL;
    exit(3);
}

$pid = pcntl_fork();
if ($pid == -1) {
    echo 'Falha na criação do fork' . PHP_EOL;
    exit(4);

} elseif ($pid > 0) {
    if (! fwrite($pidfileHandler, $pid)) {
        echo 'Falha ao escrever PID no pidfile' . PHP_EOL;
        exit(5);
    }

    echo 'Daemon inicializado (PID: ' . $pid . ').' . PHP_EOL;
    exit();
}

register_shutdown_function('unlink', $pidfile); 

// Corpo do daemon
umask(0);

fclose(STDIN);
fclose(STDOUT);
fclose(STDERR);

$fd0 = fopen('/dev/null', 'r');
$fd1 = fopen('/tmp/psd.log', 'a');
$fd2 = fopen('php://stdout', 'a');

openlog('PSD', LOG_PID | LOG_CONS, LOG_LOCAL0);

if (posix_setsid() < 0) {
    syslog(LOG_ERR, 'Não foi possível desvincular processo de sua sessão');
    exit(2);
}

chdir(__DIR__);

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
