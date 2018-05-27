<?php

    # This code is licensed under the GNU General Public License v2.0
    #
    # Please leave any/all comments, names, dates, donation addresses, ect. in place as is.
    #

    #
    # written by pav_hash       05/12/2018
    #
    #
    #
    #
    # donations gladly accepted  3CsdpkawMuhSqBrdvCcHacSyuzmjzHVTTR  (btc)
    #                            DsTyn6kv8NjY83LY4RminGTP2TF7DQBpeAw (dcr)


#
#
#
############ EDITING BELOW THIS LINE IS NOT RECOMMENDED #################
#
#
#
	session_start();

	if ( !isset( $_SESSION['logged_in'] ) ) {
		header('Location: http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . '/login.php');
		exit;
	}

	if ( isset( $_SESSION['logged_in'] ) and $_SESSION['logged_in'] !== true ) {
		header('Location: http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . '/login.php');
		exit;
	}

	function get_all_miners() {
		$ips = str_replace("\r", '', file('./lists/dragon_ips.lst'));
		return $ips;
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" media="screen" href="./css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="screen" href="./css/fontawesome-all.min.css" />
<link rel="stylesheet" type="text/css" media="screen" href="./css/styles.css" />
<link rel="stylesheet" type="text/css" media="screen" href="./css/dragonstats.css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="cache-control" content="no-cache" />
<title>DragonStats</title>

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

	function checkform() {
		var pl = $("#inputURL").val();
		if (pl == '' || pl == null) {
			alert("Pool URL is empty");
			return false;
		}
                var pu = $("#inputWorker").val();
                if (pu == '' || pu == null) {
                        alert("Pool Worker is empty");
                        return false;
                }
                var px = $("#inputPassword").val();
                if (px == '' || px == null) {
                        alert("Pool Password is empty");
                        return false;
                }
                var au = $("#adminuser").val();
                if (au == '' || au == null) {
                        alert("Admin Username is empty");
                        return false;
                }
                var ap = $("#adminpass").val();
                if (ap == '' || ap == null) {
                        alert("Admin Password is empty");
                        return false;
                }
		return true;
	}

	function popup_post(form) {

		if ( checkform() === true ) {
			window.open('', 'formpopup', 'toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=400,height=400');
			form.target = 'formpopup';
		} else {
			event.preventDefault();
		}
	}

	function get_return_url() {
		var fobar = sessionStorage.getItem( 'condensed_ck' );
		location.replace( 'dragonstats.php' + "?condensed=" + fobar);
		return fobar;
	}


</script>

</head>
<body>

<noscript>You need to enable JavaScript to run this app.</noscript>
<div id="root">
<div class="App">

<div class="wrapper light-skin">

<nav id="sidebar" class="">
     <div class="sidebar-header ds-logo"></div>
        <ul class="list-unstyled components">
        <li>
            <a aria-current="false" href="javascript:get_return_url();">
            <i class="fa fa-tachometer-alt"></i>&nbsp;&nbsp; Miner Status</a>
        </li>
        <li>
            <a aria-current="true" href="change_pool.php" class="active">
            <i class="fa fa-cog"></i>&nbsp;&nbsp; Change Pools</a>
        </li>
        <li>
            <a aria-current="false" href="security.php">
            <i class="fa fa-user"></i>&nbsp;&nbsp; Security</a>
        </li>
        <li>
            <a aria-current="false" href="settings.php">
            <i class="fa fa-wrench"></i>&nbsp;&nbsp; Settings</a>
        </li>
        <li>
            <a aria-current="false" href="logout.php">
            <i class="fa fa-power-off"></i>&nbsp;&nbsp; Logout</a>
        </li>
        </ul>
</nav>



<div class="pavhash">
  <h2>Global Pool Changer</h2>

<div class="alert alert-info mt-5">
NOTE: This function only works if ALL your miners have the same admin username and password.
</div>

<form id="myForm" action="change_pool_popup.php" onSubmit="return popup_post(this)">

<div class="row">


  <div class="col-md-12 mt-5">
  <div class="box">
  <div class="box-header">
	<h3>Available Miners</h3>
  </div>
  <div class="box-body p-4">

<?php

	$miners = get_all_miners();
	$cc = 1;

	foreach ( $miners as $miner ) {
		$miner = str_replace( "\n", "", str_replace( "\r", "", $miner ) );
		echo "    <input id='item$cc' name='miner$cc' value=$miner type='checkbox' checked> ";
		echo "    <label for='item$cc'>" . $miner . "</label><br>";

		$cc = $cc + 1;
	}
?>

  </div>
  </div>
  </div>



<div class="col-md-12 mt-5">
<div class="box">
<div class="box-header">
	<h3>Pool Information</h3>
</div>
<div class="box-body p4">
	<div class="form-group false">
		<label for="inputURL1">URL</label>
		<div class="input-group mb-2">
			<input type="text" class="form-control form-control-sm" data-pool="0" name="url" value="" id="inputURL" placeholder="Pool URL">
		</div>
	</div>
	<div class="form-group false">
		<label for="inputWorker1">Worker</label>
		<div class="input-group mb-2">
			<input type="text" class="form-control form-control-sm" data-pool="0" name="user" value="" id="inputWorker" placeholder="Pool Worker">
		</div>
	</div>
	<div class="form-group false">
		<label for="inputPassword1">Password</label>
		<div class="input-group mb-2">
			<input type="text" class="form-control form-control-sm" data-pool="0" name="pass" value="" id="inputPassword" placeholder="Pool Password">
		</div>
	</div>
</div>
</div>
</div>


<div class="col-md-12 mt-5">
<div class="box">
<div class="box-header">
        <h3>Admin Information</h3>
</div>
<div class="box-body p4">
        <div class="form-group false">
                <label for="inputWorker1">Username</label>
                <div class="input-group mb-2">
                        <input type="text" class="form-control form-control-sm" data-pool="0" name="adminuser" value="" id="adminuser" placeholder="Admin Username">
                </div>
        </div>
        <div class="form-group false">
                <label for="inputPassword1">Password</label>
                <div class="input-group mb-2">
                        <input type="text" class="form-control form-control-sm" data-pool="0" name="adminpass" value="" id="adminpass" placeholder="Admin Password">
                </div>
        </div>
</div>
</div>
</div>

<div class="col-md-12 text-center">
	<button class="btn btn-primary">Update Miners</button>
</div>
</form>


<!-- ending divs -->
</div>


<div class="col-md-12 mt-5">
	<div class="form-group false">
		Written by pav_hash --  Donations gladly accepted..<br>3CsdpkawMuhSqBrdvCcHacSyuzmjzHVTTR (btc)<br>DsTyn6kv8NjY83LY4RminGTP2TF7DQBpeAw (dcr)
	</div>
</div>



</div>

</div>
</div>
</div>

<script src="js/bootstrap.min.js"></script>

</body>
</html>

