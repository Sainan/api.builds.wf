<?php
$platform_api = [
	"pc" => "content.warframe.com",
	"ps4" => "content-ps4.warframe.com",
	"xb1" => "content-xb1.warframe.com",
	"swi" => "content-swi.warframe.com",
];

$platform = $_GET["platform"] ?? "pc";

header("Access-Control-Allow-Origin: *");

if (empty($_GET["account"])
	&& array_key_exists($platform, $platform_api)
	)
{
	http_response_code(400);
	exit;
}

$ch = curl_init("https://gql.twitch.tv/gql");
curl_setopt_array($ch, [
	CURLOPT_HTTPHEADER => [
		"Client-Id: kimne78kx3ncx6brgo4mv6wki5h1ko"
	],
	CURLOPT_POSTFIELDS => '[{"operationName":"ExtensionsForChannel","variables":{"channelID":"123858856"},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"d52085e5b03d1fc3534aa49de8f5128b2ee0f4e700f79bf3875dcb1c90947ac3"}}}]',
	CURLOPT_RETURNTRANSFER => true,
]);

$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);
// var_dump($data);

foreach ($data[0]['data']['user']['channel']['selfInstalledExtensions'] as $ext)
{
	if ($ext['token']['extensionID'] == "ud1zj704c0eb1s553jbkayvqxjft97")
	{
		$jwt = $ext['token']['jwt'];
		break;
	}
}
if (!$jwt)
{
	http_response_code(500);
	die("Failed to obtain token.");
}

$ch = curl_init("https://".$platform_api[$platform]."/dynamic/twitch/getActiveLoadout.php?account=".strtolower($_GET["account"]));
curl_setopt_array($ch, [
	CURLOPT_HTTPHEADER => [
		"Origin: https://ud1zj704c0eb1s553jbkayvqxjft97.ext-twitch.tv",
		"Referer: https://ud1zj704c0eb1s553jbkayvqxjft97.ext-twitch.tv",
		"Authorization: Bearer ".$jwt
	],
	CURLOPT_RETURNTRANSFER => true,
]);
$response = curl_exec($ch);
curl_close($ch);

header("Content-Type: application/json");
echo json_encode(json_decode($response, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
