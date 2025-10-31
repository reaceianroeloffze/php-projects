<?php

    /** Connect to the database
     *
     * @return PDO <p>
     * PHP Data Object representing the connection to the database
     * </p>
     * @throws PDOException <p>
     * Catches any errors connecting to the database
     * </p>
     * @var PDO $pdo <p>
     * A new PDO connection
     * </p>
     */
    function connectToDatabase(): PDO
    {
        try {
            $pdo = new PDO('mysql:host=localhost;port=3308;dbname=journal;charset=utf8mb4', 'root', 'M@r!ADBK1ngR3ace56', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            /* Catches any errors */
        } catch (PDOException) {
            echo 'Could not connect to the database.';
            exit;
        }
        return $pdo;
    }
