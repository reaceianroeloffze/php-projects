<?php
/** ==================
 * Connect to Database
 * =================== */

// phpinfo();

// Create a function to connect to MySql
function connectToMySQL(): PDO|string
{
    $host = '127.0.0.1';
    $user = 'root';
    $pass = 'D@t@K!ngR3@c3123';
    $db = 'course_calendar_app';
    $port = 3306;
    $conn = '';

// Connect to MySQL Server (MySql Workbench)
    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$db;", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("SET CHARACTER SET utf8mb4");
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return $conn;
}

connectToMySQL();


