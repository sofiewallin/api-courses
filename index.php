<?php
include_once './resources/template-parts/header.php';
$create_statement = '
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
';
?>
<main>
    <h1>En REST-webbtjänst för kurser</h1>
    <p>Det här är en REST-webbtjänst för kurser på Webbutvecklingsprogrammet på Mittuniversitet. Kurserna gäller för elever som påbörjade programmet höstterminen 2020.</p>
    <h2>Dokumentation</h2>
    <p>Här följer lite dokumentation för hur webbtjänsten kan användas.</p>
    <p>Returnera en lista på alla kurser:</p>
    <p><strong>URL:</strong> <a href="courses.php">https://studenter.miun.se/~sowa2002/dt173g/kursmoment5-rest/courses.php</a></p>
    <h3>Parametrar</h3>
    <ul>
        <li><em>id</em> (valfri) – ange id för den kurs som ska hämtas, uppdateras eller raderas.</li>
        <li><em>indent</em> (valfri) – ange <em>indent=true</em> om du besöker webbtjänsten manuellt och vill undersöka resultatet. Standard värde är <em>indent=false</em> för att minimera datamängden vid varje anrop. 
    </ul>
    <h3>Exempelanrop</h3>
    <ul>
        <li><a href="courses.php">https://studenter.miun.se/~sowa2002/dt173g/kursmoment5-rest/courses.php</a> – hämta alla kurser</li>
        <li><a href="courses.php?id=1">https://studenter.miun.se/~sowa2002/dt173g/kursmoment5-rest/courses.php?id=1</a> – hämta kursen med id = 1 (Webbutveckling I)</li>
        <li><a href="courses.php?indent=true">https://studenter.miun.se/~sowa2002/dt173g/kursmoment5-rest/courses.php?indent=true</a> – hämta alla kurser indenterat</li>
        <li><a href="courses.php?indent=true&id=1">https://studenter.miun.se/~sowa2002/dt173g/kursmoment5-rest/courses.php?indent=true</a> – hämta kursen med id = 1 indenterat</li>
    </ul>
    <h3>Skapa kurs</h3>
    <p>Skapa en kurs med metoden POST.</p>
    <p>Databastabellen för kurserna i webbtjänsten ser ut såhär:<p>
    <pre><?php echo $create_statement; ?></pre>
    <p>Det som behöver läggas till är alltså:</p>
    <ul>
        <li><em>start_date</em> – kursens startdatum i formatet 'YYYY-MM-DD'.</li>
        <li><em>end_date</em> – kursens slutdatum i formatet 'YYYY-MM-DD'.</li>
        <li><em>code</em> – kurskod med max 7 tecken (oftast 6), <em>ex. DT057G</em>.</li>
        <li><em>title</em> – kursnamn med minst 2 tecken, <em>ex. Webbutveckling I</em></li>
        <li><em>progression</em> – kursens progression från A–C.</li>
        <li><em>syllabus</em> – länk till kursens kursplan. Mittuniversitetets kursplaner har en länk som är formaterad enligt: <em>https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=[id]</em>. Fyll i korrekt id för den aktuella kursen. <a href="https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/">Sök kursplan</a>.</li>
    </ul>
    <p>Exempel på json objekt:</p>
    <pre>{"start_date":"2020-08-31","end_date":"2020-11-01","code":"DT057G","title":"Webbutveckling I","progression":"A","syllabus":"https://www.miun.se/utbildning/kursplaner-och-utbildningsplaner/Sok-kursplan/kursplan/?kursplanid=22782"}</pre>
    <h3>Uppdatera kurs</h3>
    <p>Uppdatera kurs med metoden PUT genom att ange en id-parameter enligt informationen ovan samt ange uppgifter på samma sätt som om du skapar en kurs.</p>
    <h3>Radera kurs</h3>
    <p>Radera en kurs med metoden DELETE genom att ange en id-parameter enligt informationen ovan.</p>
<?php
include_once './resources/template-parts/footer.php';