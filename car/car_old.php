<?php include('/../mysql_include.php'); ?>

<?php

if ($_POST['Submit']=='Submit'){

$lat=floor($_POST['form_lat']*1e8)/1e8;
$lng=floor($_POST['form_lng']*1e8)/1e8;
$who=strtoupper($_POST['who']);
if($who=='J'){$whoname='Julie';}
else if($who=='M'){$whoname='Marc';}
else if($who=='T'){$whoname='Test';}
else{$whoname='Unknown';}
$sign=$_POST['form_sign'];

$dbx = $db->prepare("UPDATE car_location set active='N'");
$dbx->execute();
$dbx = $db->prepare("INSERT into car_location(lat,lng,who,sign,timestamp,active) values (".$lat.",".$lng.",'".$whoname."','".$sign."',now(),'Y')");
$dbx->execute();
header('Location:?who='.$who);
}





$w=640;$h=640; 
$scale=2;
$orilat=40.7929283;$orilng=-73.9679973;
$zoom=16;
if($zoom==17){$pxlat=.79275e-5;$pxlng=1.04883e-5;}
elseif($zoom==16){$pxlat=1.60772e-5;$pxlng=2.05147e-5;}
else{$pxlat=0;$pxlng=0;}

$pxlat/=$scale;
$pxlng/=$scale;

//$nwlat=$orilat+$pxlat*$h/2;$nwlng=$orilng-$pxlng*$w/2;

$result = $db->query("SELECT * FROM car_location where active='Y'");

while ($row=$result->fetch_assoc()) {
$lat=$row['lat'];$lng=$row['lng'];
$sign=$row['sign'];
}

//$lat=40.7922148;$lng=-73.9659520;
//$sign='TueFri';
if($sign=='MonThu'){$clr='blue';}
elseif($sign=='TueFri'){$clr='red';}
else{$clr='white';}

?>

<html>
<html>
<head>
<title>Where's the car?</title>
<script type="text/javascript">
function point_it(event){
    pos_x = event.offsetX?(event.offsetX):event.pageX-document.getElementById("pointer_div").offsetLeft;
    pos_y = event.offsetY?(event.offsetY):event.pageY-document.getElementById("pointer_div").offsetTop;
    document.getElementById("cross").style.left = (pos_x-10);
    document.getElementById("cross").style.top = (pos_y-100);
    document.getElementById("cross").style.visibility = "visible";
    document.pointform.form_x.value = pos_x-<?php echo $w*$scale/2;?>;
    document.pointform.form_y.value = <?php echo $h*$scale/2;?>-pos_y;
    document.pointform.form_lat.value = <?php echo $orilat;?>+(<?php echo $h*$scale/2;?>-pos_y)*<?php echo $pxlat;?>;
    document.pointform.form_lng.value = <?php echo $orilng;?>+(pos_x-<?php echo $w*$scale/2;?>)*<?php echo $pxlng;?>;
}
</script>
</head>
<body style="margin:0px;">
<span style="font-size:20px;"><span style="font-size:36px;margin-right:300px;">Where's the car?</span>
<img src="MonThu.png" style="width:20px;"/>Mondays+Thursdays&nbsp;&nbsp;&nbsp;
<img src="TueFri.png" style="width:20px;"/>Tuesdays+Fridays
</span>
<form name="pointform" method="post">
<input type="hidden" name="who" value="<?=$_GET['who']?>"/>
<div id="pointer_div" onclick="point_it(event)" 
style="background-image:url('http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $orilat;?>,<?php echo $orilng;?>&zoom=<?php echo $zoom;?>&size=<?php echo $w;?>x<?php echo $h;?>&scale=<?php echo $scale;?>&markers=color:yellow%7Clabel:H%7C40.792569,-73.966108&markers=color:<?php echo $clr;?>%7Clabel:C%7C<?php echo $lat;?>,<?php echo $lng;?>');
width:<?php echo $w*$scale;?>px;height:<?php echo $h*$scale;?>px;">
</div>
<div id="cross" style="position:absolute;visibility:hidden;z-index:2;">
<select name="form_sign" style="font-size:36px;">
<option value="MonThu">Sign says: Mondays+Thursdays</option>
<option value="TueFri">Sign says: Tuesdays+Fridays</option>
</select>
</br>
<input type="submit" name="Submit" value="Submit" style="font-size:36px;"/>
</br>
<img src="http://communiqsoft.com/images/map/PinDown1.png" style="width:40px;"/>
</div>
You pointed on x = <input type="text" name="form_x" size="4" /> - y = <input type="text" name="form_y" size="4" />
You pointed on lat = <input type="text" name="form_lat" size="4" /> - lng = <input type="text" name="form_lng" size="4" />
</form>

</body>
</html>