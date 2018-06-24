<?php 
include("../inc/botstats.php");
include '../inc/geo/geoip.inc';
$gi = geoip_open("../inc/geo/GeoIP.dat", "");

$csel = $odb->query("SELECT country, COUNT(*) AS cnt FROM bots GROUP BY country ORDER BY cnt ");
$rows = array();
$rows2 = array();
 while ($c = $csel->fetch())
									{
                    $top2 = geoip_country_code_by_id($gi, $c[0]);
                    $top = number_format($c[1]);
									
                    $rows[] = $top2;
                    $rows2[] = $top;
                    
									
  }
$c = array_combine($rows, $rows2);
$final = json_encode($c);

header("Content-type: text/javascript"); 
var sample_data = "<?php echo $final;?>";
?>
