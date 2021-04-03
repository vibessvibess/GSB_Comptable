<?php

/**
 * Classe d'accÃ¨s aux donnÃ©es.
 *
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO
 * $monPdoGsb qui contiendra l'unique instance de la classe
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Cheri Bibi - RÃ©seau CERTA <contact@reseaucerta.org>
 * @copyright 2017 RÃ©seau CERTA
 * @license   RÃ©seau CERTA
 * @version   Release: 1.0
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */

class PdoGsb
{
  
   
    private static $serveur = 'mysql:host=localhost';
    private static $bdd = 'dbname=gsb_frais';
    private static $user = 'root';
    private static $mdp = '';
    private static $monPdo;
    private static $monPdoGsb = null;//correspond a la connection a la base de donnée
   

    /**
     * Constructeur privÃ©, crÃ©e l'instance de PDO qui sera sollicitÃ©e
     * pour toutes les mÃ©thodes de la classe
     */
    private function __construct()
    {
		
        PdoGsb::$monPdo = new PDO(
            PdoGsb::$serveur . ';' . PdoGsb::$bdd,
            PdoGsb::$user,
            PdoGsb::$mdp
        );
        PdoGsb::$monPdo->query('SET CHARACTER SET utf8');//pdo permet la connection mais aussi l'interaction avec la bdd et la il s'agit d'une requete ( PdoGsb::$monPdo->query)qui veut que tt la bdd soit codÃ© en certains caracteres
    }
    /**
     * MÃ©thode destructeur appelÃ©e dÃ¨s qu'il n'y a plus de rÃ©fÃ©rence sur un
     * objet donnÃ©, ou dans n'importe quel ordre pendant la sÃ©quence d'arrÃªt.
     */
    public function __destruct()//se deconnecter
    {
        PdoGsb::$monPdo = null;
    }

    /**
     * Fonction statique qui crÃ©e l'unique instance de la classe
     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
     *
     * @return l'unique objet de la classe PdoGsb
     */
    public static function getPdoGsb()// static:qui retourne tjrs pareil
    {//SI MA BASE DE DONN2E:PdoGsb N4EST PAS COnnÃ©ctÃ©e  refaire une connection
        if (PdoGsb::$monPdoGsb == null) {//si monPdoGsb == nul ca signifie que ya pas eu de connection  
            PdoGsb::$monPdoGsb = new PdoGsb();//une instance c qu'on realise tt ce qu'il ya dans le constructeur pdo (coorespond en php a la connection)
        }
        return PdoGsb::$monPdoGsb;
    }                                                          //**********************//

    /**
     * Retourne les informations d'un visiteur
     *
     * @param String $login Login du visiteur
     * @param String $mdp   Mot de passe du visiteur
     *
     * @return l'id, le nom et le prÃ©nom sous la forme d'un tableau associatif
     */
    public function getInfosVisiteur($login, $mdp)
            
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT visiteur.id AS id, visiteur.nom AS nom, '
            . 'visiteur.prenom AS prenom '
            . 'FROM visiteur '
            . 'WHERE visiteur.login = :unLogin AND visiteur.mdp = :unMdp'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);//:unLogin correspond a $login
        $requetePrepare->bindParam(':unMdp', $mdp, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }
    
    /**
     * Retourne les visiteurs qui ont une fiche cloturé qui doit etre valider
     *
     *
     * @return l'id, le nom et le prÃ©nom sous la forme d'un tableau associatif
     */
     public function getVisiteur($etatRechercher){
                 if ($etatRechercher=="CL"){
        $requetePrepare = PdoGSB::$monPdo->prepare(
          'SELECT DISTINCT visiteur.nom AS nom, visiteur.prenom AS prenom, visiteur.id AS id '
            . 'FROM visiteur '
            .'INNER JOIN fichefrais ON visiteur.id=fichefrais.idvisiteur '   
                . ' Where idetat= :unIdEtat'
       );
        $requetePrepare->bindParam(':unIdEtat', $etatRechercher, PDO::PARAM_STR);
            }else{
       $R= "MP";
       $RB='RB';
       $VA='VA';
        $requetePrepare = PdoGSB::$monPdo->prepare(
              'SELECT DISTINCT visiteur.nom AS nom, visiteur.prenom AS prenom, visiteur.id AS id '
            . 'FROM visiteur '
            .'INNER JOIN fichefrais ON visiteur.id=fichefrais.idvisiteur '
            . 'Where fichefrais.idetat = :unIdEtat OR fichefrais.idetat = :remb OR fichefrais.idetat = :mp '
            . 'ORDER BY visiteur.nom desc'
                         
            
        ); 
          $requetePrepare->bindParam(':unIdEtat',$VA, PDO::PARAM_STR);
           $requetePrepare->bindParam(':mp',$R , PDO::PARAM_STR); 
      $requetePrepare->bindParam(':remb',$RB , PDO::PARAM_STR); 
            }
        $requetePrepare->execute();
          $nom = array();
        $rowAll=$requetePrepare->fetchAll(PDO::FETCH_BOTH);
        foreach( $rowAll as $row )
        {
            $nom[]=$row['nom'];
            $nom[]=$row['prenom'];
            $nom[]=$row['id'];
             
        }
     return $nom ;
 
     }

    /** Retourne les informations d'un comptable
     *
     * @param String $login Login du visiteur
     * @param String $mdp   Mot de passe du visiteur
     *
     * @return l'id, le nom et le prÃ©nom sous la forme d'un tableau associatif
     */
    public function getInfosComptable($login, $mdp)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare( 
            'SELECT comptable.id AS id, comptable.nom AS nom, '
            . 'comptable.prenom AS prenom '
            . 'FROM comptable '
            . 'WHERE comptable.login = :unLogin AND comptable.mdp = :unMdp'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMdp', $mdp, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * hors forfait concernÃ©es par les deux arguments.
     * La boucle foreach ne peut Ãªtre utilisÃ©e ici car on procÃ¨de
     * Ã  une modification de la structure itÃ©rÃ©e - transformation du champ date-
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return tous les champs des lignes de frais hors forfait sous la forme
     * d'un tableau associatif
     */
    public function getLesFraisHorsForfait($idVisiteur, $mois)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT * FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
            . 'AND lignefraishorsforfait.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesLignes = $requetePrepare->fetchAll();
        for ($i = 0; $i < count($lesLignes); $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }

   
    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * au forfait concernÃ©es par les deux arguments
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return l'id, le libelle et la quantitÃ© sous la forme d'un tableau
     * associatif
     */
    public function getLesFraisForfait($idVisiteur, $mois)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'SELECT fraisforfait.id as idfrais, '
            . 'fraisforfait.libelle as libelle, '
            . 'lignefraisforfait.quantite as quantite '
            . 'FROM lignefraisforfait '
            . 'INNER JOIN fraisforfait '
            . 'ON fraisforfait.id = lignefraisforfait.idfraisforfait '
            . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
            . 'AND lignefraisforfait.mois = :unMois '
            . 'ORDER BY lignefraisforfait.idfraisforfait'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }
    
 
    /**
     * Retourne tous les id de la table FraisForfait
     *
     * @return un tableau associatif
     */
    public function getLesIdFrais()
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT fraisforfait.id as idfrais '
            . 'FROM fraisforfait ORDER BY fraisforfait.id'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }
    
    
    /**
     * Met Ã  jour la table ligneFraisForfait
     * Met Ã  jour la table ligneFraisForfait pour un visiteur et
     * un mois donnÃ© en enregistrant les nouveaux montants
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param Array  $lesFrais   tableau associatif de clÃ© idFrais et
     *                           de valeur la quantitÃ© pour ce frais
     *
     * @return null
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais)
    {
        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $requetePrepare = PdoGSB::$monPdo->prepare( // on rajoute a la table lignefraisForfait la quantite pour tel utillisateur , tel mois 
                'UPDATE lignefraisforfait '
                . 'SET lignefraisforfait.quantite = :uneQte '   // les frais c'est a dire le forfait etape 
                . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraisforfait.mois = :unMois '
                . 'AND lignefraisforfait.idfraisforfait = :idFrais'   //pour quel type de frais (nuit restaurant ...)
            );
            $requetePrepare->bindParam(':uneQte', $qte, PDO::PARAM_INT);
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFrais, PDO::PARAM_STR);
            $requetePrepare->execute();
        }
    }

     /**
     * Supprime un frais hors forfait
     * Supprime un frais hors forfait pour un visiteur et
     * un mois et un id, libelle de frais 
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
      * @param String $libelle 
      * @param String $idFrais
     * @return null
     */
   function SupprimerFrais($idVisiteur, $mois,$libelle,$idFrais){
   
           foreach ($idFrais as $unIdFraisH) {
         $qteL = $libelle[$unIdFraisH];
	    $qteL = 'Refuser-'.$qteL ;
          $requetePrepare = PdoGSB::$monPdo->prepare(         
         'UPDATE lignefraishorsforfait '
          
	        . 'SET lignefraishorsforfait.libelle = :libelle '   // les frais c'est a dire le forfait etape 
                . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraishorsforfait.mois = :unMois '
                . 'AND lignefraishorsforfait.id = :idFrais'   //pour quel type de frais (nuit restaurant ...)
            );
		  $requetePrepare->bindParam(':libelle', $qteL, PDO::PARAM_STR);
           $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFraisH, PDO::PARAM_STR);
            $requetePrepare->execute();
			
    }
    }  
  
     /**
     * met un jour un frais hors forfait
     * met un jour un frais hors forfait pour un visiteur et
     * un mois et un id, libelle de frais 
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
      * @param String $libelle 
      * @param String $idFrais
     * @return null
     */
    
  public function majFraisForfaitHdd($idVisiteur, $mois , $idFrais,$libelle,$montant,$date)
    {
       foreach ($idFrais as $unIdFraisH) {
       $qteL = $libelle[$unIdFraisH];
       $qteM = $montant[$unIdFraisH];
       $qteD = $date[$unIdFraisH];
       $dateFr = dateFrancaisVersAnglais($qteD);
       $requetePrepare = PdoGSB::$monPdo->prepare(         
         'UPDATE lignefraishorsforfait '
                . 'SET lignefraishorsforfait.libelle = :libelle,' 
                . 'lignefraishorsforfait.montant = :montant,'
                . 'lignefraishorsforfait.date = :date '  // les frais c'est a dire le forfait etape 
                . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraishorsforfait.mois = :unMois '
                . 'AND lignefraishorsforfait.id = :idFrais'   //pour quel type de frais (nuit restaurant ...)
            );
          $requetePrepare->bindParam(':libelle', $qteL, PDO::PARAM_INT);
            $requetePrepare->bindParam(':montant', $qteM, PDO::PARAM_INT);
             $requetePrepare->bindParam(':date', $dateFr, PDO::PARAM_INT);
           $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFraisH, PDO::PARAM_STR);
            $requetePrepare->execute();
    }
    
    }
    /**
     * Met Ã  jour le nombre de justificatifs de la table ficheFrais
     * pour le mois et le visiteur concernÃ©
     *
     * @param String  $idVisiteur      ID du visiteur
     * @param String  $mois            Mois sous la forme aaaamm
     * @param Integer $nbJustificatifs Nombre de justificatifs
     *
     * @return null
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs)
    {

        $requetePrepare = PdoGsB::$monPdo->prepare(
            'UPDATE fichefrais '
            . 'SET nbjustificatifs = :unNbJustificatifs '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam( ':unNbJustificatifs',$nbJustificatifs,PDO::PARAM_INT);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Teste si un visiteur possÃ¨de une fiche de frais pour le mois passÃ© en argument
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return vrai ou faux
     */
    public function estPremierFraisMois($idVisiteur, $mois)
    {
        $boolReturn = false;
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT fichefrais.mois FROM fichefrais '
            . 'WHERE fichefrais.mois = :unMois '
            . 'AND fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR); //ReprÃ©sente les types de donnÃ©es CHAR, VARCHAR ou les autres types de donnÃ©es sous forme de chaÃ®ne de caractÃ¨res SQL. 
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        if (!$requetePrepare->fetch()) {
            $boolReturn = true;
        }
        return $boolReturn;
    }
    
    /**
     * Teste si la fiche est dans l'etat MP pour 
     * pouvoir passer a l'etat RB
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return vrai ou faux
     */
    
    public function testEtat($idVisiteur,$mois)
    {
        
        $etat="MP";
         $boolReturn = false;
         $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT fichefrais.mois FROM fichefrais '
            . 'WHERE fichefrais.mois = :unMois '
            . 'AND fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.idetat = :unEtat '
                 
        );
         $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR); //ReprÃ©sente les types de donnÃ©es CHAR, VARCHAR ou les autres types de donnÃ©es sous forme de chaÃ®ne de caractÃ¨res SQL. 
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
         $requetePrepare->bindParam(':unEtat', $etat, PDO::PARAM_STR);
        $requetePrepare->execute();
        if (!$requetePrepare->fetch()) {
            $boolReturn = true;
        }
        return $boolReturn;
    }

    /**
     * Retourne le dernier mois en cours d'un visiteur
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return le mois sous la forme aaaamm
     */
    public function dernierMoisSaisi($idVisiteur)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT MAX(mois) as dernierMois '
            . 'FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }

    /**
     * CrÃ©e une nouvelle fiche de frais et les lignes de frais au forfait
     * pour un visiteur et un mois donnÃ©s
     *
     * RÃ©cupÃ¨re le dernier mois en cours de traitement, met Ã  'CL' son champs
     * idEtat, crÃ©e une nouvelle fiche de frais avec un idEtat Ã  'CR' et crÃ©e
     * les lignes de frais forfait de quantitÃ©s nulles
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return null
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois)
    {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur); //Retourne le dernier mois en cours d'un visiteur
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois); //Retourne les informations d'une fiche de frais(montant, son etat ..) d'un visiteur pour un mois donne 
    
        if ($laDerniereFiche['idEtat'] == 'CR') { // fiche en cours de creation
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL'); // modifie l'etat de la fiche, la met a CL qui signifie saisie cloture
        }
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'INSERT INTO fichefrais (idvisiteur,mois,nbJustificatifs,'
            . 'montantValide,dateModif,idEtat) '
            . "VALUES (:unIdVisiteur,:unMois,0,0,now(),'CR')"
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesIdFrais = $this->getLesIdFrais(); //retourne tous les id de la table ficheforfait (ensemble d'id pour definir les fofrait nuit...)
        foreach ($lesIdFrais as $unIdFrais) {
            $requetePrepare = PdoGsb::$monPdo->prepare(
                'INSERT INTO lignefraisforfait (idvisiteur,mois,'
                . 'idFraisForfait,quantite) '
                . 'VALUES(:unIdVisiteur, :unMois, :idFrais, 0)'
            );
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(
                ':idFrais',
                $unIdFrais['idfrais'],
                PDO::PARAM_STR
            );
            $requetePrepare->execute();
        }
    }

    /**
     * CrÃ©e un nouveau frais hors forfait pour un visiteur un mois donnÃ©
     * Ã  partir des informations fournies en paramÃ¨tre
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $libelle    LibellÃ© du frais
     * @param String $date       Date du frais au format franÃ§ais jj//mm/aaaa
     * @param Float  $montant    Montant du frais
     *
     * @return null
     */
    public function creeFraisHorsForfait(
        $idVisiteur,
        $mois,
        $libelle,
        $date,
        $montant,
        $idFrais
    ) {
        foreach ($idFrais as $unIdFraisH) {
         $libelle = $libelle[$unIdFraisH];
        $montant = $montant[$unIdFraisH];
        $date = $date[$unIdFraisH];
        $dateFr = dateFrancaisVersAnglais($date);
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'INSERT INTO lignefraishorsforfait '
            . 'VALUES (null, :unIdVisiteur,:unMois, :unLibelle, :uneDateFr,'
            . ':unMontant) '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->execute();
    }
    
    }
    
    
        public function creeNouveauFraisHorsForfait(
        $idVisiteur,
        $mois,
        $libelle,
        $date,
        $montant
    ) {
       
        $dateFr = dateFrancaisVersAnglais($date);
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'INSERT INTO lignefraishorsforfait '
            . 'VALUES (null, :unIdVisiteur,:unMois, :unLibelle, :uneDateFr,'
            . ':unMontant) '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->execute();
    }
    

    /**
     * Supprime le frais hors forfait dont l'id est passÃ© en argument
     *
     * @param String $idFrais ID du frais
     *
     * @return null
     */
    public function supprimerFraisHorsForfait($idFrais)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'DELETE FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.id = :unIdFrais'
        );
        $requetePrepare->bindParam(':unIdFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
    /**
     * supprimer la fiche hors forfait lorsqu'elle est reporter selon
     * son ID et le mois
     * @param type $idFrais
     * @param type $mois
     */
     public function supprimerLeFraisHorsForfait($idFrais,$mois)
    {
         foreach ($idFrais as $id){
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'DELETE FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.id = :unIdFrais AND lignefraishorsforfait.mois = :mois '  
        );
        $requetePrepare->bindParam(':unIdFrais', $id, PDO::PARAM_STR);
         $requetePrepare->bindParam(':mois', $mois, PDO::PARAM_INT);
        $requetePrepare->execute();
    }

    }
    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return un tableau associatif de clÃ© un mois -aaaamm- et de valeurs
     *         l'annÃ©e et le mois correspondant
     */
    public function getLesMoisDisponibles($idVisiteur)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'SELECT fichefrais.mois AS mois FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesMois = array();
     
        while ($laLigne = $requetePrepare->fetch()) {
           
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array( 
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois   
            );
    }//retourne le moi en fonction du statut des fiches de frais et du vsiteur
  return $lesMois;
    }
    
    
    /*Retourne tous les mois pour lesquel les utilisateurs ont 
     * une fiche cloture
     * ainsi le comptable pourra les valider
     * ou a une fiche MP VA RB selon l'etat mis en parametre
     */
    
        public function getLesMois($idVisiteur,$etat)
    {     
            if ($etat=="CL"){
                $CL='CL';
        $requetePrepare = PdoGSB::$monPdo->prepare(
           'SELECT fichefrais.mois AS mois FROM fichefrais '
           . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
          . 'AND fichefrais.idetat = :cl '
            . 'ORDER BY fichefrais.mois desc'
       );
           $requetePrepare->bindParam(':cl',$CL, PDO::PARAM_STR); 
            }else{
             $R= "MP";
             $RB='RB';
             $VA ='VA';
                $requetePrepare = PdoGSB::$monPdo->prepare(
           'SELECT DISTINCT fichefrais.mois AS mois FROM fichefrais '
           . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
          . 'AND fichefrais.idetat = :va OR fichefrais.idetat = :remb OR fichefrais.idetat = :mp '
            . 'ORDER BY fichefrais.mois desc'
        ); 
            $requetePrepare->bindParam(':mp',$R , PDO::PARAM_STR); 
             $requetePrepare->bindParam(':va',$VA , PDO::PARAM_STR); 
             $requetePrepare->bindParam(':remb',$RB , PDO::PARAM_STR); 
            }
                
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesMois = array();
           $i=0;    
        while ($laLigne = $requetePrepare->fetch()) {
            
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
          
        }
       
        return $lesMois;
    }
    
    
    
    /*
    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un
     * mois donnÃ©
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return un tableau avec des champs de jointure entre une fiche de frais
     *         et la ligne d'Ã©tat
   
  */
   public function getLesInfosFicheFrais($idVisiteur, $mois)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'SELECT fichefrais.idetat as idEtat, '
            . 'fichefrais.datemodif as dateModif,'
            . 'fichefrais.nbjustificatifs as nbJustificatifs, '
            . 'fichefrais.montantvalide as montantValide, '
            . 'etat.libelle as libEtat '
            . 'FROM fichefrais '
            . 'INNER JOIN etat ON fichefrais.idetat = etat.id '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne;
    }
     /*
    /**
     * Retourne l'etat des fiches selon uc 
     * mois donnÃ©
     */
    public function uc_visit(){
        $uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
        if($uc=='validerFrais'|| $uc=='corriger_frais'){
            
           $etatRechercher="CL";
   }else{
        $etatRechercher="VA";
   }
   
   return $etatRechercher;
        
    }
    /**
     * Modifie l'Ã©tat et la date de modification d'une fiche de frais.
     * Modifie le champ idEtat et met la date de modif Ã  aujourd'hui.
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $etat       Nouvel Ã©tat de la fiche de frais
     *
     * @return null
*/
         public function majEtatFicheFrais($idVisiteur, $mois, $etat)
    {
		
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'UPDATE fichefrais '
            . 'SET idetat = :unetat, datemodif = now() '
            . 'WHERE fichefrais.idvisiteur = :unidvisiteur '
            . 'AND fichefrais.mois = :unmois'
        );
        $requetePrepare->bindParam(':unetat', $etat, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unidvisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unmois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
	  /**
     * Modifie le montant valider en fonction de la fiche et des frais (forfait et hors forfait valider ) 
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $etat       Nouvel état de la fiche de frais
     *
     * @return null
   */ 
	public function montantValider($idVisiteur,$mois,$quantite){
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'UPDATE fichefrais '
                 .'SET montantvalide = :montant '
                 . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                  . 'AND fichefrais.mois = :unMois'
                );
         $requetePrepare->bindParam(':montant', $quantite, PDO::PARAM_INT);
         $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    public function getLesVisiteurs()
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'select *'
            . 'from visiteur'
               
        );
         $requetePrepare->execute();
       return $requetePrepare->fetchAll();
       
    }
}
