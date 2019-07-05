<?php

//Units - C

date_default_timezone_set('Europe/London');

//Configure
    //DHT22
    $AdafruitDHT = 'Adafruit_Python_DHT/examples/AdafruitDHT.py 22 4';
    //BMP280
    $BMP = 'bme280.py';
    //MySQL
    $db_server = "";
    $db_username = "";
    $db_password = "";
    $db_name = "";
        //Tables
        $realtime_table = "Realtime";
        $monthly_table = "Monthly";
	$dayfile_table = "Dayfile";
    //Offset
    $offset_temp_up = '0.0';
    $offset_temp_down = '0.0';
    $offset_hum_up = '0';
    $offset_hum_down = '0';
    $offset_press_up = '0.0';
    $offset_press_down = '0.0';

//Getting the Data
$datetime = date('Y-m-d H:i:s');

$dht22 = escapeshellcmd($AdafruitDHT);
$dht22 = shell_exec($dht22);
$bmp280 = escapeshellcmd($BMP);
$bmp280 = shell_exec($bmp280);
// Strip out Temperature
$temp = trim(substr($dht22, strpos($dht22, 'Temp=') + 5));
$temp = substr($temp, 0, strpos($temp, '*'));
$temp = $temp + $offset_temp_up - $offset_temp_down;
// Strip out Humidity
$hum = trim (substr($dht22, strpos($dht22, 'Humidity=') + 9));
$hum = substr($hum, 0, strpos($hum, '%'));
$hum = round($hum) + $offset_hum_up - $offset_hum_down;
// Strip out Pressure
$press = trim(substr($bmp280, strpos($bmp280, 'Pressure : ') +11));
$press = substr($press, 0, strpos($press, ' hPa'));
$press = round($press, 1) + $offset_press_up - $offset_press_down;

//MySQL
// Create connection
$conn = new mysqli($db_server, $db_username, $db_password, $db_name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Realtime
$realtime = "INSERT INTO $realtime_table (`LogDateTime`, `temp`, `hum`, `press`) VALUES ('$datetime', '$temp', '$hum', '$press');";
//Monthly
$monthly = "INSERT INTO $monthly_table (`LogDateTime`, `Temp`, `Humidity`, `Pressure`) VALUES ('$datetime', '$temp', '$hum', '$press');";

if ($conn->multi_query($realtime) === TRUE) {
    echo "New record for Realtime. ";
} else {
    echo "Error: " . $realtime . $conn->error;
}

if ($conn->multi_query($monthly) === TRUE) {
    echo "New record for Monthly. ";
} else {
    echo "Error: " . $monthly . $conn->error;
}

$conn->close();
