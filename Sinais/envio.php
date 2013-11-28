<?php

// Envia um 0 (verifica se o PID é válido ou não)
posix_kill($pid, 0);

// Envia um SIGUSR1 (User-defined signal 1)
posix_kill($pid, SIGUSR1);

// Envia um SIGSTOP (pausa a execução do processo)
posix_kill($pid, SIGSTOP);

// Envia um SIGCONT (continua a execução do processo)
posix_kill($pid, SIGCONT);

// Envia um SIGKILL (mata instantâneamente o processo)
posix_kill($pid, SIGKILL);
