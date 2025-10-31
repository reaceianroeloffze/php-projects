/** ===================================================
 * Frontend logic for the Google Calendar Clone Project
 * ==================================================== */

'use strict';

// Retrieve all necessary document elements
const calendarEl = document.querySelector('.calendar');
const monthYearEl = document.querySelector('.month-and-year');
const modalEl = document.querySelector('.modal');

// Create a new date object
let currentDate = new Date();

// Create a function to display the modal for adding an appointment
const openAddModal = function(dateString) {
    document.querySelector('#form-action').value = 'add';
    document.querySelector('#eventId').value = '';
    document.querySelector('#deleteEventId').value = '';
    document.querySelector('.course-title').value = '';
    document.querySelector('.instructor-name').value = '';
    document.querySelector('.start-date').value = dateString;
    document.querySelector('.end-date').value = dateString;
    document.querySelector('.start-time').value = '09:00';
    document.querySelector('.end-time').value = '10:00';

    const eventSelector = document.querySelector('.event-selection');
    const eventSelectionWrapper = document.querySelector('.event-selection-wrapper');
    if (eventSelector && eventSelectionWrapper) {
        eventSelector.innerHTML = '';
        eventSelectionWrapper.style.display = 'none';
    }

    modalEl.style.display = 'flex';
}

// Create a function to display the modal for editing an appointment
const openEditModal = function(eventsOnDate) {
    document.querySelector('#form-action').value = 'edit';
    modalEl.style.display = 'flex';

    const eventSelector = document.querySelector('.event-selection');
    const eventSelectionWrapper = document.querySelector('.event-selection-wrapper');
    eventSelector.innerHTML = '<option disabled selected>Select Event &#8595;</option>';

    eventsOnDate.forEach(event => {
            const option = document.createElement('option');
            option.value = JSON.stringify(event);
            option.textContent = `${event.title} - ${event.start} - ${event.end}`;
            eventSelector.appendChild(option);
        }
    )

    eventsOnDate > 1 ? eventSelectionWrapper.style.display = 'block'
        : eventSelectionWrapper.style.display = 'none';

    handleEventSelection(eventsOnDate[0]);
}

// Create a function to render the calendar
const renderCalendar = function(date = new Date()) {
    calendarEl.innerHTML = '';

    // Get current year, current month, and current day
    const currentYear = date.getFullYear();
    const currentMonth = date.getMonth();
    const today = new Date();

    // Get the total number of days in a month & the first day of the month
    const totalDays = new Date(currentYear, currentMonth + 1, 0).getDate();
    const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();

    // Display month and year
    monthYearEl.textContent = date.toLocaleString('default', {month: 'long'}) + ' ' + currentYear;

    // Store days of the week in an array
    const daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    // Loop through the daysOfWeek array and create a div element for each day of the week
    daysOfWeek.forEach(dayOfWeek => {
            const dayOfWeekEl = document.createElement('div');
            dayOfWeekEl.classList.add('day-of-week');
            dayOfWeekEl.textContent = dayOfWeek;
            calendarEl.appendChild(dayOfWeekEl);
        }
    )

    // Create and append empty day blocks into the calendar
    for (let i = 0; i < firstDayOfMonth; i++) {
        calendarEl.appendChild(document.createElement('div'));
    }

    // Loop through days and add them to the calendar
    for (let day = 1; day <= totalDays; day++) {
        const dateString = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        const dayEl = document.createElement('div');
        dayEl.classList.add('day');

        const calendarCell = document.createElement('div');
        calendarCell.classList.add(('day'));

        if (
            currentYear === today.getFullYear() &&
            currentMonth === today.getMonth() &&
            day === today.getDate()
        ) {
            calendarCell.classList.add('day__today');
        }

        const dateEl = document.createElement('div');
        dateEl.classList.add('date-number');
        dateEl.textContent = String(day);
        calendarCell.appendChild(dateEl);

        const todayEvents = events.filter(event => event.date === dateString);
        const eventContainer = document.createElement('div');
        eventContainer.classList.add('event-container');
        todayEvents.forEach(event => {
            const eventEl = document.createElement('div');
            eventEl.classList.add('event');

            const courseEl = document.createElement('div');
            courseEl.classList.add('course');
            courseEl.textContent = event.title.split(' - ')[0];

            const instructorEl = document.createElement('div');
            instructorEl.classList.add('instructor');
            instructorEl.textContent = '👨🏼‍🏫' + event.title.split(' - ')[1];

            const timeEl = document.createElement('div');
            timeEl.classList.add('time');
            timeEl.textContent = ' ' + event.start + ' - ' + event.end;

            eventEl.appendChild(courseEl);
            eventEl.appendChild(instructorEl);
            eventEl.appendChild(timeEl);
            eventContainer.appendChild(eventEl);

        });

        const overlay = document.createElement('div');
        overlay.classList.add('day-overlay');

        const addEventBtn = document.createElement('button');
        addEventBtn.classList.add('overlay-btn');
        addEventBtn.textContent = '+ Add Event';
        addEventBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            openAddModal(dateString);
        });

        if (todayEvents.length > 0) {
            const editBtn = document.createElement('button');
            editBtn.classList.add('overlay-btn');
            editBtn.textContent = 'Edit';
            editBtn.addEventListener('click', event => {
                event.stopPropagation();
                openEditModal(editBtn);
            });
        }

        calendarCell.appendChild(overlay);
        calendarCell.appendChild(eventContainer);
        calendarEl.appendChild(calendarCell);
    }
}

// Create a function to populate the form based on the selected event
const handleEventSelection = function(JSONEvent) {
    const eventData = JSON.parse(JSONEvent);

    document.querySelector('#eventId').value = eventData.id;
    document.querySelector('#deleteEventId').value = eventData.id;

    const [course, instructor] = eventData.title.split(' - ').map(item => item.trim());
    document.querySelector('.course-title').value = course || '';
    document.querySelector('.instructor-name').value = instructor || '';
    document.querySelector('.start-date').value = eventData.start || '';
    document.querySelector('.end-date').value = eventData.end || '';
    document.querySelector('.start-time').value = eventData.startTime || '';
    document.querySelector('.end-time').value = eventData.endTime || '';
}

// Create a function to close the modal window
const closeModal = function() {
    modalEl.style.display = 'none';
}

const changeMonth = function(offset) {
    currentDate.setMonth(currentDate.getMonth() + offset);
    renderCalendar(currentDate);
}

// Create a function to update a live clock
const updateClock = function() {
    const clockEl = document.querySelector('.clock-container__clock');
    const date = new Date();
    clockEl.textContent = date.toLocaleTimeString('en-GB', {hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false});
}

// Initialisation
renderCalendar(currentDate);
updateClock();
setInterval(updateClock, 1000);










