<?php
$pid = pcntl_fork();
if ($pid == -1) {
    echo 'Falha na criação do fork' . PHP_EOL;
    exit(2);

} elseif ($pid > 0) {
    echo 'Daemon inicializado (PID: ' . $pid . ').' . PHP_EOL;
    exit();
}

declare(ticks = 1);

// :P (http://en.wikipedia.org/wiki/Ada_Wong)
function adaWong($signal) 
{
    if ($signal != SIGCHLD) {
        return;
    }

    while (pcntl_waitpid(-1, $status, WNOHANG | WUNTRACED) > 0) {
        usleep(1000);
    }
}

pcntl_signal(SIGCHLD, 'adaWong');

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

$childrenLimit = 10;
$childrenPids = array();
while (true) {
    if (count($childrenPids) >= $childrenLimit) {
        $firstChildPid = array_shift($childrenPids);
        pcntl_waitpid($firstChildPid, $status);
    }

    $childPid = pcntl_fork();

    if ($childPid == -1) {
        syslog(LOG_ERR, 'Falha ao criar filho');
        continue;
    }

    if ($childPid > 0) {
        $childrenPids[] = $childPid;
        continue;
    }

    syslog(LOG_DEBUG, 'Envio de email iniciando');
    $sent = mail('vagrant@localhost', 'Lorem ipsum', 'Lorem ipsum dolor sit amet');
    if (true === $sent) {
        syslog(LOG_DEBUG, 'Envio de email terminado sucesso');
        exit(0);
    }
    syslog(LOG_ERR, 'Falha ao enviar email');
    exit(3);
}
closelog();
