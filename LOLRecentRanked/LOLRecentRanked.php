<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Cuddly Assassin</title>
<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../LeagueOfLegends/css/leagueoflegendsmain.css">
<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.2.min.js"></script>
</head>

<body>
    <div id="wrapper">
        <div id="header">
            <h1>Cuddly Assassin</h1>
        </div>
        <table id="navBar" class="navBar">
            <th>
                <a id="homeLink" href="../index.php">Home</a>
            </th>
            <th>
                <a id="recentGameStatsLink" href="#">League Of Legends</a>
            </th>
        </table>
    </div>  
    <hr>        
    
    
    <div id="LOLStatsFormDiv">
        <form id="recentGameStats">
            <legend id="recentGamesLegend">Recent Game Stats:</legend>
            <input type=text id="summonerName">
            <input id="summonerStatsButton" type=submit>
        </form>
    </div>
    <div id="summonerStats">
        <div id="summonerID">Summoner ID: </div> <!-- TODO: MAKEOVER DIV TO MAKE LOOK NICE !-->
        <div id="summonerGameAveragesTitle">Summoner Game Averages</div>
        <table id="summonerGameAveragesTable">
            <tr>
                <th id="sumGameAvgTableGameType" class="sumGameAvgTableHeader">Game Type: </th>
                <th id="sumGameAvgTableWins"     class="sumGameAvgTableHeader">Wins:      </th>
                <th id="sumGameAvgTableLosses"   class="sumGameAvgTableHeader">Losses:    </th>
                <th id="sumGameAvgTableWinLoss"  class="sumGameAvgTableHeader">W/L:       </th>
            </tr> 
            <tr>
                <td id="AVGKills">Avg Kills:    </td>
                <td id="AVGDeaths">Avg Deaths:  </td>
                <td id="AVGAssists">Avg Assists:</td>
                <td id="AVGKDA">Avg KDA:        </td>
            </tr> 
            <tr>
                <td id="AVGDoubles">Avg Doubles:</td>
                <td id="AVGTriples">Avg Triples:</td>
                <td id="AVGQuadras">Avg Quadras:</td>
                <td id="AVGPentas">Avg Pentas:  </td>
            </tr>
            <tr>
                <td><h2>Damages Given (DTC - Damage To Champions)</h2></td>
            </tr>
            <tr>
                <td id="AVGPhysicalDTC">Avg Physical DTC:</td>
                <td id="AVGMagicDTC">Avg Magic DTC:</td>
                <td id="AVGTrueDTC">Avg True DTC:</td>
            </tr>
            <tr>
                <td><h2>Damages Taken (TFC - Taken From Champs)</h2></td>
            </tr>
            <tr>
                <td id="AVGPhysicalTFC">Avg Physical DTC:</td>
                <td id="AVGMagicTFC">Avg Magic DTC:</td>
                <td id="AVGTrueTFC">Avg True DTC:</td>
            </tr>
            <tr>
                <td><h2>Creep SCORES!!</h2></td>
            </tr>
            <tr>
                <td id="AVGTotalMinions">Avg CS:</td>
                <td id="AVGTotalMonsters">Avg Jungle Creeps:</td>
                <td id="AVGTeamMonsters">Avg Team Jungle Creeps:</td>
                <td id="AVGEnemyMonsters">Avg Enemy Jungle Creeps:</td>
                <td id="AVGGoldEarned">Avg Gold:</td>
            </tr>
            <tr>
                <td><h2>WARDS!!</h2></td>
            </tr>
            <tr>
                <td id="AVGVWards">Avg Vision Wards:</td>
                <td id="AVGSWards">Avg Sight Wards:</td>
                <td id="AVGWardsPlaced">Avg Wards Placed:</td>
                <td id="AVGWardsKilled">Avg Wards Killed:</td>
            </tr>
            <tr>
                <td><h2>MISC!!</h2></td>
            </tr>
            <tr>
                <td id="AVGtowersDestroyed">AVG Tower Kills:</td>
                <td id="AVGCCTime">AVG CC Time:</td>
                
            </tr>    
        </table>
    </div>
    <script>
        $(document).ready(function(){

            //Stats for the last 15 Ranked Games Pretty solid if i do say so myself.
            $.ajax({
                type: 'GET',
                url: 'https://na.api.pvp.net/api/lol/na/v2.2/matchhistory/35778587?rankedQueues=RANKED_SOLO_5x5&beginIndex=0&endIndex=15&api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9',
                success: function(data){
                    //Add the summoners name to the Div ID: summonerID 
                    $('#summonerID').append(data.matches[0].participantIdentities[0].player.summonerName);
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
                        $('#sumGameAvgTableGameType').append("Ranked Solo");
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
                    $('#sumGameAvgTableWins').append(wins);
                    $('#sumGameAvgTableLosses').append(losses);
                    $('#sumGameAvgTableWinLoss').append(wins + "/" + losses + ":" + parseFloat((wins/15) * 100).toFixed(2) + "%");
                    $('#AVGKills').append(parseFloat(kills/15).toFixed(2));
                    $('#AVGDeaths').append(parseFloat(deaths/15).toFixed(2));
                    $('#AVGAssists').append(parseFloat(assists/15).toFixed(2));
                    $('#AVGKDA').append(parseFloat((kills+assists)/deaths).toFixed(2) + ":1");
                    $('#AVGDoubles').append(parseFloat(doubleKills/15).toFixed(2));
                    $('#AVGTriples').append(parseFloat(tripKills/15).toFixed(2));
                    $('#AVGQuadras').append(parseFloat(quadraKills/15).toFixed(2));
                    $('#AVGPentas').append(parseFloat((pentaKills/15).toFixed(2)));
                    $('#AVGPhysicalDTC').append(parseFloat((physicalDamageToChamps/15).toFixed(2)));
                    $('#AVGMagicDTC').append(parseFloat((magicDamageToChamps/15).toFixed(2)));
                    $('#AVGTrueDTC').append(parseFloat((trueDamageToChamps/15).toFixed(2)));
                    $('#AVGPhysicalTFC').append(parseFloat((physicalDamageTaken/15).toFixed(2)));
                    $('#AVGMagicTFC').append(parseFloat((magicDamageTaken/15).toFixed(2)));
                    $('#AVGTrueTFC').append(parseFloat((trueDamageTaken/15).toFixed(2)));
                    $('#AVGTotalMinions').append(parseFloat((totalMinionsKilled/15).toFixed(2)));
                    $('#AVGTotalMonsters').append(parseFloat((monstersKilled/15).toFixed(2)));
                    $('#AVGTeamMonsters').append(parseFloat((teamMonsters/15).toFixed(2)));
                    $('#AVGEnemyMonsters').append(parseFloat((enemyMonsters/15).toFixed(2)));
                    $('#AVGGoldEarned').append(parseFloat((goldEarned/15).toFixed(2)));
                    $('#AVGVWards').append(parseFloat((visionWards/15).toFixed(2)));
                    $('#AVGSWards').append(parseFloat((sightWards/15).toFixed(2)));
                    $('#AVGWardsPlaced').append(parseFloat((wardsPlaced/15).toFixed(2)));
                    $('#AVGWardsKilled').append(parseFloat((wardsKilled/15).toFixed(2)));
                    $('#AVGtowersDestroyed').append(parseFloat((towersDestroyed/15).toFixed(2)));
                    $('#AVGCCTime').append(parseFloat((totalTimeCCDealt/15).toFixed(2)));
                }
            });
        });
    </script>
</body>
</html>
