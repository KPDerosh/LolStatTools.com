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
    <div id="champSelectContainer">
        <table id="championFilters" class="table">
            <tr>  
                <th style="font-size:20px">Group Selection</th>
                <th style="font-size:20px">Champions</th>
            </tr> 
            <tr><!--
                <td>
                   <select name="queueSelectBox" size="10" id="queueTypeSelect" multiple="multiple">
                        <option value="RANKED_SOLO_5x5" selected="selected">Ranked Solo 5v5</option>
                    </select>
                </td>!--> 
                <td>
                    <select class="form-control" name="groupSelectBox" onclick="setChampions()" onKeyDown="if(event.keyCode==13) setChampions();" style="position:relative" size="10" id="groupSelectList" multiple="multiple">
                        <option value="" selected="selected">Choose And Select More</option>
                        <option value="22,51,42,119,81,104,222,429,96,236,133,15,18,29,110,67">Marksman</option>
                        <option value="412,78,14,111,2,86,27,57,12,122,77,89,150,254,39,106,20,102,36,113,8,154,421,120,19,72,54,75,58,31,33,83,98,201,5,44,32,48,59">Tank</option>
                        <option value="12,432,53,201,40,43,89,267,111,20,37,16,44,412,26,143">Support</option>
                        <option value="103, 84,34,1,268,63,69,131,3,79,74,30,38,55,10,85,7,127,99,90,25,76,61,68,13,50,134,4,45,161,112,8,101,115,26,143">Mage</option>
                    </select>
                </td>
                <td>
                    <select class="form-control" name="championSelectBox" size="10" id="championSelectList" multiple="multiple">
                    </select>
				</td>
            </tr>
            <tr>
                <td>
                    <div>
                        <input type="text" id="summonerName" class="form-control" placeholder="Enter Summoner Name to find stats" name="name">
                    </div>
                </td>
				<td>
					<select id="regionSelect" class="form-control">
						<option value="-1">Please select a region</option>
						<option value="na">NA - North America</option>
						<option value="eu">EU - Europe</option>
						<option value="eune">EU - Europe Nordic & East</option>
                        <option value="br">BR - Brazil</option>
						<option value="br">KR - Korea</option>
                        <option value="lan">LAN - Latin America North</option>
						<option value="las">LAS - Latin America South</option>
                        <option value="oce">OCE - Oceania</option>
						<option value="ru">RU - Russia</option>
                        <option value="tr">TR - Turkey</option>
					</select>
				</td>
			</tr>
            <tr>
                <td>
                    <button onClick="getStats()" class="btn btn-default">Get Stats</button>
                </td>
                <td>
                    <button id="currentGame" onClick="getCurrentGame()" style="display:none" class="btn btn-default">See Current Game</button>
                </td>
            </tr>
        </table>
    </div>
    <div id="statsContainer" class="container">
        <div id="summonerStats">
            <div id="summoner1" style="display:none" class="summonerDisplay"></div>
            <div id="summoner1AverageStats" style="display:none"></div>
                <ul id="summoner1Matches" style="display:none" class="matchliStyle"><ul>
            <ul id="summonersList" style="display:none">
                <li id="summoner2" class="summonerDisplay"></li>
                <li id="summoner2AverageStats" class="summonerDisplay"></li>
                <li id="summoner3" class="summonerDisplay"></li>
                <li id="summoner3AverageStats" class="summonerDisplay"></li>
                <li id="summoner4" class="summonerDisplay"></li>
                <li id="summoner4AverageStats" class="summonerDisplay"></li>
                <li id="summoner5" class="summonerDisplay"></li>
                <li id="summoner5AverageStats" class="summonerDisplay"></li>
                <li id="summoner6" class="summonerDisplay"></li>
                <li id="summoner6AverageStats" class="summonerDisplay"></li>
                <li id="summoner7" class="summonerDisplay"></li>
                <li id="summoner7AverageStats" class="summonerDisplay"></li>
                <li id="summoner8" class="summonerDisplay"></li>
                <li id="summoner8AverageStats" class="summonerDisplay"></li>
                <li id="summoner9" class="summonerDisplay"></li>
                <li id="summoner9AverageStats" class="summonerDisplay"></li>
                <li id="summoner10" class="summonerDisplay"></li>
                <li id="summoner10AverageStats" class="summonerDisplay"></li>
            </ul>
        </div>
            
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
                        $('#championSelectList').append( $("<option></option>").attr("value", jsonObj.data[champion].id + " " ).text(champion) );
                        $( '<div style="display:hidden" id="' + jsonObj.data[champion].id + '">'+ champion +'</div>' ).appendTo( "#championSelectList" );
                    }
                    sortSelect(document.getElementById('championSelectList'));
                    console.log(jsonObj.data);
                }
            });
        });

        //Function to display the filters to select champions based on summoner name
        function displayFilters(){
            $('#champSelectContainer').show();
            $('#championFilters').show();
        }

        function sortSelect(selElem) {
            var tmpAry = new Array();
            for (var i=0;i<selElem.options.length;i++) {
                tmpAry[i] = new Array();
                tmpAry[i][0] = selElem.options[i].text;
                tmpAry[i][1] = selElem.options[i].value;
            }
            tmpAry.sort();
            while (selElem.options.length > 0) {
                selElem.options[0] = null;
            }
            for (var i=0;i<tmpAry.length;i++) {
                var op = new Option(tmpAry[i][0], tmpAry[i][1]);
                selElem.options[i] = op;
            }
            return;
        }

        //Method to set champions selected based on group selection box.
        function setChampions(){
            $("#championSelectList option:selected").prop("selected", false);
            var championsSelected = $('select#groupSelectList').val();
            var splitListOfChampions = championsSelected.toString().split(",");
            for(i = 0; i < splitListOfChampions.length; i++){
                $('#championSelectList option[value="' + splitListOfChampions[i] + ' "').prop("selected", true);
            }
        }

        function showStats(summonerName){
            $('#' + summonerName + 'AverageStats').show();
            $('#' + summonerName + 'Matches').show();
        }

        function getStats(){
            $(document).ready(function(){
                var summonerName = document.getElementById('summonerName').value;
                var urlEncodeSumName = summonerName.toString().replace(/\s/g,"");
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "ajaxFunctions.php", //Relative or absolute path to response.php file
                    data:  { action: 'getSummonerID()', sumName: urlEncodeSumName, region: $('select#regionSelect').val() },
                    success: function(json){
                        var jsonObj = JSON.parse(json);
                        for(var summoner in jsonObj){
                            var summonersOfficialName = jsonObj[summoner].name;
                            console.log(summonersOfficialName);
                            //Call method to get Champion Name
                
                            $('#summoner1').html('<div id="summoner1OfficialName" style="display:none">' + summonersOfficialName + '</div>' + 
                                                 '<div id="summoner1ID" style="display:none">' + jsonObj[summoner].id + '</div>'+
                                                 '<div id="summoner1Level" style="display:none">' + jsonObj[summoner].summonerLevel + '</div>'+
                                                 '<div id="' + summonersOfficialName + '" class=sumNameDiv><a onclick=\'showStats("summoner1")\' href="#">' + summonersOfficialName+'</a> | </div>');
                        }

                        //Empty out averages div and matches div to load info into
                        $('#summoner1AverageStats').empty();
                        $('#summoner1Matches').empty();
                        //comment out the champion stuff for now
                        var CSVListOfChamps = $('select#championSelectList').val();
                        var CSVChampsNoSpace = "";

                        if(CSVListOfChamps != null){
                            CSVChampsNoSpace = CSVListOfChamps.toString().replace(/\s/g,"");
                        }
                        console.log($('#summoner1ID').text());
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "ajaxFunctions.php", //Relative or absolute path to response.php file
                            data:  { action: 'getRanked5v5Solo()', sumID: $('#summoner1ID').text(), championList: CSVChampsNoSpace},
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
                                for(i = matchesLength - 1; i >= 0; i--){
                                    //Call method to get Champion Name
                                    var li = document.createElement('li');
                                    li.id = "match" + i;
                                    var winloss;
                                    li.className = 'match'
                                    if(data.matches[i].participants[0].stats.winner === true) {
                                        winloss = "win"
                                        wins++;
                                    } else {
                                        winloss="loss";
                                        losses++;
                                    }
                                    document.getElementById('summoner1Matches').appendChild(li);
                                    $('#match' + i).html(
                                        '<div class="matchHeader ' + winloss + '">Champion: ' + $('#' + data.matches[i].participants[0].championId).html() + 
                                            '<span id="match' + i + 'duration" class="matchDuration">Match Duration: ' + parseFloat(data.matches[i].matchDuration/60).toFixed(0) + ':'+ data.matches[i].matchDuration%60 + '<span style="color:white"> | </span>' + winloss + '</span>'+
                                        '</div>' + 
                                        '<div class="championData">' +
                                            '<div class="championImage"><img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + data.matches[i].participants[0].championId).html() + '.png" height="70" width="70">' + ' </div>' +
                                            '<span class="kdaInfo">KDA: ' +
                                                data.matches[i].participants[0].stats.kills + " / " +
                                                data.matches[i].participants[0].stats.deaths + " / " +
                                                data.matches[i].participants[0].stats.assists + 
                                            '</span>' +
                                            
                                        '</div>'+
                                        '<div class="itemBuild">' +
                                            '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + data.matches[i].participants[0].championId).html() + '.png" height="70" width="70">'+
                                            '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + data.matches[i].participants[0].championId).html() + '.png" height="70" width="70">'+
                                            '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + data.matches[i].participants[0].championId).html() + '.png" height="70" width="70">'+
                                            '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + data.matches[i].participants[0].championId).html() + '.png" height="70" width="70">'+
                                            '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + data.matches[i].participants[0].championId).html() + '.png" height="70" width="70">'+
                                            '<img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + data.matches[i].participants[0].championId).html() + '.png" height="70" width="70">'+
                                        '</div>'
                                    );                   
                                            kills += data.matches[i].participants[0].stats.kills;
                                            deaths += data.matches[i].participants[0].stats.deaths;
                                            assists += data.matches[i].participants[0].stats.assists;

                                            doubleKills += data.matches[i].participants[0].stats.doubleKills;
                                            tripKills += data.matches[i].participants[0].stats.tripleKills;
                                            quadraKills += data.matches[i].participants[0].stats.quadraKills;
                                            pentaKills += data.matches[i].participants[0].stats.pentaKills;

                                            physicalDamageToChamps += data.matches[i].participants[0].stats.physicalDamageDealtToChampions;
                                            magicDamageToChamps += data.matches[i].participants[0].stats.magicDamageDealtToChampions;
                                            trueDamageToChamps += data.matches[i].participants[0].stats.trueDamageDealtToChampions;
                                            totalDamageToChampions += data.matches[i].participants[0].stats.totalDamageDealtToChampions;

                                            magicDamageTaken += data.matches[i].participants[0].stats.magicDamageTaken;
                                            physicalDamageTaken += data.matches[i].participants[0].stats.physicalDamageTaken;
                                            trueDamageTaken += data.matches[i].participants[0].stats.trueDamageTaken;
                                            totalDamageTaken += data.matches[i].participants[0].stats.totalDamageTaken;

                                            totalMinionsKilled += data.matches[i].participants[0].stats.minionsKilled;
                                            monstersKilled += data.matches[i].participants[0].stats.neutralMinionsKilled;
                                            teamMonsters += data.matches[i].participants[0].stats.neutralMinionsKilledTeamJungle;
                                            enemyMonsters += data.matches[i].participants[0].stats.neutralMinionsKilledEnemyJungle;
                                            goldEarned  += data.matches[i].participants[0].stats.goldEarned;
                                            goldSpent += data.matches[i].participants[0].stats.goldSpent;
                                            visionWards += data.matches[i].participants[0].stats.visionWardsBoughtInGame;
                                            sightWards += data.matches[i].participants[0].stats.sightWardsBoughtInGame;
                                            wardsPlaced += data.matches[i].participants[0].stats.wardsPlaced;
                                            wardsKilled += data.matches[i].participants[0].stats.wardsKilled;
                                            towersDestroyed  += data.matches[i].participants[0].stats.towerKills;
                                            totalTimeCCDealt += data.matches[i].participants[0].stats.totalTimeCrowdControlDealt;
                                        }
                                        $('#summoner1AverageStats').html(
                                            '<table class="averageTable">' + 
                                                '<tr>'+
                                                    '<td colspan="9">Average Game Stats</td>'+
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
                                                    '<td>' + wins + '/' + losses + ' : ' + parseFloat((wins/matchesLength) * 100).toFixed(2) + '%</td>'+
                                                    '<td>' + kills + '/' + deaths + '/' + assists + ':' + parseFloat((kills + assists)/deaths).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(kills/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(deaths/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(assists/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(doubleKills/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(tripKills/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(quadraKills/matchesLength).toFixed(2) + '</td>'+
                                                    '<td>' + parseFloat(pentaKills/matchesLength).toFixed(2) + '</td>'+
                                            '</table>'+
                                            '<table class="averageTable">' + 
                                                '<tr>'+
                                                    '<td colspan="4">Average Damage To Champions</td>'+
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
                                            '</table>'
                                            );
                                        /*
                                        $('#AVGPhysicalDTC').html(parseFloat((physicalDamageToChamps/matchesLength).toFixed(2)));
                                        $('#AVGMagicDTC').html(parseFloat((magicDamageToChamps/matchesLength).toFixed(2)));
                                        $('#AVGTrueDTC').html(parseFloat((trueDamageToChamps/matchesLength).toFixed(2)));
                                        $('#AVGPhysicalTFC').html(parseFloat((physicalDamageTaken/matchesLength).toFixed(2)));
                                        $('#AVGMagicTFC').html(parseFloat((magicDamageTaken/matchesLength).toFixed(2)));
                                        $('#AVGTrueTFC').html(parseFloat((trueDamageTaken/matchesLength).toFixed(2)));
                                        $('#AVGTotalMinions').html(parseFloat((totalMinionsKilled/matchesLength).toFixed(2)));
                                        $('#AVGTotalMonsters').html(parseFloat((monstersKilled/matchesLength).toFixed(2)));
                                        $('#AVGTeamMonsters').html(parseFloat((teamMonsters/matchesLength).toFixed(2)));
                                        $('#AVGEnemyMonsters').html(parseFloat((enemyMonsters/matchesLength).toFixed(2)));
                                        $('#AVGGoldEarned').html(parseFloat((goldEarned/matchesLength).toFixed(2)));
                                        $('#AVGVWards').html(parseFloat((visionWards/matchesLength).toFixed(2)));
                                        $('#AVGSWards').html(parseFloat((sightWards/matchesLength).toFixed(2)));
                                        $('#AVGWardsPlaced').html(parseFloat((wardsPlaced/matchesLength).toFixed(2)));
                                        $('#AVGWardsKilled').html(parseFloat((wardsKilled/matchesLength).toFixed(2)));
                                        $('#AVGtowersDestroyed').html(parseFloat((towersDestroyed/matchesLength).toFixed(2)));
                                        $('#AVGCCTime').html(parseFloat(((totalTimeCCDealt/60)/matchesLength).toFixed(0)) + ' mins ' + parseFloat(((totalTimeCCDealt%60)/matchesLength).toFixed(0)) + ' secs');
                                        $('AVGTotalTFC').html(parseFloat((totalDamageTaken/matchesLength).toFixed(2)));
                                        $('AVGTotalDTC').html(parseFloat((totalDamageToChampions/matchesLength).toFixed(2)));
                                        */$('#summoner1').show();
                                    }
                                });
                            }
                        });
            });
        }
                
        
    </script>
    <script src="./js/bootstrap.min.js"></script>
</body>
</html>


