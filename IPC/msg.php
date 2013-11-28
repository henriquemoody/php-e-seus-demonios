<?php

$key = getmypid();
$messageQueueId = msg_get_queue($key);

$messageSent = 'PHP e seus demônios';
$messageWasSent = msg_send($messageQueueId, 2, $messageSent);
if (! $messageWasSent) {
    echo 'Falha ao enviar mensagem' . PHP_EOL;
    exit(2);
}

if (! msg_receive($messageQueueId, 2, $msgType, 1024, $messageReceived, true, 0, $error)) {
    echo 'Falha ao ler mensagem' . $error . PHP_EOL;
    exit(3);
}
echo 'Mensagem recebida: ' . $messageReceived . PHP_EOL;

if (! msg_remove_queue($messageQueueId)) {
    echo 'Falha ao remover fila de mensagens'. PHP_EOL;
    exit(4);
}
