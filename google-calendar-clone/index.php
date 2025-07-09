<!doctype html>
<html lang="en" dir="ltr" xmlns:link="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Reace Ian Roeloffze">
    <meta name="description" content="Google Calendar Clone Project">
    <link rel="stylesheet" href="/styles/style.css">
    <title>Google Calendar Clone</title>
    <script src="/js/script.js" defer></script>
</head>
<body>
<header class="header">
    <h1 class="page-title">📅 Course Calendar <br> Google Calendar Clone Project</h1>
</header>

<!-- Clock -->
<div class="clock-container">
    <div class="clock-container__clock" id="clock"></div>
</div>

<!-- Calendar -->
<div class="calendar">
    <div class="arrow-btn-container">
        <button class="calendar__previous-month btn">&#10096;</button>
        <h2 class="month-and-year" id="month-year">May 2025</h2>
        <button class="calendar__next-month btn">&#10097;</button>
    </div>
    <div class="calendar-grid" id="calendar"></div>

    <!-- Modal for add/edit/delete apt -->
    <div id="event-selection-wrapper" class="event">
        <label class="event__site-label">
            <strong>Select Event:</strong>
            <select id="events-selection" class="event-selection">
                <option class="event-selection__event" disabled selected>Select Event &#8595;</option>
            </select>
        </label>
    </div>
</div>
</body>
</html>

