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
		header('Location: http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . '/login.php');
		exit;
	}

	if ( isset( $_SESSION['logged_in'] ) and $_SESSION['logged_in'] !== true ) {
		header('Location: http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . '/login.php');
		exit;
	}



	function savepw() {
		$dragpwfile = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']) . '/dragonstats_pass.inc';
		if ( file_exists( $dragpwfile ) ) unlink( $dragpwfile );
		file_put_contents( $dragpwfile, 'admin:' . md5( $_POST['inputConfirmPassword'] ) );
		if ( file_exists( $dragpwfile ) ) {
			$error_msg = " Password Updated.";
		} else {
			$error_msg = " Password NOT updated.";
		}
#		$_POST = array();
	}

	if ( isset( $_POST['btnpost'] ) ) {
		$_savit = true;
		$passfile = file_get_contents( $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']) . '/dragonstats_pass.inc');
		list( $_uname, $_upass ) = explode( ":", $passfile );
		$_upass = str_replace( "\n", "", $_upass );
		if ( $_upass !== md5( $_POST['inputCurrentPassword'] ) ) {
			$error_msg = " Current password does NOT match.";
			$_savit = false;
		}
		if ( $_POST['inputNewPassword'] !== $_POST['inputConfirmPassword'] && $_POST['inputNewPassword'] !== "" ) {
			$error_msg = " The new and confirm passwords do NOT match.";
			$_savit = false;
		}

		if ( $_savit )
			savepw();
	} 

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
					    <a aria-current="true" href="security.php" class="active">
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


				<div id="security_content">
					<div class="row">
						<div class="col-md-12 text-right mt-0 d-lg-block">
							<i class="fa fa-user"></i> 
							<small>admin</small>
						</div>
					</div>
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
							<h1>Security<br><small>Password</small></h1>
							<div class="row">
								<div class="col-md-12 mt-5">
									<div class="box">
										<div class="box-header">
											<h3>Change Password</h3>
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
												<label for="inputUser" class="col-sm-2 col-form-label">User</label>
												<div class="col-sm-10">
													<select class="form-control form-control-sm" id="inputUser">
														<option value="admin">admin</option>
<!--
														<option value="guest">guest</option>
-->
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label for="inputCurrentPassword" class="col-sm-2 col-form-label">Current Admin Password</label>
												<div class="col-sm-10">
													<input type="password" class="form-control form-control-sm" name="inputCurrentPassword" id="inputCurrentPassword" placeholder="Current Admin Password">
												</div>
											</div>
											<div class="form-group row">
												<label for="inputNewPassword" class="col-sm-2 col-form-label">New Password</label>
												<div class="col-sm-10">
													<input type="password" class="form-control form-control-sm" name="inputNewPassword" id="inputNewPassword" placeholder="Current Password">
												</div>
											</div>
											<div class="form-group row">
												<label for="inputConfirmPassword" class="col-sm-2 col-form-label">Confirm Password</label>
												<div class="col-sm-10">
													<input type="password" class="form-control form-control-sm" name="inputConfirmPassword" id="inputConfirmPassword" placeholder="Confirm Password">
												</div>
											</div>
										</div>
										<div class="box-footer">
											<button rel="btn" class="btn btn-primary" name="btnpost">Update </button>
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

