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
    <script src="/js/tooltip.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/jquery.powertip.css">
    <script src="./js/jquery.powertip.js"></script>
</head>

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
    
    <!-- Have the user enter in summoner stats. !-->
    <div id="userSelectContainer">

        <!-- Summoner name input !-->
        <div id="summonerInput" class="summonerInputBox">
            <input type="text" id="summonerName" class="form-control" placeholder="Enter Summoner Name to find stats" name="name">
        </div> 

        <!-- Select the region !--> 
        <div id="regionSelectContainer" class="regionSelectContainer"> 
    		<select id="regionSelect" class="form-control">
    			<option value="-1">Please select a region</option>
    			<option value="NA1">NA - North America</option>
    			<option value="EUW1">EU - Europe</option>
    			<option value="EUN1">EU - Europe Nordic & East</option>
                <option value="BR1">BR - Brazil</option>
    			<option value="KR">KR - Korea</option>
                <option value="LA1">LAN - Latin America North</option>
    			<option value="LA2">LAS - Latin America South</option>
                <option value="OC1">OCE - Oceania</option>
    			<option value="RU">RU - Russia</option>
                <option value="TR1">TR - Turkey</option>
    		</select>
            <div id="championList" style="display:hidden"></div>
            <button onClick="getStats()" class="btn btn-default" style="margin:auto; margin-top:5px">Get Game</button>
        </div>
    </div>

    <!-- Current game stats from summoners !-->
    <div id="summonersTable" style="display:none">
        <!-- Team 1 Summoners !-->
        <table id="Team1" class="teamTable">
            <tr class="headers">
                <th style="width:20%">Name</th>
                <th style="width:17%">Champion(games)</th>
                <th style="width:8%">SS</th>
                <th style="width:22%">KDA</th>
                <th style="width:21%">Rank(Lp)</th>
                <th style="width:12%">Rank W/L</th>
            </tr>
            <tr>
                <td colspan="6">
                    <div id="team1Banner" class="team1Banner"></div>
                </td>
            </tr>
        </table>

        <div id="vs" class="vs"><div id="team1Bans" class="team1Bans">Bans:</div>VS.<div id="team2Bans" class="team2Bans">Bans:</div></div>

        <!-- Team 2 Summoners !-->
        <table id="Team2" class="teamTable">
            <tr class="headers">
                <th style="width:20%">Name</th>
                <th style="width:17%">Champion(games)</th>
                <th style="width:8%">SS</th>
                <th style="width:22%">KDA</th>
                <th style="width:21%">Rank(Lp)</th>
                <th style="width:12%">Rank W/L</th>
            </tr>
            <tr>
                <td colspan="6">
                    <div id="team2Banner" class="team2Banner"></div>
                </td>
            </tr>
        </table>
    </div>

    <div id="summonerNotInGame" class="errorDiv"> Summoner is not in game</div>

    <!-- button to show all average tables/average stats!-->
    <div id="showAverageStats" style="width:50%; margin:auto; margin-top:20px; display:none"><button class="btn btn-default" style="width:100%;" onclick="showAllAverageStats();">Show Overall Average Statistics</button></div>

    <!-- Div for summoner stats like average tables and stuffs!-->
    <div id="summonerStats" style="display:none">
            <div id="summonerStatsDiv"></div>
            <!--Div for all the summoners ranked champions !-->
            <div id="allChampionStats">
                
            </div>
        </ul>
    </div>
    
    <!-- Last 15 ranked matches !-->
    <ul id="matches"><ul>
    <script>
        var summonerInfoJSON;        
        //Start when the document loads
		$(document).ready(function(){
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
        });

        /*
        Get stats button is clicked. This function loads the current game tables
        */
        function getStats(){
            $(document).ready(function(){
                
                //Hide the "summoner not in game" error div.
                $('#summonerNotInGame').hide();
                var summonerName = $('#summonerName').val();

                //Get summoners basic information.
                //Get name, id, level
                var JSON = getSummonerInformation(summonerName);
                var sumBasicInfo;
                if(JSON != false){
                    for (var first in JSON){
                        sumBasicInfo = JSON[first];
                    }
                    console.log(sumBasicInfo.name);
                    console.log(sumBasicInfo.id);
                }
                
                //Get current gameJSON for riot api.
                var currentGameJSON = getCurrentGameJSON(sumBasicInfo);;
                
                console.log(currentGameJSON.gameId);
                //If there is current game do things
                if(currentGameJSON != false){
                    //Get banned champions
                    if(currentGameJSON.bannedChampions[0] != null){
                        for(var ban = 0; ban < 6; ban++){
                            if(ban % 2 == 0){
                                $('#team1Bans').append('<img style="margin-right:5px" class="' + $('#' + currentGameJSON.bannedChampions[ban].championId).text() + '"src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + currentGameJSON.bannedChampions[ban].championId).text() + '.png" height="50" width="50">');
                            } else {
                                $('#team2Bans').append('<img style="margin-right:5px" class="' + $('#' + currentGameJSON.bannedChampions[ban].championId).text() + '" src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + currentGameJSON.bannedChampions[ban].championId).text() + '.png" height="50" width="50">');
                            }
                        }
                    }

                    //Make csv of summoner names for league data.
                    //This prevents you from making several calls and combining them all at once.
                    var csvSummonerIds = "";
                    var csvSummonerIds = csvSummonerIds.concat(currentGameJSON.participants[0].summonerId);
                    for(var summoner = 1; summoner < 10; summoner++){
                        csvSummonerIds = csvSummonerIds.concat( "," + currentGameJSON.participants[summoner].summonerId);
                    }

                    //Get all summoners league data.
                    var sumLeagueData = getSummonersLeagueData(csvSummonerIds);;

                    //For loop building the tr for each summoner
                    for(var index = 0; index < 10; index++){

                        //Getting this summoner id.
                        var summonerID = currentGameJSON.participants[index].summonerId;
                        var currentSumsChampionStats = getChampionStats(currentGameJSON.participants[index].summonerId);

                        //Finding the stats for the current champion being played.
                        //TODO: try to find somethign that uses not a for loop.
                        var numberOfGames = 0;
                        var kills = 0;
                        var deaths = 0;
                        var assists = 0;
                        var championIndex = 0;
                        if(currentSumsChampionStats != false){
                            for(var champion = 0; champion < currentSumsChampionStats.champions.length; champion++){
                                if(currentSumsChampionStats.champions[champion].id == currentGameJSON.participants[index].championId){
                                    championIndex = champion;
                                    numberOfGames = currentSumsChampionStats.champions[champion].stats.totalSessionsPlayed;
                                    kills = currentSumsChampionStats.champions[champion].stats.totalChampionKills/numberOfGames;
                                    deaths = currentSumsChampionStats.champions[champion].stats.totalDeathsPerSession/numberOfGames;
                                    assists = currentSumsChampionStats.champions[champion].stats.totalAssists/numberOfGames;
                                }
                            }
                        }

                        //Get league info from the leagueJSON data for this summoner
                        var tier;
                        var division;
                        var points;
                        var wins;
                        var losses;
                        if(sumLeagueData != false && sumLeagueData[summonerID] != null){
                                tier = sumLeagueData[summonerID][0].tier;
                                division = sumLeagueData[summonerID][0].entries[0].division;
                                points = sumLeagueData[summonerID][0].entries[0].leaguePoints;
                                wins = sumLeagueData[summonerID][0].entries[0].wins;
                                losses = sumLeagueData[summonerID][0].entries[0].losses;
                        } else {
                            tier = "Unranked";
                        }

                        var evenOdd = "oddTeam";
                        if(index % 2 === 0){
                            evenOdd = "evenTeam";
                        }
                        var team = "Team1";
                        if(currentGameJSON.participants[index].teamId == 200){
                            team ="Team2"
                        }
                        
                        var spell1 = currentGameJSON.participants[index].spell1Id;
                        var spell2 = currentGameJSON.participants[index].spell2Id;
                        var classString1 = "";
                        var classString2 = ""; 
                        switch(spell1){
                            case 4:
                                classString1 = "flash"
                                break;
                            case 3:
                                classString1 = "exhaust";
                                break;
                            case 6:
                                classString1 = "ghost";
                                break;
                            case 7:
                                classString1 = "heal";
                                break;
                            case 11:
                                classString1 = "smite";
                                break;
                            case 12:
                                classString1 = "teleport";
                                break;
                            case 13:
                                classString1 = "clari";
                                break;
                            case 14:
                                classString1 = "ignite";
                                break;
                            case 21: 
                                classString1 = "barrier";
                                break;
                            case 32: 
                                classString1 = "poroThrow";
                                break;
                        }
                        switch(spell2){
                            case 4:
                                classString2 = "flash"
                                break;
                            case 3:
                                classString2 = "exhaust";
                                break;
                            case 6:
                                classString2 = "ghost";
                                break;
                            case 7:
                                classString2 = "heal";
                                break;
                            case 11:
                                classString2 = "smite";
                                break;
                            case 12:
                                classString2 = "teleport";
                                break;
                            case 13:
                                classString2 = "clari";
                                break;
                            case 14:
                                classString2 = "ignite";
                                break;
                            case 21: 
                                classString2 = "barrier";
                                break;
                            case 32: 
                                classString2 = "poroThrow";
                                break;
                        }
                        //Build the row with the data. 
                        $('#'+team).append('<tr id="' + currentGameJSON.participants[index].summonerName + '" class="' + evenOdd + ' summonerRow">' + 
                                '<td><a onclick=javascript:loadSummonersStats(' + currentGameJSON.participants[index].championId + ',' + currentGameJSON.participants[index].summonerId + ',' + index + ',' + championIndex + ')>' + currentGameJSON.participants[index].summonerName + '</a></td>' +
                                '<td style="text-align:left">' +
                                    '<div><img style="margin-right:5px" class="' + $('#' + currentGameJSON.participants[index].championId).text()+ '" src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + currentGameJSON.participants[index].championId).text()+ '.png" height="25" width="25">' + $('#' + currentGameJSON.participants[index].championId).text() + '<b>('+ numberOfGames + ')</b></div>'+
                                '</td>' +
                                '<td>' +
                                    '<div style="float:left; margin-left:4px;"><img src="/images/summonerSpells/' + spell1 + '.png" class="' + classString1 + '" height="25" width="25" style="margin-right:2px"></div><div style="float:left"><img style="margin-right:5px; float:right;" src="/images/summonerSpells/' + spell2 + '.png" height="25" width="25" style="margin-right:2px" class="' + classString2 + '"></div>'+
                                '</td>' +
                                '<td>' +
                                    '<div>' + parseFloat(kills).toFixed(1) + '/' + parseFloat(deaths).toFixed(1) + '/' + parseFloat(assists).toFixed(1) + ': ' + parseFloat((kills+assists)/deaths).toFixed(1) + '</div>'+
                                '</td>' +
                                '<td>' +
                                    '<div>' + tier + ' ' + division + ' <b>(' + points + ')</b></div>'+
                                '</td>' +
                                '<td>' +
                                    '<div><span style="color:green">' + wins + '</span>/<span style="color:red">' + losses + '</span></div>'+
                                '</td>' +
                            '</tr>'
                        );
                    }   
                    $('#summonersTable').show();
                } else {    //Show div that displays summoners not in game
                    $('#summonerNotInGame').toggle();
                }
                setupTooltip();
                setupChampionTooltips();
            });
        }

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
            $('#summonerStatsDiv').append(
                '<table class="championStats">' + 
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
                '</table>');
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
                        $('#match' + index).html(
                            '<div class="matchHeader ' + winloss + '">Champion: ' + $('#' + match.participants[0].championId).html() + 
                                '<span class="matchDuration">Match Duration: ' + parseFloat(match.matchDuration/60).toFixed(0) + ':'+ match.matchDuration%60 + '<span style="color:white"> | </span>' + winloss + '</span>'+
                            '</div>' + 
                            '<div class="championData">' +
                                '<div class="championImage"><img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + match.participants[0].championId).html() + '.png" height="70" width="70">' + ' </div>' +
                                '<span class="kdaInfo">KDA: ' +
                                    match.participants[0].stats.kills + " / " +
                                    match.participants[0].stats.deaths + " / " +
                                    match.participants[0].stats.assists + 
                                '</span>' +
                                
                            '</div>'+
                            '<div class="itemBuild">' +
                                '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/item/'+match.participants[0].stats.item0+'.png" height="70" width="70">'+
                                '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/item/' + match.participants[0].stats.item1 + '.png" height="70" width="70">'+
                                '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/item/' + match.participants[0].stats.item2 + '.png" height="70" width="70">'+
                                '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/item/' + match.participants[0].stats.item3 + '.png" height="70" width="70">'+
                                '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/item/' + match.participants[0].stats.item4 + '.png" height="70" width="70">'+
                                '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/item/' + match.participants[0].stats.item5 + '.png" height="70" width="70">'+
                            '</div>'
                        );                   
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
                    $('#summonerStats').append(
                        '<table class="averageTable">' + 
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
                        '</table>'
                        );                                      
                },
                async:false
            });
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
            '<table id="championStatTable" class="championStatTable">'+
                '<tr>'+
                    '<th style="width:200px">Champion</th>'+
                    '<th style="width:75px">Kills</th>'+
                    '<th style="width:75px">Deaths</th>'+
                    '<th style="width:75px">Assists</th>'+
                    '<th style="width:75px">Win %</th>'+
                    '<th style="width:100px">Minions</th>'+
                '</tr> '+
            '</table>');
            
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
                } else if(index % 2==0){
                    rowColor = "even";
                } else {
                    rowColor = "odd";
                }
                var numberOfGames = championStatsJSON.champions[index].stats.totalSessionsPlayed;
                $('#championStatTable').append(
                    '<tr class="' + rowColor + '">' + 
                        '<td style="text-align:left; width:15%"><img style="margin-right:5px" src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + championStatsJSON.champions[index].id).text()+ '.png" height="25" width="25">' + $('#' + championStatsJSON.champions[index].id).text() + '</td>' +
                        '<td style="text-align:center; width:15%">' + parseFloat(championStatsJSON.champions[index].stats.totalChampionKills/numberOfGames).toFixed(2) + '</td>' +
                        '<td style="text-align:center; width:15%">' + parseFloat(championStatsJSON.champions[index].stats.totalDeathsPerSession/numberOfGames).toFixed(2) + '</td>' +
                        '<td style="text-align:center; width:15%">' + parseFloat(championStatsJSON.champions[index].stats.totalAssists/numberOfGames).toFixed(2) + '</td>' +
                        '<td style="text-align:center; width:15%">' + parseFloat(championStatsJSON.champions[index].stats.totalSessionsWon/numberOfGames*100).toFixed(2) + '%</td>' +
                        '<td style="text-align:center; width:15%">' + parseFloat(championStatsJSON.champions[index].stats.totalMinionKills/numberOfGames).toFixed(2) + '</td>' +
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
