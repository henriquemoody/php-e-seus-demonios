<?php

$key = getmypid();
$flag = 'c';
$permission = 0644;
$memorySize = 1024;

$shmId = shmop_open($key, $flag, $permission, $memorySize);
if (! $shmId) {
    echo 'Não foi possível criar o segmento de memória' . PHP_EOL;
    exit(1);
}

$stringWritten = 'PHP e seus demônios';
$shmBytesWritten = shmop_write($shmId, $stringWritten, 0);
if ($shmBytesWritten != strlen($stringWritten)) {
    echo 'Não foi possível gravar o dado e com seu tamanho correto' . PHP_EOL;
    exit(2);
}

$stringRead = shmop_read($shmId, 0, $memorySize);
if (! $stringRead) {
    echo 'Não foi possível ler o dado na memória compartilhada' . PHP_EOL;
    exit(2);
}

echo 'Dado lido na memória compartilhada foi: ' . $stringRead . PHP_EOL;

if (! shmop_delete($shmId)) {
    echo 'Não foi possível marcar o bloco de memória compartilhada para remoção';
}

shmop_close($shmId);
