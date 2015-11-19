<?php
    header("Content-type: application/json");
    $sumName = rawurlencode($_GET["sumName"]);
    $region = $_GET["region"];
    switch($region) { //Switch case for value of action
        case "NA1": $region2 = "na"; break;
        case "BR1": $region2 = "br"; break;
        case "EUW1": $region2 = "euw"; break;
        case "EUN1": $region2 = "eune"; break;
        case "KR": $region2 = "kr"; break;
        case "LA1": $region2 = "lan"; break;
        case "LA2": $region2 = "las"; break;
        case "OC1": $region2 = "oce"; break;
        case "RU": $region2 = "ru"; break;
        case "TR1": $region2 = "tr"; break;
    }
    $url = 'https://'.$region2.'.api.pvp.net/api/lol/'. $region2 .'/v1.4/summoner/by-name/'.$sumName.'?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9';
    $summonerID = file_get_contents($url);//'https://'.$region2.'.api.pvp.net/api/lol/'. $region2 .'/v1.4/summoner/by-name/'. $sumName .'?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
    $data = $summonerID;//json_encode($summonerID);
    echo $data;
?>