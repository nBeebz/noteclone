<?php
	require_once( 'functions.php' );
	if( isset( $_POST['email'] ) )
	{
		$email = $_POST['email'];
		check_injection( $email );

		$user = get_user( $email );

		if( $user )
		{
			reset_password( $email );
		}
		else
		{
			die( "You are not registered, <a href='register.html'>Register Here</a>" );
		}
	}
	else
	{
		header( 'Location: index.html' );
	}