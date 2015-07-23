<?php
    /* if this is an ajax.*/
    if (is_ajax()) {
        /*if the action is set to call a method call it.*/
        if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
            $action = $_POST["action"];
            /* switch statement for calling methods.*/
            switch($action) { 
                case "getChampionID()": getChampionID(); break;
                case "getSummonerID()": getSummonerID(); break;
                case "getRanked5v5Solo()": getRanked5v5Solo(); break;
                case "getCurrentGame()": getCurrentGame();break;
                case "getStats()": getStats();break;
                case "getLeague()": getLeague(); break;
            }
        }
    }

    //Function to check if the request is an AJAX request
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /*get the summoners league that was passed in.*/
    function getLeague(){
        header("Content-type: application/json");
        $sumID = $_POST["sumID"];
        $region = $_POST["region"];
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
        $currentGameStats = file_get_contents('https://' . $region . '.api.pvp.net/api/lol/'.$region.'/v2.5/league/by-summoner/'.$sumID.'/entry?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
        $data = json_encode($currentGameStats);
        echo $data;
    }

    function getCurrentGame(){
        header("Content-type: application/json");
        $sumID = $_POST["sumID"];
        $region = $_POST["region"];
        $region2;
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
        $currentGameStats = file_get_contents('https://'.$region2.'.api.pvp.net/observer-mode/rest/consumer/getSpectatorGameInfo/' . $region . '/' . $sumID . '?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
        $data = json_encode($currentGameStats);
        echo $data;
    }

    //Function to get list of champions and their id's.
    function getChampionID(){
        header("Content-type: application/json");
        $championData = file_get_contents('https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
        $data = json_encode($championData);
        echo $data;
    }

    //Get SummonerID
    function getSummonerID(){
        header("Content-type: application/json");
        $sumName = $_POST["sumName"];
        $region = $_POST["region"];
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
        $summonerID = file_get_contents('https://'.$region2.'.api.pvp.net/api/lol/'. $region2 .'/v1.4/summoner/by-name/'. $sumName .'?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
        $data = json_encode($summonerID);
        echo $data;
    }

    /*method to get ranked stats. 
        --add option to pick what season to choose from--
        */
    function getStats(){
        header("Content-type: application/json");
        $sumID = $_POST["sumID"];
        $region = $_POST["region"];
        $seasonString = $_POST["seasonString"];

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
        $currentGameStats = file_get_contents('https://'.$region.'.api.pvp.net/api/lol/'.$region.'/v1.3/stats/by-summoner/'.$sumID.'/ranked?season='.$seasonString.'&api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
        $data = json_encode($currentGameStats);
        echo $data;
    }

    /*method to get ranked stats 
        -- I think this only returns ranked match history--*/
    function getRanked5v5Solo(){
        header("Content-type: application/json");
        $sumID = $_POST["sumID"];
        $region = $_POST["region"];
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
        $matchHistoryData = file_get_contents('https://'.$region.'.api.pvp.net/api/lol/'.$region.'/v2.2/matchhistory/' . $sumID . '?&beginIndex=0&endIndex=15&api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
        $data = json_encode($matchHistoryData);
        echo $data;
    }

?>
