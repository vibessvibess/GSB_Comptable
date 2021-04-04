<?php
/**
 * Gestion de l'accueil
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

if ($estConnecte) {
    include 'vues/v_accueil.php';//si on est connecté a l'appli aller dans la page accueil
} else {
    include 'vues/v_connexion.php';//si on ne l'est pas rester dans la page connection de l'appli
}
