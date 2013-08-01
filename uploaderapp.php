<?php
/**************************************************
Created by ToXiC (done)
Validated, checked and corrected by EboLLa (done)
***************************************************/

ob_start("ob_gzhandler");
require "../include/tracker.php";
require "../include/usertask.php";
require "../include/validator.php";

dbconn();
loggedinorreturn();

if (get_user_class() < UC_POWER_USER || $CURUSER['warned'] == 'yes')
    stderr("Error", "Only Power User and above with a good account standing can apply!");
	
$action = isset($_GET['action']) ? (string) $_GET['action'] : false;
if (!$action)
{
    $action = isset($_POST['action']) ? (string) $_POST['action'] : (get_user_class() <= UC_HELPER ? 'main':'view');
}

/****MAIN****/

if ($action == 'main') { //aplicatie

    $HTMLOUT = "";
	
	$HTMLOUT .= "<div class='cblock'>
	<div class='cblock-headerleft'></div>
	<div class='cblock-header'><h4>Uploader Application</h4>";

if (get_user_class() >= UC_MODERATOR) {//daca clasa >= moderator, afisam butonul pentru vizualizarea aplicatiilor. Si mods pot vedea dar nu pot da approve, deny, delete etc
	$HTMLOUT .= "<h4>| <a href='?action=view'>View</a></h4>";
}
	$HTMLOUT .= "</div>
	<div class='cblock-headerright'></div>
	<div class='clearfix'></div>
	<div class='cblock-content'>
	<div class='cblock-innercontent'>";
	
	if (get_user_class() <= UC_HELPER) { // doar <= UC_HELPER pot vedea si trimite uploader app 
  $existing=mysql_fetch_assoc(mysql_query("SELECT status from upapp where userid=".sqlesc($CURUSER['id'])." LIMIT 1"));
  if ($existing){
    $status=array("in asteptare", "aprobata", "respinsa", "necunoscut");
    stderr("Eroare", "Exista deja inregistrata o aplicatie cu statutul: ". $status[$existing['status']]);
  }
	$HTMLOUT .= "<form method='post' action='uploaderapp.php?action=send'>
	<input type='hidden' name='username' value='".$CURUSER['username']."'>
	<input type='hidden' name='iduser' value='".$CURUSER['id']."'>";
	
	/****FIELDS****/
	$HTMLOUT .= "
	<legend>Ganditi-va de 5 ori inainte de a trimite aplicatia pentru ca pot aparea surprize oarecum neplacute - warn/disable.</legend><br />	
	
	<fieldset>
    <legend><b>Experienta anterioara</b></legend>
	<label>
	    <input type='radio' name='q1' value='Da'>Da. Unde?
	</label>
	<label>
		<input size='60' name='q1text' type='text'>
	</label>
	<br />
	<label>
	    <input type='radio' name='q1' value='Nu'>Nu
    </label>	
	</fieldset>
	
	<br />
	<fieldset>
	<legend><b>Stiu cum se creeaza un torrent</b></legend>
	<label>
	    <input type='radio' name='q2' value='Da'>Da
	</label>	
	<label>
	    <input type='radio' name='q2' value='Nu'>Nu
	</label>
	</fieldset>

	<br />
	<fieldset>
	<legend><b>Sunt constinent de faptul ca trebuie sa stau la seed pana exista cel putin alti 10 seederi</b></legend>
    <label>
        <input type='radio' name='q3' value='Da'>Da
    </label>
    <label>
	    <input type='radio' name='q3' value='Nu'>Nu
	</label>
	</fieldset>

	<br />
	<fieldset>
	<legend><b>Momentan mai sunt uploader pe urmatoarele trackere:</b></legend>
    <label>
        <textarea name='q5' cols='60' rows='6'></textarea>
	</label>
	</fieldset>

	<br />
	<fieldset>
	<legend><b>Ce tip de materiale iti propui sa uploadezi ?</b></legend>
    <label>
        <textarea name='q6' cols='60' rows='6'></textarea>
	</label>
	</fieldset>

	<hr class='separator'>
	<br />
	<center><h3>Speedtest / Dovezi SeedBox</h3></center>
	<hr class='separator'>

	<br />
	<fieldset>
	<legend><b><a href='http://speedtest.net'>SpeedTest</a> Bucuresti</b></legend>
    <label>
        <input type='text' name='q7' size='60' />
	</label>
		<font size='1'>(Link direct catre imagine)</font>
	</fieldset>

	<br />
	<fieldset>
	<legend><b><a href='http://speedtest.net'>SpeedTest</a> Londra</b></legend>
    <label>
        <input type='text' name='q8' size='60' />
	</label>
		<font size='1'>(Link direct catre imagine)</font>
	</fieldset>

	<br />
	<fieldset>
	<legend><b>Dovezi SeedBox</b></legend>
    <label>
        <textarea name='q9' cols='60' rows='6'></textarea>
	</label>
	<br />
	<font size='1'>• Dovezi cat mai clare, precum planul seedbox-ului si durata acestuia dar si altele</font>
	</fieldset>

	<hr class='separator'>
	<br />
	<center><h3>Surse</h3></center>
	<hr class='separator'>

	<fieldset>
	<legend><b>Trackere</b></legend>
	<h2>Cel putin 4 dintre urmatoarele:</h2>

	<font size='1'>• <a href='http://www.sceneaccess.org'><font color='red'><b>SceneAccess.org</b></font></a></font><br>
	<font size='1'>• <a href='http://www.thegft.org'><font color='red'><b>TheGFT.org</b></font></a></font><br>
	<font size='1'>• <a href='https://feedthe.net'><font color='red'><b>FeedThe.net</b></font></a></font><br>
	<font size='1'>• <a href='http://torrentleech.org'><font color='red'><b>TorrentLeech.org</b></font></a></font><br>
	<font size='1'>• <a href='http://bitme.org'><font color='red'><b>BitMe.org</b></font></a></font><br>
	<font size='1'>• <a href='http://hdbits.org'><font color='red'><b>HDBits.org</b></font></a></font><br>
	<font size='1'>• <a href='http://chdbits.org'><font color='red'><b>CHDBits.org</b></font></a></font><br>
	<font size='1'>• <a href='http://sdbits.org'><font color='red'><b>SDBits.org</b></font></a></font><br>
	<font size='1'>• <a href='https://scenehd.org'><font color='red'><b>SceneHD.org</b></font></a></font><br>
	<font size='1'>• <a href='http://torrentbytes.net'><font color='red'><b>TorrentBytes.net</b></font></a></font><br>
	<font size='1'>• <a href='http://revolutiontt.net'><font color='red'><b>RevolutionTT.net</b></font></a></font><br>
	<font size='1'>• <a href='http://www.bitmetv.org/'><font color='red'><b>BitMeTV.ORG</b></font></a></font><br>
	<font size='1'>• <a href='http://broadcasthe.net'><font color='red'><b>BroadcasThe.Net</b></font></a></font><br>
	<font size='1'>• <a href='http://www.trancetraffic.com'><font color='red'><b>TranceTraffic.com</b></font></a></font><br>
	<font size='1'>• <a href='http://www.hdchina.org'><font color='red'><b>HDChina</b></font></a></font><br>
	<font size='1'>• <a href='http://www.waffeles.fm'><font color='red'><b>Waffles.fm</b></font></a></font><br>
	<font size='1'>• <a href='http://www.what.cd'><font color='red'><b>What.CD</b></font></a></font><br>
	<font size='1'>• <a href='http://passthepopcorn.me'><font color='red'><b>PTP</b></font></a></font><br>
	<br />
	<font size='1'>• Nu va deranjati sa enumerati trackerele romanesti !</font><br />
	<font size='1'>• Scrieti direct link-urile catre profil !</font><br />
	<label>
        <textarea name='q10' cols='60' rows='6'></textarea>
	</label>
	</fieldset>

	<br />
	<fieldset>
	<legend><b>Alte surse</b></legend>
    <label>
        <textarea name='q11' cols='60' rows='6'></textarea>
	</label>
	</br>
	<font size='1'>• Usenet, posibilitatea de capping etc</font>
	</fieldset>

	<hr class='separator'>
	<br />
	<center><h3>Ce intelegeti prin urmatorii termeni</h3></center>
	<hr class='separator'>

	<br />
	<fieldset>
	<legend><b>pretime</b></legend>
    <label>
	    <textarea name='q12' cols='40' rows='5'></textarea>
	</label>
	</fieldset>

	<br />
	<fieldset>
	<legend><b>scene</b></legend>
    <label>
	    <textarea name='q13' cols='40' rows='5'></textarea>
	</label>
	</fieldset>

	<br />
	<fieldset>
	<legend><b>non-scene</b></legend>
    <label>
	    <textarea name='q14' cols='40' rows='5'></textarea>
	</label>
	</fieldset>
	
	<br />
	<fieldset>
	<legend><b>0-day</b></legend>
    <label>
	    <textarea name='q15' cols='40' rows='5'></textarea>
	</label>
	</fieldset>

	<br />
	<fieldset>
	<legend><b>top-sites</b></legend>
    <label>
	    <textarea name='q16' cols='40' rows='5'></textarea>
	</label>
	</fieldset>

	<hr class='separator'>
	<br />
	<center><h3>Cateva cuvinte despre tine</h3></center>
	<hr class='separator'>

	<br />

	<fieldset>
	<legend><b>Motivul pentru care meriti sa fii promovat</b></legend>
        <textarea name='q17' cols='60' rows='6'></textarea>
	</fieldset>

	<br />
	<fieldset>
	<legend><b>De ce doresti statutul de uploader?</b></legend>
        <textarea name='q18' cols='60' rows='6'></textarea>
	</fieldset>

	<br />
	<fieldset>
	<legend><b>Cat timp vei dedica functiei de uploader?</b></legend>
        <textarea name='q19' cols='60' rows='6'></textarea>
	</fieldset>

	<br />
	<fieldset>
	<legend><b>Persoane/useri/staff-eri care te pot recomanda</b></legend>
        <textarea name='q20' cols='60' rows='6'></textarea>
	</fieldset>
	<br />

	<fieldset>
	<legend><b>Observatii</b></legend>
        <textarea name='q21' cols='60' rows='6'></textarea>
	</fieldset>	
	
	<div align='right' style='margin-top: 15px;'>
		<input type='submit' value='Trimite aplicatia!' class='lbtn'>
	</div>
	</form>";
	/****END FIELDS****/
	} else {
		$HTMLOUT .= "<center><b>Deja faci parte din staff, deci nu poti aplica pentru postul de uploader !</b></center>";
	}
	
	$HTMLOUT .= "</div></div>
	<div class='cblock-bottom'></div>
	</div>";

	print stdhead("Uploader Application");
	print $HTMLOUT;
	print stdfoot();
}
/****END MAIN****/


if ($action == 'send') {

	if (get_user_class() <= UC_HELPER) { // doar <= UC_HELPER pot vedea si trimite uploader app 
		$username = (string) $_POST['username'];
		if (!validusername($username)) {
			stderr('Invalid username !');
		}
		$userid = $_POST['iduser'];
		
		$q1 = $_POST['q1']." ".$_POST['q1text']; // unim si raspunsul inclus dupa "da, unde ?"
		$q2 = $_POST['q2'];
		$q3 = $_POST['q3'];
		$q5 = $_POST['q5'];
		$q6 = $_POST['q6'];
		$q7 = $_POST['q7'];
		$q8 = $_POST['q8'];
		$q9 = $_POST['q9'];
		$q10 = $_POST['q10'];
		$q11 = $_POST['q11'];
		$q12 = $_POST['q12'];
		$q13 = $_POST['q13'];
		$q14 = $_POST['q14'];
		$q15 = $_POST['q15'];
		$q16 = $_POST['q16'];
		$q17 = $_POST['q17'];
		$q18 = $_POST['q18'];
		$q19 = $_POST['q19'];
		$q20 = $_POST['q20'];
		$q21 = $_POST['q21'];
		
		if ($q1 == "") {
			stderr("Error !","Va rugam sa precizati daca aveti sau nu experienta!");
			exit();
		} elseif ($q2 == "") {
			stderr("Error !","Va rugam sa precizati daca stiti cum se creeaza un torrent!");
			exit();	
		} elseif ($q3 == "") {
			stderr("Error !","Va rugam sa precizati daca sunteti constient de faptul ca trebuie sa stati la seed pana exista cel putin alti 10 seederi!");
			exit();
		} elseif ($q6 == "") {
			stderr("Error !","Va rugam sa precizati ce tip de materiale doriti sa uploadati!");
			exit();
		} elseif ($q7 == "") {
			stderr("Error !","Va rugam sa completati speedtest-ul de pe Bucuresti!");
			exit();
		} elseif ($q8 == "") {
			stderr("Error !","Va rugam sa completati speedtest-ul de pe Londra!");
			exit();
		} elseif ($q10 == "") {
			stderr("Error !","Va rugam sa precizati trackerele externe pe care le detineti!");
			exit();
		} elseif ($q12 == "") {
			stderr("Error !","Va rugam sa precizati ce intelegeti prin pretime!");
			exit();
		} elseif ($q13 == "") {
			stderr("Error !","Va rugam sa precizati ce intelegeti prin scene!");
			exit();
		} elseif ($q14 == "") {
			stderr("Error !","Va rugam sa precizati ce intelegeti prin non-scene!");
			exit();
		} elseif ($q15 == "") {
			stderr("Error !","Va rugam sa precizati ce intelegeti prin 0-day!");
			exit();
		} elseif ($q16 == "") {
			stderr("Error !","Va rugam sa precizati ce intelegeti prin top-sites!");
			exit();
		} elseif ($q17 == "") {
			stderr("Error !","Va rugam sa precizati motivul pentru care considerati ca meritati sa fiti promovat!");
			exit();
		} elseif ($q18 == "") {
			stderr("Error !","Va rugam sa precizati motivul pentru care doriti statutul de uploader!");
			exit();
		} elseif ($q19 == "") {
			stderr("Error !","Va rugam sa precizati cat timp veti dedica functiei de uploader!");
			exit();
		} else {

		if($CURUSER['muted'] != 'yes') 
      mysql_query("INSERT INTO upapp (username, userid, q1, q2, q3, q5, q6, q7, q8, q9, q10, q11, q12, q13, q14, q15, q16, q17, q18, q19, q20, q21) VALUES(
  		".sqlesc($username).",
  		".sqlesc($userid).",	
  		".sqlesc($q1).", 
  		".sqlesc($q2).", 
  		".sqlesc($q3).", 
  		".sqlesc($q5).", 
  		".sqlesc($q6).", 
  		".sqlesc($q7).", 
  		".sqlesc($q8).",
  		".sqlesc($q9).",
  		".sqlesc($q10).",
  		".sqlesc($q11).",
  		".sqlesc($q12).",
  		".sqlesc($q13).",
  		".sqlesc($q14).",
  		".sqlesc($q15).",
  		".sqlesc($q16).",
  		".sqlesc($q17).",
  		".sqlesc($q18).",
  		".sqlesc($q19).",
  		".sqlesc($q20).",
  		".sqlesc($q21)."		
  		)") or sqlerr(__FILE__,__LINE__);
		
		if(mysql_affected_rows() != 0)
			stderr("Success!","Application submited! Return to <a href='index.php'>Home</a>.");
		else
			stderr("Error!","You already applied for uploader !");
		}
	}
}


if ($action == 'view') { //view all apps
    if (get_user_class() >= UC_MODERATOR) { // doars mods+ pot vedea apps
		$HTMLOUT = "";

		$HTMLOUT .= "<div class='cblock'>
		<div class='cblock-headerleft'></div><div class='cblock-header'><h4>View applications | <a href='?action=main'>Submit</a> | <a href='?action=del1'>Delete Approved</a> | <a href='?action=del2'>Delete Denied</a></h4></div><div class='cblock-headerright'></div>
		<div class='clearfix'></div>
		<div class='cblock-content'>
		<div class='cblock-innercontent'>";
		
		$res = mysql_query("SELECT * FROM upapp ORDER BY status ASC, date DESC" ) or sqlerr(__FILE__,__LINE__);
		
		if (mysql_num_rows($res) > 0) { // avem aplicatii
			$HTMLOUT .= "<table width='90%' align='center'><tr>
			<td align='left' class='colhead' style='width:20%;'>Username</td>
			<td align='left' class='colhead' style='width:5%;'>Status</td>
			<td align='center' class='colhead' style='width:2%;'>View</td>";
			
			if (get_user_class() >= UC_ADMINISTRATOR) { // admin only
			$HTMLOUT .= "
			<td align='center' class='colhead' style='width:2%;'>Approve</td>
			<td align='center' class='colhead' style='width:2%;'>Deny</td>";
			}
			
			$HTMLOUT .= "<td align='center' class='colhead' style='width:15%;'>Added</td>";
			
			if (get_user_class() >= UC_ADMINISTRATOR) { // admin only
			$HTMLOUT .= "<td align='center' class='colhead' style='width:2%;'>Delete</td></tr>";
			}
			
			while($arr = mysql_fetch_assoc($res)) {
				if ($arr['status'] == 0) // Waiting
					$status = "<b><font color ='#7082c0'>Waiting</font></b>";
				elseif ($arr['status'] == 1) // Approved
					$status = "<b><font color ='#5bac26'>Approved</font></b>";
				elseif ($arr['status'] == 2) // Denied
					$status = "<b><font color ='#800000'>Denied</font></b>";
					
				$HTMLOUT .= "<tr>
				<td align='left'><a href='./userdetails.php?id=".$arr['userid']."'>".$arr['username']."</a></td>
				<td align='left'>".$status."</td>
				<td align='center'><a href='?action=app&id={$arr['id']}'><img src='styles/images/bullet_list.png'></a></td>";
				
				if (get_user_class() >= UC_ADMINISTRATOR) { // admin only
				$HTMLOUT .= "
				<td align='center'><a href='?action=approve&id={$arr['id']}'><img src='styles/images/approved.png'></a></td>
				<td align='center'><a href='?action=deny&id={$arr['id']}'><img src='styles/images/alert.png' title='Deny' /></a></td>";
				}
				
				$HTMLOUT .= "<td align='center'>".$arr['date']."</td>";
				
				if (get_user_class() >= UC_ADMINISTRATOR) { // admin only
				$HTMLOUT .= "<td align='center'><a href='?action=delete&id={$arr['id']}'><img src='styles/images/panel_off.gif'></a></td>";
				}
				
				$HTMLOUT .= "</tr>";
			}	
			$HTMLOUT .= "</table>";	
		} else {
			$HTMLOUT .= "<center><b>Momentan nu exista aplicatii !</b></center>";
		}
		
		$HTMLOUT .= "</div></div>
		<div class='cblock-bottom'></div>
		</div>";
		
		print stdhead("View applications");
		print $HTMLOUT;
		print stdfoot();
	}
}

if ($action == 'del1') { //delete approved
if (get_user_class() >= UC_ADMINSTRATOR) { //only admin+ can delete the apps
    $ry = mysql_query("SELECT * FROM upapp WHERE status='1'") or sqlerr(__FILE__,__LINE__);
	if (mysql_num_rows($ry) == 0)
    stderr("Eroare!","Nu exista aplicatii aprobate!");
	
	$as = mysql_fetch_assoc($ry);
	
        $HTMLOUT .= "<div class='cblock'>
		<div class='cblock-headerleft'></div><div class='cblock-header'><h4>Delete approved applications</h4></div><div class='cblock-headerright'></div>
		<div class='clearfix'></div>
		<div class='cblock-content'>
		<div class='cblock-innercontent'>";
		
		$HTMLOUT .= "<div>Are you sure you want to delete all approved application?</div>
		<form method='post' action='uploaderapp.php'>
		<input type='hidden' name='action' value='del1'>
		<br />
		<input type='submit' class='lbtn' value='Delete 'em!'>
		</form>";
		
		$HTMLOUT .= "</div></div>
		<div class='cblock-bottom'></div>
		</div>";

		print stdhead("Uploader Application");
		print $HTMLOUT;
		print stdfoot();
	} else { // delete the applications	

		mysql_query("DELETE FROM upapp WHERE status = 1") or sqlerr(__FILE__,__LINE__);
		
		if(mysql_affected_rows() != 0)
			stderr("Success!","Approved applications successfully deleted! Return to <a href='uploaderapp.php?action=view'>applications</a>.");
		else
			stderr("Error!","Could not delete applications!");
}	
}	


if ($action == 'del2') { //delete denied
if (get_user_class() >= UC_ADMINSTRATOR) { //only admin+ can delete the apps
    $ry = mysql_query("SELECT * FROM upapp WHERE status = 2") or sqlerr(__FILE__,__LINE__);
	if (mysql_num_rows($ry) == 0)
    stderr("Eroare!","Nu exista aplicatii aprobate!");
	
	$as = mysql_fetch_assoc($ry);
	
        $HTMLOUT .= "<div class='cblock'>
		<div class='cblock-headerleft'></div><div class='cblock-header'><h4>Delete denied applications</h4></div><div class='cblock-headerright'></div>
		<div class='clearfix'></div>
		<div class='cblock-content'>
		<div class='cblock-innercontent'>";
		
		$HTMLOUT .= "<div>Are you sure you want to delete all denied application?</div>
		<form method='post' action='uploaderapp.php'>
		<input type='hidden' name='action' value='del2'>
		<br />
		<input type='submit' class='lbtn' value='Delete 'em!'>
		</form>";
		
		$HTMLOUT .= "</div></div>
		<div class='cblock-bottom'></div>
		</div>";

		print stdhead("Uploader Application");
		print $HTMLOUT;
		print stdfoot();
	} else { // delete the applications	

		mysql_query("DELETE FROM upapp WHERE status = 2") or sqlerr(__FILE__,__LINE__);
		
		if(mysql_affected_rows() != 0)
			stderr("Success!","Denied applications successfully deleted! Return to <a href='uploaderapp.php?action=view'>applications</a>.");
		else
			stderr("Error!","Could not delete applications!");
}	
}	


if ($action == 'app') { //view individual
if (get_user_class() >= UC_MODERATOR) { // doars mods+ pot vedea apps
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		$id = 0 + (int) $_GET['id'];
		
		$rz = mysql_query("SELECT * FROM upapp WHERE id='{$id}'") or sqlerr(__FILE__,__LINE__);
		if (mysql_num_rows($rz) == 0)
			stderr("Iar bei?","Te-ai imbatat si bagi ID-uri din burta ?"); // ToXiC a scis asta... :)
		
		$ar = mysql_fetch_assoc($rz);
    $q7=htmlsafechars($ar['q7']); 
    $q8=htmlsafechars($ar['q8']); 
		$HTMLOUT = "";

		$HTMLOUT .= "<div class='cblock'>
		<div class='cblock-headerleft'></div><div class='cblock-header'><h4>Application: <a href='./userdetails.php?id={$ar['username']}'>{$ar['username']}</a></h4></div><div class='cblock-headerright'></div>
		<div class='clearfix'></div>
		<div class='cblock-content'>
		<div class='cblock-innercontent'>";
		 
		$HTMLOUT .= "<fieldset>
		<legend><b>Experienta</b></legend>
			".format_comment($ar['q1'])."
		</fieldset>

		<br />
		<fieldset>
		<legend><b>Stiu cum se creeaza un torrent</b></legend>
			".format_comment($ar['q2'])."
		</fieldset>

		<br />
		<fieldset>
		<legend><b>Sunt constinent de faptul ca trebuie sa stau la seed pana exista cel putin alti 10 seederi</b></legend>
			".format_comment($ar['q3'])."
		</fieldset>

		<br />
		<fieldset>
		<legend><b>Momentan mai sunt uploader pe urmatoarele trackere:</b></legend>
			".format_comment($ar['q5'])."
		</fieldset>

		<br />
		<fieldset>
		<legend><b>Ce tip de materiale iti propui sa uploadezi ?</b></legend>
			".format_comment($ar['q6'])."
		</fieldset>

		<hr class='separator'>
		<br />
		<center><h3>Speedtest / Dovezi SeedBox</h3></center>
		<hr class='separator'>

		<br />
		<fieldset> 
		<legend><b>SpeedTest Bucuresti</b></legend>
			<img src='$q7'><br />
			(".format_comment("[url]".$ar['q7']."[/url]").") <!-- posibil sa greseasca link-urile asa ca afisam si exact ce a scris in casuta respectiva -->
		</fieldset>

		<br />
		<fieldset>
		<legend><b>SpeedTest Londra</b></legend>
			<img src='$q8'><br />
			(".format_comment("[url]".$ar['q8']."[/url]").") <!-- posibil sa greseasca link-urile asa ca afisam si exact ce a scris in casuta respectiva -->
		</fieldset>

		<br />
		<fieldset>
		<legend><b>Dovezi SeedBox</b></legend>
			".format_comment($ar['q9'])."
		</fieldset>

		<hr class='separator'>
		<br />
		<center><h3>Surse</h3></center>
		<hr class='separator'>

		<fieldset>
		<legend><b>Trackere</b></legend>
			".format_comment($ar['q10'])."
		</fieldset>

		<br />
		<fieldset>
		<legend><b>Alte surse</b></legend>
			".format_comment($ar['q11'])."
		</fieldset>

		<hr class='separator'>
		<br />
		<center><h3>Ce intelegeti prin urmatorii termeni</h3></center>
		<hr class='separator'>

		<br />
		<fieldset>
		<legend><b>pretime</b></legend>
			".format_comment($ar['q12'])."
		</fieldset>

		<br />
		<fieldset>
		<legend><b>scene</b></legend>
			".format_comment($ar['q13'])."
		</fieldset>


		<br />
		<fieldset>
		<legend><b>non-scene</b></legend>
			".format_comment($ar['q14'])."
		</fieldset>

		<br />
		<fieldset>
		<legend><b>0-day</b></legend>
			".format_comment($ar['q15'])."
		</fieldset>

		<br />
		<fieldset>
		<legend><b>top-sites</b></legend>
			".format_comment($ar['q16'])."
		</fieldset>

		<hr class='separator'>
		<br />
		<center><h3>Cateva cuvinte despre tine</h3></center>
		<hr class='separator'>

		<br />
		<fieldset>
		<legend><b>Motivul pentru care meriti sa fii promovat</b></legend>
			".format_comment($ar['q17'])."
		</fieldset>

		<br />
		<fieldset>
		<legend><b>De ce doresti statutul de uploader?</b></legend>
			".format_comment($ar['q18'])."
		</fieldset>

		<br />
		<fieldset>
		<legend><b>Cat timp vei dedica functiei de uploader?</b></legend>
			".format_comment($ar['q19'])."
		</fieldset>

		<br />
		<fieldset>
		<legend><b>Persoane/useri/staff-eri care te pot recomanda</b></legend>
			".format_comment($ar['q20'])."
		</fieldset>
		
		<br />
		<fieldset>
		<legend><b>Observatii</b></legend>
			".format_comment($ar['q21'])."
		</fieldset>";

		if (get_user_class() >= UC_ADMINISTRATOR) { // admin only
		$HTMLOUT .= "<br /><hr class='separator'><br />
		<center><b><a href='?action=approve&id={$id}'>Approve</a> | <a href='?action=deny&id={$id}'>Deny</a> | <a href='?action=delete&id={$id}'>Delete</a></b></center>";
		}
		
		$HTMLOUT .= "</div></div>
		<div class='cblock-bottom'></div>
		</div>";
		
		print stdhead("Application: ".$ar['username']."");
		print $HTMLOUT;
		print stdfoot();
	}
}
}

if ($action == 'delete') { //delete application

	if (get_user_class() < UC_ADMINISTRATOR)
	stderr("Error", "Permission denied.");

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		$id = 0 + (int) $_GET['id'];
		
		$ry = mysql_query("SELECT * FROM upapp WHERE id='{$id}'") or sqlerr(__FILE__,__LINE__);
		if (mysql_num_rows($ry) == 0)
		stderr("Eroare!","Nu exista aceasta aplicatie!");
		
		$as = mysql_fetch_assoc($ry);
		
		$HTMLOUT .= "<div class='cblock'>
		<div class='cblock-headerleft'></div><div class='cblock-header'><h4>Delete application</h4></div><div class='cblock-headerright'></div>
		<div class='clearfix'></div>
		<div class='cblock-content'>
		<div class='cblock-innercontent'>";
		
		$HTMLOUT .= "<div>Are you sure you want to delete ".htmlsafechars($as['username'])."'s application?</div>
		<form method='post' action='uploaderapp.php'>
		<input type='hidden' name='action' value='delete'>
		<input type='hidden' name='id' value='".$id."'>
		<br />
		<input type='submit' class='lbtn' value='Delete Application!'>
		</form>";
		
		$HTMLOUT .= "</div></div>
		<div class='cblock-bottom'></div>
		</div>";

		print stdhead("Uploader Application");
		print $HTMLOUT;
		print stdfoot();
	} else { // delete the application	
		$id = sqlesc($_POST['id']);

		mysql_query("DELETE FROM upapp WHERE id = ".$id." LIMIT 1") or sqlerr(__FILE__,__LINE__);
		
		if(mysql_affected_rows() != 0)
			stderr("Success!","Application succefully deleted! Return to <a href='uploaderapp.php?action=view'>applications</a>.");
		else
			stderr("Error!","Could not delete application!");
	}
}

if ($action == 'deny') { //deny app

	if (get_user_class() < UC_ADMINISTRATOR)
	stderr("Error", "Permission denied.");

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		$id = 0 + (int) $_GET['id'];
		
		$ry = mysql_query("SELECT * FROM upapp WHERE id='{$id}'") or sqlerr(__FILE__,__LINE__);
		if (mysql_num_rows($ry) == 0)
		stderr("Eroare!","Nu exista aceasta aplicatie!");
		
		$as = mysql_fetch_assoc($ry);
		
		$HTMLOUT .= "<div class='cblock'>
		<div class='cblock-headerleft'></div><div class='cblock-header'><h4>Deny application</h4></div><div class='cblock-headerright'></div>
		<div class='clearfix'></div>
		<div class='cblock-content'>
		<div class='cblock-innercontent'>";
		
		$HTMLOUT .= "<div>Are you sure you want to deny ".htmlsafechars($as['username'])."'s application?</div>
		<form method='post' action='uploaderapp.php'>
		<input type='hidden' name='action' value='deny'>
		<input type='hidden' name='id' value='".$id."'>
		<input type='hidden' name='userid' value='".htmlsafechars($as['userid'])."'>
		<br />
		<input type='submit' class='btn' value='Deny!'>
		</form>";
		
		$HTMLOUT .= "</div></div>
		<div class='cblock-bottom'></div>
		</div>";

		print stdhead("Uploader Application");
		print $HTMLOUT;
		print stdfoot();
	} else {
		$id = sqlesc($_POST['id']);
		$userid = sqlesc($_POST['userid']);
		
		mysql_query("UPDATE upapp SET status='2' WHERE id = ".$id." LIMIT 1") or sqlerr(__FILE__,__LINE__);

		$added = sqlesc(get_date_time());		
		$msg = sqlesc("[[b][color=red]RO[/b][/color]]
		[b]Salut,[/b]
		Aplicatia ta pentru rank-ul de uploader a fost refuzata. Poti sa incerci din nou data viitoare!
		
		Iti multumim ca ai aplicat !
		
		[[b][color=red]EN[/b][/color]]
		[b]Hello,[/b]
		Your uploader application was denied. You may try again next time!
		
		Thank you for applying !");
		$subject = sqlesc("Aplicatie refuzata");
		
		mysql_query("INSERT INTO messages (sender, receiver, msg, added, subject) VALUES (0, $userid, $msg, $added, $subject)") or sqlerr(__FILE__, __LINE__);

		if(mysql_affected_rows() != 0)
			stderr("Success!","Application denied! Return to <a href='uploaderapp.php?action=view'>applications</a>.");
		else
			stderr("Error!","Could not deny application!");	
	}
}

if ($action == 'approve') { //approve app
	
	if (get_user_class() < UC_ADMINISTRATOR)
	stderr("Error", "Permission denied.");

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		$id = 0 + (int) $_GET['id'];
		
		$ry = mysql_query("SELECT * FROM upapp WHERE id='{$id}'") or sqlerr(__FILE__,__LINE__);
		if (mysql_num_rows($ry) == 0)
		stderr("Eroare!","Nu exista aceasta aplicatie!");
		
		$as = mysql_fetch_assoc($ry);
		
		$HTMLOUT .= "<div class='cblock'>
		<div class='cblock-headerleft'></div><div class='cblock-header'><h4>Approve application</h4></div><div class='cblock-headerright'></div>
		<div class='clearfix'></div>
		<div class='cblock-content'>
		<div class='cblock-innercontent'>";
		
		$HTMLOUT .= "<div>Are you sure you want to approve ".htmlsafechars($as['username'])."'s application?</div>
		<form method='post' action='uploaderapp.php'>
		<input type='hidden' name='action' value='approve'>
		<input type='hidden' name='id' value='".$id."'>
		<input type='hidden' name='username' value='".htmlsafechars($as['username'])."'>
		<input type='hidden' name='userid' value='".htmlsafechars($as['userid'])."'>
    ".validatorForm("approve_uploader_application_$id")."
		<br />
		<input type='submit' class='btn' value='Approve!'>
		</form>";
		
		$HTMLOUT .= "</div></div>
		<div class='cblock-bottom'></div>
		</div>";

		print stdhead("Uploader Application");
		print $HTMLOUT;
		print stdfoot();
	} else {
		//promote
		$id = sqlesc($_POST['id']);
		$userid = sqlesc($_POST['userid']);
		$username = $_POST['username'];
    validate($_POST['validator'], "approve_uploader_application_".$_POST['id']) or die();
		
		mysql_query("UPDATE upapp SET status='1' WHERE id = ".$id." LIMIT 1") or sqlerr(__FILE__,__LINE__);
		
		$added = sqlesc(get_date_time());
		$msg = sqlesc("[[b][color=red]RO[/b][/color]]
		[b]Salut,[/b]
		Aplicatia ta pentru rank-ul de uploader a fost acceptata.
		
		Te rugam sa citesti [url=http://filelist.ro/uploader-rules.php]regulile de upload[/url] si sa vizitezi [url=http://filelist.ro/forums.php?action=viewforum&forumid=4]sectiunea uploaderilor[/url].
		
		[b][color=red]Ai fost promovat la [i]New Uploader[/i][/b] ![/color]
		
		[[b][color=red]EN[/b][/color]]
		[b]Hello,[/b]
		Your application was accepted! Please read the [url=http://filelist.ro/uploader-rules.php]upload rules[/url] and visit the [url=http://filelist.ro/forums.php?action=viewforum&forumid=4]uploaders zone[/url].
		
		[b][color=red]You have been promoted to [i]New Uploader[/i][/b] ![/color]");
		$subject = sqlesc("Aplicatie acceptata");
		$modcomment = sqlesc(gmdate("Y-m-d") . " - Promoted to New Uploader by ".$CURUSER['username']." (Uploader Application).\n");
		
		mysql_query("INSERT INTO messages (sender, receiver, msg, added, subject) VALUES (0, $userid, $msg, $added, $subject)") or sqlerr(__FILE__, __LINE__);
		
		mysql_query ("UPDATE users SET class='".UC_UPLOADER2."', modcomment = CONCAT(".$modcomment.", modcomment) WHERE id=".$userid."") or sqlerr(__FILE__, __LINE__); // promote directly to new uploader, mai are de mancat pana la essential
		
		if(mysql_affected_rows() != 0)
			stderr("Success!","".$username."'s application has been accepted! Return to <a href='uploaderapp.php?action=view'>Applications</a>.");
		else
			stderr("Error!","Could not promote to uploader!");
	}
}
?>