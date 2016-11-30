<?php
	/**
	 * index.php
	 * 
	 * Web front-end for the gpio utility at drogon.net
	 * 
	 * @author  Steve Eidemiller (original version by Travis Brown)
	 * @version 0.0.1
	 * @see     https://github.com/WarriorRocker/wiringpi-web-utility
	 */

	//Change pin modes and values as requested
	if (isset($_GET['c']) && isset($_GET['p']) && isset($_GET['v']))
	{
		if ($_GET['c'] == 'pm')
		{
			exec('gpio mode ' . $_GET['p'] . ' ' . $_GET['v']);
		}
		if ($_GET['c'] == 'dw')
		{
			exec('gpio write ' . $_GET['p'] . ' ' . $_GET['v']);
		}
	}
?>
<!doctype html>
<html>
<head>
	<title>GPIO Web Utility</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<style type="text/css">
		html, body { background-color: #EEE; }
		
		h1 { font-size: 24px; }
		a { color: #000; }
		
		table {
			border-collapse: collapse;
			border-spacing: 0;
			font-size: 16px;
		}
		
		table thead tr {
			background: #a8a8a8; /* Old browsers */
			background: -moz-linear-gradient(top,  #dddddd 0%, #a8a8a8 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#dddddd), color-stop(100%,#a8a8a8)); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,  #dddddd 0%,#a8a8a8 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,  #dddddd 0%,#a8a8a8 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top,  #dddddd 0%,#a8a8a8 100%); /* IE10+ */
			background: linear-gradient(to bottom,  #dddddd 0%,#a8a8a8 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#dddddd', endColorstr='#a8a8a8',GradientType=0 ); /* IE6-9 */
		}
	
		tr.odd { background-color: #D5D5D5; }
		tr td { padding: 7px; }

		td.center { text-align: center; }
		td.physical { background-color: #a8a8a8 }
		
		tr td.green { background-color: #51FF51; }
		tr.odd td.green { background-color: #3FE93F; }
		tr td.red { background-color: #FD7878; }
		tr.odd td.red { background-color: #FF5A5A; }
		tr td.blue { background-color: #A5A5FF; }
		tr.odd td.blue { background-color: #8686FF; }
		tr td.orange { background-color: #FDCF70; }
		tr.odd td.orange { background-color: #FFC144; }
	</style>
</head>
<body>
	<h1>WiringPi GPIO Web Utility</h1>
	<?php
		//Determine GPIO availability and version info
		exec('gpio -v', $version);
		if (count($version) == 0)
		{
			exit('GPIO is not installed');
		}
		$gpioVersion = '';
		$piVersion = '';
		for ($i = 0; $i < count($version); $i++)
		{
			if (stripos($version[$i], 'version: ')) $gpioVersion = $version[$i];
			if (stripos($version[$i], 'Type: ')) $piVersion = $version[$i];
		}
		echo "<h4>$gpioVersion, $piVersion, PHP ".phpversion()."</h4>";
	?>
	<table>
		<thead>
			<tr>
				<td>BCM</td>
				<td>wPi</td>
				<td>Name</td>
				<td>Mode</td>
				<td>Value</td>
				<td>Physical</td>
				<td>Physical</td>
				<td>Value</td>
				<td>Mode</td>
				<td>Name</td>
				<td>wPi</td>
				<td>BCM</td>
			</tr>
		</thead>
		<tbody>
		<?php

			//Read all pin modes and values to display a web-based table showing that information
			exec('gpio readall', $readall);
			$even = false;
			for ($i = 3; $i < (count($readall) - 3); $i++)
			{
				$row = explode('|', $readall[$i]);

				//Start TR
				echo '<tr class="'.(($even) ? 'even' : 'odd').'">';

				//Left side
				$pin = trim($row[2]);
				$mode = trim($row[4]);
				$value = trim($row[5]);
				echo   '<td class="center">'.trim($row[1]).'</td>';
				echo   '<td class="center">'.trim($row[2]).'</td>';
				echo   '<td>'.trim($row[3]).'</td>';
				if (strlen($pin.$value) && ($mode == 'IN' || $mode == 'OUT'))
				{
					echo   '<td class="center '.($mode == 'IN' ? 'orange' : 'blue').'"><a href="?c=pm&p='.$pin.'&v='.($mode == 'IN' ? 'out' : 'in').'">'.$mode.'</a></td>';
					echo   '<td class="center '.($value == '1' ? 'green' : 'red').'"><a href="?c=dw&p='.$pin.'&v='.($value == '1' ? '0' : '1').'">'.$value.'</a></td>';
				}
				else
				{
					echo   '<td class="center">'.$mode.'</td>';
					echo   '<td class="center">'.$value.'</td>';
				}
				echo   '<td class="center physical">'.trim($row[6]).'</td>';

				//Right side
				$pin = trim($row[12]);
				$mode = trim($row[10]);
				$value = trim($row[9]);
				echo   '<td class="center physical">'.trim($row[8]).'</td>';
				if (strlen($pin.$value) && ($mode == 'IN' || $mode == 'OUT'))
				{
					echo   '<td class="center '.($value == '1' ? 'green' : 'red').'"><a href="?c=dw&p='.$pin.'&v='.($value == '1' ? '0' : '1').'">'.$value.'</a></td>';
					echo   '<td class="center '.($mode == 'IN' ? 'orange' : 'blue').'"><a href="?c=pm&p='.$pin.'&v='.($mode == 'IN' ? 'out' : 'in').'">'.$mode.'</a></td>';
				}
				else
				{
					echo   '<td class="center">'.$value.'</td>';
					echo   '<td class="center">'.$mode.'</td>';
				}
				echo   '<td>'.trim($row[11]).'</td>';
				echo   '<td class="center">'.trim($row[12]).'</td>';
				echo   '<td class="center">'.trim($row[13]).'</td>';
			
				//Close TR
				echo '</tr>';
				$even = !$even;
			}
		?>
		</tbody>
	</table>
</body>
</html>
