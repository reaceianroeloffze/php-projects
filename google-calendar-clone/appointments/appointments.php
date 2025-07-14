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
    $courseName = trim($_POST['course_name'] ?? '');
    $instructorName = trim($_POST['instructor_name'] ?? '');
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';

    if ($courseName && $instructorName && $startDate && $endDate) {
         $createdApt = createAppointment($courseName, $instructorName, $startDate, $endDate);
         header('location: ' . $_SERVER['PHP_SELF'] . '?addSuccess=1');
    } else {
        header('location: ' . $_SERVER['PHP_SELF'] . '?addFailed=1');
    }
    exit;
}

// Handle editing an appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {
    $courseId = trim($_POST['course_id'] ?? '');
    $courseName = trim($_POST['course_name'] ?? '');
    $instructorName = $_POST['instructor_name'] ?? '';
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';

    if ($courseId && $courseName && $instructorName && $startDate && $endDate) {
        $updatedApt = updateAppointment($courseId, $courseName, $instructorName, $startDate, $endDate);
        header('location: ' . $_SERVER['PHP_SELF'] . '?updateSuccess=1');
    } else {
        header('location: ' . $_SERVER['PHP_SELF'] . '?updateFailed=1');
    }
    exit;
}

// Handle deleting an appointment
if ($_SERVER['REQUEST_METHOD' === 'POST' && ($_POST['action'] ?? '') === 'delete']) {
    $courseId = $_POST['course_id'] ?? '';

    if ($courseId) {
        $deletedApt = deleteAppointment($courseId);
        header('location: ' . $_SERVER['PHP_SELF'] . '?deleteSuccess=1');
    } else {
        header('location: ' . $_SERVER['PHP_SELF'] . '?deleteFailed=1');
    }
    exit;
}
