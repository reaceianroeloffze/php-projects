<?php
/** =================================
 * Controller for course calendar app
 * ================================== */

// include connection.php file to connect to the database
require_once '../library/connections.php';
// Require the appointments' model file.
require_once '../model/appointments-model.php';

$dbEvents = []; // Store events from the database

// Post or get input with a name "action"
$action = trim(filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS))
    ?? $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

