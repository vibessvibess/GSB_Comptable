

    <div class="col-md-2" style="float: top;">
        <h5>Choisir le visiteur : </h5>
    </div>
    <div class="col-md-2"> 
        <?php 
$uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
        if($uc=='validerFrais'|| $uc=='corriger_frais'){
            ?> <form action="index.php?uc=validerFrais&action=listeMois" method="post" role="form">  <?PHP
        }elseif($uc=='SuivrePaiement'){
             ?><form action="index.php?uc=SuivreLePaiement&action=listeMois" method="post" role="form"><?PHP
        }

?>         
            <div class="row"> 
                <div class="input-group">
  <select class="custom-select form-control" id="visit"  name="visit" style="float: right;"> <!-- liste deroulante de tous les nom/prenoms des visiteurs qui ont une fiche de frais à valider -->
             
            <?php
             $i = 0;
                
                       while($i<count($nom)){
                        $i=$i+1;
                        if($nom[$i+1]==$nomASelectionner){ // permet de mettre le nom selectionner en premier dans toutes les pages
                            
                          ?>
      
                            <option  selected value="<?php echo $nom[$i+1] ?>"> <!-- on met l'id comme valeur ,$nom[] est le tableau avec dedans dans cet ordre: le nom, prenompuis l'id -->
                                  <?php echo $nom[$i-1].' '.$nom[$i] ;
                            $i=$i+2;
                              ?> </option>
                       <?php }else{
                           
                            ?>
                            <option  value="<?php echo $nom[$i+1] ?>">
                                <?php echo $nom[$i-1].' '.$nom[$i] ;
                            $i=$i+2;  ?>
                            </option> 
                            <?php
                    }
                       }
                    ?> 
                </select>
                  </form>
  </select>
  <div class="input-group-append">
    <input id="ok" type="submit" value="Valider" class="btn btn-success" 
                   role="button">
  </div>
</div>
            </div>
    </div>
               
<div class="col-md-2">
        <h5>Choisir le mois : </h5>
</div>

   <div class="col-md-2">

<?php 
         $uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
        if($uc=='validerFrais'|| $uc=='corriger_frais'){?>
<form action="index.php?uc=corriger_frais&action=afficherFrais" 
                  method="post" role="form">
  <?php }else{ ?>
    <form action="index.php?uc=SuivrePaiement&action=suivre_LePaiment" method="post" role="form"><?php
   }
?>
        <div class="row">    
<div class="input-group">
  <select class="custom-select form-control" id="lstMois" name="lstMois"  style="float: right ;"> <?php
   foreach ($lesMois as $unMois) {
                        $mois = $unMois['mois'];
                     
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                     
                        
                    
      if(($mois)==$moisASelectionner){ // permet de mettre le nom selectionner en premier dans toutes les pages
                            
                          ?>
      
                            <option  selected value="<?php $mois ?>"> <!-- on met l'id comme valeur ,$nom[] est le tableau avec dedans dans cet ordre: le nom, prenompuis l'id -->
                                  <?php echo $numMois . '/' . $numAnnee ?> </option>
                             
                       <?php }else{
                          
                            
                    ?>
                            <option value="<?php echo $mois ;?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        }
   }
                    ?>           
           
                
  </select>
  <div class="input-group-append">
    <input id="ok" type="submit" value="Valider" class="btn btn-success" 
                   role="button">
    </form>
  </div>
</div>
            </div>
     </div>

</br></br></br>        
