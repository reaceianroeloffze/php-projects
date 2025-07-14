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
        header('location: ' . $_SERVER['PHP_SELF'] . '?success=1');
    } else {
        header('location: ' . $_SERVER['PHP_SELF'] . '?error=1');
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
        header('location: ' . $_SERVER['PHP_SELF'] . '?success=2');
    } else {
        header('location: ' . $_SERVER['PHP_SELF'] . '?error=2');
    }
    exit;
}

// Handle deleting an appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $courseId = $_POST['course_id'] ?? '';

    if ($courseId) {
        $deletedApt = deleteAppointment($courseId);
        header('location: ' . $_SERVER['PHP_SELF'] . '?success=3');
    } else {
        header('location: ' . $_SERVER['PHP_SELF'] . '?error=3');
    }
    exit;
}

// Handle success and error messages
if (isset($_GET['success'])) {
    $successMsg = match ($_GET['success']) {
        '1' => '✅ Appointment added successfully!',
        '2' => '✅ Appointment updated successfully!',
        '3' => '✅ Appointment deleted successfully!',
        default => ''
    };
}
if (isset($_GET['error'])) {
    $errorMsg = match ($_GET['error']) {
        '1' => '❌ Could not add appointment. Please try again.',
        '2' => '❌ Could not update appointment. Please try again.',
        '3' => '❌ Could not delete appointment. Please try again.',
        default => ''
    };
}

// Fetch all data from the database
$result = selectAllAppointments();

if ($result && count($result) > 0) {
    foreach ($result as $row) {
        $startDate = new DateTime($row['start_date']);
        $endDate = new DateTime($row['end_date']);
        while ($startDate <= $endDate) {
            $dbEvents[] = [
                'id' => $row['course_id'],
                'title' => "{$row['course_name']} - {$row['instructor_name']}",
                'date' => $startDate->format('Y-m-d'),
                'startDate' => $row['start_date'],
                'endDate' => $row['end_date']
            ];
            $startDate->modify('+1 day');
        }
    }
}