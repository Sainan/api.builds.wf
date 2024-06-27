<?php
require "common.php";

$response = file_get_contents("https://".$platform_api[$platform]."/dynamic/getProfileViewingData.php?n=".strtolower($_GET["account"]));

header("Content-Type: application/json");
echo json_encode(@json_decode($response, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
