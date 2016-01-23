<?php include('/../mysql_include.php'); ?>

<?php

$to="marc.j.hassan@gmail.com,julienuba@gmail.com";
$header="Reply-To: marc.j.hassan@gmail.com\r\n";
$header.="Return-Path: marc.j.hassan@gmail.com\r\n";
$header.="From: speechwithjulie@speechwithjulie.com <speechwithjulie@speechwithjulie.com>\r\n";
$header.="Organization: Car\r\n";
$header.="Content-Type: text/plain\r\n";

$dtoff = time()+1*24*60*60;
$dt = date('Y-m-d',$timestamp=$dtoff);

$result = $db->query("SELECT * FROM car_location a left outer join car_aspsuspended b on b.date='".$dt."' where a.active='Y'");

while ($row=$result->fetch_assoc()) {
$lat=$row['lat'];$lng=$row['lng'];
$sign=$row['sign'];
$suspdate=$row['date'];
$suspwhy=$row['why'];
}

$sendbool=0;
$todayday=date('D');

if($sign=='MonThu'){
$sign2='Monday & Thursday';
if($todayday=='Sun'||$todayday=='Wed'){$sendbool=1;}
}
elseif($sign=='TueFri'){
$sign2='Tuesday & Friday';
if($todayday=='Mon'||$todayday=='Thu'){$sendbool=1;}
}
else{}

if($sendbool==1){

if($suspdate==''){
$subject='Move the car reminder for '.date('D, Y-m-d');
$msg="Please move the car tonight! It is in a spot where the sign says $sign2 and needs to be moved before tomorrow!\n\nhttp://speechwithjulie.com/car/car.php\n\nSign: $sign\nToday: $todayday";
} else {
$subject='DON\'T move the car reminder for '.date('D, Y-m-d');
$msg="DO NOT move the car tonight because ASP is suspended tomorrow due to $suspwhy! \n\nSign: $sign\nToday: $todayday";
}

mail($to, $subject, $msg, $header);
echo("Email sent with message:\n\n$msg");

}

else{echo("No reminder email sent.\n\nSign: $sign\nToday: $todayday");}



############## ASP Suspended ##############

$dtoff = time()+2*24*60*60;
$dt = date('Y-m-d',$timestamp=$dtoff);

$result = $db->query("SELECT * FROM car_aspsuspended where date='".$dt."'");

if($result->num_rows>0){
$row=$result->fetch_assoc();

$msg = "Alternate Side Suspended on ".date('D, Y-m-d',$timestamp=$dtoff)." for ".$row['why'];

mail($to, $msg, $msg, $header);
echo("\n\nASP suspended email sent with message:\n\n$msg");
}

?>