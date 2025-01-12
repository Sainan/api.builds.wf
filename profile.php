<?php
require "common.php";

$ch = curl_init();
curl_setopt_array($ch, [
	CURLOPT_URL => "https://".$platform_api[$platform]."/dynamic/getProfileViewingData.php?n=".strtolower($_GET["account"]),
	CURLOPT_RETURNTRANSFER => true,
]);
$response = curl_exec($ch);
curl_close($ch);

// https://content-mob.warframe.com/dynamic/getProfileViewingData.php?n=felfuri
if (substr($response, 0, 23) == "Retry with PC account: ")
{
	$platform = "pc";
	$response = file_get_contents("https://".$platform_api["pc"]."/dynamic/getProfileViewingData.php?n=".substr($response, 48));
}

header("Content-Type: application/json");
$json = @json_decode($response, true);
if (is_array($json))
{
	$json["platform"] = $platform;
}
echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
