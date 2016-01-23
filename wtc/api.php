<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json");
header("HTTP/1.1 200 OK");

try {
include('../mysql_include.php');

if (!array_key_exists('authKey',$_POST)) {throw new Exception('POST variable authKey not found');}
if (!array_key_exists('verb',$_POST)) {throw new Exception('POST variable verb not found');}
if (!array_key_exists('carKey',$_POST)) {throw new Exception('POST variable carKey not found');}

if ($_POST['authKey']!='AuthKeyMJAPPS10025') {throw new Exception('Incorrect Auth Key');}

switch($_POST['verb']) {

case 'move':
$dbx = $db->prepare("UPDATE wtc_car_location set active='N' where wtc_car_key=?");
$dbx->bind_param('i',$_POST['carKey']);
$dbx->execute();
$dbx = $db->prepare("INSERT into wtc_car_location(wtc_car_key,lat,lng,who,sign,timestamp,active) values (?,?,?,?,?,now(),'Y')");
$dbx->bind_param('iddss',$_POST['carKey'],$_POST['lat'],$_POST['lng'],$_POST['who'],$_POST['sign']);
$dbx->execute();
echo json_encode(array('Status'=>'SUCCESS'));
break;

case 'retrieve':
$dbx = $db->prepare("SELECT lat,lng,who,sign,timestamp from wtc_car_location where wtc_car_key=? and active='Y'");
$dbx->bind_param('i',$_POST['carKey']);
//$dbx = $db->prepare("SELECT lat,lng,who,sign,timestamp from car_location where active='Y'");
$dbx->execute();
$dbx->store_result();
$dbx->bind_result($lat, $lng, $who, $sign, $timestamp);
$dbx->fetch();

echo json_encode(array('Status'=>'SUCCESS','rows'=>$dbx->num_rows,'lat'=>$lat,'lng'=>$lng,'who'=>$who,'sign'=>$sign,'timestamp'=>$timestamp));

break;

default:
throw new Exception('Incorrect action.');
}

} catch (Exception $e) {echo json_encode(array('Status'=>'ERROR','Message'=>$e->getMessage()));}
?>