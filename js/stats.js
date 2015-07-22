//Global variables
var summonerInfo;
var currentGameJSON;
var leagueData; 
var championStats;

function getSummonerInformation(summonerName){
	var urlEncodeSumName = summonerName.toString().replace(/\s/g,"");
	var JSONObj;
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "../ajaxFunctions.php", //Relative or absolute path to response.php file
        data:  { action: 'getSummonerID()', sumName: urlEncodeSumName, region: $('select#regionSelect').val() },
        success: function(json){
           summonerInfo = JSON.parse(json);
        },
        async:false
    });
    return summonerInfo;
}

function getCurrentGameJSON(sumBasicInfo){
	$.ajax({
        type: "POST",
        dataType: "json", 
        url: "../ajaxFunctions.php", //Relative or absolute path to response.php file
        data:  { action: 'getCurrentGame()', sumID: sumBasicInfo.id, region: $('select#regionSelect').val()},
        success: function(json){
            currentGameJSON = JSON.parse(json);
        },
        async:false
    });
    return currentGameJSON;
}

function getSummonersLeagueData(csvSummoners){
	$.ajax({
        type: "POST",
        dataType: "json",
        url: "../ajaxFunctions.php", //Relative or absolute path to response.php file
        data:  { action: 'getLeague()', sumID: csvSummoners, region: $('select#regionSelect').val()},
        success: function(json){
            leagueData = JSON.parse(json);
        }, async:false
    });
    return leagueData;
}

function getChampionStats(summonerId){
	$.ajax({
        type: "POST",
        dataType: "json",
        url: "../ajaxFunctions.php", //Relative or absolute path to response.php file
        data:  { action: 'getStats()', sumID: summonerId, region: $('select#regionSelect').val(), seasonString: "SEASON2015"},
        success: function(json){
            championStats = JSON.parse(json);
        }, async:false
    });
    return championStats;
}