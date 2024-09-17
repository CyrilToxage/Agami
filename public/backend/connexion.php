<?php

$localIPs = array(
    '127.0.0.1',
    '::1'
);

if (in_array($_SERVER['REMOTE_ADDR'], $localIPs)) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "agamidb";

    $conn = new mysqli($servername, $username, $password, $dbname);
} else {
    $servername = "1t09fj.myd.infomaniak.com";
    $username = "1t09fj_parker";
    $password = "P_EqZ9JfuD7";
    $dbname = "1t09fj_Agami";

    $conn = new mysqli($servername, $username, $password, $dbname);
}

?>