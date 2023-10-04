<?php
require './../bootstrap.php';

// Définissez vos variables PHP ici
$USER_name = $_ENV['ABSENCE_USERNAME'];
$USER_password = $_ENV['ABSENCE_PASSWORD'];

$jwt = $_COOKIE['jwt'];
$secret_key = $_ENV['SECRET_KEY'];
$user = decodeJWT($jwt, $secret_key);

$month = date('n');
$year = date('Y');
$year1 = $year + 1;
$year_1 = $year - 1;
echo head("Absences");
?>

<body>
    <select id="semestre">
        <?php
        if (strpos($user['edu_group'], 'BUT1') !== false){
            echo '<option value="s1-2023">S1 2023</option>';
            if ($year > $year1 || ($year == $year + 1 && $month > 1)) {
                echo '<option value="s2-2023">S2 2023</option>';
            }
        } else if (strpos($user['edu_group'], 'BUT2') !== false){
            echo '<option value="s1-2022">S1 2022</option>';
            echo '<option value="s2-2022">S2 2022</option>';
            echo '<option value="s1-2023">S1 2023</option>';
            if ($year > $year1 || ($year == $year + 1 && $month > 1)) {
                echo '<option value="s2-2023">S2 2023</option>';
            }
        } else if (strpos($user['edu_group'], 'BUT3') !== false){
            echo '<option value="s1-2021">S1 2021</option>';
            echo '<option value="s2-2021">S2 2021</option>';
            echo '<option value="s1-2022">S1 2022</option>';
            echo '<option value="s2-2022">S1 2022</option>';
            echo '<option value="s1-2023">S1 2023</option>';
            if ($year > $year1 || ($year == $year + 1 && $month > 1)) {
                echo '<option value="s2-2023">S2 2023</option>';
            }
        }
        ?>
    </select>
    <button>recupa</button>
    <div id="absences"></div>
</body>

<script>
const semestre = document.getElementById('semestre');
const btn = document.querySelector('button');
const absenceMain = document.getElementById('absences');

function loadAbsences() {
            const semestreVal = semestre.value;

            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    const absencesHtmlValue = response.absencesHtml;
                    absenceMain.innerHTML = absencesHtmlValue;
                }
            };

            const data = new FormData();
            data.append('semestre', semestreVal);

            xhr.open('POST', 'absence_get.php', true);
            xhr.send(data);
            }
            
        btn.addEventListener('click', loadAbsences);

</script>
</html>



