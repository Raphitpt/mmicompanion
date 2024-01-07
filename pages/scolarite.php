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


$additionalStyles = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />';


echo head("MMI Companion | Scolarité", $additionalStyles);
?>


<body class="body-all">

    <?php generateBurgerMenuContent($user_sql['role'], 'Scolarité', notifsHistory($dbh, $user['id_user'], $user['edu_group'])) ?>

    <main class="main_all">

        <div style="height:30px"></div>

        <section class="section_absences-scolarite">
            <div class="title-scolarite">
                <div class="title_trait">
                    <h1>Absences</h1>
                    <div></div>
                </div> 
            </div>

            <div style="height:15px"></div>

            <div class="description-scolarite">
                <div class="description_content-scolarite">
                    <i class="fi fi-br-clock"></i>
                    <p>Les absences sont relevées en fin de semaine.</p>
                </div>
                <div class="description_content-scolarite">
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
                            if (date('Y-m-d') >= '2021-09-01') {
                                echo "<option value='s1-2023' selected>1er année - S1</option>";
                                echo "<option value='s2-2024' selected>1er année - S2</option>";
                            } else {
                                echo "<option value='s1-2023' selected>1er année - S1</option>";
                            }
                        } else if (strpos($user_sql['edu_group'], 'BUT2') !== false) {
                            if (date('Y-m-d') >= '2021-09-01') {
                                echo "<option value='s3-2023'>2e année - S3</option>";
                                echo "<option value='s4-2024' selected>2e année - S4</option>";
                            } else {
                                echo "<option value='s3-2023' selected>2e année - S3</option>";
                            }
                        } else if (strpos($user_sql['edu_group'], 'BUT3') !== false) {
                            if (date('Y-m-d') >= '2021-09-01') {
                                echo '<option value="s5-2023">3e année - S5</option>';
                                echo '<option value="s6-2024" selected>3e année - S6</option>';
                            } else {
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
        </section>

        <div style="height:30px"></div>

        <section class="section_notes-scolarite">
            <div class="title-scolarite">
                <div class="title_trait">
                    <h1>Notes</h1>
                    <div></div>
                </div> 
            </div>

            <div style="height:15px"></div>

            <div class="description-scolarite">
                <div class="description_content-scolarite">
                    <i class="fi fi-br-info"></i>
                    <p>Pour valider une compétence, il faut avoir une moyenne d’au moins <span style="font-weight:600">10/20</span>.</p>
                </div>
            </div>

            <div style="height:20px"></div>

            <div class="container-notes">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="item-notes">
                                <div class="title_item-notes">
                                    <p>UE 3.1</p>
                                    <p>Comprendre</p>
                                </div>
                                <div class="separation_item-scolarite"></div>
                                <div class="content_item-notes">
                                    <p>Ta moyenne : <span style="font-weight:600">...</span></p>
                                    <p>La moyenne de la promo : <span style="font-weight:600">...</span></p>
                                    <p>Ton rang dans la promo : <span style="font-weight:600">...</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="item-notes">
                                <div class="title_item-notes">
                                    <p>UE 3.2</p>
                                    <p>Concevoir</p>
                                </div>
                                <div class="separation_item-scolarite"></div>
                                <div class="content_item-notes">
                                    <p>Ta moyenne : <span style="font-weight:600">...</span></p>
                                    <p>La moyenne de la promo : <span style="font-weight:600">...</span></p>
                                    <p>Ton rang dans la promo : <span style="font-weight:600">...</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="item-notes">
                                <div class="title_item-notes">
                                    <p>UE 3.3</p>
                                    <p>Exprimer</p>
                                </div>
                                <div class="separation_item-scolarite"></div>
                                <div class="content_item-notes">
                                    <p>Ta moyenne : <span style="font-weight:600">...</span></p>
                                    <p>La moyenne de la promo : <span style="font-weight:600">...</span></p>
                                    <p>Ton rang dans la promo : <span style="font-weight:600">...</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="item-notes">
                                <div class="title_item-notes">
                                    <p>UE 3.4</p>
                                    <p>Développer</p>
                                </div>
                                <div class="separation_item-scolarite"></div>
                                <div class="content_item-notes">
                                    <p>Ta moyenne : <span style="font-weight:600">...</span></p>
                                    <p>La moyenne de la promo : <span style="font-weight:600">...</span></p>
                                    <p>Ton rang dans la promo : <span style="font-weight:600">...</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="item-notes">
                                <div class="title_item-notes">
                                    <p>UE 3.5</p>
                                    <p>Entreprendre</p>
                                </div>
                                <div class="separation_item-scolarite"></div>
                                <div class="content_item-notes">
                                    <p>Ta moyenne : <span style="font-weight:600">...</span></p>
                                    <p>La moyenne de la promo : <span style="font-weight:600">...</span></p>
                                    <p>Ton rang dans la promo : <span style="font-weight:600">...</span></p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="btn_content-menu btn_next">
                        <p>Suivant</p>
                        <i class="fi fi-br-angle-right"></i>
                    </div>

                    <div class="btn_content-menu btn_prev">
                        <i class="fi fi-br-angle-left"></i>
                        <p>Précédent</p>
                    </div>

                </div>
            </div>

        </section>
        
        <div style="height:30px"></div>
        
        <div class="p_credit-scolarite">
            <p>Mis à jour selon les données de <a href="https://mmi-angouleme-dashboard.alwaysdata.net/" target="_blank">MMI Dashboard</a></p>
        </div>

        <div style="height:30px"></div>
        <!-- <p>Le relevé de notes arrive prochainement</p> -->
        <!-- <canvas id="fireworks"></canvas> -->
    </main>

    <script src="../assets/js/script_all.js?v=1.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- <script src="../assets/js/fireworks.js"></script> -->
    
    <script>
        // Faire apparaître le background dans le menu burger
        let select_background_profil = document.querySelector('#select_background_vie_sco-header');
        select_background_profil.classList.add('select_link-header');

        // ----------------------------------------------

        // Swiper
        let swiper = new Swiper(".mySwiper", {
            autoHeight: true,
            spaceBetween: 30,
            navigation: {
                nextEl: ".btn_next",
                prevEl: ".btn_prev",
            },
        });

        // ----------------------------------------------

        // Relevé de notes et absences
        const semestre = document.querySelector('#semestre');

        const recapAbsences = document.querySelector('#content_recapitulatif-absences');
        
        window.addEventListener('load', loadAbsencesNotes);

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
            separationDiv.classList.add('separation_item-scolarite');

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

        function loadAbsencesNotes() {
    const semestreVal = semestre.value;
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const result = JSON.parse(xhr.responseText);
                    console.log(result);

                    let contentItemNotes = document.querySelectorAll('.content_item-notes');
                    console.log(contentItemNotes);

                    // Notes
                    let index = 0; // Ajoutez un index pour suivre l'élément à mettre à jour
                    for (let ue in result.notes.ues) {
                        if (result.notes.ues.hasOwnProperty(ue) && result.notes.ues[ue].moy !== null) {
                            let ueData = result.notes.ues[ue];
                            console.log(ueData);

                            // Utilisez forEach pour itérer sur chaque élément
                            contentItemNotes.forEach((element, i) => {
                                if (i === index) {
                                    element.querySelector('p:nth-child(1) span').textContent = ueData.moy.toFixed(2);
                                    element.querySelector('p:nth-child(2) span').textContent = ueData.moy_promo.toFixed(2);
                                    element.querySelector('p:nth-child(3) span').textContent = ueData.rang;
                                }
                            });

                            index++; // Incrémentez l'index pour la prochaine itération
                        }
                    }

                    // Absences
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
                } catch (error) {
                    console.error('Error parsing JSON:', error);
    console.log('Response text:', xhr.responseText);
                }
            } else {
                console.error('HTTP request failed with status:', xhr.status);
            }
        }
    };

    const data = new FormData();
    data.append('semestre', semestreVal);

    xhr.open('POST', 'absence_get.php', true);
    xhr.send(data);
}


        semestre.addEventListener('change', loadAbsencesNotes);

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