<?php
/**
 * Index du projet GSB
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

require_once 'includes/fct.inc.php';//le suffixe _once sert à limiter cette inclusion à une seule par page.cette bibliotheque est necessaire pour le php
require_once 'includes/class.pdogsb.inc.php';//require: inclure
session_start();
$pdo = PdoGsb::getPdoGsb();//connection ouverture de l'application:dans la variable on appelle la fonction getPdoGsb de la classe PdoGsb 
$estConnecte = estConnecte();
require 'vues/v_entete.php';//c'est l'entete . message d'erreur si il n'arrive pas à l'inclure
$uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);//filtre le contenue qui est envoye pr qu'il soit que en string pr pouvoir l'exploiter
if ($uc && !$estConnecte) { // si on est pas connecté et si il ya qqch dans $uc
    $uc = 'connexion';
} elseif (empty($uc)) {
    $uc = 'accueil'; 
}
switch ($uc) {
case 'connexion':
    include 'controleurs/c_connexion.php';
    break;
case 'accueil': //$uc prend la valeur accueil une foie que l'utlisateur est connecte(plus haut) 
    include 'controleurs/c_accueil.php';
    break;
case 'gererFrais':
    include 'controleurs/c_gererFrais.php'; //permettre de creer la nouvelle fiche
    break;
case 'validerFrais': //pour valider l fiche de frais quand il clique sur valider fiche de frais
    include 'controleurs/c_validerFrais.php'; 
    break;
case'corriger_frais':
    include 'controleurs/c_corriger_frais.php';
    break;
case 'etatFrais':
    include 'controleurs/c_etatFrais.php';
    break;
case 'SuivreLePaiement':
    include 'controleurs/c_validerFrais.php';
    break;
case 'SuivrePaiement':
    include 'controleurs/c_SuivrePaiment.php';
    break;
case 'deconnexion':
    include 'controleurs/c_deconnexion.php';
    break;
}
require 'vues/v_pied.php';//pied de page de l'accueil
