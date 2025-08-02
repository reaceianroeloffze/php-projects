# Create this database after connecting to the MySQL server
# to have a database to connect to when connecting
# to the MySQL Server with PHP.
CREATE DATABASE course_calendar_app;

# Create a calendar database to hold appointments
CREATE DATABASE calendar;

# Select the calendar database
USE calendar;

# Populate calendar database
CREATE TABLE IF NOT EXISTS `calendar`.`appointments`
(
    `course_id`       INT                                                           NOT NULL AUTO_INCREMENT,
    `course_name`     VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
    `instructor_name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
    `start_date`      DATE                                                          NOT NULL,
    `end_date`        DATE NOT NULL,
    `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP() NOT NULL,
    PRIMARY KEY (`course_id`)
);

# If mistakes are made, drop the database, make corrections, and create everything again.
# DROP DATABASE calendar