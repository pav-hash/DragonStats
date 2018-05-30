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
?>

<html>
<head>
<script src="//fast.wistia.net/labs/fresh-url/v1.js"></script>
<script>
    function closeWindow() {
        window.open('','_parent','');
        window.close();
    }
</script> 
</head>
<body>


<?php

# parse off the url params..
parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $query);

function get_all_miners( $query ) {
        $miners = array();
        foreach ( $query as $key => $value ) {
                if ( strpos( $key, 'miner' ) !== false ) {
                        array_push( $miners, $key );
                }
        }
        return $miners;
}

function get_key_value( $_key, $query ) {
	foreach ( $query as $key => $value ) {
		if ( $_key === $key ) {
			return $value;
		}
	}
	return "";
}


function post_to_miner( $_m, $_l, $_u, $_p, $_au, $_ap ) {
	$url = 'http://' . $_au . ':' . $_ap . '@' . $_m . '/api/updatePools';

	$ch = curl_init($url);

	$data = array(
	    'Pool1' => $_l,
	    'UserName1' => $_u,
	    'Password1' => $_p,
	    'Pool2' => "",
	    'UserName2' => "",
	    'Password2' => "",
	    'Pool3' => "",
	    'UserName3' => "",
	    'Password3' => ""
	);


	// use key 'http' even if you send the request to https://...
	$options = array(
	    'http' => array(
		'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		'method'  => 'POST',
		'content' => http_build_query($data)
	    )
	);

	$context  = stream_context_create($options);

	$result = file_get_contents($url, false, $context);

	if ($result === FALSE) {
		return "Failed";
	}

	return "Success";
}


$miners = get_all_miners( $query );

$total_miners_submitted = count( $miners );

if ( $total_miners_submitted <= 0 ) {
	echo "<center><h2><b>No miners selected to change pools.</b></h2></center><br>";
}

$cc = 1;

foreach( $miners as $miner ) {
        $ip = get_key_value( $miner, $query );
        $url = get_key_value( "url", $query );
        $user = get_key_value( "user", $query );
        $mname = get_key_value( "mname", $query );
        $pass = get_key_value( "pass", $query );
        $auser = get_key_value( "adminuser", $query );
        $apass = get_key_value( "adminpass", $query );

        if ( $mname == '' )
                $mname = 'dm';

        if ( strpos( $user, '.' ) === false ) {
                #username does not contain a worker name, so lets add one..
                $user = $user . '.' . $mname . $cc;
        } else {
                #username has a worker name.. strip it and we'll add our own ONLY if count( $miners ) > 1..
                if ( total_miners_submitted == 1 ) {
                        ## allow the username to pass through..
                } else {
                        $user = substr( $user, 0, stripos( $user, '.' ) ) . '.' . $mname . $cc;
                }
        }

	## write out the applying changes first.. then wait till the post returns..
        echo "Applying changes to: " . $miner . ' IP: ' . $ip . ' ..... ' . post_to_miner( $ip, $url, $user, $pass, $auser, $apass ) . "<br>";


        $cc = $cc + 1;
}

?>

<br><center>
<input type="button" onclick="closeWindow();" value="Close Window" />
</center>

</body>
</html>

