
<?php

    # This code is licensed under the GNU General Public License v2.0
    #
    # Please leave any/all comments, names, dates, donation addresses, ect. in place as is.
    #

    #
    # written by pav_hash	05/12/2018
    #
    # revision: 05/15/2018 - include button to auto-scan all miners in your /24 subnet
    #		auto-scanning the complete /24 should take between 5-20 seconds depending upon number of machines.
    #
    # revision: 05/16/2018 - include the ability to display mac addresses, along with having the ability to span
    #		subnets looking for miners.
    #
    #
    #
    # donations gladly accepted  3CsdpkawMuhSqBrdvCcHacSyuzmjzHVTTR  (btc)  
    #				 DsTyn6kv8NjY83LY4RminGTP2TF7DQBpeAw (dcr)
 



#### Custom define statements you may optionally enable ####


# NOTE: setting this to true will REALLY slow down ui rendering!!
$show_mac_addy = false;


# If you have subnets that span across multiple subnets, add all the subnets here.
# For example:  $multi_subnets = array( "192.168.1.1-100", "192.168.100.1-50", "10.10.0.5-15" );
# The '1-100' in the above example defines that you have been assigned ip's ending in .1 THROUGH .100
#
# NOTE: Your server must be able to ping across subnets for this to work properly.
#
# The array is empty by default.
#
$multi_subnets = array();



#### End of custom defines ####


#
#
#
############ EDITING BELOW THIS LINE IS NOT RECOMMENDED #################
#
#
#


##########################################################################
# functins
##########################################################################


function get_all_dragon_ips() {
	#get all ips that are alive via ping and save off to a file
	$my_subnet = $_SERVER['SERVER_ADDR'];
	$my_subnet = substr( $_SERVER['SERVER_ADDR'], 0, strrpos( $my_subnet, "." ) ) . '.0-255';

	$cmd = exec("rm /tmp/found_ips.lst");

	if ( count( $multi_subnets ) >= 1 ) {
		foreach ( $multi_subnets as $isub ) {
			$cmd = "nmap -T5 -sP $isub | grep 'Nmap scan report for ' | cut -f 5 -d ' ' >>/tmp/found_ips.lst";
			$nop = exec( $cmd );
		}
	} else {
		$cmd = "nmap -T5 -sP $my_subnet | grep 'Nmap scan report for ' | cut -f 5 -d ' ' >/tmp/found_ips.lst";
		$nop = exec( $cmd );
	}

	$cmd = exec('rm /tmp/dragon_ips.lst');

        $ip_list = file('/tmp/found_ips.lst');
        foreach ( $ip_list as $ipaddy ) {
		$ipaddy = str_replace("\n", "", str_replace("\r","",$ipaddy));
		$_stat = exec( 'python ./get_stats.py ' . $ipaddy . ' devs ' );
		if ( strpos($_stat, 'STATUS') !== false ) {
			$cmd = exec('echo ' . $ipaddy . ' >>/tmp/dragon_ips.lst');
		} 
        }
}

function get_all_miners() {
	$ips = str_replace("\r", '', file('/tmp/dragon_ips.lst'));
	return $ips;
}

function remove_ip( $rip ) {
	$cmd = "cat /tmp/dragon_ips.lst |grep -v " . $rip . " > /tmp/dragon_ips_tmp.lst";
	$cmd = exec( $cmd );
	$cmd = copy( '/tmp/dragon_ips_tmp.lst', '/tmp/dragon_ips.lst' );
}

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

function find_active_stratum( $what, $_string, $instance ) {
        if ( $instance === NULL )
                $instance = 1;

        $count = 1;
        $what = $what . ":";
        foreach ( $_string as $_element ) {
                if ( strpos( $_element, $what ) !== false ) {
                        if ( $instance == $count ) {
				if ( substr_count( $_element, ':' ) > 1 ) {
					list( $noop, $base, $value ) = explode( ": ", $_element );
				} else {
                                	list( $base, $value ) = explode( ": ", $_element );
				}
                                return str_replace( "}]", "", $value );
                        } else {
                                $count = $count + 1;
                        }
                }
        }
        return "";
}

######################### end functions ##################################


if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['get_all_dragon_ips']))
{
	get_all_dragon_ips();
	header('Location:'.$_SERVER['PHP_SELF']);
}

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['remove_ip']))
{
	remove_ip( $_POST['remove_ip'] );
#	header('Location:'.$_SERVER['PHP_SELF']);
}



# check if the ip_table.lst file exists..
if (!file_exists('/tmp/dragon_ips.lst')) {
	get_all_dragon_ips();
}	

$ip = get_all_miners();


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

<table id="ant_devs" class="cbi-section-table">
<tr><th>
<form action="dragonstats.php" method="post">
    <input type="submit" name="get_all_dragon_ips" value="Rescan Network" style="font-size:12pt;color:white; background-color:green;border:2px solid #336600;padding:3px; />
</form>
</th></tr>
</table>

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
                <th class="cbi-section-table-cell">Miner Name (Type)</th>
		<th class="cbi-section-table-cell">Running Since</th>
                <th class="cbi-section-table-cell">Hashboard #</th>
                <th class="cbi-section-table-cell">Status</th>
                <th class="cbi-section-table-cell">Accepted</th>
                <th class="cbi-section-table-cell">Temp</th>
		<th class="cbi-section-table-cell">Fan Speed</th>
		<th class="cbi-section-table-cell">Hashrate (1m)</th>
		<th class="cbi-section-table-cell">Hashrate (avg)</th>
		<th class="cbi-section-table-cell">HW Errors</th>
		<th class="cbi-section-table-cell"></th>
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
                <th class="cbi-section-table-cell"></th>
		<th class="cbi-section-table-cell"></th>
                </tr>
   



<?php




foreach ($ip as $ipaddy ) {

	if ( $show_mac_addy === true ) {
		$cmd = "arp -a | grep " . str_replace( "\n", "", str_replace( "\r", "", $ipaddy ) ) . "  | cut -f 4 -d ' ' 2>&1 ";
		$mac_addy = exec( $cmd );
	}

	## chain number
	echo "<tr class=\"cbi-section-table-row cbi-rowstyle-1\" id=\"cbi-table-1\">";
	echo "<td class=\"cbi-value-field\">";
	echo "<div id=\"cbi-table-1-chain\"><b><a href=\"http://$ipaddy/cgi-bin/minerStatus.cgi\" target=\"_blank\">$ipaddy</a></b></div>";
	echo "<div id=\"cbip-table-1-chain\"></div>";
	echo "</tr></td>";

	$ipaddy = str_replace("\n", "", str_replace("\r","",$ipaddy));

	$_stats = exec('python ./get_stats.py ' . $ipaddy . ' devs 2>&1');	
	$stats = str_replace( "'", "", str_replace( "u'", "", explode(", u",$_stats) ) );

	$_pools = exec('python ./get_stats.py ' . $ipaddy . ' pools 2>&1');
	$pools = str_replace( "'", "", str_replace( "u'", "", explode(", u",$_pools) ) );

        $_fullstats = exec('python ./get_stats.py ' . $ipaddy . ' stats 2>&1');     
        $fullstats = str_replace( "'", "", str_replace( "u'", "", explode(", u",$_fullstats) ) );


	for ($cc = 1; $cc <= 3; $cc++) {

	   try {
		## miner ip address
		echo "<td class=\"cbi-value-field\">";
		if ( $cc == 1 && $show_mac_addy ) {
			echo "<div id=\"cbi-table-1-asic\">mac address: $mac_addy</div>";
		} else {
			echo "<div id=\"cbi-table-1-asic\"></div>";
		}
		echo "<div id=\"cbip-table-1-asic\"></div>";
		echo "</td>";

                ## miner type
		$miner_miner = find_element("Description", $stats);
		if ( strpos( $miner_miner, 'cgminer 4.9.0' ) !== false ) {
			## is this an antminer??
			$miner_type = ' (antminer)';
		} elseif ( strpos( $miner_miner, 'cgminer' ) !== false ) {
			$miner_type = ' (T1)';
		} elseif ( strpos( $miner_miner, 'sgminer' ) !== false ) {
			$miner_type = ' (B29/D9)';
		} else {
			$miner_type = ' (Unknown)';
		}	


		for ($pp = 1; $pp <= 3; $pp++) {
			if ( find_active_stratum("Stratum Active", $pools, $pp) === "True" ) {
				$miner_miner = find_element("User", $pools, $pp );
				break;
			}
		}

                echo "<td class=\"cbi-value-field\">";
                if ( $cc == 1 ) {
                        echo "<div id=\"cbi-table-1-elapsed\">" . $miner_miner . $miner_type . "</div>";
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

                ## fan speed 
                echo "<td class=\"cbi-value-field\">";
		if ( $cc == 2 ) {
	                echo "<div id=\"cbi-table-1-temp\">" . find_element("Fan duty", $fullstats ) . "</div>";
		} else {
			echo "<div id=\"cbi-table-1-temp\"></div>";
		}
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

                ## remove miner ip address
                echo "<td class=\"cbi-value-field\">";
                if ( $cc == 2 ) {
#			echo "<a id=\"remove_ip\" href=" . $_SERVER['PHP_SELF'] . "?remove_ip=" . $ipaddy . ">Remove Miner</a>";
                        echo "<form action=" . $_SERVER['PHP_SELF'] . " method='post'> <button type='submit' name='remove_ip' value='$ipaddy' class='remove_ip'>Remove Miner</button></form>";
                } else {
                        echo "<div id=\"cbi-table-1-asic\"></div>";
                }
                echo "<div id=\"cbip-table-1-asic\"></div>";
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


