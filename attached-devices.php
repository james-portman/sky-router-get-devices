<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://192.168.0.1/sky_attached_devices.html");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERPWD, "admin:sky");
$output = curl_exec($ch);
$info = curl_getinfo($ch);

if (!$output || $info['http_code'] != "200") {
	header(' 500 Internal Server Error ', true, 500);
	print "500 Internal Server Error\nCurl error: ".curl_error($ch)."\nCurl returned HTTP code: ".$info['http_code']."\n";
	exit;
}
curl_close($ch);


// cut JS device list out
$cut = explode("attached_dev_list",$output,2);
$cut = $cut[1];
$cut = explode("[",$cut,2);
$cut = $cut[1];
$cut = explode("]",$cut,2);
$cut = $cut[0];

// turn into valid json
$cut = '{ "devices": ['.$cut.']}';
$cut = str_replace('mac:','"mac":',$cut);
$cut = str_replace('ipv4:','"ipv4":',$cut);
$cut = str_replace('ipv6:','"ipv6":',$cut);
$cut = str_replace('hostname:','"hostname":',$cut);
header('Content-Type: application/json');
print $cut;
