<?php
header("Access-Control-Allow-Origin: *");

$latitude1 = $_POST['latitude1'];
$longitude1 = $_POST['longitude1'];
$latitude2 = $_POST['latitude2'];
$longitude2 = $_POST['longitude2'];
$format = $_POST['format'];

//params to send to the API
$params = array(
    'api_key' => '5a9ff9afbf3deba6741621c4f543dee5',
    'method' => 'flickr.photos.search',
    'bbox' => "$longitude1,$latitude1,$longitude2,$latitude2",
    'extras' => 'geo',
    'has_geo' => '1', // just geotagged photos
    'per_page' => '20',
    'page' => '1',
    'format' => 'json',
    'nojsoncallback' => '1',
);

$encoded_params = array();
foreach ($params as $k => $v){
    $encoded_params[] = urlencode($k).'='.urlencode($v);
}
$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params); // add params to URL

$rsp = file_get_contents($url); // response from server

$rsp = str_replace( 'jsonFlickrApi(', '', $rsp );
$rsp = substr( $rsp, 0, strlen( $rsp ) );

$rsp2 = json_decode($rsp, true);

//  echo '<pre>';
//  var_dump($rsp2);
//  echo '</pre>';

$photos = $rsp2['photos']['photo'];

//output photos either as pictures or as JSON
foreach($photos as $p){
    $imgsrc = 'https://farm'.$p["farm"].'.staticflickr.com/'.$p["server"] . '/'.$p["id"].'_'.$p["secret"].'.jpg';
    $output = '<div style="margin: 15px; background-color:lightgray; padding:15px; display: inline-block;">';
    if($format == 'photos'){
        $output .= "<img src='$imgsrc' >";
    }
    else if($format == 'JSON'){
        $output .= "$imgsrc<br><pre>" . print_r($p, true) . "</pre>";
    }
    else $output .= "Error: unknown format: $format";
    $output .= '</div>';
    echo $output;
}


?>