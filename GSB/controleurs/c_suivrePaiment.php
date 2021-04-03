<?php
/**
 * gestion pour permettre le paiement et le suivre
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
case 'suivre_LePaiment':
    $idVisiteur = $_SESSION['idVisiteur'];
    $mois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    MoiSelectionne($mois);
    $moisASelectionner = $mois;
    $nomASelectionner = $idVisiteur; 
    $etatRechercher=$pdo->uc_visit();
    $nom = $pdo->getVisiteur($etatRechercher);// pour afficher la liste des visiteurs
    $lesMois = $pdo->getLesMois($idVisiteur,$etatRechercher); // verification s'ill existe des mois VA pour ce visiteur
    $lesCles = array_keys($lesMois);   
    $mois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    MoiSelectionne($mois);
     include 'vues/v_mois.php';
    break;
    case"rembourser":
        $idVisiteur = $_SESSION['idVisiteur'];
        $mois=$_SESSION['mois'];
        $moisASelectionner = $mois;
        $nomASelectionner = $idVisiteur; 
        $etatRechercher=$pdo->uc_visit();
        $nom = $pdo->getVisiteur($etatRechercher);// pour afficher la liste des visiteurs
        $lesMois = $pdo->getLesMois($idVisiteur,$etatRechercher); // verification s'ill existe des mois VA pour ce visiteur
        $lesCles = array_keys($lesMois); 
     try{
    if (isset($_POST['Demander_Remboursement'])) {
             $etat = "MP";
             $pdo-> majEtatFicheFrais($idVisiteur, $mois,$etat); // permet de modifier l'etat d'une fiche
              echo '<script type="text/javascript">window.alert("La fiche a bien été mis en etat de mise en paiement ");</script>';
    } elseif(isset($_POST['Confirmer_Remboursement'])) {
               if ($pdo->testEtat($idVisiteur, $moisASelectionner)) { // si yen a ps
             echo '<script type="text/javascript">window.alert("Avant de mettre la fiche en remboursement, la mettre en mise en paiement svp");</script>';
      }else{
           $etat = "RB";
         $pdo-> majEtatFicheFrais($idVisiteur, $mois,$etat); // permet de modifier l'etat d'une fiche
          echo '<script type="text/javascript">window.alert("La fiche est bien remboursée ");</script>';
           } 
      }
   }
catch(Exception $e)
{
	exit('<b>Catched exception at line '. $e->getLine() .' :</b> '. $e->getMessage());
}
 include 'vues/v_mois.php';
    break;

}
     
     $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $moisASelectionner);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $moisASelectionner);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $moisASelectionner);
    $numAnnee = substr($moisASelectionner, 0, 4);
    $numMois = substr($moisASelectionner, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
   require 'vues/v_etatFrais.php';
?>