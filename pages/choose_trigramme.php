<?php
session_start();
require '../bootstrap.php';

$profs = [
    "Mehrez Hanen" => "HMEH",
    "Barré Marielle" => "MBA",
    "Bachir Smail" => "SBA",
    "Badulescu Cristina" => "CBA",
    "Calou Bastien" => "BCA",
    "Calvez Yann" => "YCAL",
    "Chaulet Bernadette" => "BCH",
    "Couegnas Carole" => "CCO",
    "Burn Jean Baptiste" => "JBB",
    "Daghmi Fathallah" => "FDA",
    "Domont Éric" => "EDO",
    "Henry Simon" => "SHE",
    "Glénison Émilie" => "EGL",
    "Galonnier Didier" => "DGA",
    "Delayre Stéphanie" => "SDE",
    "Gineste Olivier" => "OGI",
    "Gourceau Carine" => "CGOU",
    "Hénin Jean Luc" => "JLH",
    "Henry Yann" => "YHE",
    "Le folgoc Cyrille" => "CFO",
    "Louet François" => "FLT",
    "Bui Quoc Marion" => "MBUI",
    "Vallade Christophe" => "CVAL",
    "Scutella Soline" => "SSCU",
    "Combot Mathilde" => "MCOMB",
    "Sulaiman Hamid" => "HSUL",
    "Chapeau Julie" => "JCHA",
    "Poyrault Matthieu" => "MPOY",
    "Brunie Julia" => "JBRU",
    "Cauvin-Doumic Frédérique" => "FCAU",
    "Hautot Adrian" => "AHAU"
];


$search_term = 'Chau';

$found_professors = [];

foreach ($profs as $name => $code) {
    if (stripos($name, $search_term) !== false) {
        $found_professors[$name] = $code;
    }
}

if (!empty($found_professors)) {
    // Afficher les codes associés aux professeurs trouvés
    foreach ($found_professors as $name => $code) {
        echo "Nom : $name, Code : $code<br>";
    }
} else {
    echo "Aucun professeur contenant '$search_term' n'a été trouvé.";
}

return $code;