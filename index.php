<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		
		<title>Travel Dudes</title>

		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/ju-1.11.4/jqc-1.11.3,dt-1.10.8/datatables.min.css"/>
 
		<script type="text/javascript" src="https://cdn.datatables.net/r/ju-1.11.4/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').dataTable({
				        "aaSorting": [[ 2, "desc" ]]
				});
			} );
		</script>
		<style>
		h1 { color: #336699; font-size: 20px; font-family: 'Signika', sans-serif; padding-bottom: 10px; }
                a {text-decoration: none; }
                p { color: #336699; margin-top: 0em; margin-bottom: 0em;}
		</style>
	</head>
	<body>

	<img src="logo.png">
	
		<div class="container">

<table id="example" class="display compact" cellspacing="0" width="100%" >
<thead><tr>
<th>Name</th>
<th>Flights</th>
<th>Distance Travelled (Km)</th>
<th>Carbon Footprint</th>
</tr></thead>
<tbody>

<?php
require ('simple_html_dom.php');
include('functions.php');
$combinedFlights=0;
$combinedDistance=0;
$rank=0;
$timeInAir=0;
$combinedCarbonFootprint=0;
## Insert your flightdiary username in the array below, separated with commas.
## i.e.
## $dudes = array("boogiespook",
## "dude2",
## "dude3",
## "dude4")

$dudes = array("boogiespook");
foreach ($dudes as $dude) {
$url = "http://flightdiary.net/$dude/2016";
$rank++;

$html = file_get_html($url);
foreach($html->find('h1') as $element) {
$name = ucwords($element->plaintext); 
       }

## Number of flights
$e = $html->find('ul', 0)->find('li', 0);
 $flights = $e->plaintext;
 $total = explode(' ',$flights);

 $combinedFlights = $combinedFlights + $total[2];

 
## Distance
$e = $html->find('ul', 0)->find('li', 1);
 $distance = $e->plaintext;
 $totalDistance = explode(' ',$distance);
 $numDistance = "$totalDistance[2]$totalDistance[3]";
$combinedDistance = $combinedDistance + $numDistance;

## Carbon Footprint
$e = $html->find('ul', 0)->find('li', 3);
$footprint = $e->plaintext;
$footprintParts = explode(' ',$footprint);
$dudeFootprint = $footprintParts[2];
$combinedCarbonFootprint = $combinedCarbonFootprint + $dudeFootprint;

 print "<tr>
    <td><a href='$url' target=_blank>$name</a></td><td>$total[2]</td><td>$totalDistance[2]$totalDistance[3]</td><td>$dudeFootprint</td></tr>";
    
## Duration
$e = $html->find('ul', 0)->find('li', 2);
 $duration = $e->plaintext;
$durationParts = explode(' ',$duration);
#print_r($durationParts);
$hrs = ltrim($durationParts[2],"0");
$mins = ltrim($durationParts[5],"0");
$dudeTimeInAir =  ($hrs * 60) + $mins;
$timeInAir = $timeInAir + ($dudeTimeInAir * 60);


}

?>

</tbody></table>
			
		</div>
<?php 
$formattedDTG = secondsToTime($timeInAir);

print "<p><b>Total Flights</b>: $combinedFlights</p>";
print "<p><b>Total Distance</b>: " . number_format($combinedDistance) . " Kms</p>";
print "<p><b>Total Time in the Air</b>: " . $formattedDTG['d'] . " Days, " . $formattedDTG['h'] . " Hours and " . $formattedDTG['m'] . " Minutes </p>";
print "<p><b>Total Carbon Footprint:</b> " . $combinedCarbonFootprint . " Tons CO2</p>";
$percentage = floor(($combinedDistance / 370300 ) * 100);
print "<p><b>Percentage of the way to the moon:</b> " . $percentage . "%</p>";
?>
	</body>
</html>


