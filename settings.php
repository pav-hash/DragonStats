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

	$error_msg = "";

	session_start();


	if ( !isset( $_SESSION['logged_in'] ) ) {
		header('Location: http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . 'login.php');
		exit;
	}

	if ( isset( $_SESSION['logged_in'] ) and $_SESSION['logged_in'] !== true ) {
		header('Location: http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . 'login.php');
		exit;
	}



	function save_settings() {
		if ( file_exists( $_SERVER['DOCUMENT_ROOT'] . '/dragonstats_settings.inc' ) ) unlink( $_SERVER['DOCUMENT_ROOT'] . '/dragonstats_settings.inc' );
			file_put_contents( $_SERVER['DOCUMENT_ROOT'] . '/dragonstats_settings.inc', '{ "dragonstats": { "refresh":"' . $_POST['inputRefresh'] . '","auser":"' . $_POST['inputUser'] . '","apass":"' . $_POST['inputPassword'] . '" } }' );

		if ( file_exists( $_SERVER['DOCUMENT_ROOT'] . '/dragonstats_settings.inc' ) ) {
			$error_msg = " Settings Updated.";
		} else {
			$error_msg = " Settings NOT updated.";
		}
	}


	function get_refresh($_json) {
		return get_json_element( $_json, 'refresh');
	}

	function get_settings() {
		$_string = file_get_contents( $_SERVER['DOCUMENT_ROOT'] . '/dragonstats_settings.inc' );
		return $_string;
	}


	function get_json_element( $_json, $_element ) {
		$json = json_decode( $_json, true );
		return $json['dragonstats'][$_element];
	}



	if ( isset( $_POST['btnpost'] ) ) {
		$_savit = unlink( $_SERVER['DOCUMENT_ROOT'] . '/dragonstats_settings.inc' );
		if ( $_savit )
			save_settings();
	} 


	## load up the existing settings if there are any..
	$json_settings = get_settings();

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" media="screen" href="./css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="screen" href="./css/fontawesome-all.min.css" />
<link rel="stylesheet" type="text/css" media="screen" href="./css/dragonstats.css" />
<link rel="stylesheet" type="text/css" media="screen" href="./css/styles.css" />

	
<title>DragonStats</title>
	
<style type="text/css">/* Chart.js */
	@-webkit-keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}
	@keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}.chartjs-render-monitor{-webkit-animation:chartjs-render-animation 0.001s;animation:chartjs-render-animation 0.001s;}
</style>


<script type="text/javascript">

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
					    <a aria-current="false" href="change_pool.php">
					    <i class="fa fa-cog"></i>&nbsp;&nbsp; Change Pools</a>
					</li>
					<li>
					    <a aria-current="false" href="security.php">
					    <i class="fa fa-user"></i>&nbsp;&nbsp; Security</a>
					</li>
					<li>
					    <a aria-current="true" href="settings.php" class="active">
					    <i class="fa fa-wrench"></i>&nbsp;&nbsp; Settings</a>
					</li>
					<li>
					    <a aria-current="false" href="logout.php">
					    <i class="fa fa-power-off"></i>&nbsp;&nbsp; Logout</a>
					</li>
					</ul>
				</nav>


				<div id="security_content">

					<nav class="navbar navbar-default m-0 d-md-none">
						<div class="container-fluid">
							<div class="navbar-header">
								<div class="ds-logo"></div>
								<button type="button" id="sidebarCollapse" class="btn navbar-btn btn-sm">
									<i class="fa fa-bars"></i>
								</button>
							</div>
						</div>
					</nav>

					<form method="POST" action="">
					<div id="page-content">
						<div class="Securitypage">
							<h1>Settings</h1>
							<div class="row">
								<div class="col-md-12 mt-5">
									<div class="box">
										<div class="box-header">
											<h3>Change Default Dragon Stats Settings</h3>
										</div>


										<?php
											if ( $error_msg !== "" ) {
												echo '<div class="alert alert-warning">';
												echo '<strong>Error</strong>';
												echo ' ' . $error_msg;
												echo '</div>';
											}
										?>

										<div class="box-body p-4">
											<div class="form-group row">
												<label for="inputRefresh" class="col-sm-2 col-form-label">Status Refresh Interval</label>
												<div class="col-sm-10">
													<select class="form-control form-control-sm" name="inputRefresh" id="inputRefresh">
														<option <?php echo get_refresh($json_settings) === "15" ? ' selected ' : '';?>value="15">Every 15 Seconds</option>
														<option <?php echo get_refresh($json_settings) === "30" ? ' selected ' : '';?>value="30">Every 30 Seconds</option>
														<option <?php echo get_refresh($json_settings) === "60" ? ' selected ' : '';?>value="60">Every Minute</option>
														<option <?php echo get_refresh($json_settings) === "300" ? ' selected ' : '';?>value="300">Every 5 Minutes</option>
														<option <?php echo get_refresh($json_settings) === "3600" ? ' selected ' : '';?>value="3600">Every Hour</option>
														<option <?php echo get_refresh($json_settings) === "-1" ? ' selected ' : '';?>value="-1">Disable</option>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label for="inputUser" class="col-sm-2 col-form-label">Miner Admin Username</label>
												<div class="col-sm-10">
													<input type="text" class="form-control form-control-sm" name="inputUser" value="<?php echo get_json_element( $json_settings, 'auser'); ?>" id="inputUser" placeholder="Miner Admin Username">
												</div>
											</div>
											<div class="form-group row">
												<label for="inputPassword" class="col-sm-2 col-form-label">Miner Admin Password</label>
												<div class="col-sm-10">
													<input type="text" class="form-control form-control-sm" name="inputPassword" value="<?php echo get_json_element( $json_settings, 'apass'); ?>" id="inputPassword" placeholder="Miner Admin Password">
												</div>
											</div>
										</div>
										<div class="box-footer">
											<button rel="btn" class="btn btn-primary" name="btnpost">Update</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					</form>
			
					<div class="col-md-12 mt-5">
						<div class="form-group false">
							<center>
							Written by pav_hash --  Donations gladly accepted..<br>3CsdpkawMuhSqBrdvCcHacSyuzmjzHVTTR (btc)<br>DsTyn6kv8NjY83LY4RminGTP2TF7DQBpeAw (dcr)
							</center>
						</div>
					</div>

				</div>
			</div>
		</div>

	</div>
	
<script src="js/jquery.min.js"></script><script src="js/bootstrap.min.js"></script>
	
</body>
</html>

