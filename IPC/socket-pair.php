<?php

$sockets = array();

if (false === socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $sockets)) {
    echo 'Falha ao criar par de sockets: ' . socket_strerror(socket_last_error()) . PHP_EOL;
}

$pid = pcntl_fork();
if ($pid == -1) {
    echo 'Falha na criação do fork' . PHP_EOL;
} elseif ($pid > 0) {

    socket_close($sockets[0]);
    $messageWritten = 'Mensagem enviada pelo processo pai';
    if (false === socket_write($sockets[1], $messageWritten, strlen($messageWritten))) {
        echo 'Falha ao escrever dados no socket: ' . socket_strerror(socket_last_error($sockets));
        exit(3);
    }

    $messageGiven = socket_read($sockets[1], 1024, PHP_BINARY_READ);

    echo 'Mensagem no processo pai: ' . "\t" . $messageGiven . PHP_EOL;
    socket_close($sockets[1]);

} else {

    socket_close($sockets[1]);
    $messageWritten = 'Mensagem enviada pelo processo filho';
    if (false === socket_write($sockets[0], $messageWritten, strlen($messageWritten))) {
        echo 'Falha ao escrever dados no socket: ' . socket_strerror(socket_last_error($sockets));
        exit(3);
    }

    $messageGiven = socket_read($sockets[0], 1024, PHP_BINARY_READ);

    echo 'Mensagem no processo filho: ' . "\t" . $messageGiven . PHP_EOL;
    socket_close($sockets[0]);
}
