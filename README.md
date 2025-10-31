# Welcome to my PHP Projects Repo

In this repository, I will store all the projects I build using PHP.

## Table of Contents

|                        **Project Name**                         |          
|:---------------------------------------------------------------:|
| [Culinary Cove Restaurant Site](#culinary-cove-restaurant-site) |
|         [Google Calendar Clone](#google-calendar-clone)         | 
|                 [Image Gallery](#image-gallery)                 |
|         [Temperature Converter](#temperature-converter)         |
|    [Air Quality Index Explorer](#air-quality-index-explorer)    |
|    [Auto-Update Image Showcase](#auto-update-image-showcase)    |
|                         [Diary](#diary)                         |

## Projects

### [Culinary Cove Restaurant Site](https://github.com/reaceianroeloffze/php-projects/tree/Root/culinary-cove-restaurant-site)

A simple brochure/static website built dynamically using PHP basics, including variables, `include()`, `if/else`,
`empty()`, and `isset()`.

### [Google Calendar Clone](https://github.com/reaceianroeloffze/php-projects/tree/Root/google-calendar-clone)

A full-stack course appointment setting web application built with HTML, CSS, JavaScript, PHP, and MySQL. No frameworks
or libraries.

### [Image Gallery](https://github.com/reaceianroeloffze/php-projects/tree/Root/image-gallery)

A simple image gallery that displays an image gallery and renders specific image content dynamically when clicking on a
specific image using `$_GET`, `htmlspecialchars()`, `http_build_query()`, and `rawurlencode()`. Associative arrays were
also used.

### [Temperature Converter](https://github.com/reaceianroeloffze/php-projects/tree/Root/temperature-converter)

A simple responsive temperature converter. It takes a number, an initial unit, and a final unit. A from and to
conversion, essentially, e.g. 12 from Celsius to Kelvin. It converts between Celsius, Kelvin, and Fahrenheit. I use
`$_GET`, `switch`, PHP alternate syntax, and a bit of modularisation.

### [Air Quality Index Explorer](https://github.com/reaceianroeloffze/php-projects/tree/Root/air-quality-explorer)

An air quality index explorer that measures air quality in specific countries using various instruments and units. All
data from this project is sourced by the [OpenAQ](https://openaq.org/) API, which is a NPO that devotes time to
providing air quality data, and all their respective data providers. I am using `Guzzle` and `composer` to request the
API data.

This project was inspired by Jannis Seemann, creator
of [Modern PHP: The Complete Guide - from Beginner to Advanced](https://www.udemy.com/course/modern-php/)
on [Udemy](https://udemy.com), who built a similar project in his course. It was also inspired by my love for using and
working with APIs and a project I had to build for a class, which involves using APIs to request data and render it.

No content on this website is hardcoded (aside from a few paragraphs) or downloaded. All data is dynamically generated
and rendered using PHP. All locations, countries, and air quality data are sourced/requested from the OpenAQ API. As
such, I have no control over the data. I just render it. I try to account for missing data and other types of potential
bugs/errors that could occur.

Because I have no control over the data, I cannot guarantee that all countries and locations will have data to render.
Some locations may not have data for a specific air quality index. I narrow the locations down to those that are still
recording data, but I cannot guarantee that all locations will have data for all air quality parameters. While there are
a number of air quality parameters, I only render data from the PM2.5, and the PM10 parameters.

I tried to set my code up in such a way that it would be easy to add more or replace air quality parameters. I recommend
only generating data for two parameters at a time and a limited number of countries and locations as the amount of data
that can be generated is large. This can, of course, cause the website to take a long time to load.

As it stands, there is no option to choose a different parameter on the site itself, only in the code. For now, the
intent for this project is to showcase the power of PHP (as all the projects in this repo will hopefully do) and to
populate a webpage/site using an API to request data and render it. I wanted to enhance my PHP skills and learn more
about APIs and how to use them.

I use [chart.js](https://www.chartjs.org/) to represent the data visually, followed by tabulation of the data. The data
I request is a summary, or average, of hourly data over the period of 1 month.

### [Auto-Update Image Showcase](https://github.com/reaceianroeloffze/php-projects/tree/Root/auto-update-image-showcase)

This site uses PHP to dynamically generate images with descriptions by reading/scanning a directory of images and text
files and rendering them on the page. The image and description are only added when an image is added to the images
folder. There is no means of uploading a file on the webpage yet. I use:

- `scandir();`
- `pathinfo();`
- `is_dir();`
- `is_file();`
- `file_exists();`
- `glob();`
- `file();`
- `strcmp();`

All are built into PHP. No external libraries or frameworks were used.

I also got some practice in with using PHPDoc comments/DocBlocks using built-in methods and functions as references.

### [Diary](https://github.com/reaceianroeloffze/php-projects/tree/Root/diary)