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
                    <li class="active"><a href="#">Recent Game Stats</a></li>
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
            <div id="summonerGameAveragesTitle"></div>
            <table id="summonerGameAveragesHeaderTable" class="winLossTable">
                <tr>
                    <th id="sumGameAvgTableGameType" ></th>
                    <th id="sumGameAvgTableWins"     ></th>
                    <th id="sumGameAvgTableLosses"   ></th>
                    <th id="sumGameAvgTableWinLoss"  ></th>
                </tr> 
	    	</table>
			<div id="AveragesContainer">
				<table class="averageTable">
                    <thead>
    					<tr>
                            <th>Kills</th>
                            <th>Deaths</th>
                            <th>Assists</th>
                            <th>KDA</th>
                            <th>Doubles</th>
                            <th>Triples</th>
                            <th>Quadras</th>
                            <th>Pentas</th>
                        </tr>
                    </thead>
                    <tr>
                        <td id="AVGKills"></td>
                        <td id="AVGDeaths"></td>
                        <td id="AVGAssists"></td>
                        <td id="AVGKDA"></td> 
                        <td id="AVGDoubles"></td>
                        <td id="AVGTriples"></td>
                        <td id="AVGQuadras"></td>
                        <td id="AVGPentas"></td>
                    </tr>
				</table>
                <table class="averageTable">
                    <thead>
                        <tr>
                            <th>Physical Damage To Champs</th>
                            <th>Magic Damage To Champs</th>
                            <th>True Damage To Champs</th>
                        </tr>
                    </thead>
                    <tr>
                        <td id="AVGPhysicalDTC"></td>
                        <td id="AVGMagicDTC"></td>
                        <td id="AVGTrueDTC"></td>
                    </tr>
                </table>
                <table class="averageTable">
                    <thead>
    					<tr>
    						<th>Physical Damage Taken</th>
                            <th>Magic Damage Taken</th>
                            <th>True Damage Taken</th>
                        </tr>
                    </thead>
                    <tr>
						<td id="AVGPhysicalTFC"></td>
						<td id="AVGMagicTFC"></td>
						<td id="AVGTrueTFC"></td>
                    </tr>
                </table>
                <table class="averageTable">
                    <thead>
                        <tr>
                            <th>Total Minions</th>
                            <th>Team Monsters</th>
                            <th>Enemy Monsters</th>
                            <th>Total Monsters</th>
                            <th>Gold Earned</th>
                        </tr>
                    </thead>
                    <tr>
                        <td id="AVGTotalMinions"></td>
                        <td id="AVGTotalMonsters"></td>    
                        <td id="AVGTeamMonsters"></td>
                        <td id="AVGEnemyMonsters"></td>
                        <td id="AVGGoldEarned"></td>
                    </tr>
                </table>
                <table class="averageTable">
                    <thead>
                        <tr>
                            <th>Vision Wards</th>
                            <th>Sight Wards</th>
                            <th>Wards Placed</th>
                            <th>Wards Killed</th>
                            <th>Towers Destroyed</th>
                            <th>CC Time Dealt</th>
                        </tr>
                    </thead>
                    <tr>
                        <td id="AVGVWards"></td>
                        <td id="AVGSWards"></td>
                        <td id="AVGWardsPlaced"></td>
                        <td id="AVGWardsKilled"></td>
                        <td id="AVGtowersDestroyed"></td>
                        <td id="AVGCCTime"></td> 
                    </tr>
                </table>
			</div>
        </div>
        <ul id="matchesContainer"></ul>
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

        function showDropDown(i){
            $('#dropDownMatchStats' + i).show();
            $('#dropDown' + i).html('<a onclick="hideDropDown(' + i + ')"><img src="./images/drop-up-button.png" height="25" width="25"></a>');
        }

        function hideDropDown(i){
            $('#dropDownMatchStats' + i).hide();
            $('#dropDown' + i).html('<a onclick="showDropDown(' + i + ')"><img src="./images/drop-down-button.png" height="25" width="25"></a>');
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
                                    var li = document.createElement('li');
                                    li.id = "match" + i;
                                    var winloss;
                                    li.className = 'matchliStyle'
                                    if(data.matches[i].participants[0].stats.winner === true) {
                                        winloss = "win"
                                        wins++;
                                    } else {
                                        winloss="loss";
                                        losses++;
                                    }
                                    document.getElementById('matchesContainer').appendChild(li);
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
    '</div>' +  
    '<div id="dropDown' + i + '" class="dropDownButton">' +
        '<a onclick="showDropDown(' + i + ')"><img src="./images/drop-down-button.png" height="25" width="25"></a>'+
    '</div>'

);  
                                    var li = document.createElement('li');
                                    li.id = "dropDownMatchStats" + i;
                                    li.className = 'dropDownStats';
                                    document.getElementById('matchesContainer').appendChild(li);
$('#dropDownMatchStats' + i).html(
    '<div class="summoners">'+
        '<table>' +
            '<tr><td colspan="2">Summoners</td></tr>' +
            '<tr>' + 
                '<td><img style="margin-right:4px" src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + data.matches[i].participants[0].championId).html() + '.png" height="30" width="30"></td>'+
                '<td><img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + data.matches[i].participants[0].championId).html() + '.png" height="30" width="30"></td>'+
            '</tr>'+
        '</table>' + 
    '</div>' +
    '<div class="wards">'+
        '<table>' +
            '<tr><td colspan="2">Wards</td></tr>' +
            '<tr>' + 
                '<td>Vision Wards</td>'+
                '<td>' + data.matches[i].participants[0].stats.visionWardsBoughtInGame + '</td>'+
            '</tr>'+
        '</table>'+
    '</div>'
);  

                                    /*$('#match' + i).html(
'<table id="matchTable' + i + '" class="matchTable ' + winloss + '">' +
    '<thead>' +
        '<th style="width:17%">Champion: ' + $('#' + data.matches[i].participants[0].championId).html() + '</th>'+
        '<th>Creep Score</th>' +
        '<th>Jungle Creeps</th>' +
        '<th>Team Jungle Creeps</th>' +
        '<th>Enemy Jungle Creeps</th>' +
        '<th>Gold Earned</th>' +
    '</thead>' +
    '<tr>' +
        '<td rowspan="9" style="width:17%">'+
            '<table id="ChampionData" style="width:100%; border:none;" >'+
                '<tr>'+
                    '<td ><img src="http://ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/' + $('#' + data.matches[i].participants[0].championId).html() + '.png" height="70" width="70"></td>' +
                '</tr>' +
                '<tr>'+
                    '<td>' +
                        '<div>KDA: ' + 
                            data.matches[i].participants[0].stats.kills + " / " +
                            data.matches[i].participants[0].stats.deaths + " / " +
                            data.matches[i].participants[0].stats.assists +
                        '</div>' +
                    '</td>' + 
                '</tr>' + 
                '<tr>'+
                    '<td>Match Time: ' + parseFloat(data.matches[i].matchDuration/60).toFixed(0) + ':'+ data.matches[i].matchDuration%60 +'</td>' + 
                '</tr>' + 
            '</table>'+
        '</td>' + 
        '<td>' + (data.matches[i].participants[0].stats.minionsKilled + data.matches[i].participants[0].stats.neutralMinionsKilled) + '</td>' + 
        '<td>' + data.matches[i].participants[0].stats.neutralMinionsKilled + '</td>' + 
        '<td>' + data.matches[i].participants[0].stats.neutralMinionsKilledTeamJungle+ '</td>' + 
        '<td>' + data.matches[i].participants[0].stats.neutralMinionsKilledEnemyJungle + '</td>' +
        '<td>' + data.matches[i].participants[0].stats.goldEarned + '</td>' +
    '</tr>' +
    '<tr class="headerRow">' +
        '<td>Double Kills</td>' + 
        '<td>Triple Kills</td>' + 
        '<td>Quadra Kills</td>' + 
        '<td>Penta Kills</td>' +
        '<td>Multi Kills</td>' +
    '</tr>' +
    '<tr>' +
        '<td>' + data.matches[i].participants[0].stats.doubleKills + '</td>' + 
        '<td>' + data.matches[i].participants[0].stats.tripleKills + '</td>' + 
        '<td>' + data.matches[i].participants[0].stats.quadraKills + '</td>' + 
        '<td>' + data.matches[i].participants[0].stats.pentaKills + '</td>' +
        '<td>' + (data.matches[i].participants[0].stats.doubleKills+data.matches[i].participants[0].stats.tripleKills+data.matches[i].participants[0].stats.quadraKills+data.matches[i].participants[0].stats.pentaKills)+ '</td>' + 
    '</tr>' +
    '<tr class="headerRow">' +
        '<td>Sight Wards</td>' + 
        '<td>Vision Wards</td>' + 
        '<td>Wards Placed</td>' + 
        '<td>Wards Killed</td>' +
        '<td></td>'+ 
    '</tr>' +
    '<tr>' +
        '<td>' + data.matches[i].participants[0].stats.sightWardsBoughtInGame + '</td>' + 
        '<td>' + data.matches[i].participants[0].stats.visionWardsBoughtInGame + '</td>' + 
        '<td>' + data.matches[i].participants[0].stats.wardsPlaced + '</td>' + 
        '<td>' + data.matches[i].participants[0].stats.wardsKilled + '</td>' +
        '<td></td>' + 
    '</tr>' +
    '<tr class="headerRow">' +
        '<td>Physical Damage Dealt</td>' + 
        '<td>Magic Damage Dealt</td>' + 
        '<td>True Damage Dealt</td>' + 
        '<td>Total Damage Dealt</td>' +
        '<td></td>'+ 
    '</tr>' +
    '<tr>' +
        '<td>' + data.matches[i].participants[0].stats.physicalDamageDealtToChampions + '</td>' + 
        '<td>' + data.matches[i].participants[0].stats.magicDamageDealtToChampions + '</td>' + 
        '<td>' + data.matches[i].participants[0].stats.trueDamageDealtToChampions + '</td>' + 
        '<td>' + data.matches[i].participants[0].stats.totalDamageDealtToChampions + '</td>' +
        '<td></td>' + 
    '</tr>' +
    '<tr class="headerRow">' +
        '<td>Physical Damage Taken</td>' + 
        '<td>Magic Damage Taken</td>' + 
        '<td>True Damage Taken</td>' + 
        '<td>Total Damage Taken</td>' +
        '<td></td>'+ 
    '</tr>' +
    '<tr>' +
        '<td>' + data.matches[i].participants[0].stats.physicalDamageTaken + '</td>' + 
        '<td>' + data.matches[i].participants[0].stats.magicDamageTaken + '</td>' + 
        '<td>' + data.matches[i].participants[0].stats.trueDamageTaken + '</td>' + 
        '<td>' + data.matches[i].participants[0].stats.totalDamageTaken + '</td>' +   
         /*'<table id="KDATable" class="table" >'+
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
        
    '</tr>'+
'</table>'
);*/

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
                                $('#summonerGameAveragesTitle').html(summonerName); 
                                //$('#summonerGameAveragesTitle').html("Last " + matchesLength + " Game Averages: " + summonerName);
                                $('#sumGameAvgTableWins').html("Wins : " + wins);
                                $('#sumGameAvgTableLosses').html("Losses : " + losses);
                                $('#sumGameAvgTableWinLoss').html("W/L: " + wins + "/" + losses + ":" + parseFloat((wins/matchesLength) * 100).toFixed(2) + "%");
								$('#AVGKills').html(parseFloat(kills/matchesLength).toFixed(2));
                                $('#AVGDeaths').html(parseFloat(deaths/matchesLength).toFixed(2));
                                $('#AVGAssists').html(parseFloat(assists/matchesLength).toFixed(2));
                                $('#AVGKDA').html(parseFloat((kills + assists)/deaths).toFixed(2));
                                $('#AVGDoubles').html(parseFloat(doubleKills/matchesLength).toFixed(2) );
                                $('#AVGTriples').html(parseFloat(tripKills/matchesLength).toFixed(2) );
                                $('#AVGQuadras').html(parseFloat(quadraKills/matchesLength).toFixed(2) );
                                $('#AVGPentas').html(parseFloat(pentaKills/matchesLength).toFixed(2) );
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
                                $('#summonerStats').show();
                            }
                        });
                    }
                });
            });
        }
        
    </script>
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>


