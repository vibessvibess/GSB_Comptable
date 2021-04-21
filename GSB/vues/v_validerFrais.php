<?php
/**
 * Vue État de Frais
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
?> <hr>
<?php if ($boolFicheDeFrais) { ?>
   
    <div class="panel panel-primary">
        <div class="panel-heading">Fiche de frais du mois 
            <?php echo $numMois . '-' . $numAnnee ?> : </div>
        <div class="panel-body">
            <strong><u>Etat :</u></strong> <?php echo $libEtat ?>
            depuis le <?php echo $dateModif ?> <br> 
            <strong><u>Montant validé :</u></strong> <?php echo $montantValide ?>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">Eléments forfaitisés</div>
        <table class="table table-bordered table-responsive">
            <tr>
                <?php
                foreach ($lesFraisForfait as $unFraisForfait) {
                    $libelle = $unFraisForfait['libelle']; ?>
                    <th> <?php echo htmlspecialchars($libelle) ?></th>
                    <?php
                }
                ?>
            </tr>
            <tr></tr>
            <tr>
            <form action="index.php?uc=validerFrais&action=modifierFraisForfait" 
                 method="post" role="form">
                <?php
                foreach ($lesFraisForfait as $unFraisForfait) {
                    $quantite = $unFraisForfait['quantite']; ?>
                    <td><input id="idForfait" type="text" name="<?php echo $idVisiteur."-".$leMois."-".$unFraisForfait['idfrais'] ?>" value="<?php echo $quantite  ?>" ></td>
                    <?php
                }
                ?>
                 <input id="lstVisiteurs" name="lstVisiteurs" type="hidden" value='<?php echo  $idVisiteur ?>' >
                 <input id="lstMois" name="lstMois" type="hidden" value='<?php echo  $leMois ?>' >
                 <td><input id="modifier" type="submit" value="Valider" class="btn btn-success" role="button"></td>
            </form>
            </tr>
        </table>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">Descriptif des éléments hors forfait - 
            <?php echo $nbJustificatifs ?> justificatifs reçus</div>
        <table class="table table-bordered table-responsive">
            <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class='montant'>Montant</th>
                <th class='montant'>Reporter</th>
                <th class='montant'>Refuser</th>               
            </tr>
            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $date = $unFraisHorsForfait['date'];
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $montant = $unFraisHorsForfait['montant']; ?>
                <tr>
                    <td><?php echo $date ?></td>
                    <td><?php echo $libelle ?></td>
                    <td><?php echo $montant ?></td>
                    <td>  
                        <form action="index.php?uc=validerFrais&action=reporter" 
                        method="post" role="form">
                            <input id="reporter" type="submit" value="Reporter" class="btn btn-warning" role="button">
                            <input id="lstVisiteurs" name="lstVisiteurs" type="hidden" value='<?php echo  $idVisiteur ?>' >
                            <input id="lstMois" name="lstMois" type="hidden" value='<?php echo  $leMois ?>' >
                            <input id="libelle" name="libelle" type="hidden" value='<?php echo $libelle ?>' >
                            <input id="idHorsForfait" name="idHorsForfait" type="hidden" value='<?php echo  $unFraisHorsForfait['id'] ?>' >
                        </form>
                    </td>
                    <td>
                        <form action="index.php?uc=validerFrais&action=refuser"
                            method="post" role="form">
                            <input id="refuser" type="submit" value="Refuser" class="btn btn-danger" role="button">
                            <input id="lstVisiteurs" name="lstVisiteurs" type="hidden" value='<?php echo  $idVisiteur ?>' >
                            <input id="lstMois" name="lstMois" type="hidden" value='<?php echo  $leMois ?>' >
                            <input id="libelle" name="libelle" type="hidden" value='<?php echo $libelle ?>' >
                            <input id="idHorsForfait" name="idHorsForfait" type="hidden" value='<?php echo  $unFraisHorsForfait['id'] ?>' >
                            
                        </form>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>

    <form action="index.php?uc=validerFrais&action=validerFiche" method="post" role="form">
            <input id="lstVisiteurs" name="lstVisiteurs" type="hidden" value='<?php echo  $idVisiteur ?>' >
            <input id="lstMois" name="lstMois" type="hidden" value='<?php echo  $leMois ?>' >
            <input id="valider" type="submit" value="Valider Fiche" class="btn btn-success" role="button">
   </form>
<?php } else { ?>
    Pas de fiche de frais pour ce mois et ce visiteur
<?php } ?>