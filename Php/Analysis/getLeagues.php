<?php
    header("Content-type: application/json");
    $sumIDs = $_GET["sumIDs"];
    $region = $_GET["region"];
    switch($region) { //Switch case for value of action
        case "NA1": $region = "na"; break;
        case "BR1": $region = "br"; break;
        case "EUW1": $region = "euw"; break;
        case "EUN1": $region = "eune"; break;
        case "KR": $region = "kr"; break;
        case "LA1": $region = "lan"; break;
        case "LA2": $region = "las"; break;
        case "OC1": $region = "oce"; break;
        case "RU": $region = "ru"; break;
        case "TR1": $region = "tr"; break;
    }
    $currentGameStats = file_get_contents('https://'.$region.'.api.pvp.net/api/lol/'.$region.'/v2.5/league/by-summoner/'.$sumIDs.'/entry?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
    $data = $currentGameStats;
    echo $data;
?>