<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Saving Upload</title>
</head>
<body>

<?php
$name = $_FILES['any_file']['name'];
echo "Name: $name<br />";

$size = $_FILES['any_file']['size'];
echo "Size: $size<br />";

$type = $_FILES['any_file']['type'];
echo "Type: $type<br >";

$tmp_name = $_FILES['any_file']['tmp_name'];
echo "Tmp Name: $tmp_name<br />";

// use the session id to create unique name
session_start();
$final_name = session_id() . "-" . $name;

// move from cache to the lesson10/uploads folder
move_uploaded_file($tmp_name, "uploads/$final_name");


?>

</body>
</html>
