<?php
$passwords = ["", "root", "root123", "admin", "123456", "1234", "password", "helloworld", "admin098@", "Dwj24imzMo/gBGvd", "root@123", "mysql"];
foreach ($passwords as $p) {
    try {
        $pdo = new PDO("mysql:host=127.0.0.1;port=3306", "root", $p);
        echo "SUCCESS_ROOT: '" . $p . "'\n";
        $stmt = $pdo->query("SHOW DATABASES");
        $dbs = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "Databases: " . implode(", ", $dbs) . "\n";
        exit(0);
    } catch (Exception $e) {
        // continue
    }
}
echo "No root password matched.\n";
