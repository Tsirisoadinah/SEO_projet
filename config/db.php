<?php
$pdo = new PDO("pgsql:host=localhost;dbname=projet_web", "postgres", "postgres");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>