<?php
ob_start();
session_start();

## DEBUG MODE (comment out to test mail server)
define( 'DEBUG', 1 );

## Constants
//Database Information
define( 'DB_HOST', 'localhost' );
define( 'DB_USER', 'root' );
define( 'DB_PASS', '' );
define( 'DB_NAME', 'noteclone' );

define( 'SALT', 'saltydog' );//Password salt

define( 'DIR', 'localhost/noteClone/' );//Full URL path to website

$db = false;//Database Connection

## Functions
function update_images( $email, $file )
{
    $fname = $file['name'];
    if (save_image( $file ))
    {
        $image = file_get_contents("temp/".session_id()."/".$fname);
        put_image_on_db($email, $image, $fname);
    }
}

function remove_images_from_db( $email, $filenames )
{
    foreach($filenames as $filename)
    {
        $q = "DELETE FROM images
            WHERE email='$email'
              AND filename='$filename'";
        mysqli_query(get_db(), $q) or die(mysqli_error(get_db()));
    }
}

function save_image( $file )
{
    $target_dir = "temp/".session_id()."/";
    $regex =  "/\.(jpg|jpeg|gif)$/";
    if( !empty($file['name']) )
    {
        if (preg_match($regex, $file['name'])) {
            if (move_uploaded_file($file["tmp_name"], $target_dir . $file['name'])) {
                echo "The file " . basename($file["name"]) . " has been uploaded.";
                return true;
            } else {
                echo "Sorry, there was an error uploading your file.";
                return false;
            }
        } else {
            echo "Sorry. Only jpgs and gifs allowed";
            return false;
        }
    }
    else
        return false;
}

function put_image_on_db( $email, $image, $fname )
{
    $image = addslashes( $image );
    $fname = addslashes( $fname );
    $q = "INSERT INTO images( email, image, filename )
            VALUES( '$email', '$image', '$fname' )";
    mysqli_query(get_db(), $q) or die(mysqli_error(get_db()));
}

function get_images( $email )
{
    $filenames = array();

	$q = "SELECT image, filename
			FROM images
			WHERE email='$email'";
	
	$result = mysqli_query( get_db(), $q );
	while($row = mysqli_fetch_assoc($result))
	{
        $fname = $row['filename'];
        file_put_contents("temp/".session_id()."/".$fname, $row['image'] );
        array_push( $filenames, $fname );
	}
	return get_image_table( $filenames );
}

function get_image_table( $filenames )
{
    $table = "<table>";
    foreach( $filenames as $file )
    {
        $table = $table."<tr><td><a href='temp/".session_id()."/$file'><img src='temp/".session_id()."/$file' width=100 ></a></td>";
        $table = $table."<td><input type='checkbox' name='delete[]' value='$file'>Delete</td></tr>";
    }
    return $table."</table>";
}

function hash_with_salt( $pass )
{
    return md5($pass.SALT);
}

//Updates websites in database
function update_websites( $email, $sites )
{
    $i = 0;
	foreach( $sites as $site )
	{
        if( empty($site) )
        {
            $q = "DELETE FROM sites
                    WHERE email='$email'
                      AND id=$i";
        }
        else
        {
            $site = urlencode( $site );
            $q = "INSERT INTO sites( email, id, site )
                    VALUES( '$email', $i, '$site')
                    ON DUPLICATE KEY UPDATE site = '$site'";
        }
        mysqli_query(get_db(), $q) or die(mysqli_error(get_db()));
        $i++;
	}
}

//Updates notes in database
function update_notes( $email, $str )
{
    $str = mysqli_real_escape_string ( get_db() , $str );
	$q = "UPDATE notes
			SET note='$str'
			WHERE email='$email'";

	mysqli_query( get_db(), $q ) or die( mysqli_error(get_db()) );
}

//Updates TBD in database
function update_tbd( $email, $str )
{
    $str = mysqli_real_escape_string ( get_db() , $str );
	$q = "UPDATE tbd
			SET tbd='$str'
			WHERE email='$email'";
	
	mysqli_query( get_db(), $q ) or die( mysqli_error(get_db()) );
}

//Returns string if 4 images are in DB, else returns file input button
function get_image_button( $email )
{
	$q = "SELECT COUNT(*) as count
			FROM images
			WHERE email='$email'";
	$result = mysqli_query( get_db(), $q );
	$row = mysqli_fetch_assoc( $result );
	mysqli_free_result( $result );
	$count = $row['count'];
	if( $count == 4 )
	{
		return "You have uploaded the maximum number of images. Delete some to upload more";
	}
	else
	{
		return '<input type="file" name="i"><br><br>';
	}
}

//Returns note in DB
function get_notes( $email )
{
	$q = "SELECT note
			FROM notes
			WHERE email = '$email'";
	$result = mysqli_query( get_db(), $q );
	$row = mysqli_fetch_assoc( $result );
	mysqli_free_result( $result );
	return ( $row['note'] );
}

//returns TBD in DB
function get_tbd( $email )
{
	$q = "SELECT tbd
			FROM tbd
			WHERE email = '$email'";
	$result = mysqli_query( get_db(), $q );
	$row = mysqli_fetch_assoc( $result );
	mysqli_free_result( $result );
	return ( $row['tbd'] );
}

//returns websites in DB as text boxes
function get_websites( $email )
{
	$str = "";
	$q = "SELECT site
			FROM sites
			WHERE email = '$email'";
	$result = mysqli_query( get_db(), $q );
	while($row = mysqli_fetch_assoc($result))
	{
		$str = $str."<input type='text' name='websites[]' value='".$row['site']."'><br>";
	}
	return $str;
}

//Checks if passed strings contains tags and kills script
function check_injection( $str )
{
	if( $str != strip_tags($str)) die("NOPE!");
}

//Starts database connection
function get_db()
{	
	global $db;
	if( $db === false )
	{
		$db = mysqli_connect( DB_HOST, DB_USER, DB_PASS, DB_NAME) or 
			die(mysqli_connect_error());
	}
	return $db;
}

//Returns associative array for given user
function get_user( $email )
{
	$q = "SELECT * FROM users WHERE email = '$email'";
	$result = mysqli_query( get_db(), $q );
	$row = mysqli_fetch_assoc( $result );
	mysqli_free_result( $result );
	return( $row );
}

//Adds given user to database
function add_user( $email, $pass )
{
	$pass = hash_with_salt( $pass );
	$q = "INSERT INTO users ( email, password )
			VALUES ( '$email', '$pass' )";
	mysqli_query(get_db(), $q) or die(mysqli_error(get_db()));
}

//Activates given user
function activate_user( $email )
{
	$q = "UPDATE users
			SET active=1
			WHERE email='$email'";
	mysqli_query(get_db(), $q) or die(mysqli_error(get_db()));

    $q = "INSERT INTO notes( email, note )
          VALUES( '$email', '' )";
    mysqli_query(get_db(), $q) or die(mysqli_error(get_db()));

    $q = "INSERT INTO tbd( email, tbd )
          VALUES( '$email', '' )";
    mysqli_query(get_db(), $q) or die(mysqli_error(get_db()));
}

//handles incorrect password entry
function incorrect_pass( $email )
{	
	$user = get_user( $email );
	$attempts = $user['attempts'];
	set_attempts( $email, ++$attempts );
	reset_password( $email );
}

//sets number of login attempts
function set_attempts( $email, $attempts )
{
	$q = "UPDATE users
			SET attempts=$attempts
			WHERE email='$email'";
	mysqli_query(get_db(), $q) or die(mysqli_error(get_db()));
    if( $attempts==3 )
    {
        kill_session();
        die( "This account has been locked" );
    }

}

//resets password and sends email
function reset_password( $email ) 
{
    $newPass = uniqid("", true);
    $body = "Your account has requested a password reset \nNew Password: $newPass\nIf you did not request a reset then a break-in attempt was detected on your account";
	$newPass = hash_with_salt($newPass);
	$q = "UPDATE users
			SET password = '$newPass'
			WHERE email = '$email'";
	mysqli_query(get_db(), $q) or die(mysqli_error(get_db()));
	//mail($email, "Note Clone Account Password Reset", $body);
}

function kill_session()
{
    $files = glob('temp/'.session_id().'/*'); // get all file names
    foreach($files as $file){ // iterate files
        if(is_file($file))
            unlink($file); // delete file
    }
	rmdir( 'temp/'.session_id() );
	if (session_status() != PHP_SESSION_NONE) 
	{
		// Unset all of the session variables.
		$_SESSION = array();

		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}

		// Finally, destroy the session.
		session_destroy();
	}
}

?>