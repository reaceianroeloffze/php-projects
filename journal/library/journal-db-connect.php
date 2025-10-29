<?php

    /* Connect to the database */
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=journal', 'root', 'M@r!ADBK1ngR3ace56', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        /* Catches any errors */
    } catch (PDOException $e) {
        echo 'Could not connect to the database.';
        exit;
    }
