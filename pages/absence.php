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
    <button>Get</button>
    <div id="absences"></div>
</body>

<script>
const base64 = (data) => btoa(data);
const btn = document.querySelector('button');

async function getAbsences(url) {
  const options = {
    method: 'GET',
    headers: {
      Accept: 'application/json',
      Authorization: `Basic <?php echo base64_encode($USER_name . ':' . $USER_password); ?>`,
    },
  };
  try {
    let rep = await fetch(url, options);
    const json = await rep.json();
    return json.absences;
  } catch (err) {
    console.error(err);
    return err;
  }
}
btn.addEventListener('click', () => {
const absences = document.querySelector('#absences');
const apiUrl = "https://mmi-angouleme-dashboard.alwaysdata.net/api-v1/absences/{semestre}/{email}?detailled={details}";
const semestre = "s1-2022";
const email = "<?= $user['edu_mail']?>";
const details = true;
const url1 = apiUrl.replace('{semestre}', semestre)
    .replace('{email}', email)
    .replace('{details}', details ? 'true' : 'false');
getAbsences(url1)
  .then(data => {
    // Faites quelque chose avec les données obtenues
    absences.innerHTML = JSON.stringify(data);
    console.log(data);
  })
  .catch(error => {
    // Gérez les erreurs ici
    console.error(error);
  });
});
</script>
</html>



