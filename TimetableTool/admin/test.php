<?php
    $command = escapeshellcmd('python hello.py');
    $output = exec($command);
    echo $output; 
?>
