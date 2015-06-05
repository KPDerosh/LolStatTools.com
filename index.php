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
    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.2.min.js"></script>
</head>

<body>
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
                    <li class="active"><a href="#">Summoner Stats</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
    
    <!-- Filter champs with this container!-->
    <div id="userSelectContainer">
        <div id="summonerInput" class="summonerInputBox">
            <input type="text" id="summonerName" class="form-control" placeholder="Enter Summoner Name to find stats" name="name">
        </div>  
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
    <div id="summonersTable" style="display:none">
        <table id="Team1" class="teamTable">
            <tr class="headers">
                <th style="width:25%">Name</th>
                <th style="width:20%">Champion</th>
                <th style="width:25%">KDA</th>
                <th style="width:18%">Rank</th>
                <th style="width:12%">Rank W/L</th>
            </tr>
            <tr>
                <td colspan="5">
                    <div id="team1Banner" class="team1Banner"></div>
                </td>
            </tr>
        </table>
        <div id="vs" class="vs">VS.</div>
        <table id="Team2" class="teamTable">
            <tr class="headers">
                <th style="width:25%">Name</th>
                <th style="width:20%">Champion</th>
                <th style="width:25%">KDA</th>
                <th style="width:18%">Rank</th>
                <th style="width:12%">Rank W/L</th>
            </tr>
            <tr>
                <td colspan="5">
                    <div id="team2Banner" class="team2Banner"></div>
                </td>
            </tr>
        </table>
    </div>

    <script>        
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

        function showStats(summoner){
            console.log(summoner);
            $('#' + summoner + 'AverageStats').toggle();
            $('#' + summoner + 'Matches').toggle();
        }

        function getStats(){
            $(document).ready(function(){
                $('#loadingData').show();
                var summonerInfoJSONString = "";
                var summonerName = $('#summonerName').val();
                var urlEncodeSumName = summonerName.toString().replace(/\s/g,"");
                //Get this summoners information and turn it into a json variable
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "ajaxFunctions.php", //Relative or absolute path to response.php file
                    data:  { action: 'getSummonerID()', sumName: urlEncodeSumName, region: $('select#regionSelect').val() },
                    success: function(json){
                        var jsonObj = JSON.parse(json);
                        for(var summoner in jsonObj){
                            summonerInfoJSONString = summonerInfoJSONString.concat('{ "summonerInfo": { "summonerName":"' + jsonObj[summoner].name + '",');
                            summonerInfoJSONString = summonerInfoJSONString.concat('"summonerID":"' + jsonObj[summoner].id + '"}}');
                        }
                    },
                    async:false
                });
                var summonerInfoJSON = JSON.parse(summonerInfoJSONString);

                console.log(summonerInfoJSON.summonerInfo.summonerName);
                console.log(summonerInfoJSONString);
               

                var currentGameJSON;
                //CurrentGame information and store it in json
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "ajaxFunctions.php", //Relative or absolute path to response.php file
                    data:  { action: 'getCurrentGame()', sumID: summonerInfoJSON.summonerInfo.summonerID, region: $('select#regionSelect').val()},
                    success: function(json){
                        currentGameJSON = JSON.parse(json);
                    },
                    async:false
                });
                if(currentGameJSON != false){
                    //Make csv
                    var csvSummonerIds = "";
                    csvSummonerIds = csvSummonerIds.concat(currentGameJSON.participants[0].summonerId);
                    for(var summoner = 1; summoner < 10; summoner++){
                        csvSummonerIds = csvSummonerIds.concat( "," + currentGameJSON.participants[summoner].summonerId);
                    }
                    //League Data
                    var leagueJSON;
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "ajaxFunctions.php", //Relative or absolute path to response.php file
                        data:  { action: 'getLeague()', sumID: csvSummonerIds, region: $('select#regionSelect').val()},
                        success: function(json){
                            leagueJSON = JSON.parse(json);
                        }, async:false
                    });
                    console.log(csvSummonerIds);
                    console.log(currentGameJSON.gameId);
                    //For loop building the tr for each summoner
                    for(var index = 0; index < 10; index++){
                        var summonerID = currentGameJSON.participants[index].summonerId;
                        var championStatsJSON;
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "ajaxFunctions.php", //Relative or absolute path to response.php file
                            data:  { action: 'getStats()', sumID: currentGameJSON.participants[index].summonerId, region: $('select#regionSelect').val()},
                            success: function(json){
                                championStatsJSON = JSON.parse(json);
                            }, async:false
                        });
                        var numberOfGames = 0;
                        var kills = 0;
                        var deaths = 0;
                        var assists = 0;

                        if(championStatsJSON != false){
                            for(var champion = 0; champion < championStatsJSON.champions.length; champion++){
                                if(championStatsJSON.champions[champion].id == currentGameJSON.participants[index].championId){
                                    numberOfGames = championStatsJSON.champions[champion].stats.totalSessionsPlayed;
                                    kills = championStatsJSON.champions[champion].stats.totalChampionKills/numberOfGames;
                                    deaths = championStatsJSON.champions[champion].stats.totalDeathsPerSession/numberOfGames;
                                    assists = championStatsJSON.champions[champion].stats.totalAssists/numberOfGames;
                                }
                            }
                        }
                        var tier;
                        var division;
                        var points;
                        var wins;
                        var losses;
                        if(leagueJSON != false){
                                tier = leagueJSON[summonerID][0].tier;
                                division = leagueJSON[summonerID][0].entries[0].division;
                                points = leagueJSON[summonerID][0].entries[0].leaguePoints;
                                wins = leagueJSON[summonerID][0].entries[0].wins;
                                losses = leagueJSON[summonerID][0].entries[0].losses;
                        } else {
                            tier = "Unranked";
                        }
  
                        console.log(index);
                        var evenOdd = "odd";
                        if(index % 2 === 0){
                            evenOdd = "even";
                        }
                        var team = "Team1";
                        if(currentGameJSON.participants[index].teamId ==200){
                            team ="Team2"
                        }
                        $('#'+team).append('<tr id="' + currentGameJSON.participants[index].summonerName + '" class="' + evenOdd + ' summonerRow">' + 
                                '<td>' + currentGameJSON.participants[index].summonerName + '</td>' +
                                '<td style="text-align:left">' +
                                    '<div><img style="margin-right:5px" src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + currentGameJSON.participants[index].championId).text()+ '.png" height="25" width="25">' + $('#' + currentGameJSON.participants[index].championId).text() + '<b>('+ numberOfGames + ')</b></div>'+
                                '</td>' +
                                '<td>' +
                                    '<div>' + parseFloat(kills).toFixed(1) + '/' + parseFloat(deaths).toFixed(1) + '/' + parseFloat(assists).toFixed(1) + ': ' + parseFloat((kills+assists)/deaths).toFixed(1) + '</div>'+
                                '</td>' +
                                '<td>' +
                                    '<div>' + tier + ' ' + division + ' (' + points + ')</div>'+
                                '</td>' +
                                '<td>' +
                                    '<div>' + wins + '/' + losses + '</div>'+
                                '</td>' +

                            '</tr>'
                        );
                    }   
                }
                $('#summonersTable').show();
                var matchHistoryData;
                //get MatchHistory data
                /*
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "ajaxFunctions.php", //Relative or absolute path to response.php file
                    data:  { action: 'getRanked5v5Solo()', summonerInfoJSON.summonerInfo.summonerID, championList: $('#summoner' + i + 'ChampionID').text() },
                    success: function(dataObject){
                        matchHistoryData = JSON.parse(dataObject);
                    },
                    async:false
                });*/
            });
        }


        /*
if(json == false){
                            console.log("summoner is not in game");
                            //throw up error div
                        } else {
                            console.log("summoner is in game");
                            for(var i = 0; i < 10; i++){
                                var championGamesWon;
                                var championGamesLost;
                                var numberOfChampionGames;
                                var totalKills;
                                var totalDeaths;
                                
                                var urlEncodeSumName = data.participants[i].summonerName.toString().replace(/\s/g,"");
                                var div = $('<li id="' + urlEncodeSumName + '" class="summonerDisplay"><div><img style="float:left; margin-left:10px; margin-right:10px; margin-top:5px" src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + data.participants[i].championId ).text()+ '.png " height="25" width="25"></div>' + 
                                    '<div id="summoner' + i + 'id" style="display:none">' + data.participants[i].summonerId + '</div>' +
                                    '<div id="summoner' + i + 'ChampionID" style="display:none">' + data.participants[i].championId + '</div>' +  
                                    '<div class="sumNameDiv"><a onclick=showStats(\'' + urlEncodeSumName+ '\')>' + data.participants[i].summonerName +  
                                    '</a><span style="float:right">|</span></div>'+
                                    '<div id="' + urlEncodeSumName + 'championStats" style="" class="averageStats"></div>'+
                                    '<div id="' + urlEncodeSumName + 'AverageStats" style="" class="averageStats"></div>'+
                                    '<div id="' + urlEncodeSumName + 'Matches" style="display:none; float:left" class="matchliStyle"></div>'+
                                    '</li>');
                                $('#summonersList').append(div);

                                $.ajax({
                                    type: "POST",
                                    dataType: "json",
                                    url: "ajaxFunctions.php", //Relative or absolute path to response.php file
                                    data:  { action: 'getStats()', sumID: $('#summoner' + i + 'id').text(), region: $('select#regionSelect').val()},
                                    success: function(json){
                                        var championStatsData = JSON.parse(json);
                                        $('#' + urlEncodeSumName + 'championStats').html(
                                            '<table>'+
                                                '<th>Champion Stats: ' + $('#' + $('#summoner' + i + 'ChampionID').text()).text() + 
                                                '<tr>'+ 
                                                '</tr>' + 
                                            '</table>'

                                        );
                                    },
                                    async:false
                                });
                                $.ajax({
                                    type: "POST",
                                    dataType: "json",
                                    url: "ajaxFunctions.php", //Relative or absolute path to response.php file
                                    data:  { action: 'getRanked5v5Solo()', sumID: $('#summoner' + i + 'id').text(), championList: $('#summoner' + i + 'ChampionID').text() },
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
                                            li.id = $('#summoner' + i + 'id').text() + "match" + index;
                                            var winloss;
                                            li.className = 'match';
                                            if(data.matches[index].participants[0].stats.winner === true) {
                                                winloss = "win";
                                                wins++;
                                            } else {
                                                winloss="loss";
                                                losses++;
                                            }
                                            document.getElementById(urlEncodeSumName + "Matches").appendChild(li);
                                            var match = data.matches[index];
                                            $('#' + $('#summoner' + i + 'id').text() + "match" + index).html(
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
                                                    '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + match.participants[0].championId).html() + '.png" height="70" width="70">'+
                                                    '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + match.participants[0].championId).html() + '.png" height="70" width="70">'+
                                                    '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + match.participants[0].championId).html() + '.png" height="70" width="70">'+
                                                    '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + match.participants[0].championId).html() + '.png" height="70" width="70">'+
                                                    '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + match.participants[0].championId).html() + '.png" height="70" width="70">'+
                                                    '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + match.participants[0].championId).html() + '.png" height="70" width="70">'+
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
                                        $('#' + urlEncodeSumName + 'AverageStats').html(
                                            '<table class="averageTable">' + 
                                                '<tr>'+
                                                    '<td colspan="9" class="averageTableTitle">Average Game Stats</td>'+
                                                '</tr>'+
                                                '<tr>' +
                                                    '<th>Win/Loss</th>'+
                                                    '<th>KDA</th>'+
                                                    '<th>Kills</th>'+
                                                    '<th>Deaths</th>'+
                                                    '<th>Assists</th>'+
                                                    '<th>2x Kills</th>'+
                                                    '<th>3x Kills</th>'+
                                                    '<th>4x Kills</th>'+
                                                    '<th>Penta Kills</th>'+
                                                '</tr>'+
                                                '<tr>'+
                                                    '<td>' + wins + '/' + losses + ': ' + parseFloat((wins/matchesLength) * 100).toFixed(2) + '%</td>'+
                                                    '<td>' + kills + '/' + deaths + '/' + assists + ': ' + parseFloat((kills + assists)/deaths).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(kills/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(deaths/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(assists/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(doubleKills/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(tripKills/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(quadraKills/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(pentaKills/matchesLength).toFixed(2) + '</td>'+
                                                '</tr>'+
                                            '</table>'+
                                            '<table class="averageTable">' + 
                                                '<tr>'+
                                                    '<td colspan="4" class="averageTableTitle">Average Damage To Champions</td>'+
                                                '</tr>'+
                                                '<tr>' +
                                                    '<th>Physical</th>'+
                                                    '<th>Magical</th>'+
                                                    '<th>True</th>'+
                                                    '<th>Total</th>'+
                                                '</tr>'+
                                                '<tr>'+
                                                    '<td>' + parseFloat(physicalDamageToChamps/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(magicDamageToChamps/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(trueDamageToChamps/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(totalDamageToChampions/matchesLength).toFixed(2) + '</td>'+
                                                '</tr>'+
                                            '</table>'+
                                            '<table class="averageTable">' + 
                                                '<tr>'+
                                                    '<td colspan="4" class="averageTableTitle">Average Damage From Champions</td>'+
                                                '</tr>'+
                                                '<tr>' +
                                                    '<th>Physical</th>'+
                                                    '<th>Magical</th>'+
                                                    '<th>True</th>'+
                                                    '<th>Total</th>'+
                                                '</tr>'+
                                                '<tr>'+
                                                    '<td>' + parseFloat(physicalDamageTaken/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(magicDamageTaken/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(trueDamageTaken/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(totalDamageTaken/matchesLength).toFixed(2) + '</td>'+
                                                '</tr>'+
                                            '</table>'+
                                            '<table class="averageTable">' + 
                                                '<tr>'+
                                                    '<td colspan="4" class="averageTableTitle">Average Minion Counts</td>'+
                                                '</tr>'+
                                                '<tr>' +
                                                    '<th>Creep Score</th>'+
                                                    '<th colspan="2">Jungle Monsters</th>'+
                                                    '<th>Gold Earned</th>'+
                                                '</tr>'+
                                                '<tr>'+
                                                    '<td rowspan="2">' +parseFloat(totalMinionsKilled/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>Team: ' + parseFloat(teamMonsters/matchesLength).toFixed(2)+'</td>'+
                                                    '<td>Enemy: ' + parseFloat(enemyMonsters/matchesLength).toFixed(2) + '</td>'+
                                                    '<td rowspan="2">' + parseFloat(goldEarned/matchesLength).toFixed(2) + '</td>'+
                                                '</tr>'+
                                                '<tr>' + 
                                                    '<td colspan="2">Total: ' + parseFloat(monstersKilled/matchesLength).toFixed(2) + '</td>' + 
                                                '</tr>'+
                                            '</table>' + 
                                            '<table class="averageTable">' + 
                                                '<tr>'+
                                                    '<td colspan="4" class="averageTableTitle">Average Warding Stats</td>'+
                                                '</tr>'+
                                                '<tr>' +
                                                    '<th>Vision Wards</th>'+
                                                    '<th>Sight Wards</th>'+
                                                    '<th>Wards Placed</th>'+
                                                    '<th>Wards Killed</th>'+
                                                '</tr>'+
                                                '<tr>'+
                                                    '<td>' + parseFloat(visionWards/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(sightWards/matchesLength).toFixed(2)+'</td>'+
                                                    '<td>' + parseFloat(wardsPlaced/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(wardsKilled/matchesLength).toFixed(2) + '</td>'+
                                                '</tr>'+
                                            '</table>'
                                            );                                      
                                    },
                                    async:false
                                });
                            }
                        }*/

    </script>
    <script src="./js/bootstrap.min.js"></script>
</body>
</html>
