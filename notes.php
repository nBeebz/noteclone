<?php
	require_once( 'functions.php' );
	if ( !isset( $_SESSION['user'] ) )
	{
		header('Location: index.html');
	}
	elseif( isset( $_SESSION['logintime'] ) )
	{
		if( ((time() - $_SESSION['logintime'])/60) > 20 )
		{
			header('Location: logout.php');
		}
	}
	else
	{
		$_SESSION['logintime'] = time();
	}
	
	$email = $_SESSION['user'];
	if( isset( $_POST['notes'] ) )
	{
		update_notes( $email, $_POST['notes'] );
	}
	if( isset( $_POST['tbd'] ) )
	{
		update_tbd( $email, $_POST['tbd'] );
	}
	if( isset( $_POST['websites'] ) )
	{
		update_websites( $email, $_POST['websites'] );
	}
	if( isset( $_FILES['i'] ) )
	{
		update_images( $email, $_FILES['i'] );
	}
    if( !empty($_POST['delete']) )
    {
        remove_images_from_db( $email, $_POST['delete'] );
    }
	
	

?>


<html><head>
<title>Note-to-myself : notes</title>
<link rel="shortcut icon" href="pencil.ico">
<script type="text/javascript">
	function openInNew(textbox){
		window.open(textbox.value);
		this.blur();
	}
</script>


<link href="css/notes.css" rel="stylesheet" type="text/css" media="screen">



</head>
<body>

<div id="wrapper">
<form action="notes.php" enctype="multipart/form-data" method="post">
    <h2 id="header"><?php echo $_SESSION['user']; ?><span><a href="logout.php">Log out</a></span></h2>
	

    <div id="section1">

        <div id="column1">
			<h2>notes</h2>
           <textarea cols="16" rows="40" id="notes" name="notes"><?php echo get_notes( $_SESSION['user'] ); ?></textarea>
        </div>

        <div id="column2">
			<h2>websites</h2><h3>click to open</h3>

			<?php echo get_websites( $_SESSION['user'] ) ?>
           <input type="text" name="websites[]"><br>
           <input type="text" name="websites[]"><br>
           <input type="text" name="websites[]"><br>
           <input type="text" name="websites[]"><br>

        </div>

    </div>

    <div id="section2">

        <div id="column3">
		   <h2>images</h2><h3>click for full size</h3>
          <!-- <textarea cols="16" rows="40" id="image" name="image" /></textarea> -->
					<?php echo get_image_button( $_SESSION['user'] ); ?>


					<div>
						<?php echo get_images( $_SESSION['user'] ); ?>
					</div>



        </div>

        <div id="column4">
			<h2>tbd</h2>
           <textarea cols="16" rows="40" id="tbd" name="tbd"><?php echo get_tbd( $_SESSION['user'] ); ?></textarea>
        </div>

    </div>

    <div id="footer">
      <input type="submit" value="Save" style="width:200px;height:80px" name="submitting">
    </div>

</form></div>


</body></html>