<?php
require './../bootstrap.php';

// Définissez vos variables PHP ici
$USER_name = $_ENV['ABSENCE_USERNAME'];
$USER_password = $_ENV['ABSENCE_PASSWORD'];

$user = onConnect($dbh);

// Si la personne ne possède pas le cookie, on la redirige vers la page d'accueil pour se connecter
if (!isset($_COOKIE['jwt'])) {
    header('Location: ./index.php');
    exit;
}


$user_sql = "SELECT * FROM users WHERE id_user = :id_user";
$stmt = $dbh->prepare($user_sql);
$stmt->execute([
    'id_user' => $user['id_user']
]);
$user_sql = $stmt->fetch(PDO::FETCH_ASSOC);


$month = date('n');
$year = date('Y');
$year1 = $year + 1;
$year_1 = $year - 1;
echo head("MMI Companion | Scolarité");
?>


<body class="body-all">

    <?php generateBurgerMenuContent($user_sql['role'], 'Scolarité') ?>

    <main class="main-scolarite">
        <div style="height:30px"></div>
        <div class="scol_info_container">
            <div class="info_title_flextop-informations">
                <div class="title_trait">
                    <h1>Absences</h1>
                    <div></div>
                </div>
            </div>
            <div style="height:30px"></div>
            <p class="scol_info_absence">Attention, les absences sont relevées en fin de semaine, il faut donc attendre ce délai pour qu'elles apparaissent.</p>
            <select id="semestre">
                <?php
                if (strpos($user_sql['edu_group'], 'BUT1') !== false) {
                    if ($year > $year1 || ($year == $year1 && $month > 1)) {
                        echo "<option value='s1-2023' selected>1er année - S1</option>";
                        echo "<option value='s2-2024' selected>1er année - S2</option>";
                    } else {
                        echo "<option value='s1-2023' selected>1er année - S1</option>";
                    }
                } else if (strpos($user_sql['edu_group'], 'BUT2') !== false) {
                    if ($year > $year1 || ($year == $year + 1 && $month > 1)) {
                        echo "<option value='s1-2022'>1er année - S1</option>";
                        echo "<option value='s2-2023'>1er année - S2</option>";
                        echo "<option value='s3-2023'>2e année - S3</option>";
                        echo "<option value='s4-2024' selected>2e année - S4</option>";
                    } else {
                        echo "<option value='s1-2022'>1er année - S1</option>";
                        echo "<option value='s2-2023'>1er année - S2</option>";
                        echo "<option value='s3-2023' selected>2e année - S3</option>";
                    }
                } else if (strpos($user_sql['edu_group'], 'BUT3') !== false) {
                    if ($year > $year1 || ($year == $year + 1 && $month > 1)) {
                        echo '<option value="s1-2021"1er année - S1</option>';
                        echo '<option value="s2-2022">1er année - S2</option>';
                        echo '<option value="s3-2022">2e année - S3</option>';
                        echo '<option value="s4-2023">2e année - S4</option>';
                        echo '<option value="s5-2023">3e année - S5</option>';
                        echo '<option value="s6-2024" selected>3e année - S6</option>';
                    } else {
                        echo '<option value="s1-2021">1er année - S1</option>';
                        echo '<option value="s2-2022">1er année - S2</option>';
                        echo '<option value="s3-2022">2e année - S3</option>';
                        echo '<option value="s4-2023">2e année - S4</option>';
                        echo '<option value="s5-2023" selected>3e année - S5</option>';
                    }
                }
                ?>
            </select>
            <div>
                <div id="total"></div>
                <div id="justif"></div>
                <div id="absences"></div>
            </div>
        </div>
        <!-- <div class="info_title_flextop-informations">
            <div class="title_trait">
                <h1>Notes</h1>
                <div></div>
            </div>
        </div>
        <div style="height:30px"></div>
        <p>Le relevé de notes arrive prochainement</p> -->
      </main>
</body>
<script src="../assets/js/menu-navigation.js?v=1.1"></script>
 
<script>
    // Faire apparaître le background dans le menu burger
    let select_background_profil = document.querySelector('#select_background_vie_sco-header');
    select_background_profil.classList.add('select_link-header');

    const semestre = document.getElementById('semestre');
    // const btn = document.querySelector('button');
    const absenceMain = document.getElementById('absences');
    const justifMain = document.getElementById('justif');
    const totalMain = document.getElementById('total');

    window.addEventListener('load', loadAbsences);

    function loadAbsences() {
    const semestreVal = semestre.value;
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const result = JSON.parse(xhr.responseText);
            console.log(result);
            totalMain.innerHTML = '';
            justifMain.innerHTML = '';
            absenceMain.innerHTML = '';

            const absences = result.absences;
            const total = absences.total;
            const unjustified = absences.unjustified;
            const detailled = absences.detailled;

            if (total == 0) {
                const totalElement = document.createElement('p');
                totalElement.textContent = `Aucune absence n'a été trouvée pour ce semestre.`;
                totalMain.appendChild(totalElement);
            } else {
                const totalElement = document.createElement('p');
                totalElement.textContent = `Tu as ${total} absence(s)`;
                totalMain.appendChild(totalElement);

                // Créer un bouton de collapse unique pour toutes les semaines
                const semaineElementButton = document.createElement('button');
                semaineElementButton.setAttribute('class', 'collapsible');
                semaineElementButton.textContent = `Voir le détail des absences`;
                absenceMain.appendChild(semaineElementButton);

                // Créer un conteneur pour le contenu des semaines
                const semaineElementContent = document.createElement('div');
                semaineElementContent.setAttribute('class', 'content');
                absenceMain.appendChild(semaineElementContent);

                if (unjustified > 0) {
                    const unjustifiedElement = document.createElement('p');
                    unjustifiedElement.textContent = `Tu as ${unjustified} absence(s) injustifiée(s)`;
                    justifMain.appendChild(unjustifiedElement);
                } else {
                    const unjustifiedElement = document.createElement('p');
                    unjustifiedElement.textContent = `Il n'y a aucune absence injustifiée.`;
                    justifMain.appendChild(unjustifiedElement);
                }

                // Pour chaque semaine, ajouter le détail au conteneur de contenu des semaines
                for (const semaine in detailled) {
                    if (detailled.hasOwnProperty(semaine)) {
                        if (detailled[semaine].t > 0) {
                            const semaineDetail = document.createElement('p');
                            semaineDetail.textContent = `Semaine ${semaine}: Justifiées: ${detailled[semaine].j}, Total: ${detailled[semaine].t}`;
                            semaineElementContent.appendChild(semaineDetail);
                        }
                    }
                }

                // Ajouter un gestionnaire d'événements pour le bouton de collapse
                semaineElementButton.addEventListener('click', function () {
                    this.classList.toggle('active');
                    if (semaineElementContent.style.display === 'block') {
                        semaineElementContent.style.display = 'none';
                    } else {
                        semaineElementContent.style.display = 'block';
                    }
                });
            }
        }
    };

    const data = new FormData();
    data.append('semestre', semestreVal);

    xhr.open('POST', 'absence_get.php', true);
    xhr.send(data);
}

    semestre.addEventListener('change', loadAbsences);
</script>

</html>