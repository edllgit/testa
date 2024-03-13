<?php
// POLAR
case 'PROMO PRECISION+ POLAR GREY SUPER AR BACK': 

    switch($EYE){	
        case 'Both': 
            if ($RE_ADD=='' || $LE_ADD==''){
                if ($UnSvUnProg == false){	
                    $InsererDansBD = false;	
                    $ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
                }
            }	
    }//END Switch
    
        //Partie commune
        $ProdTable = "ifc_ca_exclusive"; 
        $CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
        $SauterValidationFH = "";
        //Paramètres propre à ce produit seulement
        $ProdName  = "  product_name like '%Precision+%' AND product_name like '%promo%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
        AND product_name not like '%360%' AND product_name not like '%420%'  "; 
        $ORDER_PRODUCT_COATING	= "AR Backside";
        $ORDER_PRODUCT_POLAR	= "Grey";
        $ORDER_PRODUCT_PHOTO	= "None";
        if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
            $InsererDansBD  = false;
            $ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
            The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
        }//Fin si aucun corridor n'a été fournis	
        
        switch($CORRIDOR){
                case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
                case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
                case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
        }//END SWITCH
    break;


    // CLEAR

    case 'NURBS PROG CLEAR':  //Halifax 

        switch($EYE){	
            case 'Both': 
                if ($RE_ADD=='' || $LE_ADD==''){
                    if ($UnSvUnProg == false){	
                        $InsererDansBD = false;	
                        $ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
                    }
                }	
        }//END Switch
        
            $ProdName  = "  product_name like '%Deep%' and lens_category <> 'sv' AND polar='None' and photo='none' and product_name NOT like '%tinted%'"; 
            $ProdTable = "ifc_ca_exclusive"; 
            $CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
            $SauterValidationFH = "";
            
            if ($CORRIDOR <> ''){
                //Produit HKO + corridor = On doit filtrer le corridor avec le code produit
                switch($CORRIDOR){
                    case '5':  $ProdName  .= " AND product_code like '%5H%'";  $SauterValidationFH = "yes"; break; 
                    case '7':  $ProdName  .= " AND product_code like '%7H%'";  $SauterValidationFH = "yes"; break;    
                    case '9':  $ProdName  .= " AND product_code like '%9H%'";  $SauterValidationFH = "yes"; break;    
                    case '11': $ProdName  .= " AND product_code like '%11H%'"; $SauterValidationFH = "yes"; break;    
                    case '13': $ProdName  .= " AND product_code like '%13H%'"; $SauterValidationFH = "yes"; break;    
                    case '15': $ProdName  .= " AND product_code like '%15H%'"; $SauterValidationFH = "yes"; break;    
                }	
            }elseif ($EYE == 'Both'){
                $ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
            }elseif($EYE == 'R.E.'){
                $ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
            }elseif($EYE == 'L.E.'){
                $ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
            }
        break;


        //Debut

        // CLEAR

       // case 'NURBS PROG CLEAR':  //Halifax ç
        case 'PRECISION+ S 1.5 CLEAR HC':

            switch($EYE){	
                case 'Both': 
                    if ($RE_ADD=='' || $LE_ADD==''){
                        if ($UnSvUnProg == false){	
                            $InsererDansBD = false;	
                            $ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
                        }
                    }	
            }//END Switch
            
                $ProdName  = "  product_name like '%Precision+ S%' and lens_category = 'prog ff' AND polar='None' and photo='none' and product_name NOT like '%tinted%'"; 
                $ProdTable = "ifc_ca_exclusive"; 
                $CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
                $SauterValidationFH = "";

                $ORDER_PRODUCT_COATING	= "Hard Coat";

                if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '7') || ($CORRIDOR == '9')) {
                    $InsererDansBD  = false;
                    $ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (11, 13 ou 15). Svp ajouter le corridor (11-13-15) et re-exporter la commande.<br> 
                    The corridor (11-13-15) is mandatory for this product. Please add a corridor (11, 13 or 15) and re-export the order.';
                }//Fin si aucun corridor n'a été fournis	


                switch($CORRIDOR){
                        case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;    
                        case '13': 	$ProdName  .= " AND corridor = 13 "; 	$SauterValidationFH = "yes"; break;    
                        case '15': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
                }//END SWITCH
               
            break;


            case 'PRECISION+ S 1.5 CLEAR AR+ETC':

                switch($EYE){	
                    case 'Both': 
                        if ($RE_ADD=='' || $LE_ADD==''){
                            if ($UnSvUnProg == false){	
                                $InsererDansBD = false;	
                                $ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
                            }
                        }	
                }//END Switch
                
                    $ProdName  = "  product_name like '%Precision+ S%' and lens_category = 'prog ff' AND polar='None' and photo='none' and product_name NOT like '%tinted%'"; 
                    $ProdTable = "ifc_ca_exclusive"; 
                    $CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
                    $SauterValidationFH = "";
    
                    $ORDER_PRODUCT_COATING	= " ITO AR";
    
                    if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '7') || ($CORRIDOR == '9')) {
                        $InsererDansBD  = false;
                        $ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (11, 13 ou 15). Svp ajouter le corridor (11-13-15) et re-exporter la commande.<br> 
                        The corridor (11-13-15) is mandatory for this product. Please add a corridor (11, 13 or 15) and re-export the order.';
                    }//Fin si aucun corridor n'a été fournis	
                    
                    switch($CORRIDOR){
                            case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;    
                            case '13': 	$ProdName  .= " AND corridor = 13 "; 	$SauterValidationFH = "yes"; break;    
                            case '15': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
                    }//END SWITCH
                   
                break;

                // polarisé
                case 'PRECISION+ S 1.53 POLAR GREY HC': 

                    switch($EYE){	
                        case 'Both': 
                            if ($RE_ADD=='' || $LE_ADD==''){
                                if ($UnSvUnProg == false){	
                                    $InsererDansBD = false;	
                                    $ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
                                }
                            }	
                    }//END Switch
                    
                        //Partie commune
                        $ProdTable = "ifc_ca_exclusive"; 
                        $CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
                        $SauterValidationFH = "";
                        //Paramètres propre à ce produit seulement
                        $ProdName  = "  product_name like '%Precision+ S%' AND product_name like '%POLAR%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%Grey%' 
                        AND product_name not like '%360%' AND product_name not like '%420%'  "; 
                        $ORDER_PRODUCT_COATING	= "Hard Coat";
                        $ORDER_PRODUCT_POLAR	= "Grey";
                        $ORDER_PRODUCT_PHOTO	= "None";
                        if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
                            $InsererDansBD  = false;
                            $ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
                            The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
                        }//Fin si aucun corridor n'a été fournis	
                        
                        switch($CORRIDOR){
                                case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
                                case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
                                case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
                        }//END SWITCH
                    break;


                                    // polarisé
                case 'PRECISION+ S 1.53 POLAR BROWN HC': 

                    switch($EYE){	
                        case 'Both': 
                            if ($RE_ADD=='' || $LE_ADD==''){
                                if ($UnSvUnProg == false){	
                                    $InsererDansBD = false;	
                                    $ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
                                }
                            }	
                    }//END Switch
                    
                        //Partie commune
                        $ProdTable = "ifc_ca_exclusive"; 
                        $CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
                        $SauterValidationFH = "";
                        //Paramètres propre à ce produit seulement
                        $ProdName  = "  product_name like '%Precision+ S%' AND product_name like '%POLAR%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%BROWN%' 
                        AND product_name not like '%360%' AND product_name not like '%420%'  "; 
                        $ORDER_PRODUCT_COATING	= "Hard Coat";
                        $ORDER_PRODUCT_POLAR	= "Grey";
                        $ORDER_PRODUCT_PHOTO	= "None";
                        if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
                            $InsererDansBD  = false;
                            $ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
                            The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
                        }//Fin si aucun corridor n'a été fournis	
                        
                        switch($CORRIDOR){
                                case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
                                case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
                                case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
                        }//END SWITCH
                    break;
                

?>