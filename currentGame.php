<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Cuddly Assassin</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/leagueoflegendsmain.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="/js/stats.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/jquery.powertip.css">
    <script src="./js/jquery.powertip.js"></script>
</head>

<!--Create a Summoner Class and load all the information into a summoner !-->
<?php
    include "classes.inc";
    //SUPER DUPER GLOBAL DATA DRAGON VARS
    //Data dragon urls
    $dataDragonVersion = "5.14.1";
    $dataDragonChampionURL = 'http://ddragon.leagueoflegends.com/cdn/'.$dataDragonVersion.'/img/champion/';
    $dataDragonItemURL = "http://ddragon.leagueoflegends.com/cdn/".$dataDragonVersion."/img/item/";

    $summoners = array(
        new Summoner(),
        new Summoner(),
        new Summoner(),
        new Summoner(),
        new Summoner(),
        new Summoner(),
        new Summoner(),
        new Summoner(),
        new Summoner(),
        new Summoner(),
        );

    //Get current Game things.
    //Need to get the current game and stuff from the summoners name
    $sumName = $_GET['sumName'];
    $urlEncodedSumName = rawurlencode($sumName);
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
    $summonerInfo = file_get_contents('https://'.$region2.'.api.pvp.net/api/lol/'. $region2 .'/v1.4/summoner/by-name/'. $urlEncodedSumName .'?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
    $summonerInfoJSON = json_decode($summonerInfo, true);
    $urlEncodedSumNameKey = strtolower($urlEncodedSumName);
    $urlEncodedSumNameKey = str_replace('%20', '', $urlEncodedSumNameKey);
    $sumID = $summonerInfoJSON[$urlEncodedSumNameKey]['id'];
    $sumName = $summonerInfoJSON[$urlEncodedSumNameKey]['name'];
    $sumLevel = $summonerInfoJSON[$urlEncodedSumNameKey]['summonerLevel'];

    //Get current game
    $currentGameStats = file_get_contents('https://'.$region2.'.api.pvp.net/observer-mode/rest/consumer/getSpectatorGameInfo/NA1/'.$sumID.'?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
    $CGJSON = json_decode($currentGameStats);

    //Get a Fuck ton of champion data.
    //Get all champion data needed for webpage
    $championData = file_get_contents('https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?champData=all&api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');
    $championData = json_decode($championData, true);


    if($CGJSON != false){
        //Get league data from all summmoners.
        for($index=0; $index<10; $index++){
            $csvSumNames = $csvSumNames.$CGJSON->participants[$index]->summonerId.",";
        }
        $league = file_get_contents('https://' . $region2 . '.api.pvp.net/api/lol/'.$region2.'/v2.5/league/by-summoner/'.$csvSumNames.'/entry?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
        $league = json_decode($league, true);
        for($index = 0; $index < 10; $index++){

            $summoners[$index]->setSumName($CGJSON->participants[$index]->summonerName);
            $summoners[$index]->setSumID($CGJSON->participants[$index]->summonerId);
            //Set Champion Stuff.
            $summoners[$index]->setChampionID($CGJSON->participants[$index]->championId);
            $summoners[$index]->setChampionName($championData["keys"][$summoners[$index]->getChampionID()]);
            $seasonString = "SEASON2015";
            $sumChampStats = file_get_contents('https://'.$region2.'.api.pvp.net/api/lol/'.$region2.'/v1.3/stats/by-summoner/'.$summoners[$index]->getSumID().'/ranked?season='.$seasonString.'&api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
            $sumChampStats = json_decode($sumChampStats, true);
            $champions = $sumChampStats["champions"];
            
            $summoners[$index]->setSummonerRankedChampionStatsJSON($sumChampStats);
            
            foreach($champions as $item){
                if($item["id"] == $championID){
                    sleep(1);
                    $summoners[$index]->setChampionGames($item["stats"]["totalSessionsPlayed"]);
                    $summoners[$index]->setChampionKills($item["stats"]["totalChampionKills"]);
                    $summoners[$index]->setChampionDeaths($item["stats"]["totalDeathsPerSession"]);
                    $summoners[$index]->setChampionAssists($item["stats"]["totalAssists"]);
                    $summoners[$index]->setChampionWins($item["stats"]["totalSessionsWon"]);
                    $summoners[$index]->setChampionLosses($item["stats"]["totalSessionsLost"]);
                    break;
                }else{
                    $numberOfGames = 0;
                }
            }
            //Set Sum Spells
            $summoners[$index]->setSpell1($CGJSON->participants[$index]->spell1Id);
            $summoners[$index]->setspell2($CGJSON->participants[$index]->spell2Id);

            //Set Ranked stuff
            $tier = $league[$summonerID][0]["tier"];
            $division = $league[$summonerID][0]["entries"][0]["division"];
            $points = $league[$summonerID][0]["entries"][0]["leaguePoints"];
            $wins = $league[$summonerID][0]["entries"][0]["wins"];
            $losses = $league[$summonerID][0]["entries"][0]["losses"];
                    
            $summoners[$index]->setTier($tier);
            $summoners[$index]->setDivision($division);
            $summoners[$index]->setPoints($points);
            $summoners[$index]->setRankedWins($wins);
            $summoners[$index]->setRankedLosses($losses);
        }
    }
?>

<body>
    <!--Navigation bar !-->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">LOL Statistics Made Ez(real)</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Current Game</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <img id="loadingGIF" src="/images/loading.GIF" style="position:absolute; top:50%; left:50%; margin:auto; margin-left:-64px; margin-top:-19px; z-index:10;">

    <!-- Current game stats from summoners !-->
    <div id="summonersTable" class="currentGameDiv">
        <!--php script to get the html for the table!-->
        <?php
            //var_dump($CGJSON);
            if($CGJSON === NULL){
                echo('<div id="summonerNotInGame" class="errorDiv"> Summoner is not in game</div>');
            } else{
                echo('<table id="Team1" class="teamTable">');
                echo('  <tr class="headers">');
                echo(       '<th style="width:20%">Name</th>');
                echo(       '<th style="width:17%">Champion(games)</th>');
                echo(       '<th style="width:8%">SS</th>');
                echo(       '<th style="width:22%">KDA</th>');
                echo(       '<th style="width:25%">Rank(Lp)</th>');
                echo(       '<th style="width:8%">Rank W/L</th>');
                echo(   '</tr>');
                echo(   '<tr>');
                echo(       '<td colspan="6">');
                echo(           '<div id="team1Banner" class="team1Banner"></div>');
                echo(       '</td>');
                echo(   '</tr>');
                for($index = 0; $index<5; $index++){
                    $evenOdd = "oddTeam";
                    if($index % 2 == 0){
                        $evenOdd = "evenTeam";
                    }
                    $championName = $summoners[$index]->getChampionName();

                    $spell1 = $CGJSON->participants[$index]->spell1Id;
                    $spell2 = $CGJSON->participants[$index]->spell2Id;
                    $numberOfGames = 0;
                    $kills = 0;
                    $deaths = 0;
                    $assists = 0;

                    $seasonString = "SEASON2015";
                    $sumChampStats = $summoners[$index]->getSummonerRankedChampionStatsJSON();
                    $champions = $sumChampStats["champions"];

                    echo('<tr id="'.$summoners[$index]->getSumName().'" class="'.$evenOdd.' summonerRow">');
                    echo('  <td><a onclick=javascript:loadSummonersStats('.$summoners[$index]->getChampionID().','.$sumID.','.$index.')>'.$summoners[$index]->getSumName().'</a></td>');
                    echo('  <td style="text-align:left">');

                    //Get the champion name and how many games have been played.
                    echo('      <div><img style="margin-right:5px" class="'.$summoners[$index]->getChampionName().'" src="'.$dataDragonChampionURL.$summoners[$index]->getChampionName().'.png" height="25" width="25">'.$summoners[$index]->getChampionName().'<b>('.$summoners[$index]->getChampionGames().')</b></div>');
                    echo('  </td>');
                   
                    //Summoner Spells
                    echo('  <td>');
                    echo('      <div style="margin:auto;">');
                    echo('          <div style="display:inline-block;">'); 
                    echo('              <img src="/images/summonerSpells/'.$spell1.'.png" class="'.$spell1.'" height="25" width="25" >');
                    echo('          </div>');
                    echo('          <div style="display:inline-block">');
                    echo('              <img src="/images/summonerSpells/'.$spell2.'.png" height="25" width="25" class="'.$spell2.'">');
                    echo('          </div>');
                    echo('      </div>');
                    echo('  </td>');

                    //KDA
                    echo('  <td>');
                    echo('      <div>'.round(($kills/$numberOfGames),2).'/'.round(($deaths/$numberOfGames),2).'/'.round(($assists/$numberOfGames),2).': '.round(((($kills + $assists)/$deaths)/$numberOfGames),2).'</div>');
                    echo('  </td>');

                    //rank
                    echo('  <td>');
                    echo('      <div style="margin:auto; ">');
                    $tier = $league[$summoners[$index]->getSumID()][0]["tier"];
                    $division = $league[$summoners[$index]->getSumID()][0]["entries"][0]["division"];
                    $points = $league[$summoners[$index]->getSumID()][0]["entries"][0]["leaguePoints"];
                    $wins = $league[$summoners[$index]->getSumID()][0]["entries"][0]["wins"];
                    $losses = $league[$summoners[$index]->getSumID()][0]["entries"][0]["losses"];
                    echo('      <div style="display:inline-block; width:25px; height:25px; margin-right:2px;"><img src="/images/rankedIcons/'.strtolower($tier).'.png" class="'.$tier.'" height="25" width="25"></div>'); 
                    echo('          <div style="display:inline-block;">'.$tier.' '.$division.' <b>('.$points.')</b></div>');
                    echo('      </div>');
                    echo('  </td>');

                    //Ranked win loss
                    echo('  <td>');
                    echo('      <div><span style="color:#4CAF50">'.$wins.'</span>/<span style="color:#D32F2F">'.$losses.'</span></div>');
                    echo('  </td>');
                    echo('</tr>');
                }
                echo('</table>');

                //Bans
                echo('<div id="vs" class="vs">');
                echo('  <div id="team1Bans" class="team1Bans">Bans:');
                for($index=0; $index < 6; $index = $index+2){
                    $bannedChampion = $championData["keys"][$CGJSON->bannedChampions[$index]->championId];
                    echo('<img style="margin-right:5px" class="'.$bannedChampion.'"src="'.$dataDragonChampionURL.$bannedChampion.'.png" height="50" width="50">');
                }
                echo('</div>VS.<div id="team2Bans" class="team2Bans">Bans:');
                for($index=1; $index < 6; $index = $index+2){
                    $bannedChampion = $championData["keys"][$CGJSON->bannedChampions[$index]->championId];
                    echo('<img style="margin-right:5px" class="'.$bannedChampion.'"src="'.$dataDragonChampionURL.$bannedChampion.'.png" height="50" width="50">');
                }
                echo('</div></div>');
                echo('<table id="Team2" class="teamTable">');
                echo('  <tr class="headers">');
                echo(       '<th style="width:20%">Name</th>');
                echo(       '<th style="width:17%">Champion(games)</th>');
                echo(       '<th style="width:8%">SS</th>');
                echo(       '<th style="width:22%">KDA</th>');
                echo(       '<th style="width:25%">Rank(Lp)</th>');
                echo(       '<th style="width:8%">Rank W/L</th>');
                echo(   '</tr>');
                echo(   '<tr>');
                echo(       '<td colspan="6">');
                echo(           '<div id="team2Banner" class="team2Banner"></div>');
                echo(       '</td>');
                echo(   '</tr>');
                for($index = 5; $index<10; $index++){
                    $evenOdd = "oddTeam";
                    if($index % 2 == 0){
                        $evenOdd = "evenTeam";
                    }
                    $summoner = $CGJSON->participants[$index]->summonerName;
                    $summonerID = $CGJSON->participants[$index]->summonerId;
                    $championID = $CGJSON->participants[$index]->championId;
                    $championName = $championData["keys"][$championID];

                    $spell1 = $CGJSON->participants[$index]->spell1Id;
                    $spell2 = $CGJSON->participants[$index]->spell2Id;
                    $numberOfGames = 0;
                    $kills = 0;
                    $deaths = 0;
                    $assists = 0;

                    $seasonString = "SEASON2015";
                    $sumChampStats = file_get_contents('https://'.$region2.'.api.pvp.net/api/lol/'.$region2.'/v1.3/stats/by-summoner/'.$sumID.'/ranked?season='.$seasonString.'&api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
                    $sumChampStats = json_decode($sumChampStats, true);
                    $champions = $sumChampStats["champions"];

                    echo('<tr id="'.$summoner.'" class="'.$evenOdd.' summonerRow">');
                    echo('  <td><a onclick=javascript:loadSummonersStats('.$championID.','.$sumID.','.$index.')>'.$summoner.'</a></td>');
                    echo('  <td style="text-align:left">');

                    //Get the champion name and how many games have been played.
                    echo('      <div><img style="margin-right:5px" class="'.$championName.'" src="'.$dataDragonChampionURL.$championName.'.png" height="25" width="25">'.$championName.'<b>(');
                    

                    foreach($champions as $item){
                        if($item["id"] == $championID){
                            $numberOfGames = $item["stats"]["totalSessionsPlayed"];
                            $kills = $item["stats"]["totalChampionKills"];
                            $deaths = $item["stats"]["totalDeathsPerSession"];
                            $assists = $item["stats"]["totalAssists"];
                            break;
                        }else{
                            $numberOfGames = 0;
                        }
                    }

                    echo(       $numberOfGames.')</b></div>');
                    echo('  </td>');
                   
                    //Summoner Spells
                    echo('  <td>');
                    echo('      <div style="margin:auto;">');
                    echo('          <div style="display:inline-block;">'); 
                    echo('              <img src="/images/summonerSpells/'.$spell1.'.png" class="'.$spell1.'" height="25" width="25" >');
                    echo('          </div>');
                    echo('          <div style="display:inline-block">');
                    echo('              <img src="/images/summonerSpells/'.$spell2.'.png" height="25" width="25" class="'.$spell2.'">');
                    echo('          </div>');
                    echo('      </div>');
                    echo('  </td>');

                    //KDA
                    echo('  <td>');
                    echo('      <div>'.round(($kills/$numberOfGames),2).'/'.round(($deaths/$numberOfGames),2).'/'.round(($assists/$numberOfGames),2).': '.round(((($kills + $assists)/$deaths)/$numberOfGames),2).'</div>');
                    echo('  </td>');

                    //rank
                    echo('  <td>');
                    echo('      <div style="margin:auto; ">');
                    $tier = $league[$summonerID][0]["tier"];
                    $division = $league[$summonerID][0]["entries"][0]["division"];
                    $points = $league[$summonerID][0]["entries"][0]["leaguePoints"];
                    $wins = $league[$summonerID][0]["entries"][0]["wins"];
                    $losses = $league[$summonerID][0]["entries"][0]["losses"];
                    echo('      <div style="display:inline-block; width:25px; height:25px; margin-right:2px;"><img src="/images/rankedIcons/'.strtolower($tier).'.png" class="'.$tier.'" height="25" width="25"></div>'); 
                    echo('          <div style="display:inline-block;">'.$tier.' '.$division.' <b>('.$points.')</b></div>');
                    echo('      </div>');
                    echo('  </td>');

                    //Ranked win loss
                    echo('  <td>');
                    echo('      <div><span style="color:#4CAF50">'.$wins.'</span>/<span style="color:#D32F2F">'.$losses.'</span></div>');
                    echo('  </td>');
                    echo('</tr>');
                }
                echo('</table>');
            }
        ?>
    </div>

    <!-- button to show all average tables/average stats!-->
    <div id="showAverageStats" style="width:50%; margin:auto; margin-top:20px; display:none"><button class="btn btn-default" style="width:100%;" onclick="showAllAverageStats();">Show Overall Average Statistics</button></div>

    <!-- Div for summoner stats like average tables and stuffs!-->
    <div id="summonerStats" style="display:none">
        <div id="summonerStatsDiv"></div>
        <!--Div for all the summoners ranked champions !-->
        <div id="allChampionStats">
            
        </div>
    </div>
    
    <!-- Last 15 ranked matches !-->
    <ul id="matches"><ul>
    <script>
        //Start when the document load
        //Get all champions and their id's/names
		$(document).ready(function(){
            $('#loadingGIF').hide();
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "ajaxFunctions.php", //Relative or absolute path to response.php file
                data:  { action: 'getChampionID()' },
                success: function(data) {
                    var jsonObj = JSON.parse(data);
                    for(var champion in jsonObj.data){
                        $( '<div style="display:hidden" id="' + jsonObj.data[champion].id + '">'+ champion +'</div>' ).appendTo("#championList");
                    }
                }
            });
            $('#championList').toggle();
            setupTooltip();
            setupChampionTooltips();
        });

        function setupTooltip(){
            $(document).ready(function(){
                $.ajax({
                    dataType: "json",
                    url: '/jsonData/summonerSpellData.json',
                    success: function(JSONData) {
                        var counter = 0;
                        var classes = [".barrier", ".cleanse", ".clairvoyance", ".ignite", ".exhaust", ".flash", ".ghost", ".heal", ".clarity", ".garrison", ".poroRecall", ".poroThrow", ".revive", ".smite", ".teleport"];
                        for(var key in JSONData.data){
                            $(classes[counter]).powerTip({
                                followMouse: 'true'
                            }).data('powertip', '<div style="width:500px">Name: ' + JSONData.data[key].name + '</div>' + 
                                    '<div style="width:500px; white-space: pre-wrap;">Description: ' + JSONData.data[key].description + '</div>' + 
                                    '<div style="width:500px">Cooldown: ' + JSONData.data[key].cooldownBurn + '</div>');
                            counter++;  
                        }
                    }
                });
            });
        }

        function setupChampionTooltips(){
            $(document).ready(function(){
                $.ajax({
                    dataType: "json",
                    url: '/jsonData/championData.json',
                    success: function(JSONData) {
                        var counter = 0;
                        for(var key in JSONData.data){
                            tooltipElement = '<div style="width:500px">Name: ' + JSONData.data[key].name + '</div>' + 
                                             '<div style="width:500px; white-space: pre-wrap;">Description: ' + JSONData.data[key].blurb + '</div>' + 
                                             '<div style="width:500px; white-space: pre-wrap;">Types: ';
                            for(var tagKey in JSONData.data[key].tags){
                                tooltipElement +=  JSONData.data[key].tags[tagKey] + " " ;
                            }
                            tooltipElement += '</div>';
                            $('.' + key).powerTip({
                                followMouse: 'true'
                            }).data('powertip', tooltipElement.toString());
                            counter++;  
                        }
                    }
                });
            });
        }

        function setupItemTooltips(){
            $(document).ready(function(){
                $.ajax({
                    dataType: "json",
                    url: '/jsonData/itemData.json',
                    success: function(JSONData) {
                        var counter = 0;
                        for(var key in JSONData.data){
                            tooltipElement = '<div style="width:300px">Name: ' + JSONData.data[key].name + '</div>' + 
                                             '<div style="width:300px; white-space: pre-wrap;">' + JSONData.data[key].description + '</div>' + 
                                             '<div style="width:300px; white-space: pre-wrap;"></br>'+ JSONData.data[key].plaintext + '</div>' + 
                                             '<div style="width:300px; white-space: pre-wrap;">Buy For: ' + JSONData.data[key].gold.total + '<img src="/images/basicIcons/gold.png" height="25" width="25"></div>';

                            $('.' + key).powerTip({
                                followMouse: 'true'
                            }).data('powertip', tooltipElement.toString());
                            counter++;  
                        }
                    }
                });
            });
        }
        
        /*function to load most of the extra averages stats and gives a match history.*/
        function loadSummonersStats(championId, summonerId, partIndex, championIndex){
            //Empty all the divs (reloads them)
            $('#showAllChampionStats').show();
            $('#showAverageStats').show();
            $('#summonerStatsDiv').empty();
            $('#championStatTable').empty();
            $('#matches').empty();

            var championStatsJSON = loadRankedChampionStats(summonerId, championId, "SEASON2015");
            var numOfChampionGames = 0;
            var championWins = 0;
            var championLoss = 0;
            var championKills = 0;
            var championDeaths = 0;
            var championAssists = 0;
            
            if(championStatsJSON != false){
                numOfChampionGames = championStatsJSON.champions[championIndex].stats.totalSessionsPlayed;
                championWins = championStatsJSON.champions[championIndex].stats.totalSessionsWon;
                championLoss = championStatsJSON.champions[championIndex].stats.totalSessionsLost;
                championKills = championStatsJSON.champions[championIndex].stats.totalChampionKills/numOfChampionGames;
                championDeaths = championStatsJSON.champions[championIndex].stats.totalDeathsPerSession/numOfChampionGames;
                championAssists = championStatsJSON.champions[championIndex].stats.totalAssists/numOfChampionGames;
            }
            var html = '<table class="championStats">' + 
                    '<tr>'+
                        '<td colspan="6" class="averageTableTitle">Champion Stats - ' + $('#' + championId).text() +'</td>'+
                    '</tr>'+
                    '<tr>' +
                        '<th>Wins</th>'+
                        '<th>Losses</th>'+
                        '<th>Kills</th>'+
                        '<th>Deaths</th>'+
                        '<th>Assists</th>'+
                        '<th>KDA</th>'+
                    '</tr>'+
                    '<tr>'+
                        '<td>' + championWins +'</td>'+
                        '<td>' + championLoss +'</td>'+
                        '<td>' + parseFloat(championKills).toFixed(2) + '</td>'+
                        '<td>' + parseFloat(championDeaths).toFixed(2) + '</td>'+
                        '<td>' + parseFloat(championAssists).toFixed(2) + '</td>'+
                        '<td>' + parseFloat(championKills).toFixed(2) + '/' + parseFloat(championDeaths).toFixed(2) + '/' + parseFloat(championAssists).toFixed(2) + ': ' + parseFloat((championKills + championAssists)/championDeaths).toFixed(2) + '</td>'+
                    '</tr>'+
                '</table>';
            $('#summonerStatsDiv').append(html);
            var matchHistoryData;
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "ajaxFunctions.php", //Relative or absolute path to response.php file
                data:  { action: 'getRanked5v5Solo()', sumID: summonerId, region: $('select#regionSelect').val()},
                success: function(dataObject){
                    var data = JSON.parse(dataObject);
                    var gameType = data.matches[0].queueType;
                    var matchDuration = 0;
                    
                    //Stats
                    var wins = 0;
                    var losses = 0;
                    var kills = 0;
                    var deaths = 0;
                    var assists = 0;
                    var doubleKills = 0;
                    var tripKills = 0;
                    var quadraKills = 0;
                    var pentaKills = 0;

                    //Damage Stats
                    var totalDamageToChampions = 0;
                    var physicalDamageToChamps = 0;
                    var magicDamageToChamps = 0;
                    var trueDamageToChamps = 0;
                    var totalDamageTaken = 0;
                    var magicDamageTaken = 0;
                    var physicalDamageTaken = 0;
                    var trueDamageTaken = 0;
                    
                    //CS STATS
                    var totalMinionsKilled = 0;
                    var monstersKilled = 0; 
                    var teamMonsters = 0;
                    var enemyMonsters = 0;
                    var goldEarned = 0;
                    var goldSpent = 0;
                    
                    //Wards
                    var visionWards = 0;
                    var sightWards = 0;
                    var wardsPlaced = 0;
                    var wardsKilled = 0;
                    var towersDestroyed = 0;
                    var totalTimeCCDealt = 0;

                    if(gameType === "RANKED_SOLO_5x5"){
                        $('#sumGameAvgTableGameType').html("Game Type: Ranked Solo");
                    }
                    var matchesLength = data.matches.length;
                    for(var index = matchesLength - 1; index >= 0; index--){
                        //Call method to get Champion Name
                        var li = document.createElement('li');
                        li.id = "match" + index;
                        var winloss;
                        li.className = 'match';
                        if(data.matches[index].participants[0].stats.winner === true) {
                            winloss = "win";
                            wins++;
                        } else {
                            winloss="loss";
                            losses++;
                        }
                        document.getElementById("matches").appendChild(li);
                        
                        var match = data.matches[index];
                        
                        var html = "";
                        html += '<div class="matchHeader ' + winloss + '">Champion: ' + $('#' + match.participants[0].championId).html() + 
                                    '<span class="matchDuration">Match Duration: ' + parseFloat(match.matchDuration/60).toFixed(0) + ':'+ match.matchDuration%60 + '<span style="color:white"> | </span>' + winloss + '</span>'+
                                '</div>' + 
                                '<div class="championData">' +
                                    '<div class="championImage">' + 
                                        '<img src="' + dataDragonChampionURL + $('#' + match.participants[0].championId).html() + '.png" class="' + $('#' + match.participants[0].championId).text() + '" height="70" width="70">' + 
                                    '</div>' +
                                    '<span class="kdaInfo">KDA: ' + '<img src="/images/basicIcons/score.png" height="25" width="25">' + 
                                        match.participants[0].stats.kills + " / " +
                                        match.participants[0].stats.deaths + " / " +
                                        match.participants[0].stats.assists + 
                                    '</span>' +
                                '</div>'+
                                '<div class="itemBuild">';
                                console.log("Got Here");
                        for(var i = 0; i < 6; i++){
                            if(match.participants[0].stats['item' + i] != 0){
                                html += '<img src="' + dataDragonItemURL + match.participants[0].stats['item' + i]+'.png" class="' + match.participants[0].stats['item' + i] + '" height="70" width="70">';
                            }
                            else{
                                html += '<img src="/images/transparent.png" height="70" width="70">';
                            }
                        }
                        html += '</div>';
                            
                        $('#match' + index).html(html);   

                        kills += match.participants[0].stats.kills;
                        deaths += match.participants[0].stats.deaths;
                        assists += match.participants[0].stats.assists;

                        doubleKills += match.participants[0].stats.doubleKills;
                        tripKills += match.participants[0].stats.tripleKills;
                        quadraKills += match.participants[0].stats.quadraKills;
                        pentaKills += match.participants[0].stats.pentaKills;

                        physicalDamageToChamps += match.participants[0].stats.physicalDamageDealtToChampions;
                        magicDamageToChamps += match.participants[0].stats.magicDamageDealtToChampions;
                        trueDamageToChamps += match.participants[0].stats.trueDamageDealtToChampions;
                        totalDamageToChampions += match.participants[0].stats.totalDamageDealtToChampions;

                        magicDamageTaken += match.participants[0].stats.magicDamageTaken;
                        physicalDamageTaken += match.participants[0].stats.physicalDamageTaken;
                        trueDamageTaken += match.participants[0].stats.trueDamageTaken;
                        totalDamageTaken += match.participants[0].stats.totalDamageTaken;

                        totalMinionsKilled += match.participants[0].stats.minionsKilled;
                        monstersKilled += match.participants[0].stats.neutralMinionsKilled;
                        teamMonsters += match.participants[0].stats.neutralMinionsKilledTeamJungle;
                        enemyMonsters += match.participants[0].stats.neutralMinionsKilledEnemyJungle;
                        goldEarned  += match.participants[0].stats.goldEarned;
                        goldSpent += match.participants[0].stats.goldSpent;
                        visionWards += match.participants[0].stats.visionWardsBoughtInGame;
                        sightWards += match.participants[0].stats.sightWardsBoughtInGame;
                        wardsPlaced += match.participants[0].stats.wardsPlaced;
                        wardsKilled += match.participants[0].stats.wardsKilled;
                        towersDestroyed  += match.participants[0].stats.towerKills;
                        totalTimeCCDealt += match.participants[0].stats.totalTimeCrowdControlDealt; 
                    }  
                    html = '<table class="averageTable">' + 
                            '<tr>'+
                                '<td colspan="2" class="averageTableTitle">Average Game Stats</td>'+
                            '</tr>'+
                            '<tr><td>Win/Loss</td><td>' + wins + '/' + losses + ': ' + parseFloat((wins/matchesLength) * 100).toFixed(2) + '%</td></tr>'+
                            '<tr><td>KDA</td><td>' + kills + '/' + deaths + '/' + assists + ': ' + parseFloat((kills + assists)/deaths).toFixed(2) + '</td></tr>'+
                            '<tr><td>Kills</td><td>' + parseFloat(kills/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr><td>Deaths</td><td>' + parseFloat(deaths/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr><td>Assists</td><td>' + parseFloat(assists/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr><td>2x Kills</td><td>' + parseFloat(doubleKills/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr><td>3x Kills</td><td>' + parseFloat(tripKills/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr><td>4x Kills</td><td>' + parseFloat(quadraKills/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr><td>Penta Kills</td><td>' + parseFloat(pentaKills/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr>'+
                                '<td colspan="2" class="averageTableTitle">Average Damage To Champions</td>'+
                            '</tr>'+
                            '<tr><td>Physical</td><td>' + parseFloat(physicalDamageToChamps/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr><td>Magical</td><td>' + parseFloat(magicDamageToChamps/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr><td>True</td><td>' + parseFloat(trueDamageToChamps/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr><td>Total</td><td>' + parseFloat(totalDamageToChampions/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr>'+
                                '<td colspan="2" class="averageTableTitle">Average Damage From Champions</td>'+
                            '</tr>'+
                            '<tr>' +
                            '<tr><td>Physical</td><td>' + parseFloat(physicalDamageTaken/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr><td>Magical</td><td>' + parseFloat(magicDamageTaken/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr><td>True</td><td>' + parseFloat(trueDamageTaken/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr><td>Total</td><td>' + parseFloat(totalDamageTaken/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr>'+
                                '<td colspan="2" class="averageTableTitle">Average Minion Counts</td>'+
                            '</tr>'+
                            '<tr><td>Creep Score</td><td rowspan="2">' +parseFloat(totalMinionsKilled/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr colspan="2"><td>Jungle Monsters</td></tr>'+
                            '<tr><td>Team:</td><td> ' + parseFloat(teamMonsters/matchesLength).toFixed(2)+'</td></tr>'+
                            '<tr><td>Enemy:</td><td> ' + parseFloat(enemyMonsters/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr><td>Gold Earned</td><td>' + parseFloat(goldEarned/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr>' + 
                                '<td >Total: ' + parseFloat(monstersKilled/matchesLength).toFixed(2) + '</td>' + 
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="averageTableTitle">Average Warding Stats</td>'+
                            '</tr>'+
                            '<tr><td>Vision Wards</td><td>' + parseFloat(visionWards/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr><td>Sight Wards</td><td>' + parseFloat(sightWards/matchesLength).toFixed(2)+'</td></tr>'+
                            '<tr><td>Wards Placed</td><td>' + parseFloat(wardsPlaced/matchesLength).toFixed(2) + '</td></tr>'+
                            '<tr><td>Wards Killed</td><td>' + parseFloat(wardsKilled/matchesLength).toFixed(2) + '</td></tr>'+
                        '</table>';
                    $('#summonerStats').append(html);                                      
                },
                async:false
            });
            setupTooltip();
            setupChampionTooltips();
            setupItemTooltips();
        }

        function loadRankedChampionStats(summonerId, championId, season){
            $('#allChampionStats').empty();
            $('#allChampionStats').append(
            '<div id="buttonContainer" style="margin:auto; margin-top:15px; width:468px; ">'+
                '<ul>'+
                    '<li style="display:inline; margin-right:10px;"><button class="btn btn-mini" onclick="loadRankedChampionStats(' + summonerId +', ' + championId + ', \'SEASON1\')">Season 1</button></li>'+
                    '<li style="display:inline; margin-right:10px;"><button class="btn btn-mini" onclick="loadRankedChampionStats(' + summonerId +', ' + championId + ', \'SEASON2\')">Season 2</button></li>'+
                    '<li style="display:inline; margin-right:10px;"><button class="btn btn-mini" onclick="loadRankedChampionStats(' + summonerId +', ' + championId + ', \'SEASON3\')">Season 3</button></li>'+
                    '<li style="display:inline; margin-right:10px;"><button class="btn btn-mini" onclick="loadRankedChampionStats(' + summonerId +', ' + championId + ', \'SEASON2014\')">Season 4</button></li>'+
                    '<li style="display:inline;"><button class="btn btn-mini" onclick="loadRankedChampionStats(' + summonerId +', ' + championId + ', \'SEASON2015\')">Season 5</button></li>'+
                '</ul>'+
            '</div>'+
            '<div style="margin:auto; border: 1px solid black; width:97%; overflow-x:scroll;">' + 
                '<table id="championStatTable" class="championStatTable">'+
                   
                    '<tr>'+
                        '<th style="min-width:140px">Champion</th>'+
                        '<th style="min-width:60px">Kills</th>'+
                        '<th style="min-width:60px">Deaths</th>'+
                        '<th style="min-width:60px">Assists</th>'+
                        '<th style="min-width:65px">Win %</th>'+
                        '<th style="min-width:110px">Minions</th>'+
                        '<th style="min-width:120px">Damage Dealt</th>'+
                        '<th style="min-width:65px">Physical</th>'+
                        '<th style="min-width:50px">Magic</th>'+
                        '<th style="min-width:120px">Damage Taken</th>'+
                        '<th style="min-width:120px">Gold Earned</th>'+
                        '<th style="min-width:100px">Max Kills</th>'+
                        '<th style="min-width:100px">Max Deaths</th>'+

                    '</tr> '+
                '</table>' +
            '</div>');
            
            //Get the ranked champion stats data.
            var championStatsJSON;
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "ajaxFunctions.php", //Relative or absolute path to response.php file
                data:  { action: 'getStats()', sumID: summonerId, region: $('select#regionSelect').val(), seasonString: season},
                success: function(json){
                    championStatsJSON = JSON.parse(json);
                }, async:false
            });

            var divClass = "";
            var rowColor = "even";
            for(var index = 0; index < championStatsJSON.champions.length; index++){
                
                if(championId === championStatsJSON.champions[index].id){
                    rowColor="highlighted";
                } else if((index + 1) % 2==0){
                    rowColor = "even";
                } else {
                    rowColor = "odd";
                }
                var numberOfGames = championStatsJSON.champions[index].stats.totalSessionsPlayed;
                var championName = $('#' + championStatsJSON.champions[index].id).text();
                $('#championStatTable').append(
                    '<tr id="' + championName + 'Totals" class="' + rowColor + '">' + 
                        '<td style="text-align:left; "><img style="margin-right:5px" src="' + dataDragonChampionURL + championName+ '.png" class="' + championName + '"height="25" width="25">' + championName + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalChampionKills).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalDeathsPerSession).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalAssists).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalSessionsWon).toFixed(2) + '%</td>' +
                        '<td style="text-align:center;">' +
                            '<div>' +
                                '<img src="/images/basicIcons/minion.png" height="25" width="25" style="float:left">' + parseFloat(championStatsJSON.champions[index].stats.totalMinionKills).toFixed(2) + 
                            '</div>'+
                        '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalDamageDealt).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalPhysicalDamageDealt).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalMagicDamageDealt).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalDamageTaken).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalGoldEarned).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.maxChampionsKilled).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.maxNumDeaths).toFixed(2) + '</td>' +
                    '</tr>' +
                    '<tr id="' + championName + 'Totals" class=' + rowColor + '>' +
                        '<td style="text-align:left;">' + championName + ' Averages</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalChampionKills/numberOfGames).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalDeathsPerSession/numberOfGames).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalAssists/numberOfGames).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalSessionsWon/numberOfGames*100).toFixed(2) + '%</td>' +
                        '<td style="text-align:center;">' +
                            '<div style="margin:auto;">' +
                                '<img src="/images/basicIcons/minion.png" height="25" width="25" style="float:left">' + parseFloat(championStatsJSON.champions[index].stats.totalMinionKills/numberOfGames).toFixed(2) + 
                            '</div>'+
                        '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalDamageDealt/numberOfGames).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalPhysicalDamageDealt/numberOfGames).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalMagicDamageDealt/numberOfGames).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalDamageTaken/numberOfGames).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.totalGoldEarned/numberOfGames).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.maxChampionsKilled/numberOfGames).toFixed(2) + '</td>' +
                        '<td style="text-align:center;">' + parseFloat(championStatsJSON.champions[index].stats.maxNumDeaths/numberOfGames).toFixed(2) + '</td>' +
                    '</tr>'
                    );
            }
            
            return championStatsJSON;
        }

        /*
        =====================SHOW METHODS======================
        */
        //When current game is loaded so is all of the ranked champions for the summoner selected.
        function showAllRankChampions(){
            $('#allChampionStats').toggle();
        }
        function showAllAverageStats(){
            $('#summonerStats').toggle();
        }
    </script>
    <script src="./js/bootstrap.min.js"></script>
</body>
</html>
