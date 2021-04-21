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

require_once 'includes/fct.inc.php';
require_once 'includes/class.pdogsb.inc.php';
require_once 'tests/gendatas/fonctions.php';

session_start();
$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();
require 'vues/v_entete.php';
$uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
if ($uc && !$estConnecte) {
    $uc = 'connexion';

} elseif (empty($uc)) {
    $uc = 'accueil';
}

if ($estConnecte) {
    
    // Lors de la connexion d'un Medecin
    if ($_SESSION['role'] == "V"){
        switch ($uc) {
            case 'accueil':
                include 'controleurs/c_accueil.php';
                break;
            case 'gererFrais':
                include 'controleurs/c_gererFrais.php';
                break;
            case 'etatFrais':
                include 'controleurs/c_etatFrais.php';
                break;
            case 'deconnexion':
                include 'controleurs/c_deconnexion.php';
                break;
           
        }    
    }
    // Lors de la connexion d'un comptable
    if ($_SESSION['role'] == "C"){
        switch ($uc) {
            case 'accueil':
                include 'controleurs/c_accueil.php';
                break;
            case 'validerFrais':
                include 'controleurs/c_validerFrais.php';
                break;
            case 'suivreFrais':
                include 'controleurs/c_suivreFrais.php';
                break;
            case 'deconnexion':
                include 'controleurs/c_deconnexion.php';
                break;
        }    
        
    }
} else {
    switch ($uc) {
        case 'connexion':
            include 'controleurs/c_connexion.php';
            break;
        case 'accueil':
            include 'controleurs/c_accueil.php';
            break;
        case 'deconnexion':
            include 'controleurs/c_deconnexion.php';
            break;
    }

}

require 'vues/v_pied.php';
