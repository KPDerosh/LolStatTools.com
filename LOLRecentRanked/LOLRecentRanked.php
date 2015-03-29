<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cuddly Assassin</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../LOLRecentRanked/css/leagueoflegendsmain.css">
    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.2.min.js"></script>
</head>

<body>
    <div id="wrapper">
        <div id="header">LOL Stat Tools</div>
    </div> 

    <div id="sideBarNav" class="sideBarNav">
        <ul style="list-style-type:none">
            <li><a id="homeLink" href="../index.php">Home</a></li>
            <li><a id="recentGameStatsLink" href="#">Recent Ranked Avgs</a></li>
            <li><a id="currentGameLink" href="../CurrentGame/currentgamestats.php">Current Game</a></li>
    </div>

    <div id="statsContainer">
        <!-- Filter champs with this container! -->
        <div id="champSelectContainer">
            <table id="championFilters">
                <tr>
                    <th style="font-size:40px">Champions</th>
                </tr> 
            </table>
            <div>
                <select style="position:relative" size="10" id="championSelectList" multiple="multiple"></select>
            </div>
        </div>

        <div id="LOLStatsFormDiv" style="height: 50px;">
            <legend id="recentGamesLegend">Summoner ID:</legend>
            <input type=text id="summonerName">
            <input id="summonerStatsButton" type="submit" name="Submit" value="Submit" onclick="getStats()">
        </div>

        <div id="summonerStats">
            <div id="displayStats" style="display:none">
                <div id="summonerGameAveragesTitle"></div>
                    
                    <table id="summonerGameAveragesTable" class="statstable">
                        <tr>
                            <th id="sumGameAvgTableGameType" class="sumGameAvgTableHeader"></th>
                            <th id="sumGameAvgTableWins"     class="sumGameAvgTableHeader"></th>
                            <th id="sumGameAvgTableLosses"   class="sumGameAvgTableHeader"></th>
                            <th id="sumGameAvgTableWinLoss"  class="sumGameAvgTableHeader"></th>
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
                    <table id="summonerAVGDamageToChamps" class="statstable">
                        <tr>
                            <th colspan="3">Damages Given (DTC - Damage To Champions)</th>
                        </tr>
                        <tr>
                            <td id="AVGPhysicalDTC"></td>
                            <td id="AVGMagicDTC"></td>
                            <td id="AVGTrueDTC"></td>
                        </tr>
                    </table>
                    <hr style="color:white">
                    <table id="summonerAVGDamageTFCChamps" class="statstable">
                        <tr>
                            <th colspan="3">Damages Taken (TFC - Taken From Champs)</th>
                        </tr>
                        <tr>
                            <td id="AVGPhysicalTFC"></td>
                            <td id="AVGMagicTFC"></td>
                            <td id="AVGTrueTFC"></td>
                        </tr>
                    </table>
                    <hr style="color:white">
                    <table id="summonerAVGDamageCS" class="statstable">
                        <tr>
                            <th colspan="5">Creep SCORES!!</th>
                        </tr>
                        <tr>
                            <td id="AVGTotalMinions"></td>
                            <td id="AVGTotalMonsters"></td>
                            <td id="AVGTeamMonsters"></td>
                            <td id="AVGEnemyMonsters"></td>
                            <td id="AVGGoldEarned"></td>
                        </tr>
                    </table>
                    <hr style="color:white">
                    <table id="summonerAVGWards" class="statstable">
                        <tr>
                            <th colspan="4">WARDS!!</th>
                        </tr>
                        <tr>
                            <td id="AVGVWards"></td>
                            <td id="AVGSWards"></td>
                            <td id="AVGWardsPlaced"></td>
                            <td id="AVGWardsKilled"></td>
                        </tr>
                    </table>
                    <hr style="color:white">
                    <table id="summonerAVGMisc" class="statstable">
                        <tr>
                            <th colspan="2">MISC!!</th>
                        </tr>
                        <tr>
                            <td id="AVGtowersDestroyed"></td>
                            <td id="AVGCCTime"></td>
                        </tr>    
                    </table>
            </div>
        </div>
        <hr style="color:white">
        <div id="matchesContainer"></div>
    </div>


    <script>
        $(document).ready(function(){
            $.ajax({
                type: 'GET',
                url: 'https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9',
                success: function(json){
                    var championArray = [];
                    for(var champion in json.data){
                        $('#championSelectList').append( $("<option></option>").attr("value", json.data[champion].id + " " ).text(champion) );
                    }
                    sortSelect(document.getElementById('championSelectList'));
                }
            });
        });
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
        function getStats(){
            $(document).ready(function(){
                var summonerName = document.getElementById('summonerName').value;
                var sumIdNum;
                var CSVListOfChamps = $('select#championSelectList').val();
                var CSVChampsNoSpace = CSVListOfChamps.toString().replace(/\s/g,"");

                $.ajax({
                    type: 'GET',
                    url: 'https://na.api.pvp.net/api/lol/na/v1.4/summoner/by-name/' + summonerName + '?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9',
                    success: function(data){
                        for(var key in data){
                            sumIdNum = key;
                        }
                        //Stats for the last 15 Ranked Games Pretty solid if i do say so myself.
                        $.ajax({
                            type: 'GET',
                            url: 'https://na.api.pvp.net/api/lol/na/v2.2/matchhistory/' + data[sumIdNum].id + '?championIds=' + CSVChampsNoSpace + '&rankedQueues=RANKED_SOLO_5x5&beginIndex=0&endIndex=15&api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9',
                            success: function(data){
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
                                    var div = document.createElement('div');
                                    div.id = "match" + i;

                                    if(data.matches[i].participants[0].stats.winner === true) {
                                        div.className = 'win'
                                        wins++;
                                    } else {
                                        div.className = 'loss'
                                        losses++;
                                    }
                                    
                                    matchDuration += 0;

                                    //Create a div to contain the kda for the match on the given summoner.
                                    var matchTable = document.createElement("TABLE");
                                    var header = matchTable.createTHead();
                                    header.className="matchTableHeader";
                                    var row = header.insertRow(0);
                                    var cell = row.insertCell(0);
                                    cell.innerHTML = "Game Type: Ranked Solo Queue";
                                    matchTable.style.width = "100%";
                                    /*newDiv.id="matchKDA"
                                    newDiv.style.width = "120px";
                                    var text = document.createTextNode("KDA " 
                                        + data.matches[i].participants[0].stats.kills + " / "
                                        + data.matches[i].participants[0].stats.deaths + " / "
                                        + data.matches[i].participants[0].stats.assists
                                    );
                                    newDiv.appendChild(text)
                                    div.appendChild(newDiv);
                                    kills += data.matches[i].participants[0].stats.kills;
                                    deaths += data.matches[i].participants[0].stats.deaths;
                                    assists += data.matches[i].participants[0].stats.assists;

                                    var multiKillsContainerDiv = document.createElement("div");
                                    multiKillsContainerDiv.id="MultiKillsContainer";
                                    div.appendChild(multiKillsContainerDiv);
                                    
                                    $('MultiKillsContainer').html('<div id="testDiv">Hello</div>');
                                    
                                    //newDiv.id="doubleKills";
                                    //text = document.createTextNode("Double Kills: " + data.matches[i].participants[0].stats.doubleKills + " ");
                                    //newDiv.appendChild(text);
                                    
                                    //doubleKills += data.matches[i].participants[0].stats.doubleKills;
                                    

                                    text = document.createTextNode("Triple Kills: " + data.matches[i].participants[0].stats.tripleKills + " ");
                                    div.appendChild(newDiv);
                                    tripKills += data.matches[i].participants[0].stats.tripleKills;

                                    text = document.createTextNode("Quadra Kills: " + data.matches[i].participants[0].stats.quadraKills + " ");
                                    div.appendChild(newDiv);
                                    quadraKills += data.matches[i].participants[0].stats.quadraKills;

                                    text = document.createTextNode("Penta Kills: " + data.matches[i].participants[0].stats.pentaKills + " ");
                                    div.appendChild(newDiv);
                                    pentaKills += data.matches[i].participants[0].stats.pentaKills;

                                    text = document.createTextNode("Physical DTC: " + data.matches[i].participants[0].stats.physicalDamageDealtToChampions + " ");
                                    div.appendChild(newDiv);
                                    physicalDamageToChamps += data.matches[i].participants[0].stats.physicalDamageDealtToChampions;

                                    text = document.createTextNode("Magical DTC: " +data.matches[i].participants[0].stats.magicDamageDealtToChampions + " ");
                                    div.appendChild(newDiv);
                                    magicDamageToChamps += data.matches[i].participants[0].stats.magicDamageDealtToChampions;

                                    text = document.createTextNode("True Damage: " + data.matches[i].participants[0].stats.trueDamageDealtToChampions + " ");
                                    div.appendChild(newDiv);
                                    trueDamageToChamps += data.matches[i].participants[0].stats.trueDamageDealtToChampions;

                                    text = document.createTextNode("Total DTC" + data.matches[i].participants[0].stats.totalDamageDealtToChampions + " ");
                                    div.appendChild(newDiv);
                                    totalDamageTaken += data.matches[i].participants[0].stats.totalDamageDealtToChampions;

                                    text = document.createTextNode("Magic Damage TFC: " + data.matches[i].participants[0].stats.magicDamageTaken + " ");
                                    div.appendChild(newDiv);
                                    magicDamageTaken += data.matches[i].participants[0].stats.magicDamageTaken;

                                    text = document.createTextNode("Physical Damage TFC: " + data.matches[i].participants[0].stats.physicalDamageTaken + " ");
                                    div.appendChild(newDiv);
                                    physicalDamageTaken += data.matches[i].participants[0].stats.physicalDamageTaken;

                                    text = document.createTextNode("True Damage TFC: " + data.matches[i].participants[0].stats.trueDamageTaken + " ");
                                    div.appendChild(newDiv);
                                    trueDamageTaken += data.matches[i].participants[0].stats.trueDamageTaken;

                                    text = document.createTextNode("CS: " + data.matches[i].participants[0].stats.minionsKilled + " ");
                                    div.appendChild(newDiv);
                                    totalMinionsKilled += data.matches[i].participants[0].stats.minionsKilled;

                                    text = document.createTextNode("Neutral CS: " + data.matches[i].participants[0].stats.neutralMinionsKilled + " ");
                                    div.appendChild(newDiv);
                                    monstersKilled += data.matches[i].participants[0].stats.neutralMinionsKilled;

                                    text = document.createTextNode("Neutral Team CS: " + data.matches[i].participants[0].stats.neutralMinionsKilledTeamJungle + " ");
                                    div.appendChild(newDiv);
                                    teamMonsters += data.matches[i].participants[0].stats.neutralMinionsKilledTeamJungle;

                                    t = document.createTextNode("Neutral Enemy CS: " + data.matches[i].participants[0].stats.neutralMinionsKilledEnemyJungle + " ");
                                    div.appendChild(newDiv);
                                    enemyMonsters += data.matches[i].participants[0].stats.neutralMinionsKilledEnemyJungle;

                                    t = document.createTextNode("Gold Earned: " + data.matches[i].participants[0].stats.goldEarned + " ");
                                    div.appendChild(newDiv);
                                    goldEarned  += data.matches[i].participants[0].stats.goldEarned;

                                    t = document.createTextNode("Gold Spent: " + data.matches[i].participants[0].stats.goldSpent + " ");
                                    div.appendChild(newDiv);
                                    goldSpent += data.matches[i].participants[0].stats.goldSpent;

                                    t = document.createTextNode("Vision Wards: " + data.matches[i].participants[0].stats.visionWardsBoughtInGame + " ");
                                    div.appendChild(newDiv);
                                    visionWards += data.matches[i].participants[0].stats.visionWardsBoughtInGame;

                                    t = document.createTextNode("Sight Wards: " + data.matches[i].participants[0].stats.sightWardsBoughtInGame + " ");
                                    div.appendChild(newDiv);
                                    sightWards += data.matches[i].participants[0].stats.sightWardsBoughtInGame;

                                    t = document.createTextNode("Wards Placed: " + data.matches[i].participants[0].stats.wardsPlaced + " ");
                                    div.appendChild(newDiv);
                                    wardsPlaced += data.matches[i].participants[0].stats.wardsPlaced;

                                    t = document.createTextNode("Wards Killed: " + data.matches[i].participants[0].stats.wardsKilled + " ");
                                    div.appendChild(newDiv);
                                    wardsKilled += data.matches[i].participants[0].stats.wardsKilled;

                                    t = document.createTextNode("Tower Kills: " + data.matches[i].participants[0].stats.towerKills + " ");
                                    div.appendChild(newDiv);
                                    towersDestroyed  += data.matches[i].participants[0].stats.towerKills;

                                    t = document.createTextNode("Total CC Time Dealt: " + data.matches[i].participants[0].stats.totalTimeCrowdControlDealt + " ");
                                    div.appendChild(newDiv);
                                    totalTimeCCDealt += data.matches[i].participants[0].stats.totalTimeCrowdControlDealt;
                                    */
                                    div.appendChild(matchTable);
                                    document.getElementById('matchesContainer').appendChild(div);
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
                                $('#AVGCCTime').html("AVG CC Time Dealt: " + parseFloat(((totalTimeCCDealt/60)/matchesLength).toFixed(2)));
                                $('displayStats').show();
                            }
                        });
                    }
                });
            });
            document.getElementById("displayStats").style.display = 'block';
        }
    </script>
</body>
</html>