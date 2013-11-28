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
    if (0 !== posix_getuid()) {
        error_log('É necessário ser root para alterar informações do processo');
        exit(2);
    }

    if (! posix_setuid(1000)) {
        error_log('Não foi possível definir o usuário do processo como 1000');
        exit(3);
    }

    if (! posix_setgid(1000)) {
        error_log('Não foi possível definir o grupo do processo como 1000');
        exit(4);
    }

    mail('vagrant@localhost', 'Lorem ipsum', 'Dolor sit amet');
}
