
<?php

    # Define all your miners IP addresses here that you would
    # like to have monitored.

    $ip = array("192.168.100.160", "192.168.100.161", "192.168.100.162", "192.168.100.163", "192.168.100.164", "192.168.100.170", "192.168.100.171");

    # NOTE:  There is nothing below this line that needs to be edited.  Doing so has the
    # potential to cause issues with the rendering of the webpage.

    # written by pav_hash
    #
    # donations gladly accepted  3CsdpkawMuhSqBrdvCcHacSyuzmjzHVTTR  (btc)  
    #				 DsTyn6kv8NjY83LY4RminGTP2TF7DQBpeAw (dcr)
 


##########################################################################
##########################################################################

?>


<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="refresh" content="60" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/cascade.css" />
<title>Dragon Miner</title>

<script type="text/javascript"> 
	function display_c(){
		var refresh = 1000; // Refresh rate in milli seconds
		mytime = setTimeout('display_ct()',refresh)
	}

	function display_ct() {
		var strcount
		var x = new Date();
		var n = x.toDateString();
		var t = x.toLocaleTimeString();
		document.getElementById('ct').innerHTML = n + "   " + t;
		tt = display_c();
	}
</script>

</head>
<body onload=display_ct()>


  <div id="maincontainer">
     <div id="tabmenu">
        <div class="tabmenu1">
            <ul class="tabmenu l1">
                <li class="tabmenu-item-network active"><a href=<?php echo $_SERVER['PHP_SELF'] ?>>Dragon Miner Status</a></li>
            </ul>
            <br style="clear: both" />
        </div>
     </div>
     <div id="maincontent">
        <noscript>
           <div class="errorbox">
               <strong>Java Script required!</strong><br /> You must enable Java Script or LuCI will not work properly.
           </div>
        </noscript>
        <h2 style="padding-bottom:10px;"><a id="content" name="content">Status <span style='float: right' id='ct'></span></a></h2>
        <div class="cbi-map" id="cbi-cgminerstatus">
            <!-- tblsection -->
            <!-- tblsection -->
            <fieldset class="cbi-section" id="cbi-table-table">
            <legend>My Miners</legend>
            <div class="cbi-section-descr"></div>
            <div class="cbi-section-node">
            <table id="ant_devs" class="cbi-section-table">
                <tr class="cbi-section-table-titles">
                <th class="cbi-section-table-cell">Miner IP</th>
                <th class="cbi-section-table-cell">Miner Type</th>
		<th class="cbi-section-table-cell">Running Since</th>
                <th class="cbi-section-table-cell">Hashboard #</th>
                <th class="cbi-section-table-cell">Status</th>
                <th class="cbi-section-table-cell">Accepted</th>
                <th class="cbi-section-table-cell">Temp</th>
		<th class="cbi-section-table-cell">Hashrate (1m)</th>
		<th class="cbi-section-table-cell">Hashrate (avg)</th>
		<th class="cbi-section-table-cell">HW Errors</th>
                </tr>
                <tr class="cbi-section-table-descr">
                <th class="cbi-section-table-cell"></th>
                <th class="cbi-section-table-cell"></th>
		<th class="cbi-section-table-cell"></th>
                <th class="cbi-section-table-cell"></th>
                <th class="cbi-section-table-cell"></th>
                <th class="cbi-section-table-cell"></th>
		<th class="cbi-section-table-cell"></th>
                <th class="cbi-section-table-cell"></th>
		<th class="cbi-section-table-cell"></th>
                <th class="cbi-section-table-cell"></th>
                </tr>
   



<?php


function find_element( $what, $_string, $instance ) {
	if ( $instance === NULL )
		$instance = 1;

	$count = 1;
	$what = $what . ":";
        foreach ( $_string as $_element ) {
		if ( substr( $_element, 0, strlen( $what ) ) === $what ) {
			if ( $instance == $count ) {
				list( $base, $value ) = explode( ": ", $_element );
				return str_replace( "}]", "", $value );
			} else {
				$count = $count + 1;
			}
		}
        }
        return "";
}



foreach ($ip as $ipaddy ) {

	## chain number
	echo "<tr class=\"cbi-section-table-row cbi-rowstyle-1\" id=\"cbi-table-1\">";
	echo "<td class=\"cbi-value-field\">";
	echo "<div id=\"cbi-table-1-chain\"><b><a href=\"http://$ipaddy/cgi-bin/minerStatus.cgi\" target=\"_blank\">$ipaddy</a></b></div>";
	echo "<div id=\"cbip-table-1-chain\"></div>";
	echo "</tr></td>";


	$_stats = exec('python ./get_stats.py ' . $ipaddy . ' devs 2>&1');	

	$stats = str_replace( "'", "", str_replace( "u'", "", explode(", u",$_stats) ) );



	for ($cc = 1; $cc <= 3; $cc++) {

	   try {
		## miner ip address
		echo "<td class=\"cbi-value-field\">";
		echo "<div id=\"cbi-table-1-asic\"></div>";
		echo "<div id=\"cbip-table-1-asic\"></div>";
		echo "</td>";

                ## miner type 
                echo "<td class=\"cbi-value-field\">";
                if ( $cc == 1 ) {
                        echo "<div id=\"cbi-table-1-elapsed\">" . find_element("Description", $stats) . "</div>";
                } else {
                        echo "<div id=\"cbi-table-1-elapsed\"></div>";
                }
                echo "<div id=\"cbip-table-1-temp\"></div>";
                echo "</td>";

                ## elapsed time
		$ant_elapsed = find_element("Device Elapsed", $stats, $cc);
                ## elapsed time
                echo "<td class=\"cbi-value-field\">";
                if ( $cc == 1 ) {
			echo "<div id=\"cbi-table-1-elapsed\">" . date('m-d h:i:s', time() - $ant_elapsed) . "</div>";
                } else {
                        echo "<div id=\"cbi-table-1-elapsed\"></div>";
                }
                echo "<div id=\"cbip-table-1-temp\"></div>";
                echo "</td>";


		## chain #
		echo "<td class=\"cbi-value-field\">";
		echo "<div id=\"cbi-table-1-chain\">$cc</div>";
		echo "<div id=\"cbip-table-1-chain\"></div>";
		echo "</td>";

		## status 
		echo "<td class=\"cbi-value-field\">";
		echo "<div id=\"cbi-table-1-asic\">" . find_element("Status", $stats, $cc) . "</div>";
		echo "<div id=\"cbip-table-1-asic\"></div>";
		echo "</td>";

		## accepted 
		echo "<td class=\"cbi-value-field\">";
		echo "<div id=\"cbi-table-1-frequency\">" . find_element("Accepted", $stats, $cc) . "</div>";
		echo "<div id=\"cbip-table-1-frequency\"></div>";
		echo "</td>";

		## temp
		echo "<td class=\"cbi-value-field\">";
		echo "<div id=\"cbi-table-1-temp\">" . find_element("Temperature", $stats, $cc ) . "</div>";
		echo "<div id=\"cbip-table-1-temp\"></div>";
		echo "</td>";

                ## hashrate 1m
                echo "<td class=\"cbi-value-field\">";
                echo "<div id=\"cbi-table-1-temp\">" . find_element("MHS 1m", $stats, $cc) . "</div>";
                echo "<div id=\"cbip-table-1-temp\"></div>";
                echo "</td>";

                ## hashrate avg
                echo "<td class=\"cbi-value-field\">";
                echo "<div id=\"cbi-table-1-temp\">" . find_element("MHS av", $stats, $cc) . "</div>";
                echo "<div id=\"cbip-table-1-temp\"></div>";
                echo "</td>";

                ## hw errors 
                echo "<td class=\"cbi-value-field\">";
                echo "<div id=\"cbi-table-1-temp\">" . find_element("Hardware Errors", $stats, $cc) . "</div>";
                echo "<div id=\"cbip-table-1-temp\"></div>";
                echo "</td>";

		echo "</tr>";

	   } catch ( Exception $e ) {
	   }

	}
}


?>

</table>
<br><br><br><br>
<table id="ant_devs" class="cbi-section-table">
<tr><th><center>
    Written by pav_hash --  donations gladly accepted..    3CsdpkawMuhSqBrdvCcHacSyuzmjzHVTTR (btc)   DsTyn6kv8NjY83LY4RminGTP2TF7DQBpeAw (dcr) 
</th></tr>


</table>
</body>
</html>


