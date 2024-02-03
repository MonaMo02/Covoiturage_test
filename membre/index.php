<?php
$title="Accueil";          //ceci est la page index du membre, lorsque un membre connecté va sur la page index du site il est directement redirigé ici.

require '../config/BDD.php';
$bdd=  getBdd();
session_start(); //on demarre la session
if (isset($_SESSION['login'])) { //on verifie que l'utilisateur est bien connecté
ob_start();
?>


<section class="sec-1">
	<div class="container">
		<div class="col6" style="width:600px;margin-top:-30px">
            <h1 style="position:absolute; font-weight: 600; font-size: 40px;color: #14525c;" >Bienvenue <?php echo ucfirst(strtolower($_SESSION['prenom']));?></h1>
			<br><h4 class="heading" style="margin-top:160px; "> Découvrez le confort <br>du covoiturage avec <br> <span>CarFetch <span></h4><br>
			<p>Économisez de l'Argent <br> et Réduisez Votre Impact Écologique</p>
			<br>
			<button class="btn-r"><a style="color:white;" href="../trajet/recherche_trajet.php">Cherchez votre trajet maintenant !</a> </button>
		</div>
		<div class="col6" style="margin-left:-220px ; margin-right:180px">
			<img src="../templates/image/carFetch.png" class="taxi-img">
		</div>
	</div>
</section>

<hr>
<section class="sec-2">
	<div class="container">
                <br>
		<h3 class="heading-2">Ce Que Nous Offrons</h3>
		<br>
		<br>
		<div class="col3">
			<div class="box">
                <i class="fa fa-lightbulb-o icon-1"></i>
				<h3>Simplicité</h3>
				<p>Une plateforme conçue pour une expérience fluide, de l'inscription à la recherche de trajets. </p>
			</div>
		</div>
		<div class="col3">
			<div class="box">
                <i class="fa fa-shield icon-1"></i>
				<h3>Confiance</h3>
				<p>La sécurité de nos membres est primordiale, avec des mesures rigoureuses pour garantir la fiabilité et la confiance. </p>
			</div>
		</div>
		<div class="col3">
			<div class="box">
                <i class="fa fa-money icon-1"></i>
				<h3>Économies</h3>
				<p>Covoiturez avec CarFetch pour des trajets plus abordables et écologiques.</p>
			</div>
		</div>
		<div class="col3">
			<div class="box">
				<i class="fa fa-users icon-1"></i>
					<h3>Communauté</h3>
					<p>Rejoignez une communauté dynamique de covoitureurs partageant les mêmes valeurs et contribuez à créer des liens durables.</p>
				</div>
			</div>
	</div>
</section>	

<hr>
<section class="sec-3">
	<br>
	<div class="container">
		<h2 class="heading-3">About us</h2>	
		<br>
		<div class="col6">
			<p class="p3">
				Nous croyons en la puissance du covoiturage pour transformer nos déplacements quotidiens.
				Fondée avec une passion pour l'efficacité, l'économie collaborative et la réduction de l'empreinte carbone,
				notre plateforme vise à connecter les personnes partageant les mêmes trajets, rendant le covoiturage accessible à tous.<br>
				<b>Notre Vision :</b> <br>
				Créer une communauté de covoitureurs engagée, réduisant la congestion routière,
				les émissions de carbone et renforçant les liens entre les membres.
				Nous aspirons à faciliter des voyages durables, économiques et conviviaux pour tous.<br>
				<b>Notre Équipe :</b> <br>
				Une équipe passionnée dédiée à faire du covoiturage une expérience exceptionnelle.
				Chacun de nous croit que chaque trajet partagé fait une différence.
				Rejoignez-nous dans notre mission pour rendre le covoiturage accessible,
					convivial et bénéfique pour tous. Ensemble, créons un avenir où chaque trajet compte.<br>
				<b>Bienvenue à bord !</b><br><br>
				<b>L'équipe FeTech.</b>
			</p>
			<br>
			
		</div>
		<div class="col6">
			<img src="../templates/image/5421477.jpg" style="width: 100%; margin-top: -100px;margin-left:80px">
		</div>
		<div class="clearfix"></div>
	</div>
</section>


<hr>
<section class="end">
        
        <div class="end-nav">
			<button><a href="./profil.php">Profil</a></button>
			<button><a href="./mon_compte.php">Mes comptes</a></button>
			<button><a href="./mes_messages.php">Messagerie</a></button>
			<button><a href="./envoyer_message.php">Envoyer Un message</a></button>
			<button><a href="../trajet/mes_trajets.php">Mes trajet</a></button>
			<button><a href="../trajet/recherche_trajet.php">Recherche trajet</a></button>
			<button><a href="../inscription/inscription_voiture.php">Enregiste voitre</a></button>           
        </div>
        <hr>
        <h3>Created By <span>FeTech Group</span> | All Rights Reserved</h3>
</section>

<?php $contenu = ob_get_clean();
if($_SESSION["pilote"]==TRUE){
require '../templates/pages/gabarit_pilote.php';  //on choisit le gabarit on fonction si c'est un pilote ou passager
}
else{
require '../templates/pages/gabarit_passager.php';  
}

}
else{
        header ('Location: ../index.php'); //si ce l'utilisateur n'est pas connecté on le redirige vers l'index du site
        exit();
}?>  

                

