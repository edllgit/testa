<?php include('inc/header.php');?>
  
<?php /*
<div class="slider">
    <div id="coin-slider"> 
        <a href="#"><img 
        src="http://c.direct-lens.com/direct-lens/images-design/<?php echo ($mylang == "lang_french") ? "fr": "en"; ?>/image1.jpg"
        alt="Vision" /></a> 
        <a href="#"><img 
        src="http://www.direct-lens.com/direct-lens/images-design/<?php echo ($mylang == "lang_french") ? "fr": "en"; ?>/image2.jpg"
        alt="Innovation" /></a> 
        <a href="#"><img 
        src="http://www.direct-lens.com/direct-lens/images-design/<?php echo ($mylang == "lang_french") ? "fr": "en"; ?>/image3.jpg"
        alt="Solutions" /></a>  
    </div>
</div>
*/ ?>
<div class="clear"></div>  
<div id="middle-nav">
    <div class="box">
	<?php  if ($mylang == 'lang_french') {  ?>
            <h2>Prescription.</h2>
            <p>Remplissez le formulaire de prescription et choisissez un verre parmi les résultats. Ajouter à votre panier et recevez 
            votre commande directement à votre bureau.</p>
            <a href="connexion.php">COMMANDER PAR PRESCRIPTION </a>
    <?php  }else{ ?>
            <h2>Prescription</h2>
            <p>Fill in the prescription form and choose a lens from the available results. Add to cart and have it shipped 
            right to your office.</p>
            <a href="connexion.php">SHOP BY PRESCRIPTION</a>
    <?php  } ?>           
    </div>  
    <div class="box">
	<?php  if ($mylang == 'lang_french') {  ?>
            <h2>VERRES &amp; MONTURES</h2>
            <p>Nos ensembles donnent accès à des centaines de combinaisons de monture en PLUS de nos verres de haute qualité, et ce à un seul bas prix.</p>
            <a href="http://www.ifcclub.ca/" target="_blank">MAGASINER PAR ENSEMBLE (IFC)</a>
    <?php  }else{ ?>
            <h2>Frames &amp; Lens Packages</h2>
            <p>Our packages provide access to hundreds of frame combinations PLUS our high quality lenses at one low price.</p>
            <a href="http://www.ifcclub.ca/" target="_blank">SHOP BY PACKAGE WITH IFC CLUB CANADA</a>
    <?php  } ?>   
    </div> 
    <div class="box">
	<?php  if ($mylang == 'lang_french') {  ?>
            <h2>VERRES DE STOCK</h2>
            <p>Parcourez notre gamme de verres de stock et commandez-les directement de ce site.</p>
            <a href="connexion.php">MAGASINER PAR VERRES DE STOCK</a>
    <?php  }else{ ?>
            <h2>Stock Lenses</h2>
            <p>Browse our range of stock lenses and order them directly from this website.</p>
            <a href="connexion.php">SHOP STOCK LENSES</a>
    <?php  } ?>      
    </div>
    <div class="clear"></div>                         
</div>    
<div id="content">
    <div id="content-text">
	<?php  if ($mylang == 'lang_french') {  ?>
           <h2>Bienvenue</h2>
           
            <p>Ce site a été développé pour fournir aux professionnels de la vue la possibilité d'acheter directement en ligne et ainsi sauver 
            temps et argent. Direct-Lens porte une grande attention à vos besoins, notre but premier lorsque nous lançons de nouveaux produits 
            et services est de vous satisfaire et vous offrir une solution !</p> 
            <p>Notre objectif est de vous offrir fréquemment de nouvelles gammes de produits qui rencontreront vos exigences les plus élevées !</p>
             
            
    <?php  }else{ ?>
           
           <h2>Welcome</h2>
     
            
            <p>This optic marketplace has been developed to provide eye care professionals with the option of direct purchase ordering online. 
            Direct-Lens pays close attention to your requirements, as our goal is to satisfy you when launching new products and services.</p> 
            <p>We will frequently provide a new range of products - bought, designed and manufactured - that meet the highest of your expectations.</p>
            
            

            
    <?php  } ?>  
  
        
    </div> 
    <div id="side-bar">    
   	
  
          
        <h2><?php echo ($mylang == "lang_french") ? "Connexion Client": "Customer login"; ?></h2>       
        <?php  include('inc/connexion.php');?>  
        <?php //echo ($mylang == "lang_french") ? "Site web temporairement indisponible pour maintenance": "Website temporary down for maintenance"; ?>      
    </div>  
    <div class="clear"></div>                  
</div> 
<?php include('inc/footer.php');?>