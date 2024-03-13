// <![CDATA[
$(function(){
	// Slider
	$('#coin-slider').coinslider({width:980,height:236,opacity:1});	
});

// Codes pour le Translator - Changement de langue
function ChangeLang(mylang){
		var date = new Date();
		date.setTime(date.getTime()+(30*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = "mylang="+mylang+expires+"; path=/";
		window.location.reload();
		//window.location = "index.php";
		return false;
}

function checkfr(formname, nouveaucompte) {
  var errors = '';
  errors += checkSelect(formname, 'title', 'Titre');
  errors += checkText(formname, 'user_id', "Nom d'utilisateur");
  errors += checkText(formname, 'first_name', 'Pr\351nom');
  errors += checkText(formname, 'last_name', 'Nom de famille');
  errors += checkText(formname, 'bill_address1', 'Adresse de facturation');
  errors += checkText(formname, 'bill_city', 'Ville de facturation');
  errors += checkText(formname, 'bill_state', 'Etat/Province de facturation');
  errors += checkText(formname, 'bill_zip', 'Code postal de facturation');
  errors += checkText(formname, 'phone', 'T\351l\351phone');
  errors += checkText(formname, 'password', 'Mot de passe');
  errors += checkSelect(formname, 'bill_country', 'Pays de facturation');
  errors += checkSelect(formname, 'language', 'Langue');
  errors += checkSelect(formname, 'currency', 'Devise');
  errors += checkSelect(formname, 'business_type', "Type d'entreprise");
  errors += checkSelect(formname, 'purchase_unit', "Unit\351s d'achat");
  errors += checkText(formname, 'company', 'Compagnie');
  errors += checkText(formname, 'main_lab', 'Main Lab');
  errors += checkEmail(formname, 'email', 'Email');
  errors += checkText(formname, 'password_confirmation', 'Confirmation de mot de passe');
  errors += checkText(formname, 'captcha_code', 'Entrer le texte contenu dans l\'image');
  checkThisForm(formname, nouveaucompte, errors);
}
function checken(formname, newaccount) {
  var errors = '';
  errors += checkSelect(formname, 'title', 'Title');
  errors += checkText(formname, 'user_id', 'Username');
  errors += checkText(formname, 'first_name', 'First Name');
  errors += checkText(formname, 'last_name', 'Last Name');
  errors += checkText(formname, 'bill_address1', 'Billing Address 1');
  errors += checkText(formname, 'bill_city', 'Billing City');
  errors += checkText(formname, 'bill_state', 'Billing State/Province');
  errors += checkText(formname, 'bill_zip', 'Billing Zip/Postal Code');
  errors += checkText(formname, 'phone', 'Phone');
  errors += checkText(formname, 'password', 'Password');
  errors += checkSelect(formname, 'bill_country', 'Billing Country');
  errors += checkSelect(formname, 'language', 'Language');
  errors += checkSelect(formname, 'currency', 'Currency');
  errors += checkSelect(formname, 'business_type', 'Business Type');
  errors += checkSelect(formname, 'purchase_unit', 'Purchase Unit');
  errors += checkText(formname, 'company', 'Company');
  errors += checkText(formname, 'main_lab', 'Main Lab');
  errors += checkText(formname, 'password_confirmation', 'Confirm Password');
  errors += checkEmail(formname, 'email', 'Email');
  errors += checkText(formname, 'captcha_code', 'Type the text that is in the image');
  checkThisForm(formname, newaccount, errors);
}

function checkcontactfr(formname, formulairecontact) {
  var errors = '';
  errors += checkText(formname, 'contact_name', 'Nom');
  errors += checkEmail(formname, 'contact_email', 'Email');
  errors += checkText(formname, 'contact_message', 'Message');
  checkThisForm(formname, formulairecontact, errors);
}

function checkcontacten(formname, contactform) {
  var errors = '';
  errors += checkText(formname, 'contact_name', 'Name');
  errors += checkEmail(formname, 'contact_email', 'Email');
  errors += checkText(formname, 'contact_message', 'Message');
  checkThisForm(formname, contactform, errors);
}

function checkconnexionfr(formname, newaccount) {
  var errors = '';
  errors += checkText(formname, 'username', "Nom d'usager");
  errors += checkText(formname, 'password', 'Mot de passe');
  checkThisForm(formname, newaccount, errors);
}

function checkconnexionen(formname, newaccount) {
  var errors = '';
  errors += checkText(formname, 'username', "Username");
  errors += checkText(formname, 'password', 'Password');
  checkThisForm(formname, newaccount, errors);
}