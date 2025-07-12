<!doctype html>
<html lang="en" dir="ltr" xmlns:link="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Reace Ian Roeloffze">
    <meta name="description" content="Google Calendar Clone Project">
    <link rel="stylesheet" href="./styles/style.css">
    <title>Google Calendar Clone</title>
    <script src="/js/script.js" defer></script>
</head>
<body>
<header class="header">
    <h1 class="page-title">&#128197; Course Calendar <br> Google Calendar Clone Project</h1>
</header>

<!-- Clock -->
<div class="clock-container">
    <div class="clock-container__clock" id="clock"></div>
</div>

<!-- Calendar -->
<div class="calendar">
    <div class="arrow-btn-container">
        <button class="calendar__previous-month btn">&#10096;</button>
        <h2 class="month-and-year" id="month-year"></h2>
        <button class="calendar__next-month btn">&#10097;</button>
    </div>
    <div class="calendar-grid" id="calendar"></div>

</div>

<!-- Modal for add/edit/delete apt -->
<!-- Modal Window -->
<div class="modal" id="event-modal">
    <!-- Content inside the modal window -->
    <div class="modal__modal-content">
        <!-- Event selection for editing/deleting -->
        <div id="event-selection-wrapper" class="event">
            <label class="event__siteLabel">
                <strong>Select Event:</strong>
                <select id="events-selection" class="event-selection">
                    <option class="event-selection__event" disabled selected>Select Event &#8595;</option>
                </select>
            </label>
        </div>

        <!-- Main form -->
        <form class="site-form" id="event-form" method="POST">
            <!-- Course input -->
            <label class="form-label course-title">Course Title:<span class="required-ast">&#42;</span>
                <input class="form-input" type="text" name="course_name" required>
            </label>
            <!-- Instructor input -->
            <label class="form-label instructor-name">Instructor Name:<span class="required-ast">&#42;</span>
                <input class="form-input" type="text" name="instructor_name" required>
            </label>
            <!-- Start Date -->
            <label class="form-label start-date">Start Date:<span class="required-ast">&#42;</span>
                <input class="form-input" type="date" name="start_date" required>
            </label>
            <!-- End Date -->
            <label class="form-label start-date">End Date:<span class="required-ast">&#42;</span>
                <input class="form-input" type="date" name="end_date" required>
            </label>
            <!-- Submit button -->
            <button class="submit-btn btn" type="submit"> &#9989; Set Appointment</button>
            <!-- hidden input -->
            <input type="hidden" name="action" value="add" id="form-action">
            <input type="hidden" name="event_id" id="eventId">
        </form>

        <!-- Delete Form-->
        <form method="POST" class="site-form delete-form">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="event_id" value="delete" id="deleteEventId">
            <button class="submit-btn delete-btn btn">&#128465; Delete Appointment</button>
        </form>

        <!-- ❌ Cancel -->
        <button class="cancel-btn submit-btn btn" type="button">&#10060; Cancel</button>
    </div>
</div>
</body>
</html>

