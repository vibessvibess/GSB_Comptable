  <?php
/**
 * gestion d'affichage des listes des visisteurs et mois pour valider la fiche et pour la mettre en paiement
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
switch ($action) {
case 'listeVisiteurs':
    $etatRechercher=$pdo->uc_visit();
    $idVisiteur = filter_input(INPUT_POST, 'visit', FILTER_SANITIZE_STRING); // recupere l'utilisteur selectionner
    VisiteurSelectionne($idVisiteur);
    $nom = $pdo->getVisiteur($etatRechercher); // pour l'affichage
    $nomASelectionner = $idVisiteur;      // pour que quand la page se recharge l'utilisateur seletionner est mis par defaut 
   
    include 'vues/v_ChoixV.php';
        break;
    
case 'listeMois': // lorsqu'il a choisit l'utilisateur
    $etatRechercher=$pdo->uc_visit();
    $nom = $pdo->getVisiteur($etatRechercher); // pour l'affichage 
    $idVisiteur = filter_input(INPUT_POST, 'visit', FILTER_SANITIZE_STRING); // recupere l'utilisteur selectionner
    VisiteurSelectionne($idVisiteur);
    $nomASelectionner = $idVisiteur; 
    $lesMois = $pdo->getListeMoisValidation($idVisiteur,$etatRechercher);// Afin de sélectionner par défaut le dernier mois dans la zone de liste
    $lesCles = array_keys($lesMois);
    $mois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    MoiSelectionne($mois);
    $moisASelectionner = $mois;
    include 'vues/v_ChoixM.php';
 break;
}

