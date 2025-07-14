<?php

// Create a function to create a new appointment
function createAppointment($courseName, $instructorName, $startDate, $endDate): int
{
    $db = connectToMySQL();
    $sql = 'INSERT INTO appointments (course_name, instructor_name, start_date, end_date) VALUES (:course_name, :instructor_name, :start_date, :end_date)';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $stmt->bindValue(':course_name', $courseName, PDO::PARAM_STR);
    $stmt->bindValue(':instructor_name', $instructorName, PDO::PARAM_STR);
    $stmt->bindValue(':start_date', $startDate, PDO::PARAM_STR);
    $stmt->bindValue(':end_date', $endDate, PDO::PARAM_STR);
    $stmt->execute();
    $updatedRows = $stmt->rowCount();
    $stmt->closeCursor();
    return $updatedRows;
}

