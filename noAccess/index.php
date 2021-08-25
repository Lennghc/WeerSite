<?php

include('../database/config.php');
include('../database/opendb.php');

//Check if we have a get variable place on our end

if ( !isset($_GET['city']) ){
    $city = "Nieuwegein";
} else {
    $city = $_GET['city'];
}

//Get the realtime data from our weather server or database cache

$query  = "SELECT * ";
$query .= "FROM data ";
$query .= "WHERE plaats = ?";

$preparedquery = $dbaselink->prepare($query);
$preparedquery->bind_param("s", $city);
$preparedquery->execute();

if ($preparedquery->errno) {
    //echo "Fout bij uitvoeren commando";
} else {
  $result = $preparedquery->get_result();

  if($result->num_rows === 1) {

    $cache_file = 'jason/data.json';
    if (file_exists($cache_file)) {
        $data = json_decode(file_get_contents($cache_file));
    } else {
        $api_url = 'https://weerlive.nl/api/json-data-10min.php?key=' . $apiKey . '&locatie=' . urlencode($city);

        if ($json = file_get_contents($api_url)) {
            $decoded = json_decode($json);
            $data = $decoded->liveweer[0];
        }
    }

    if ( $data->plaats != "None" ) {

      $id = 0;
      $city = $data->plaats;
      $temp = $data->temp;
      $gtemp = $data->gtemp;
      $windr = $data->windr;
      $windms = $data->windms;
      $winds = $data->winds;
      $windbft = $data->windbft;
      $windknp = $data->windknp;
      $windk = $data->windk;
      $windkmh = $data->windkmh;
      $luchtd = $data->luchtd;
      $dauwp = $data->dauwp;
      $zicht = $data->zicht;
      $d0tmax = $data->d0tmax;
      $d0tmin = $data->d0tmin;
      $d0neerslag = $data->d0neerslag;
      $sup = $data->sup;
      $sunder = $data->sunder;

      $query = "UPDATE data ";
      $query .= "SET temp = ?, ";
      $query .= "gtemp = ?, ";
      $query .= "windr = ?, ";
      $query .= "windms = ?, ";
      $query .= "winds = ?, ";
      $query .= "windbft = ?, ";
      $query .= "windknp = ?, ";
      $query .= "windk = ?, ";
      $query .= "windkmh = ?, ";
      $query .= "luchtd = ?, ";
      $query .= "dauwp = ?, ";
      $query .= "zicht = ?, ";
      $query .= "d0tmin = ?, ";
      $query .= "d0tmax = ?, ";
      $query .= "d0neerslag = ?, ";
      $query .= "sup = ?, ";
      $query .= "sunder = ? ";
      $query .= "WHERE plaats = ? ";

      $preparedquery = $dbaselink->prepare($query);
      $preparedquery->bind_param("iiiiiiiiiiiiiiisss", $temp, $gtemp, $windr, $windms, $winds, $windbft, $windknp, $windk, $windkmh, $luchtd, $dauwp, $zicht, $d0tmax, $d0tmin, $d0neerslag, $sup, $sunder, $city);
      $result = $preparedquery->execute();

      if (($result===false) || ($preparedquery->errno)) {
        echo "Oops, fout";
      } else {
        $preparedquery->close();
        include("../database/closedb.php");
        header("Location: /index.php?city=" . $city);
        exit;
      }
    }
  }
}

$preparedquery->close();
include("../database/closedb.php");
header("Location: /index.php?city=" . $city);
exit;
?>




