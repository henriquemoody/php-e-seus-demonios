<?php

function reaper($signal)
{
    if ($signal != SIGCHLD) {
        return;
    }

    while (pcntl_waitpid(-1, $status, WNOHANG | WUNTRACED) > 0) {
        usleep(1000);
    }
}

pcntl_signal(SIGCHLD, 'reaper');

