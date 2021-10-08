<?php
/*
* Code by Sofie Wallin (sowa2002), student at MIUN, 2021
*/

require_once('resources/config.php');
include_once './resources/template-parts/header.php';

echo '<main>';

/*------ Create instance of database ------*/

$database = new Database();
$database_connection = $database->getConnection();

/*------ Create table: courses ------*/

$statement = "
DROP TABLE IF EXISTS courses;
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
";

/*------ Insert rows in table ------*/

$statement .= "
INSERT INTO courses(start_date, end_date, code, title, progression, syllabus) VALUES('2020-08-31', '2020-11-01', 'DT057G', 'Webbutveckling I', 'A', 'https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=22782');
INSERT INTO courses(start_date, end_date, code, title, progression, syllabus) VALUES('2020-08-31', '2020-11-01', 'DT084G', 'Introduktion till programmering med JavaScript', 'A', 'https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=30554');
INSERT INTO courses(start_date, end_date, code, title, progression, syllabus) VALUES('2020-11-02', '2021-01-17', 'DT163G', 'Digital bildbehandling för webb', 'A', 'https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=24403');
INSERT INTO courses(start_date, end_date, code, title, progression, syllabus) VALUES('2020-11-02', '2021-01-17', 'GD008G', 'Typografi och form för webb', 'A', 'https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=24399');
INSERT INTO courses(start_date, end_date, code, title, progression, syllabus) VALUES('2021-01-18', '2021-03-21', 'DT003G', 'Databaser', 'A', 'https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=21595');
INSERT INTO courses(start_date, end_date, code, title, progression, syllabus) VALUES('2021-01-18', '2021-03-21', 'DT093G', 'Webbutveckling II', 'B', 'https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=27133');
INSERT INTO courses(start_date, end_date, code, title, progression, syllabus) VALUES('2021-03-22', '2021-06-06', 'DT068G', 'Webbanvändbarhet', 'B', 'https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=30563');
INSERT INTO courses(start_date, end_date, code, title, progression, syllabus) VALUES('2021-03-22', '2021-06-06', 'DT152G', 'Webbdesign för CMS', 'B', 'https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=22324');
INSERT INTO courses(start_date, end_date, code, title, progression, syllabus) VALUES('2021-08-30', '2021-10-31', 'IK060G', 'Projektledning', 'A', 'https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=27003');
INSERT INTO courses(start_date, end_date, code, title, progression, syllabus) VALUES('2021-08-30', '2021-10-31', 'DT173G', 'Webbutveckling III', 'B', 'https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=22706');
";

/*------ Show statements ------*/

echo "<pre>$statement</pre>";

/*------ Perform query ------*/
if($database_connection->multi_query($statement)) {
    echo '<p class="message success">Tabellen har installerats med data.</p>';
} else {
    echo '<p class="message error">Fel vid installation av databastabell eller inmatning av data.</p>'; 
}

include_once './resources/template-parts/footer.php';