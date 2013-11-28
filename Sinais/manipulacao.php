<?php

declare(ticks = 1);
function signalHandler($signal)
{
    switch ($signal) {
        case SIGQUIT;
            error_log('Me fecharam com o teclado (Control-\)');
            exit(1);
        case SIGINT:
            error_log('Me interromperam com o teclado (Control-C)');
            exit(1);
        case SIGHUP:
            error_log('Fecharam meu terminal');
            exit(1);
        case SIGTERM:
            error_log('Me pediram para me matar');
            exit(0);
    }
} 

pcntl_signal(SIGQUIT, 'signalHandler');
pcntl_signal(SIGINT, 'signalHandler');
pcntl_signal(SIGHUP, 'signalHandler');
pcntl_signal(SIGTERM, 'signalHandler');
pcntl_signal(SIGTSTP, 'signalHandler');
pcntl_signal(SIGTSTP, SIG_IGN); // SIG_IGN faz com que SIGTSTP seja ignorado
pcntl_signal(SIGCONT, SIG_IGN); // SIG_IGN faz com que SIGCONT seja ignorado

echo 'PID: ' . getmypid() . PHP_EOL;
while (true) { 
    echo date('Y-m-d H:i:s') . PHP_EOL;
    sleep(1);
}
