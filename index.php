<!DOCTYPE html>
<html>
<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Cuddly Assassin</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/leagueoflegendsmain.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
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
                    <li class="active"><a href="#">Recent Game Stats</a></li>
                    <li><a href="./RiotApiChallenge/main_stats.php">Nurf Game Statistics</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
    
    <!-- Filter champs with this container!-->
    <div id="champSelectContainer">
            <table id="championFilters" class="table">
                <tr>
                    <th style="font-size:20px">Queue Type</th>
                    <th style="font-size:20px">Group Selection</th>
                    <th style="font-size:20px">Champions</th>
                </tr> 
                <tr>
                    <td>
                        <select name="queueSelectBox" size="10" id="queueTypeSelect" multiple="multiple">
                            <option value="RANKED_SOLO_5x5" selected="selected">Ranked Solo 5v5</option>
                        </select>
                    </td> 
                    <td>
                        <select  name="groupSelectBox" onclick="setChampions()" onKeyDown="if(event.keyCode==13) setChampions();" style="position:relative" size="10" id="groupSelectList" multiple="multiple">
                            <option value="" selected="selected">Choose And Select More</option>
                            <option value="22,51,42,119,81,104,222,429,96,236,133,15,18,29,110,67">Marksman</option>
                            <option value="412,78,14,111,2,86,27,57,12,122,77,89,150,254,39,106,20,102,36,113,8,154,421,120,19,72,54,75,58,31,33,83,98,201,5,44,32,48,59">Tank</option>
                            <option value="12,432,53,201,40,43,89,267,111,20,37,16,44,412,26,143">Support</option>
                            <option value="103, 84,34,1,268,63,69,131,3,79,74,30,38,55,10,85,7,127,99,90,25,76,61,68,13,50,134,4,45,161,112,8,101,115,26,143">Mage</option>
                        </select>
                    </td>
                    <td>
                        <select style="position:relative" name="championSelectBox" size="10" id="championSelectList" multiple="multiple">
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div>
                            <input type="text" id="summonerName" class="form-control" placeholder="Enter Summoner Name to find stats" name="name">
                        </div>
                    </td>
                </tr> 
                <tr>
                    <td>
                        <button onClick="getStats()" class="btn btn-default">Get Stats</button>
                    </td>
                    <td>
                        <div id="currentGameButton"></div>
                    </td>
                </tr>
            </table>
        
    </div>

    <div id="statsContainer" class="container">
        <div id="summonerStats" style="display:none">
            <div id="displayStats" style="display:none">
                <div id="summonerGameAveragesTitle"></div>
                    
                    <table id="summonerGameAveragesTable" class="table">
                        <tr>
                            <th id="sumGameAvgTableGameType" ></th>
                            <th id="sumGameAvgTableWins"     ></th>
                            <th id="sumGameAvgTableLosses"   ></th>
                            <th id="sumGameAvgTableWinLoss"  ></th>
                        </tr> 
                        <tr>
                            <td id="AVGKills"></td>
                            <td id="AVGDeaths"></td>
                            <td id="AVGAssists"></td>
                            <td id="AVGKDA"></td>
                        </tr> 
                        <tr>
                            <td id="AVGDoubles"></td>
                            <td id="AVGTriples"></td>
                            <td id="AVGQuadras"></td>
                            <td id="AVGPentas"></td>
                        </tr>
                    </table>
                    <hr style="color:white">
                    <table id="summonerAVGDamageToChamps" class="table">
                        <tr>
                            <th class="col-lg-3">Damages Given (DTC - Damage To Champions)</th>
                        </tr>
                        <tr>
                            <td id="AVGPhysicalDTC" class="col-lg-2"></td>
                            <td id="AVGMagicDTC"    class="col-lg-2"></td>
                            <td id="AVGTrueDTC"     class="col-lg-2"></td>
                            <td class="col-lg-6"></td>
                        </tr>
                    </table>
                    <hr style="color:white">
                    <table id="summonerAVGDamageTFCChamps" class="table">
                        <tr>
                            <th class="col-lg-3">Damages Taken (TFC - Taken From Champs)</th>
                        </tr>
                        <tr>
                            <td id="AVGPhysicalTFC" class="col-lg-2"></td>
                            <td id="AVGMagicTFC" class="col-lg-2"></td>
                            <td id="AVGTrueTFC" class="col-lg-2"></td>
                            <td class="col-lg-6"></td>
                        </tr>
                    </table>
                    <hr style="color:white">
                    <table id="summonerAVGDamageCS" class="table">
                        <tr>
                            <th>Creep SCORES!!</th>
                        </tr>
                        <tr>
                            <td id="AVGTotalMinions" class="col-lg-2"></td>
                            <td id="AVGGoldEarned" class="col-lg-2"></td>
                            <td class="col-lg-8"></td>
                        </tr>
                        <tr>
                            <td id="AVGTotalMonsters" class="col-lg-2"></td>
                            <td id="AVGTeamMonsters" class="col-lg-2"></td>
                            <td id="AVGEnemyMonsters" class="col-lg-2"></td>
                            <td class="col-lg-6"></td>
                        </tr>
                    </table>
                    <hr style="color:white">
                    <table id="summonerAVGWards" class="table">
                        <tr>
                            <th>WARDS!!</th>
                        </tr>
                        <tr>
                            <td id="AVGVWards" class="col-lg-2"></td>
                            <td id="AVGSWards" class="col-lg-2"></td>
                            <td id="AVGWardsPlaced" class="col-lg-2"></td>
                            <td id="AVGWardsKilled" class="col-lg-2"></td>
                            <td class="col-lg-4"></td>
                        </tr>
                    </table>
                    <hr style="color:white">
                    <table id="summonerAVGMisc" class="table">
                        <tr>
                            <th>MISC!!</th>
                        </tr>
                        <tr>
                            <td id="AVGtowersDestroyed" class="col-lg-2"></td>
                            <td id="AVGCCTime" class="col-lg-2"></td>
                            <td class="col-lg-8"></td>
                        </tr>    
                    </table>
            </div>
        </div>
        <div id="matchesContainer" ></div>
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
            $("#championSelectList option:selected").prop("selected", false)
            console.log("working");
            var championsSelected = $('select#groupSelectList').val();
            console.log(championsSelected);
            var splitListOfChampions = championsSelected.toString().split(",");
            console.log(splitListOfChampions);
            for(i = 0; i < splitListOfChampions.length; i++){
                $('#championSelectList option[value="' + splitListOfChampions[i] + ' "').prop("selected", true);
            }
        }

        function getStats(){
            $(document).ready(function(){
                $('#matchesContainer').empty();
                var summonerName = document.getElementById('summonerName').value;
                
                var CSVListOfChamps = $('select#championSelectList').val();
                var CSVChampsNoSpace = "";
                console.log("please wtf");
                //First things first check for a current game to display.
                var urlEncodeSumName = summonerName.toString().replace(/\s/g,"");
                $('#currentGameButton').html('<a href="https://kelnet.org/game.php?game=' + urlEncodeSumName + '"><button style="margin:auto" class="btn btn-default">See Current Game</button></a>').show();
                if(CSVListOfChamps != null){
                    CSVChampsNoSpace = CSVListOfChamps.toString().replace(/\s/g,"");
                }

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "ajaxFunctions.php", //Relative or absolute path to response.php file
                    data:  { action: 'getSummonerID()', sumName: urlEncodeSumName},
                    success: function(data){
                        var jsonObj = JSON.parse(data);
                        var sumIdNum = jsonObj[urlEncodeSumName].id;
                        var queueType = $('select#queueTypeSelect').val()[0];
                        //urlFace = 'https://na.api.pvp.net/api/lol/na/v2.2/matchhistory/' + data[sumIdNum].id + '?&rankedQueues=' + queueType + '&beginIndex=0&endIndex=15&api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9';
                       
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "ajaxFunctions.php", //Relative or absolute path to response.php file
                            data:  { action: 'getMatchHistoryStats()', sumID: sumIdNum, championList: CSVChampsNoSpace, queueType: queueType},
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

                                    
                                    var div = document.createElement('div');
                                    div.id = "match" + i;
                                    if(data.matches[i].participants[0].stats.winner === true) {
                                        div.className = 'win'
                                        wins++;
                                    } else {
                                        div.className = 'loss'
                                        losses++;
                                    }
                                    document.getElementById('matchesContainer').appendChild(div);
                                    console.log($('#'+data.matches[i].participants[0].championId).html());
                                    $('#match' + i).html(
'<table id="matchTable' + i + '" class="matchTableStyle table">' +
    '<tr>' +
        '<td style="width:10%"></td>'+
        '<td style="width:300px">' +
            '<table id="KDATable" class="table" >'+
                '<tr><th colspan="2">GAME Type: Ranked Solo</th></tr>'+
                '<tr><td id="championName' + i + '" colspan="2" style="text-align:left">Champion Name: ' + $('#' + data.matches[i].participants[0].championId).html() +'</td></tr>'+
                '<tr>'+
                    '<td rowspan="2"><img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + data.matches[i].participants[0].championId).html() + '.png" height="70" width="70"></td>' +
                    '<td>' +
                        '<div>KDA: ' + 
                            data.matches[i].participants[0].stats.kills + " / " +
                            data.matches[i].participants[0].stats.deaths + " / " +
                            data.matches[i].participants[0].stats.assists +
                        '</div>' +
                    '</td>' + 
                '</tr>' + 
                '<tr>'+
                    '<td>Match Duration: ' + parseFloat(data.matches[i].matchDuration/60).toFixed(0) + ':'+ data.matches[i].matchDuration%60 +'</td>' + 
                '</tr>' + 
            '</table>'+
        '</td>'+
        '<td>' +
            '<table id="CSTable" style="text-align:center" class="table">'+
                '<tr><th colspan="2" style="text-align:center"><u>Creep Score</u></th></tr>'+
                '<tr>'+
                    '<td colspan="2">TOTAL CS: ' + (data.matches[i].participants[0].stats.minionsKilled + data.matches[i].participants[0].stats.neutralMinionsKilled) +'</td>' 
                +'</tr>' +
                '<tr>' +
                    '<td colspan="2">Jungle Creeps: ' + data.matches[i].participants[0].stats.neutralMinionsKilled +'</td>' 
                +'</tr>' + 
                '<tr>' +
                    '<td>Team: ' + data.matches[i].participants[0].stats.neutralMinionsKilledTeamJungle + 
                    '</td><td>Enemy: ' + data.matches[i].participants[0].stats.neutralMinionsKilledEnemyJungle +'</td>' 
                +'</tr>' +
                '<tr>' +
                    '<td colspan="2">Gold Earned: ' + data.matches[i].participants[0].stats.goldEarned
                +'</tr>' + 
            '</table>'+
        '</td>'+
        '<td>' +
            '<table id="WardsTable" style="text-align:center" class="table">'+
                '<tr><th colspan="2" style="text-align:center"><u>Wards</u></th></tr>'+
                '<tr>'+
                    '<td colspan="2">#Vision Wards: ' + data.matches[i].participants[0].stats.visionWardsBoughtInGame +'</td>' 
                +'</tr>' +
                '<tr>' +
                    '<td colspan="2">#Sight Wards: ' + data.matches[i].participants[0].stats.sightWardsBoughtInGame +'</td>' 
                +'</tr>' + 
                '<tr>' +
                    '<td>Wards Placed: ' + data.matches[i].participants[0].stats.wardsPlaced + '</td>'
                +'</tr>' +
                '<tr>' +
                    '<td colspan="2">Wards Killed: ' + data.matches[i].participants[0].stats.wardsKilled
                +'</tr>' + 
            '</table>'+
        '</td>'+
        '<td>' +
            '<table id="MultiKillsTable" style="text-align:center" class="table">'+
                '<tr><th colspan="2" style="text-align:center"><u>MultiKills</u></th></tr>'+
                '<tr>'+
                    '<td>X2 Kills: ' + data.matches[i].participants[0].stats.doubleKills +'</td>' 
                +'</tr>' +
                '<tr>' +
                    '<td>X3 Kills: ' + data.matches[i].participants[0].stats.tripleKills +'</td>' 
                +'</tr>' +
                '<tr>' +
                    '<td>X4 Kills: ' + data.matches[i].participants[0].stats.quadraKills+'</td>' 
                +'</tr>' + 
                '<tr>' +
                    '<td>X5 Kills: ' +  data.matches[i].participants[0].stats.pentaKills +'</td>' 
                +'</tr>' +  
            '</table>'+
        '</td>'+
        '<td>' +
            '<table id="DamageGivenTable" style="text-align:center" class="table">'+
                '<tr><th colspan="2" style="text-align:center"><u>Damage Dealt To Champs</u></th></tr>'+
                '<tr>'+
                    '<td>Physical Damage Dealt: ' + data.matches[i].participants[0].stats.physicalDamageDealtToChampions +'</td>' 
                +'</tr>' +
                '<tr>' +
                    '<td>Magic Damage Dealt: ' + data.matches[i].participants[0].stats.magicDamageDealtToChampions +'</td>' 
                +'</tr>' +
                '<tr>' +
                    '<td>True Damage Dealt: ' + data.matches[i].participants[0].stats.trueDamageDealtToChampions + '</td>' 
                +'</tr>' + 
                '<tr>' +
                    '<td>Total Damage Dealt: ' +  data.matches[i].participants[0].stats.totalDamageDealtToChampions +'</td>' 
                +'</tr>' +  
            '</table>'+
        '</td>'+
        '<td>' +
            '<table id="DamageTakenTable" style="text-align:center" class="table">'+
                '<tr><th colspan="2" style="text-align:center"><u>Damage Taken From Champs</u></th></tr>'+
                '<tr>'+
                    '<td>Physical Damage Taken: ' + data.matches[i].participants[0].stats.physicalDamageTaken +'</td>' 
                +'</tr>' +
                '<tr>' +
                    '<td>Magic Damage Taken: ' + data.matches[i].participants[0].stats.magicDamageTaken +'</td>' 
                +'</tr>' +
                '<tr>' +
                    '<td>True Damage Taken: ' + data.matches[i].participants[0].stats.trueDamageTaken + '</td>' 
                +'</tr>' + 
                '<tr>' +
                    '<td>Total Damage Taken: ' +  data.matches[i].participants[0].stats.totalDamageTaken +'</td>' 
                +'</tr>' +  
            '</table>'+
        '</td>'+
    '</tr>'+
'</table>'
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
                                $('#summonerGameAveragesTitle').html("Summoner Game Averages: " + summonerName);
                                $('#sumGameAvgTableWins').html("Wins : " + wins);
                                $('#sumGameAvgTableLosses').html("Losses : " + losses);
                                $('#sumGameAvgTableWinLoss').html("W/L: " + wins + "/" + losses + ":" + parseFloat((wins/matchesLength) * 100).toFixed(2) + "%");
                                $('#AVGKills').html("AVG Kills: " + parseFloat(kills/matchesLength).toFixed(2));
                                $('#AVGDeaths').html("AVG Deaths: " + parseFloat(deaths/matchesLength).toFixed(2));
                                $('#AVGAssists').html("AVG Assists: " + parseFloat(assists/matchesLength).toFixed(2));
                                $('#AVGKDA').html("AVG KDA: " + parseFloat((kills + assists)/deaths).toFixed(2) + "1");
                                $('#AVGDoubles').html("AVG Doubles: " + parseFloat(doubleKills/matchesLength).toFixed(2));
                                $('#AVGTriples').html("AVG Triples: " + parseFloat(tripKills/matchesLength).toFixed(2));
                                $('#AVGQuadras').html("AVG Quadras: " + parseFloat(quadraKills/matchesLength).toFixed(2));
                                $('#AVGPentas').html("AVG Pentas: " + parseFloat(pentaKills/matchesLength).toFixed(2));
                                $('#AVGPhysicalDTC').html("AVG Physical DTC: " + parseFloat((physicalDamageToChamps/matchesLength).toFixed(2)));
                                $('#AVGMagicDTC').html("AVG Magic DTC: " + parseFloat((magicDamageToChamps/matchesLength).toFixed(2)));
                                $('#AVGTrueDTC').html("AVG True DTC: " + parseFloat((trueDamageToChamps/matchesLength).toFixed(2)));
                                $('#AVGPhysicalTFC').html("AVG Physical TFC: " + parseFloat((physicalDamageTaken/matchesLength).toFixed(2)));
                                $('#AVGMagicTFC').html("AVG Magic TFC: " + parseFloat((magicDamageTaken/matchesLength).toFixed(2)));
                                $('#AVGTrueTFC').html("AVG True TFC: " + parseFloat((trueDamageTaken/matchesLength).toFixed(2)));
                                $('#AVGTotalMinions').html("AVG Total Minions: " + parseFloat((totalMinionsKilled/matchesLength).toFixed(2)));
                                $('#AVGTotalMonsters').html("AVG Total Monsters: " + parseFloat((monstersKilled/matchesLength).toFixed(2)));
                                $('#AVGTeamMonsters').html("AVG Team Jungle Monsters: " + parseFloat((teamMonsters/matchesLength).toFixed(2)));
                                $('#AVGEnemyMonsters').html("AVG Enemy Jungle Monsters: " + parseFloat((enemyMonsters/matchesLength).toFixed(2)));
                                $('#AVGGoldEarned').html("AVG Gold Earned: " + parseFloat((goldEarned/matchesLength).toFixed(2)));
                                $('#AVGVWards').html("AVG Vision Wards: " + parseFloat((visionWards/matchesLength).toFixed(2)));
                                $('#AVGSWards').html("AVG Sight Wards: " + parseFloat((sightWards/matchesLength).toFixed(2)));
                                $('#AVGWardsPlaced').html("AVG Wards Placed: " + ((wardsPlaced/matchesLength).toFixed(2)));
                                $('#AVGWardsKilled').html("AVG Wards Killed: " + parseFloat((wardsKilled/matchesLength).toFixed(2)));
                                $('#AVGtowersDestroyed').html("AVG Towers Destroyed: " + parseFloat((towersDestroyed/matchesLength).toFixed(2)));
                                $('#AVGCCTime').html("AVG CC Time Dealt: " + parseFloat(((totalTimeCCDealt/60)/matchesLength).toFixed(0)) + ' mins ' + parseFloat(((totalTimeCCDealt%60)/matchesLength).toFixed(0)) + ' secs');
                                $('AVGTotalTFC').html("AVG Total Damage Taken: " + parseFloat((totalDamageTaken/matchesLength).toFixed(2)));
                                $('AVGTotalDTC').html("AVG Total Dealt: " + parseFloat((totalDamageToChampions/matchesLength).toFixed(2)));
                                $('#displayStats').show();
                                $('#summonerStats').show();
                            }
                        });
                    }
                });
            });
        }
        
    </script>
    <!--<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.2.min.js"></script>
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) 
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>


