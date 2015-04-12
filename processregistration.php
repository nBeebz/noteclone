<?php
	require_once( 'functions.php' );
	include_once $_SERVER['DOCUMENT_ROOT'] . 'noteclone/securimage/securimage.php';
	$securimage = new Securimage();

	if( isset( $_POST['email'] )
		&& isset( $_POST['passwd'] ))
	{
		$email = $_POST['email'];
		$pass = $_POST['passwd'];
		check_injection( $email );
		check_injection( $pass );
		
		$user = get_user( $email );
		if( $user )
		{
			die( "Error, email already exists. <a href='register2.html'>Return to registration</a>" );
		}
		else
		{
			if ($securimage->check($_POST['captcha_code']) == false) 
			{
			  echo "The security code entered was incorrect.<br /><br />";
			  echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
			  exit;
			}
			else
			{
				add_user( $email, $pass );
                if( defined( "DEBUG" ) )
                {
                    activate_user( $email );
                    die("Your account has been activated");
                }
                else
                {
                    $pass = hash_with_salt( $pass );
                    $body = "Thank you for registering at Note Clone. To activate your account, please click on this link:\n"
                            .DIR."activate.php?user=$user&hp=$pass to activate your account";
                    //mail( $user, "Note Clone Confirmation", $body );
                    die("A confirmation email has been sent. <a href='index.html'>Log In</a>");
                }
            }
		}
	}
	else
	{
		header( 'Location: register.html' );
	}