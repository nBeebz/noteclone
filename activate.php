<?php

require_once( 'functions.php' );

if( !isset($_GET['user']) || !isset($_GET['hp']) )
    die("An error has occurred");

$email =$_GET['user'];
$pass = $_GET['hp'];
check_injection( $email );
check_injection( $pass );

$user = get_user( $email );

if( $pass == $user['password'] )
    activate_user( $email );
else
    die('Verification Failed.');