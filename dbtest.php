<?php
	require_once('')
// Create connection
$con=mysqli_connect("localhost","Nav","navb5218","noteclone");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$result = mysqli_query($con, $query);

while($row = mysqli_fetch_assoc($result)){
	echo "ID: ".$row['ID'].PHP_EOL;
	echo "TEXT: ".$row['Text'].PHP_EOL;
	echo PHP_EOL;
}

mysqli_close($con);
?>