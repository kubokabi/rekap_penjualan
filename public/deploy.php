<?php
// Optional: Cek IP GitHub biar lebih aman (skip dulu kalau belum butuh)
$logFile = __DIR__ . '/../deploy.log';
$cmd = '/bin/bash ' . __DIR__ . '/../deploy.sh';

file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Deployment triggered\n", FILE_APPEND);

$output = shell_exec($cmd . " 2>&1");

file_put_contents($logFile, $output . "\n", FILE_APPEND);

echo "Deployment executed.";
?>