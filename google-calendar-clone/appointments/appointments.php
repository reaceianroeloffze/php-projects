<?php
/** =================================
 * Controller for course calendar app
 * ================================== */

// include connection.php file to connect to the database
require_once '../library/connections.php';
// Require the appointments' model file.
require_once '../model/appointments-model.php';

$successMsg = '';
$errorMsg = '';
$dbEvents = []; // Store events from the database

// Handle adding an appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    // Submit/post the data
    $courseName = $_POST['course_name'] ?? '';
    $instructorName = $_POST['instructor_name'] ?? '';
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';

    if ($courseName && $instructorName && $startDate && $endDate) {
         $createdApt = createAppointment($courseName, $instructorName, $startDate, $endDate);
         header('location: ' . $_SERVER['PHP_SELF'] . '?success=1');
    } else {
        header('location: ' . $_SERVER['PHP_SELF'] . '?error=1');
    }
}
