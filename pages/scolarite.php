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

    <main class="main_all">

        <div style="height:30px"></div>

        <div class="title-absences">
            <div class="title_trait">
                <h1>Absences</h1>
                <div></div>
            </div> 
        </div>

        <div style="height:15px"></div>

        <div class="description-absences">
            <div class="description_content-absences">
                <i class="fi fi-br-clock"></i>
                <p>Les absences sont relevées en fin de semaine.</p>
            </div>
            <div class="description_content-absences">
                <i class="fi fi-br-info"></i>
                <p>Au delà de <span style="font-weight:600">5 absences injustifiées</span>, chaque absence supplémentaire entraine un malus de 0.2 points sur chacune des compétences.</p>
            </div>
        </div>

        <div style="height:20px"></div>

        <div class="select-absences">
            <label for="semestre" class="title_select-absences">Sélectionne ton semestre</label>
            <div class="content_select-absences">
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
            </div>
        </div>

        <div style="height:15px"></div>

        <div class="content_recapitulatif_details-absences">
            <div class="recapitulatif-absences" id="recapitulatif-absences">
                <div class="title_recapitulatif_details-absences">
                    <i class="fi fi-br-book-alt"></i>
                    <p>Récapitulatif de tes absences :</p>
                </div>
                <div class="container_recapitulatif_details-absences" id="content_recapitulatif-absences">
                    <p>Chargement des données...</p>
                </div>
            </div>
        </div>

        

        <div style="height:30px"></div>
        <!-- <p>Le relevé de notes arrive prochainement</p> -->
        <!-- <canvas id="fireworks"></canvas> -->
    </main>

    <script src="../assets/js/script_all.js?v=1.1"></script>
    <!-- <script src="../assets/js/fireworks.js"></script> -->
    
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_vie_sco-header');
        select_background_profil.classList.add('select_link-header');


        // ----------------------------------------------

        // Relevé de notes et absences
        const semestre = document.querySelector('#semestre');

        const recapAbsences = document.querySelector('#content_recapitulatif-absences');
        
        window.addEventListener('load', loadAbsences);

        function buildDetailsAbsences(detailled) {
            
            // Sélectionner l'élément #recapitulatif-absences
            const recapAbsencesContainer = document.querySelector('#recapitulatif-absences');

            // Création de #details-absences comme frère de #recapitulatif-absences
            const detailsAbsencesContainer = document.createElement('div');
            detailsAbsencesContainer.classList.add('details-absences');
            detailsAbsencesContainer.id = 'details-absences';

            // Ajout de #details-absences comme frère de #recapitulatif-absences
            recapAbsencesContainer.insertAdjacentHTML('afterend', detailsAbsencesContainer.outerHTML);

            // Effacer le contenu actuel
            detailsAbsencesContainer.innerHTML = '';

            // Création de la div de séparation
            const separationDiv = document.createElement('div');
            separationDiv.classList.add('separation_recapitulatif_details-absences');

            // Ajout de la div de séparation au-dessus de #details-absences
            parentElement.insertBefore(separationDiv, detailsAbsences);

            // Créer et ajouter le titre
            const titleElement = document.createElement('div');
            titleElement.classList.add('title_recapitulatif_details-absences');

            const iconElement = document.createElement('i');
            iconElement.classList.add('fi', 'fi-br-search');

            const titleTextElement = document.createElement('p');
            titleTextElement.textContent = 'Détail de tes absences :';

            titleElement.appendChild(iconElement);
            titleElement.appendChild(titleTextElement);
            detailsAbsencesContainer.appendChild(titleElement);

            // Créer et ajouter le contenu
            const contentElement = document.createElement('div');
            contentElement.classList.add('container_recapitulatif_details-absences');
            contentElement.id = 'content_details-absences';

            // Si aucune donnée n'est disponible
            if (Object.keys(detailled).length === 0) {
                const noAbsenceElement = document.createElement('p');
                noAbsenceElement.textContent = `Aucun détail d'absence disponible pour ce semestre.`;
                contentElement.appendChild(noAbsenceElement);
            } else {
                // Créer et ajouter la liste ul
                const listElement = document.createElement('ul');
                contentElement.appendChild(listElement);

                // Ajouter les détails d'absences à la liste
                for (const semaine in detailled) {
                    if (detailled.hasOwnProperty(semaine)) {
                        const semaineDetail = document.createElement('li');
                        semaineDetail.textContent = `Semaine ${semaine}: Justifiées: ${detailled[semaine].j}, Total: ${detailled[semaine].t}`;
                        listElement.appendChild(semaineDetail);
                    }
                }
            }

            // Ajouter le contenu à #details-absences
            detailsAbsencesContainer.appendChild(contentElement);
        }

        function loadAbsences() {
            const semestreVal = semestre.value;
            const xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const result = JSON.parse(xhr.responseText);
                    console.log(result);

                    recapAbsences.innerHTML = '';

                    const absences = result.absences;
                    const total = absences.total; // Correction de la référence à la variable total
                    const unjustified = absences.unjustified;
                    const justified = total - unjustified;
                    const detailled = absences.detailled;

                    if (total === 0) {
                        let totalElement = document.createElement('p');
                        totalElement.textContent = `Aucune absence n'a été trouvée pour ce semestre.`;
                        recapAbsences.appendChild(totalElement);
                    } else {
                        let listElement = document.createElement('ul');
                        recapAbsences.appendChild(listElement);

                        let justifiedElement = document.createElement('li');
                        justifiedElement.textContent = `Tu as ${justified} absence(s) justifiée(s)`;
                        listElement.appendChild(justifiedElement);

                        let unjustifiedElement = document.createElement('li');
                        unjustifiedElement.textContent = `Tu as ${unjustified} absence(s) injustifiée(s)`;
                        listElement.appendChild(unjustifiedElement);

                        buildDetailsAbsences(detailled);
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

</body>


</html>


<!-- // Boucle à travers les données et insérer dans le tableau
                    // for (const ue in result.notes.ues) {
                    //     if (result.notes.ues.hasOwnProperty(ue) && result.notes.ues[ue].moy !== null) {
                    //         const ueData = result.notes.ues[ue];

                    //         // Créer une nouvelle ligne
                    //         const row = tbody.insertRow();

                    //         // Insérer les cellules avec les données correspondantes
                    //         const cellUE = row.insertCell(0);
                    //         const cellMoyenne = row.insertCell(1);
                    //         const cellRang = row.insertCell(2);
                    //         const cellMoyennePromo = row.insertCell(3);

                    //         // Remplir les cellules avec les données
                    //         cellUE.textContent = ue;
                    //         cellMoyenne.textContent = ueData.moy !== null ? ueData.moy : "N/A";
                    //         cellRang.textContent = ueData.rang;
                    //         cellMoyennePromo.textContent = ueData.moy_promo.toFixed(2);
                    //     }
                    // } -->