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

if( isset( $_SESSION['logged_in'] ) )
	unset($_SESSION['logged_in']);

session_destroy();

header('Location: http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . 'login.php');

?>

