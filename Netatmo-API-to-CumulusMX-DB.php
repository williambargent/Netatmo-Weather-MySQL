<?php

//Units - C, MPH, MM, MB

date_default_timezone_set('Europe/London');

//Configure
    //Netatmo API
    $client_id = '';
    $client_secret = '';
    $username = '';
    $password = '';
    $device_id = '';
    //MySQL
    $db_server = "";
    $db_username = "";
    $db_password = "";
    $db_name = "";
        //Tables
        $realtime_table = "Realtime";
        $monthly_table = "Monthly";
        $dayfile_table = "Dayfile";

    //Netatmo API
    $scope = 'read_station';
    $token_url = "https://api.netatmo.com/oauth2/token";
    $postdata = http_build_query(
        array(
            'grant_type' => "password",
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'username' => $username,
            'password' => $password,
            'scope' => $scope
        )
    );

    $opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
    );

    $context  = stream_context_create($opts);

    $response = file_get_contents($token_url, false, $context);
    $params = null;
    $params = json_decode($response, true);

    $api_url = "https://api.netatmo.com/api/getstationsdata?access_token=" . $params['access_token'] . "&device_id=" . $device_id;

    $data = (array) json_decode(file_get_contents($api_url));
    $data = (array) $data['body'];
    $data = (array) $data['devices'][0];
    $in = (array) $data;
    $data = (array) $data['modules'];
    
    $rain = (array) $data[0];
    $wind = (array) $data[1];
    $out = (array) $data[2]; 

    $datetime = date('Y-m-d H:i:s');
    $intemp = $in['dashboard_data']->Temperature;
    $inhum =  $in['dashboard_data']->Humidity;
    $press = $in['dashboard_data']->Pressure;
    // If Outside module not reporting
    if (isset($out['dashboard_data'])) {
        $temp = $out['dashboard_data']->Temperature;
	$hum =  $out['dashboard_data']->Humidity;
    } else {
	$temp = '0';
	$hum = '0';
    }
    // If Wind module not reporting
    if (isset($wind['dashboard_data'])) {
        $wspeed = ($wind['dashboard_data']->WindStrength * 0.6214);
	$bearing = $wind['dashboard_data']->WindAngle;
	$wgust = ($wind['dashboard_data']->GustStrength * 0.6214);
	// Wind bearing to direction
	if ($bearing<=22.5) { $currentwdir="N"; }
        elseif ($bearing<=67.5) { $currentwdir="NE"; }
        elseif ($bearing<=112.5) { $currentwdir="E"; }
        elseif ($bearing<=157.5) { $currentwdir="SE"; }
        elseif ($bearing<=202.5) { $currentwdir="S"; }
        elseif ($bearing<=247.5) { $currentwdir="SW"; }
        elseif ($bearing<=292.5) { $currentwdir="W"; }
        elseif ($bearing<=337.5) { $currentwdir="NW"; }
	else { $currentwdir="N"; }
	// Wind speed to beaufort number
	if ($wspeed<1) { $beaufortnumber = "0"; }
	elseif ($wspeed<=3) { $beaufortnumber = "1"; }
	elseif ($wspeed<=7) { $beaufortnumber = "2"; }
        elseif ($wspeed<=12) { $beaufortnumber = "3"; }
        elseif ($wspeed<=18) { $beaufortnumber = "4"; }
        elseif ($wspeed<=24) { $beaufortnumber = "5"; }
        elseif ($wspeed<=31) { $beaufortnumber = "6"; }
        elseif ($wspeed<=38) { $beaufortnumber = "7"; }
        elseif ($wspeed<=46) { $beaufortnumber = "8"; }
        elseif ($wspeed<=54) { $beaufortnumber = "9"; }
        elseif ($wspeed<=63) { $beaufortnumber = "10"; }
        elseif ($wspeed<=72) { $beaufortnumber = "11"; }
	else { $beaufortnumber = "12"; }
    } else {
        $wspeed = '0';
	$bearing = '0';
	$wgust = '0';
	$currentwdir = 'N';
	$beaufortnumber = '0';
    }	
    // If Rain Module not reporting
    if (isset($rain['dashboard_data'])) {
        $rrate = $rain['dashboard_data']->sum_rain_1;
        $rfall = $rain['dashboard_data']->sum_rain_24;
    } else {
        $rrate = '0';
	$rfall = '0';
    }
    // If Outside and Wind Modules not reporting
    if (isset($out['dashboard_data']) && isset($wind['dashboard_data'])) {	   
        $apptemp = ($hum / 100) * 6.105 * pow(2.71828, ((17.27 * $temp) / (237.7 + $temp)));
	$apptemp = round(($temp + 0.33 * $apptemp - 0.7 * $wspeed - 4), 1);
	$wchill = round((13.12 + 0.6215 * $apptemp - 11.37 * pow(($wspeed / 0.6214),0.16) + 0.3965 * $apptemp * pow(($wspeed / 0.6214),0.16)), 1);
    } else {
	$apptemp = '0';
	$wchill = '0';
    }

//MySQL
// Create connection
$conn = new mysqli($db_server, $db_username, $db_password, $db_name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Realtime
$realtime = "INSERT INTO $realtime_table (`LogDateTime`, `temp`, `hum`, `wspeed`, `wlatest`, `bearing`, `rrate`, `rfall`, `press`, `currentwdir`, `beaufortnumber`, `intemp`, `inhum`, `wchill`, `wgust`, `apptemp`) VALUES ('$datetime', '$temp', '$hum', '$wspeed', '$wspeed', '$bearing', '$rrate', '$rfall', '$press', '$currentwdir', '$beaufortnumber', '$intemp', '$inhum', '$wchill', '$wgust', '$apptemp');";
//Monthly
$monthly = "INSERT INTO $monthly_table (`LogDateTime`, `Temp`, `Humidity`, `Windspeed`, `Windgust`, `Windbearing`, `RainRate`, `TodayRainSoFar`, `Pressure`, `InsideTemp`, `InsideHumidity`, `LatestWindGust`, `WindChill`, `ApparentTemp`, `CurrWindBearing`, `RainSinceMidnight`, `WindbearingSym`, `CurrWindBearingSym`) VALUES ('$datetime', '$temp', '$hum', '$wspeed', '$wgust', '$bearing', '$rrate', '$rfall', '$press', '$intemp', '$inhum', '$wgust', '$wchill', '$apptemp', '$bearing', '$rfall', '$currentwdir', '$currentwdir');";

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
