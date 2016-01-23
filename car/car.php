<?php include('/../mysql_include.php'); ?>

<?php

if (isset($_POST['SubmitMonThu'])||isset($_POST['SubmitTueFri'])){

$lat=round($_POST['form_lat'],8);
$lng=round($_POST['form_lng'],8);
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

$lat=0; $lng=0;
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
<head>
<title>Where's the car?</title>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<style>
html, body, #map-canvas {
    height: 100%;
    margin: 0px;
    padding: 0px
}
</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3"></script>
<script type="text/javascript">
var marker;
var infowindow;

function initialize() {
  var mapOptions = {
    zoom: 16,
    center: new google.maps.LatLng(<?php echo $lat;?>,<?php echo $lng;?>),
    disableDefaultUI: true,
    styles: [{ featureType: "poi", elementType: "labels", stylers: [{ visibility: "off" }]}],
    draggableCursor: 'auto',
    draggingCursor: 'move'
  };

  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var markerHome = new google.maps.Marker({
      position: new google.maps.LatLng(40.792569,-73.966108),
      map: map,
      title: 'Home',
      icon: {url: 'house.png', scaledSize: new google.maps.Size(40,40), anchor: new google.maps.Point(20,30)}
  });

  var markerActive = new google.maps.Marker({
      position: new google.maps.LatLng(<?php echo $lat;?>,<?php echo $lng;?>),
      map: map,
      title: 'The Car!',
      icon: {url: 'car.png', scaledSize: new google.maps.Size(40,40), anchor: new google.maps.Point(20,30)}
  });

  var infowindowActive = new google.maps.InfoWindow({
      content: '<div id="content2">Sign says: No Parking on <strong><?php echo $sign; ?></strong></div>'
  });

  google.maps.event.addListener(markerActive, 'click', function(event) {
    infowindowActive.open(map,markerActive);
  });


  var contentString = '<div id="content">Sign says: <strong>No Parking</strong> on'+
      '<form name="pointform" method="post">'+
      '<input type="hidden" name="who" value="<?=$_GET['who']?>"/>'+
      '<input type="hidden" name="form_lat" />'+
      '<input type="hidden" name="form_lng" />'+
      '<input type="hidden" name="form_sign" />'+
      '<input type="submit" name="SubmitMonThu" value="Mon + Thu" onclick="javascript:document.pointform.form_sign.value=\'MonThu\';"/>'+
      '<input type="submit" name="SubmitTueFri" value="Tue + Fri" onclick="javascript:document.pointform.form_sign.value=\'TueFri\';"/>'+
      '</form>'+
      '</div>';

  var infowindow = new google.maps.InfoWindow({
      content: contentString
  });

  google.maps.event.addListener(map, 'click', function(event) {
    placeMarker(map,event.latLng);
    infowindow.open(map,marker);
    document.pointform.form_lat.value=event.latLng.lat();
    document.pointform.form_lng.value=event.latLng.lng();
  });

}


function placeMarker(map,latLng) {
  if (marker == undefined){
    marker = new google.maps.Marker({
      position: latLng,
      map: map,
      animation: google.maps.Animation.DROP,
      icon: {url: 'spongebob.png', scaledSize: new google.maps.Size(60,60), anchor: new google.maps.Point(30,50)}
    });
  } else {
    marker.setPosition(latLng);
  }
}

google.maps.event.addDomListener(window, 'load', initialize);


</script>
</head>
<body>
<div id="map-canvas"></div>
</body>
</html>