
    <div class="col-md-2" style="float: top;">
        <h5>Choisir le visiteur : </h5>
    </div>


    <div class="col-md-2"> 
   
        <?php 
$uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
        if($uc=='validerFrais'|| $uc=='corriger_frais'){
            ?>  <form action="index.php?uc=validerFrais&action=listeMois" method="post" role="form"> 
            <?PHP
        }elseif($uc=='SuivreLePaiement'){
             ?> <form action="index.php?uc=SuivreLePaiement&action=listeMois" method="post" role="form"> <?PHP
       }

?>
            <div class="row"> 
                <div class="input-group">
  <select class="custom-select form-control" id="visit"  name="visit" style="float: right;"> 
             
            <?php
             $i = 0;
                
                       while($i<count($nom)){
                        $i=$i+1;
                        if($nom[$i+1]==$nomASelectionner){
                            
                          ?>
      
                            <option  selected value="<?php echo $nom[$i+1] ?>">
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
    <input class="btn btn-outline-secondary"  type="submit" value="ok"  role="button">
  </div>
</div>
      
                
                