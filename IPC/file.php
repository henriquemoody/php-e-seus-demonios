<?php

$filename = '/tmp/' . getmypid() . '.ipc';
if (! is_file($filename)) {
    touch($filename);
}

$dataWritten  = 'PHP e seus Demônios';
if (false === file_put_contents($filename, $dataWritten)) {
    echo 'Falha ao gravar dados no arquivo' . PHP_EOL;
    exit(2);
}

$dataGiven = file_get_contents($filename);
if (false === $dataGiven) {
    echo 'Falha ao ler dados no arquivo' . PHP_EOL;
    exit(3);
}

echo 'Dado lido no arquivo: ' . $dataGiven . PHP_EOL;

if (! unlink($filename)) {
    echo 'Falha ao tentar remover o arquivo' . PHP_EOL;
    exit(3);
}
