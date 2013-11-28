<?php

$key = getmypid();
$permission = 0644;
$memorySize = 1024;

$shmId = shm_attach($key, $memorySize, $permission);
if (! $shmId) {
    echo 'Falha ao criar o segmento de memória' . PHP_EOL;
    exit(1);
}

$stringWritten = 'PHP e seus demônios';
if (! shm_put_var($shmId, 1, $stringWritten)) {
    echo 'Falha ao gravar o dado na memória compartilhada' . PHP_EOL;
    exit(2);
}

if (! shm_has_var($shmId, 1)) {
    echo 'Nenhum dado na chave 1 foi encontrado na memória' . PHP_EOL;
    exit(2);
}

$stringRead = shm_get_var($shmId, 1);
if (! $stringRead) {
    echo 'Falha ao ler o dado da chave 1 na memória compartilhada' . PHP_EOL;
    exit(2);
}

echo 'Dado lido na memória compartilhada foi: ' . $stringRead . PHP_EOL;

if (! shm_remove($shmId)) {
    echo 'Falha ao remover do bloco de memória compartilhada';
}

if (! shm_detach($shmId)) {
    echo 'Falha ao se desconectar do bloco de memória compartilhada';
}
