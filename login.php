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

ob_start();
session_start();

$initial_setup = false;
$file = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']) . '/dragonstats_pass.inc';
$setup_file = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']) . '/dragonstats_settings.inc';

if ( !is_file( $setup_file ) )
	$initial_setup = true;

if ( !is_file( $setup_file ) ) {
	file_put_contents( $setup_file, '' );
}

if ( !is_file( $file ) ) {
	$contents = 'admin:' . md5( 'dragonstats' );
	file_put_contents( $file, $contents );
}

$passfile = file_get_contents( $file );

list( $_uname, $_upass ) = explode( ":", $passfile );

?>


<html lang="en">
   
   <head>
      <script type="text/javascript">
	  sessionStorage.clear();
      </script>

      <title>DragonStats Login</title>
      <link href = "css/bootstrap.min.css" rel = "stylesheet">
      
      <style>
         body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #ffffff;
         }
        
	.imgcontainer {
	    text-align: center;
	    margin: 6px 0 6px 0;
	}

	img.avatar {
	    height: 200px;
	    width: 300px;
	    border-radius: 2%;
	    border: none;
	    outline: none;
	}
 
         .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
            color: #017572;
         }
         
         .form-signin .form-signin-heading,
         .form-signin .checkbox {
            margin-bottom: 10px;
         }
         
         .form-signin .checkbox {
            font-weight: normal;
         }
         
         .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
         }
         
         .form-signin .form-control:focus {
            z-index: 2;
         }
         
         .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            border-color:#017572;
         }
         
         .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-color:#017572;
         }
         
         h2{
            text-align: center;
            color: #017572;
         }
	 .footer {
	    position: fixed;
	    left: 0;
	    bottom: 0;
	    width: 100%;
	    background-color: red;
	    color: white;
	    text-align: center;
	}
	      
      </style>
      
   </head>
	
   <body>
      
      <div class = "container form-signin">
         
         <?php
            $msg = '';
            
            if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {

               $_upass = str_replace( "\n", "", $_upass );

               if ($_POST['username'] == $_uname && md5( $_POST['password'] ) === $_upass ) { 
                  $_SESSION['logged_in'] = true;
                  $_SESSION['timeout'] = time();

		   header('Location: http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . '/dragonstats.php');
               } else { 
		   header('Location: http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . '/login.php');
		   exit;
               }
            }
         ?>
      </div> <!-- /container -->
     
	<div class="imgcontainer">
		<img src="images/dragon_stats.jpg" alt="DragonStats" class="avatar">
	</div>
 
      <div class = "container">
      
         <form class = "form-signin" role = "form" 
            action = " <?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>" method = "post"> 
            <h4 class = "form-signin-heading"><?php echo $msg; ?></h4>
            <input type = "text" class = "form-control" 
               name = "username" placeholder = "username = admin" 
               required autofocus></br>
            <input type = "password" class = "form-control"
               name = "password" placeholder = "password = dragonstats" required>
            <button class = "btn btn-lg btn-primary btn-block" type = "submit" 
               name = "login">Login</button>
         </form>
         
      </div> 

<?php
        if ( $initial_setup ) {
?>
                <div class="row"><div class="container"><div class="alert alert-warning"><div class="ml-2 lds-dual-ring small pt-1"></div>
                <center>Please note the the initial setup may take awhile!</center></div></div></div>
<?php
        }
?> 

  <div class="footer">
  <div class="pavhash">Written by pav_hash --  Donations gladly accepted..<br>3CsdpkawMuhSqBrdvCcHacSyuzmjzHVTTR (btc)<br>DsTyn6kv8NjY83LY4RminGTP2TF7DQBpeAw (dcr)</div>
  </div>

   </body>
</html>

