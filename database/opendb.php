<?php

$dbaselink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die ("error met verbinding maken" . mysqli_connect_error());
    set_time_limit(60);
?>