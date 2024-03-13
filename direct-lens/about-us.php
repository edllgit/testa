<?php 
require_once(__DIR__.'/../constants/url.constant.php');
include('inc/header.php');
?>
     
<div id="content" class="page contact">
    <div id="content-text" class="full">  
     
     	<h2><?php echo ($mylang == "lang_french") ? "Qui sommes-nous ?": "About US"; ?></h2>    
        
		<?php  if ($mylang == 'lang_french') {  ?>
			<h3>Une Vision</h3>
            <p>DirectLab pense de façon globale mais   agit de façon locale. Sa vision; utiliser les dernières technologies et   principalement le 
            web 2.0 pour toutes ses activités, communications en   ligne (e-mail et facebook, twitter), les achats et transferts   automatiques, 
            la confirmation de commande, la vérification de délais et   la facturation.</p>
            <p>« Nous habitons sur une petite planète,    c’est pourquoi penser globalement nous permet d’offrir les bons   produits, aux bons prix » 
            dit Daniel Beaulieu, président et directeur   général du réseau. C’est pourquoi grâce aux outils développés par le   réseau, il est possible 
            de gérer des achats internationaux et des   livraisons locales facilement.</p>
            <p>La population canadienne vit aussi   actuellement un virement vert, le réseau suit cette tendance.   L’utilisation de l’internet 
            pour les affaires diminue l’utilisation du   papier, éliminant les télécopies (méthode de commande utilisée depuis   plus de 25 ans) 
            pour des factures électroniques et des courriels.</p>
            
            <h3>De l’Innovation</h3>
            <p>La plateforme <a href="<?php echo constant('DIRECT_LENS_URL'); ?>/">www.direct-lens.com</a> est un produit unique au Canada. Un site de commande 
            en ligne et de   gestion des commandes pour l’acheteur et le vendeur. Après 5 années de   conception et de test, elle est maintenant 
            utilisée par différents   laboratoires partenaires canadiens et américains et internationaux.</p>
            <p>De plus, Direct-Lab Management Software   est un système complémentaire à la plateforme web. Direct-Lab fût créé   pour établir 
            une relation de fabrication locale avec des équipements qui   doivent habituellement fonctionner avec un logiciel de gestion, qui est   
            coûteux et qui rend le laboratoire dépendant de ce fournisseur.   Direct-Lab est aussi complet que ses concurrents mais beaucoup plus   
            flexible, il peut ainsi gérer la machinerie et les équipements en   relation avec nos différentes plateformes de commande en ligne.</p>
            
            <h3>Une Solution</h3>
            <p>En plus d’offrir plusieurs collections   de différents produits reconnus mondialement, la gamme de produits   exclusive offerte 
            par DirectLab a fait l’objet de nombreuses analyses et   recherches. Celle-ci permet d’atteindre le niveau de qualité recherché   
            par le marché actuel, à des prix plus compétitifs que les   multinationales qui gardent une politique de prix monopolistique. Les   
            principaux fournisseurs de matières premières sont installés sur   plusieurs continents, soit en Asie, en Inde, en Europe ainsi qu’en 
            <strong>Amérique du Nord</strong>, offrant un large choix de produits qui conviennent aux besoins des professionnels de la vue, ainsi 
            une alternative de choix.</p>

        <?php  }else{ ?>
                <p>To come</p>
        <?php  } ?>            
            
    </div>                
</div> 

<?php include('inc/footer.php');?>
