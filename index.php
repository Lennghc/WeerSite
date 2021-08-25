<?php
//ini_set('display_errors', 1); -- Debug

include('database/config.php');
include('database/opendb.php');

//Check if we have a get variable place on our end

if ( !isset($_GET['city']) || empty($_GET['city']) ){
    $city = "Nieuwegein";
} else {
    $city = ucwords($_GET['city']);
}

if ( isset($_GET['error']) ){
    switch ($_GET['error']) {
        case 404:
            $error = "<strong style='font-size: 50px;'>404</strong><br><br><center>Plaats niet gevonden...</center><br><br>";
            break;
        
        default:
            $error = "Fout";
            break;
    }
}

//Get the realtime data from our weather server or database cache

if ( !isset($error) ) {
    $query  = "SELECT * ";
    $query .= "FROM data ";
    $query .= "WHERE plaats = ?";

    $preparedquery = $dbaselink->prepare($query);
    $preparedquery->bind_param("s", $city);
    $preparedquery->execute();

    if ($preparedquery->errno) {
        //Fout bij uitvoeren commando;
    } else {
        $result = $preparedquery->get_result();

        if($result->num_rows === 0) { //City not in our database, so we insert it

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

            if ( $data->plaats == $city ) {

                $id = 0;
                $city = ucwords($data->plaats);
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
        
                $query ="INSERT INTO data ";
                $query .= "(id, plaats, temp, gtemp, windr, windms, winds, windbft, windknp, windk, windkmh, luchtd, dauwp, zicht, d0tmin, d0tmax, d0neerslag, sup, sunder) ";
                $query .="VALUES (?, ?, ?, ?, ?, ? ,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
        
                $preparedquery = $dbaselink->prepare($query);
                $preparedquery->bind_param("isiiiiiiiiiiiiiiiss", $id, $city, $temp, $gtemp, $windr, $windms, $winds, $windbft, $windknp, $windk, $windkmh, $luchtd, $dauwp, $zicht, $d0tmax, $d0tmin, $d0neerslag, $sup, $sunder);
                $result = $preparedquery->execute();
        
                if (($result===false) || ($preparedquery->errno)) {
                    //Oops, fout;
                } else {
                    //Plaats is toegevoegd;
                }
            } else {
                $preparedquery->close();
                include('database/closedb.php');
                header("Location: /index.php?error=404");
            }

        } else {
            $row = $result->fetch_assoc();

            $city = $row['plaats'];
            $temp = $row['temp'];
            $gtemp = $row['gtemp'];
            $windr = $row['windr'];
            $windms = $row['windms'];
            $winds = $row['winds'];
            $windbft = $row['windbft'];
            $windknp = $row['windknp'];
            $windk = $row['windk'];
            $windkmh = $row['windkmh'];
            $luchtd = $row['luchtd'];
            $dauwp = $row['dauwp'];
            $zicht = $row['zicht'];
            $d0tmax = $row['d0tmax'];
            $d0tmin = $row['d0tmin'];
            $d0neerslag = $row['d0neerslag'];
            $sup = $row['sup'];
            $sunder = $row['sunder'];
        }
    }

    $preparedquery->close();
}

//Preprare out variables

$city = urldecode($city);

//Functions

function convert2cen($value, $unit)
{
    if ($unit == 'C') {
        return $value;
    } elseif ($unit == 'F') {
        $cen = ($value - 32) / 1.8;
        return round($cen, 2);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weer-Nu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style/style.css"/>
</head>
<body>
    <script>
      window.Promise ||
        document.write(
          '<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"><\/script>'
        )
      window.Promise ||
        document.write(
          '<script src="https://cdn.jsdelivr.net/npm/eligrey-classlist-js-polyfill@1.2.20171210/classList.min.js"><\/script>'
        )
      window.Promise ||
        document.write(
          '<script src="https://cdn.jsdelivr.net/npm/findindex_polyfill_mdn"><\/script>'
        )
    </script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <nav class="navbar navbar-light bg-light navbar-expand-lg">
        <a class="navbar-brand  mb-0 h1" href="/">Weer-Nu</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <form action="index.php" class="form-inline my-2 my-lg-0" method="get">
                <input class="form-control mr-sm-2" name="city" type="search" required placeholder="Plaats">
                <button class="btn btn-primary my-2 my-sm-0" type="submit">Zoeken</button>
            </form>
        </div>
      </nav>
    <div class="container py-5">
        <h3 class="title text-center mb-4">
            <?php echo isset($error) ?  $error : "Weersvoorspelling van: " . ucfirst($city);?>
        </h3>
<?php 
        if (!isset($error)) {
?>
        <h1 class="font-weight-bold mb-4"
            <?php 
                if( $temp >= 20 ) {
                    echo " style='color: #32CD32'"; //20 Degrees C
                } else {
                    if ( $temp >= 10 ) { //10 Degrees C
                        echo " style='color: #FFA500'";
                    } else { //lower than 10 C
                        echo " style='color: #FF4500'";
                    }
                }
                echo ">" . $temp;
            ?> &#176;C
        </h1>

        <div class="row">
            <div class="col-md-6" >
                <div class="row text-light">

                    <div class="col-sm-12">
                        <div class="weather-icon">
                            <p>
                                <strong>Windsnelheid : </strong>
                                <?php echo $windkmh; ?> km/h
                            </p>
                            <p>
                                <strong>Zonsopkomst : </strong>
                                <?php echo $sup; ?>
                            </p>
                            <p>
                                <strong>Zonsondergang : </strong>
                                <?php echo $sunder; ?>
                            </p>
                        </div>
                    </div>

                    <div class="col-sm-12" >
                        <div class="weather-icon">
                            <p>
                                <strong>Windsnelheid in knopen : </strong>
                                <?php echo $windk; ?> knopen
                            </p>
                            <p>
                                <strong>Zicht : </strong>
                                <?php echo $zicht; ?> km
                            </p>
                            <p>
                                <strong>Windrichting : </strong>
                                <?php echo $windr; ?>
                            </p>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="weather-icon">
                            <p>
                                <strong>Min temperatuur: </strong>
                                <?php echo $d0tmin; ?> &#176;C
                            </p>
                            <p>
                                <strong>Max temperatuur: </strong>
                                <?php echo $d0tmax; ?> &#176;C
                            </p>
                            <p>
                                <strong>Gevoelstemperatuur: </strong>
                                <?php echo $gtemp; ?> &#176;C
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div id="chart"></div>
            </div>
        </div>
<?php 
        }
?>
    </div>

    <section class="p-3 mt-3" style="background-color: #5e93e3;">
        <div class="container text-center text-light">
            <p>© ALLE RECHTEN VOORBEHOUDEN</p>
        </div>
    </section>
</body>
</html>

<?php

$temp = 0;
$count = 0;
$windkmh = 0;
$zicht = 0;

$query = "SELECT temp, windkmh, zicht ";
$query.="FROM data ";

$preparedquary = $dbaselink->prepare($query);
$preparedquary->execute();

if ($preparedquary->errno) {
    echo "Fout bij uitvoeren commando";
} else {
    $result = $preparedquary->get_result();

    //check if there are no id's in the table
    if ($result->num_rows) {

        while($row=$result->fetch_assoc()){
            $temp += $row['temp'];
            $windkmh += $row['windkmh'];
            $zicht += $row['zicht'];
            $count++;
        };
    }
}

$temp = round($temp/$count);
$windkmh = round($windkmh/$count);
$zicht = round($zicht/$count);

$preparedquary->close();
include("database/closedb.php");
?>

<script>
    var temp = <?php echo $temp?>;
    var windkmh = <?php echo $windkmh;?>;
    var zicht = <?php echo $zicht;?>;


    var options = {
          series: [
          {
            name: 'Weer',
            data: [
              {
                x: 'Gemiddelde temperatuur',
                y: temp,
              },
              {
                x: 'Windsnelheid',
                y: windkmh,

              },
              {
                x: 'Zicht',
                y: zicht,

              }
            ]
          }
        ],
          chart: {
          height: 350,
          type: 'bar'
        },
        colors: ['#5e93e3'],
        dataLabels: {
          enabled: false
        },
        legend: {
          show: true,
          showForSingleSeries: true
        }
    };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>

<script>
    setInterval(() => {
        window.location.replace("/noaccess/index.php?city=" + "<?php echo $city;?>");
    }, 300000);
</script>
