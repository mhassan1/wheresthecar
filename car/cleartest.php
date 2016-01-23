<?php include('/../mysql_include.php'); ?>

<?php

$dbx = $db->prepare("DELETE from car_location where who is null or who='' or who='Test'");
$dbx->execute();

?>