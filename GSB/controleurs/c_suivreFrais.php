<?php
/**
 * Gestion de l'affichage des frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

switch ($action) {
case 'misePaiementFrais':
    // Récupération du formulaire
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $montant = filter_input(INPUT_POST, 'montant', FILTER_SANITIZE_STRING);
    $pdo->majMontantFicheFrais($idVisiteur, $leMois, "RB" , $montant);
    
case 'listeFrais':
$visiteurs = $pdo->getListVisiteur();
// Afin de sélectionner par défaut le premier visiteur dans la zone de liste
// on demande toutes les clés, et on prend la première,
$lesClesVisi = array_keys($visiteurs);
if(array_key_exists(0,  $visiteurs)){
    $visiASelectionner =  $lesClesVisi[0];
}
$lesMois = $pdo->getLesMois("VA");
// Afin de sélectionner par défaut le dernier mois dans la zone de liste
// on demande toutes les clés, et on prend la première,
// les mois étant triés décroissants
$lesClesMois = array_keys($lesMois);
if(array_key_exists(0,  $lesMois)){
    $moisASelectionner = $lesClesMois[0];
}
include 'vues/v_selectionMoisVisiteur.php';
break;

case 'etatFrais':
    // Récupération du formulaire
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);

    // recuperation des informations pour les champs d'informations
    $lesMois =   $lesMois = $pdo->getLesMois("VA");
    $visiteurs = $pdo->getListVisiteur();

    // Les champs selectionnés par defaut
    $moisASelectionner = $leMois;
    $visiASelectionner =  $idVisiteur;

      // recupération des information des fiches de frais
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFraisStatus($idVisiteur, $leMois, "VA");

    // vérification quu'une fiche et une fiche de frais exist net calcul du mointant total de la fiche
    if (count($lesInfosFicheFrais) == 1){

        $montantValide = $lesInfosFicheFrais['montantValide'];
        $boolFicheDeFrais = false;
    } else {
        $fraisCalculer = $pdo->calculerFicheFrais($idVisiteur, $leMois);
        $montantValide = $fraisCalculer[0];
        $boolFicheDeFrais = true;
    }

     //Formalisation des dates
    $numAnnee = substr($leMois, 0, 4);
    $numMois = substr($leMois, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);

    include 'vues/v_selectionMoisVisiteur.php';
    include 'vues/v_suivreFrais.php';
    break;
     



  

}
