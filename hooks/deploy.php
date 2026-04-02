<?php

$output = [];
$returnCode = 0;

exec('/home/beme2883/dev/bash/sa_deploy.sh 2>&1', $output, $returnCode);

echo "<pre>";
echo implode("\n", $output);
echo "\n\nExit Code: " . $returnCode;
echo "</pre>";
