<?php

setlocale(LC_TIME, 'fr_FR');
$moisCourant = strftime('%B');

// Obtenez la date du mois précédent
$moisPrecedent = strtotime('-1 month');

$nomMoisPrecedent = strftime('%B', $moisPrecedent);

echo 'Le mois courant est : ' . $moisCourant . '<br>';
echo 'Le mois précédent est : ' . $nomMoisPrecedent.'<br>';


$anneeCourante = date('Y');
echo 'L\'année courante est : ' . $anneeCourante;

?>