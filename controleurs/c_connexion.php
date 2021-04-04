<?php
/**
 * Gestion de la connexion
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
if (!$uc) {
    $uc = 'demandeconnexion';
}

switch ($action) {
case 'demandeConnexion':
    include 'vues/v_connexion.php';
    break;
case 'valideConnexion'://on a juste validé le mot de passe et l'identifiant mais on est pas allé encore a une autre page
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING);
    $visiteur = $pdo->getInfosVisiteur($login, $mdp);//dans variable visiteur ya un tableau avec les infos id, nom et prénom
    $comptable= $pdo->getInfosComptable($login, $mdp);
    if (!is_array($visiteur) && !is_array($comptable)) {//si la variable visiteur na pas de tableau(array) alors...
        ajouterErreur('Login ou mot de passe incorrect');
        include 'vues/v_erreurs.php';
        include 'vues/v_connexion.php';
    } else {
        if(is_array($visiteur)){//on a séparé les variables du tableau
         $idUtilisateur = $visiteur['id'];
         $nom = $visiteur['nom'];
         $prenom = $visiteur['prenom'];
         $statut = 'visiteur';//pour que par la suite on ditingue facilement visiteur de comptable
        }elseif(is_array ($comptable)){
         $idUtilisateur = $comptable['id'];
         $nom = $comptable['nom'];
         $prenom = $comptable['prenom'];   
         $statut = 'comptable';
        }
        connecter($idUtilisateur, $nom, $prenom,$statut);
        header('Location: index.php');//permet de renvoyer a une page avec les données existantes
    }
    break;
default:
    include 'vues/v_connexion.php';
    break;
}
