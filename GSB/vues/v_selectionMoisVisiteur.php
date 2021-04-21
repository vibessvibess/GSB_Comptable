<?php
/**
 * Vue Liste des mois
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
?>
<h2>Mes fiches de frais</h2>
<div class="row">
    <div class="col-md-4">
        <h3>Sélectionner Informations : </h3>
    </div>
    <div class="col-md-4">
    <?php if ($uc == "validerFrais" ) { ?>
        <form action="index.php?uc=validerFrais&action=voirEtatFrais" 
              method="post" role="form">
    <?php } else { ?>
        <form action="index.php?uc=suivreFrais&action=etatFrais" 
              method="post" role="form">
    <?php } ?>
            <div class="form-group">
                <label for="lstMois" accesskey="n">Mois : </label>
                <select id="lstMois" name="lstMois" class="form-control">
                    <?php
                    foreach ($lesMois as $unMois) {
                        $mois = $unMois['mois'];
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        if ($mois == $moisASelectionner) {
                            ?>
                            <option selected value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        }
                    }
                    ?> 
                </select>
            </div>
    
            <div class="form-group">
                <label for="lstVisiteurs" accesskey="n">Visiteurs: </label>
                <select id="lstVisiteurs" name="lstVisiteurs" class="form-control">
                    <?php
                    foreach ($visiteurs as $key => $visiteur) {
                        $visiteurId = $visiteur['id'];
                        $visiteurNom = $visiteur['nom'];
                        $visiteurPrenom = $visiteur['prenom'];

                        if ($visiteurId == $visiASelectionner) {
                            ?>
                            <option selected value="<?php echo  $visiteurId ?>">
                                <?php echo $visiteurPrenom . ' ' .  $visiteurNom ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $visiteurId ?>">
                                <?php echo $visiteurPrenom . ' ' .  $visiteurNom ?> </option>
                            <?php
                        }
                    }
                    ?>    

                </select>
            </div>
            <input id="ok" type="submit" value="Valider" class="btn btn-success" 
                   role="button">
            <input id="annuler" type="reset" value="Effacer" class="btn btn-danger" 
                   role="button"  onclick="window.location.href = 'index.php?uc=validerFrais&action=selectionnerMoisVisiteur';">
        </form>
    </div>
</div>