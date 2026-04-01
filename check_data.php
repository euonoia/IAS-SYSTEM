<?php
require 'vendor/autoload.php';
$pdo = new PDO('sqlite:database/database.sqlite');
$stmt = $pdo->query('SELECT COUNT(*) FROM consultations');
echo 'Consultations: ' . $stmt->fetchColumn() . PHP_EOL;
$stmt = $pdo->query('SELECT COUNT(*) FROM medicines');
echo 'Medicines: ' . $stmt->fetchColumn() . PHP_EOL;
?>