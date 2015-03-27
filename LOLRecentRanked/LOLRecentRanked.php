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
        <div id="header">
            <h1>LOL Stat Tools</h1>
        </div>
        <table id="navBar" class="navBar">
            <th>
                <a id="homeLink" href="../index.php">Home</a>
            </th>
            <th>
                <a id="recentGameStatsLink" href="#">Recent Ranked Averages</a>
            </th>
        </table>
    </div>    
    
    
    <div id="LOLStatsFormDiv">
            <legend id="recentGamesLegend">Summoner ID:</legend>
            <input type=text id="summonerName">
            <input id="summonerStatsButton" type="submit" name="Submit" value="Submit" onclick="getStats()">
    </div>
    <div id="summonerStats">
        <div id="displayStats" style="display:none">
            <div id="summonerID">Summoner ID: </div> <!-- TODO: MAKEOVER DIV TO MAKE LOOK NICE !-->
            <div id="summonerGameAveragesTitle">Summoner Game Averages</div>
            
                <table id="summonerGameAveragesTable">
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
                    <tr>
                        <td><h2>Damages Given (DTC - Damage To Champions)</h2></td>
                    </tr>
                    <tr>
                        <td id="AVGPhysicalDTC"></td>
                        <td id="AVGMagicDTC"></td>
                        <td id="AVGTrueDTC"></td>
                    </tr>
                    <tr>
                        <td><h2>Damages Taken (TFC - Taken From Champs)</h2></td>
                    </tr>
                    <tr>
                        <td id="AVGPhysicalTFC"></td>
                        <td id="AVGMagicTFC"></td>
                        <td id="AVGTrueTFC"></td>
                    </tr>
                    <tr>
                        <td><h2>Creep SCORES!!</h2></td>
                    </tr>
                    <tr>
                        <td id="AVGTotalMinions"></td>
                        <td id="AVGTotalMonsters"></td>
                        <td id="AVGTeamMonsters"></td>
                        <td id="AVGEnemyMonsters"></td>
                        <td id="AVGGoldEarned"></td>
                    </tr>
                    <tr>
                        <td><h2>WARDS!!</h2></td>
                    </tr>
                    <tr>
                        <td id="AVGVWards"></td>
                        <td id="AVGSWards"></td>
                        <td id="AVGWardsPlaced"></td>
                        <td id="AVGWardsKilled"></td>
                    </tr>
                    <tr>
                        <td><h2>MISC!!</h2></td>
                    </tr>
                    <tr>
                        <td id="AVGtowersDestroyed"></td>
                        <td id="AVGCCTime"></td>
                        
                    </tr>    
                </table>
        </div>
    </div>
    <script>
        function getStats(){
            alert("Getting Stats");
            $(document).ready(function(){
                var summonerName = document.getElementById('summonerName').value;
                var sumIdNum;
                alert(summonerName);
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
                            url: 'https://na.api.pvp.net/api/lol/na/v2.2/matchhistory/'+data[sumIdNum].id+'?rankedQueues=RANKED_SOLO_5x5&beginIndex=0&endIndex=15&api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9',
                            success: function(data){
                                //Add the summoners name to the Div ID: summonerID 
                                $('#summonerID').append(summonerName);
                                var gameType = data.matches[0].queueType;
                                
                                //matchDuration
                                //creepPerMinDeltas 0-10, 10-20, 20-30, 30-end
                                //xpPerMinDeltas 0-10, 10-20, 20-30, 30-end
                                //goldPerMinDeltas 0-10, 10-20, 20-30, 30-end
                                //damageTakenPerMinDeltas 0-10, 10-20, 20-30, 30-end
                                
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
                                for(i = 0; i < data.matches.length; i++){
                                    if(data.matches[i].participants[0].stats.winner === true) {
                                        wins++;
                                    } else {
                                        losses++;
                                    }
                                    kills += data.matches[i].participants[0].stats.kills;
                                    deaths += data.matches[i].participants[0].stats.deaths;
                                    assists += data.matches[i].participants[0].stats.assists;
                                    doubleKills += data.matches[i].participants[0].stats.doubleKills;
                                    tripKills += data.matches[i].participants[0].stats.tripKills;
                                    quadraKills += data.matches[i].participants[0].stats.quadraKills;
                                    pentaKills += data.matches[i].participants[0].stats.pentaKills;
                                    totalDamageToChampions += data.matches[i].participants[0].stats.totalDamageDealtToChampions;
                                    physicalDamageToChamps += data.matches[i].participants[0].stats.physicalDamageDealtToChampions;
                                    magicDamageToChamps += data.matches[i].participants[0].stats.magicDamageDealtToChampions;
                                    trueDamageToChamps += data.matches[i].participants[0].stats.trueDamageDealtToChampions;
                                    totalDamageTaken += data.matches[i].participants[0].stats.totalDamageTaken;
                                    magicDamageTaken += data.matches[i].participants[0].stats.magicDamageTaken;
                                    physicalDamageTaken += data.matches[i].participants[0].stats.physicalDamageTaken;
                                    trueDamageTaken += data.matches[i].participants[0].stats.trueDamageTaken;
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
                                $('#sumGameAvgTableWins').html("Wins : " + wins);
                                $('#sumGameAvgTableLosses').html("Losses : " + losses);
                                $('#sumGameAvgTableWinLoss').html("KDA: " + wins + "/" + losses + ":" + parseFloat((wins/15) * 100).toFixed(2) + "%");
                                $('#AVGKills').html("AVG Kills " + parseFloat(kills/15).toFixed(2));
                                $('#AVGDeaths').html("AVG Deaths " + parseFloat(deaths/15).toFixed(2));
                                $('#AVGAssists').html("AVG Assists " + parseFloat(assists/15).toFixed(2));
                                $('#AVGKDA').html("AVG KDA " + parseFloat((kills+assists)/deaths).toFixed(2) + "1");
                                $('#AVGDoubles').html("AVG Doubles " + parseFloat(doubleKills/15).toFixed(2));
                                $('#AVGTriples').html("AVG Triples " + parseFloat(tripKills/15).toFixed(2));
                                $('#AVGQuadras').html("AVG Quadras " + parseFloat(quadraKills/15).toFixed(2));
                                $('#AVGPentas').html("AVG Pentas " + parseFloat((pentaKills/15).toFixed(2)));
                                $('#AVGPhysicalDTC').html("AVG Physical DTC " + parseFloat((physicalDamageToChamps/15).toFixed(2)));
                                $('#AVGMagicDTC').html("AVG Magic DTC " + parseFloat((magicDamageToChamps/15).toFixed(2)));
                                $('#AVGTrueDTC').html("AVG True DTC " + parseFloat((trueDamageToChamps/15).toFixed(2)));
                                $('#AVGPhysicalTFC').html("AVG Physical TFC " + parseFloat((physicalDamageTaken/15).toFixed(2)));
                                $('#AVGMagicTFC').html("AVG Magic TFC " + parseFloat((magicDamageTaken/15).toFixed(2)));
                                $('#AVGTrueTFC').html("AVG True TFC " + parseFloat((trueDamageTaken/15).toFixed(2)));
                                $('#AVGTotalMinions').html("AVG Total Minions " + parseFloat((totalMinionsKilled/15).toFixed(2)));
                                $('#AVGTotalMonsters').html("AVG Total Monsters " + parseFloat((monstersKilled/15).toFixed(2)));
                                $('#AVGTeamMonsters').html("AVG Team Jungle Monsters " + parseFloat((teamMonsters/15).toFixed(2)));
                                $('#AVGEnemyMonsters').html("AVG Enemy Jungle Monsters " + parseFloat((enemyMonsters/15).toFixed(2)));
                                $('#AVGGoldEarned').html("AVG Gold Earned " + parseFloat((goldEarned/15).toFixed(2)));
                                $('#AVGVWards').html("AVG Vision Wards " + parseFloat((visionWards/15).toFixed(2)));
                                $('#AVGSWards').html("AVG Sight Wards " + parseFloat((sightWards/15).toFixed(2)));
                                $('#AVGWardsPlaced').html("AVG Wards Placed " + ((wardsPlaced/15).toFixed(2)));
                                $('#AVGWardsKilled').html("AVG Wards Killed " + parseFloat((wardsKilled/15).toFixed(2)));
                                $('#AVGtowersDestroyed').html("AVG Towers Destroyed " + parseFloat((towersDestroyed/15).toFixed(2)));
                                $('#AVGCCTime').html("AVG CC Time Dealt " + parseFloat((totalTimeCCDealt/15).toFixed(2)));
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
