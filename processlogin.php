<?php
	require_once( 'functions.php' );
	if( isset( $_POST['email'] )
		&& isset( $_POST['passwd'] ) )
	{
		$email = $_POST['email'];
		$pass = $_POST['passwd'];
		check_injection( $email );
		check_injection( $pass );
		$user = get_user( $email );
		if( !is_null($user) )
		{	
			if( !$user['active'] )
			{
				kill_session();
				die( "Your account has not been activated. Check your email for a confirmation link." );
			}

            $pass = md5( $pass.SALT );
            if( $user['password'] == $pass )
            {
                mkdir("temp/".session_id());
                $_SESSION['user'] = $email;
                $_SESSION['logintime'] = time();
                set_attempts( $email, 0 );
                header( 'Location: notes.php' );
            }
			elseif( $user['attempts'] == 3 )
			{
				kill_session();
				die( "This account has been locked" );
			}
			else
			{
				incorrect_pass( $email );
			}
		}
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml"><head>

<link rel="shortcut icon" href="pencil.ico">

    <title>Note to Myself - Log in</title>
    <link type="text/css" href="css/register2.css" rel="stylesheet" media="screen">
    <script src="js/jquery-1.4.1.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/login2.js"></script>

</head>
<body>

Login error. Did you <a href="forgotpassword.php">forget your password</a>? 
Please try again to <a href="register.html">register</a> or <a href='index.html'>log in</a>

</body>
</html>