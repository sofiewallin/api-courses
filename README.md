# A REST web service for courses
A REST web service for the courses included in my education. This is part of a school assignment where I'm learning how to build a REST web service with PHP and use it with JavaScript.

## Using the published API
I've published the web service at https://studenter.miun.se/~sowa2002/dt173g/kursmoment5-rest/. Down below you can find some instructions on how to use the web service.

Return a list of all courses:

**URL:** https://studenter.miun.se/~sowa2002/dt173g/kursmoment5-rest/courses.php

### Parameters

- *id* (optional) – enter the ID of the course that you wanna return, update or delete.
- *indent* – enter *indent=true* if you visit the web service manually and want to examine the results. Default value is *indent=false* to minimize the amount of data on each request.

### Request examples

- https://studenter.miun.se/~sowa2002/dt173g/kursmoment5-rest/courses.php – return all courses
- https://studenter.miun.se/~sowa2002/dt173g/kursmoment5-rest/courses.php?id=1 – return the course with id = 1 (Webbutveckling I)
- https://studenter.miun.se/~sowa2002/dt173g/kursmoment5-rest/courses.php?indent=true – return all courses indented
– https://studenter.miun.se/~sowa2002/dt173g/kursmoment5-rest/courses.php?indent=true – return the course with id = 1 indented

### Create course

Create a course with the method POST.

```sql
CREATE TABLE courses(
    course_id       INT(3) NOT NULL AUTO_INCREMENT,
    start_date      DATE NOT NULL,
    end_date        DATE NOT NULL,
    code            VARCHAR(10) NOT NULL,
    title           VARCHAR(255) NOT NULL,
    progression     CHAR(1) NOT NULL,
    syllabus        VARCHAR(255) NOT NULL,
    created_date    TIMESTAMP NOT NULL,
    PRIMARY KEY (course_id)
);
```
So what needs to be added is:

- *start_date* – the course start date in the format 'YYYY-MM-DD'.
- *end_date* – the course end date in the format 'YYYY-MM-DD'.
- *code* – the course code with a maximum of 7 characters (mostly 6), *ex. DT057G*.
- *title* – the course title with at least 2 characters, *ex. Webbutveckling I*.
- *progression* – the course progression from A–C.
- *syllabus* – link to the course syllabus. Mid Sweden University's syllabus have a link that is formatted according to: *https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=[id]* Enter the correct id. [Search syllabus](https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/)

Example JSON object:

```json
{"start_date":"2020-08-31","end_date":"2020-11-01","code":"DT057G","title":"Webbutveckling I","progression":"A","syllabus":"https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=22782"}
```

### Update course

Update the course with the PUT method by entering an ID parameter according to the information above and entering information in the same way as if you were creating a course.

### Deleting course 

Delete a course using the DELETE method by entering an ID parameter according to the information above.

## Developing the API

Here's some information you need if your going to clone and use this repository with your own database connection.

### Database connection

To establish a connection to your database you need to follow these steps:

1. Open the file `config-sample.php`.
1. Enter your database connection information. You can enter information for both a local connection and a production connection. Toggle `development_mode` in file to decide what information is currently being used.
1. When you've entered your information rename the file from `config-sample.php` to `config.php`.
1. To create the correct table in your database with som inserts you can open `install.php`.

*The connection to the database is established using the information above in `resources/classes/Database.class.php`.*
