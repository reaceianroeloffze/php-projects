<?php

/** ==================================================
 * Caching file for data requested from the OpenAQ API
 * =================================================== */

// create new sqlite PDO
$database = new PDO('sqlite:C:/Users/temp/OneDrive/Desktop/Reace/Coding/Projects/repos/php/air-quality-explorer/inc/data_cache.sqlite');
$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create a table for countries
$database->exec('
    CREATE TABLE IF NOT EXISTS countries (
        id INTEGER PRIMARY KEY,
        code TEXT UNIQUE,
        name TEXT
        );
');

// Create a table for locations
$database->exec('
    CREATE TABLE IF NOT EXISTS locations (
        id INTEGER PRIMARY KEY,
        country_id INTEGER,
        name TEXT,
        latitude REAL,
        longitude REAL,
        owner TEXT,
        provider TEXT,
        FOREIGN KEY (country_id) REFERENCES countries(id)
    );
');

// Create a table for measurements
$database->exec('
    CREATE TABLE IF NOT EXISTS measurements (
        id INTEGER PRIMARY KEY,
        location_id INTEGER,
        sensor_id INTEGER,
        value REAL,
        latitude REAL,
        longitude REAL,
        last_updated DATETIME,
        FOREIGN KEY (location_id) REFERENCES locations(id)
    );
');

// Create a table for sensors
$database->exec('
    CREATE TABLE IF NOT EXISTS sensors (
        id INTEGER PRIMARY KEY,
        parameter TEXT,
        name TEXT NOT NULL,
        unit TEXT,
        display_name TEXT
    );
');