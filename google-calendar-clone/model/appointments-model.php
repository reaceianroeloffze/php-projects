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
    $rowsAdded = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsAdded;
}

// Create a function to update an existing appointment
function updateAppointment($courseId, $courseName, $instructorName, $startDate, $endDate): int {
    $db = connectToMySQL();
    $sql = 'UPDATE appointments SET course_name = :course_name, instructor_name = :instructor_name, start_date = :start_date, end_date = :end_date WHERE course_id = :id';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $stmt->bindValue(':course_name', $courseName, PDO::PARAM_STR);
    $stmt->bindValue(':instructor_name', $instructorName, PDO::PARAM_STR);
    $stmt->bindValue(':start_date', $startDate, PDO::PARAM_STR);
    $stmt->bindValue(':end_date', $endDate, PDO::PARAM_STR);
    $stmt->bindValue(':id', $courseId, PDO::PARAM_INT);
    $stmt->execute();
    $rowsUpdated = $stmt->rowCount();
    $stmt->closeCursor();
    return $rowsUpdated;
}

