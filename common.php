<?php
$platform_api = [
	"pc" => "content.warframe.com",
	"ps4" => "content-ps4.warframe.com",
	"xb1" => "content-xb1.warframe.com",
	"swi" => "content-swi.warframe.com",
	"mob" => "content-mob.warframe.com",
];

$platform = $_GET["platform"] ?? "pc";

header("Access-Control-Allow-Origin: *");

if (empty($_GET["account"])
	|| !array_key_exists($platform, $platform_api)
	)
{
	http_response_code(400);
	exit;
}
