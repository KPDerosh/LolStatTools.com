//========================Dragon data variables=========================
var dataDragonVersion = "5.14.1";
var dataDragonChampionURL = 'http://ddragon.leagueoflegends.com/cdn/' + dataDragonVersion + '/img/champion/';
var dataDragonItemURL = "http://ddragon.leagueoflegends.com/cdn/" + dataDragonVersion + "/img/item/";


//Global information variables.=============================================
var summonerBasicInfo;
var currentGameJSON;
var leagueData; 
var championStats;
 //Create a json object of class strings
//If you want another tooltip add class info here

var classStrings = {
                        "1": "cleanse",
                        "3": "exhaust",
                        "4": "flash", 
                        "6": "ghost",
                        "7": "heal",
                        "11": "smite",
                        "12": "teleport",
                        "13": "clari",
                        "14": "ignite",
                        "21": "barrier",
                        "32": "poroThrow",
                    };
function loadAllInformation(summonerName){
    console.log("Starting to load information");
	getSummonerInformation(summonerName);
    getCurrentGameJSON(summonerBasicInfo);
    //Make csv of summoner names for league data.
    //This prevents you from making several calls and combining them all at once.
    var csvSummonerIds = "";
    var csvSummonerIds = csvSummonerIds.concat(currentGameJSON.participants[0].summonerId);
    for(var summoner = 1; summoner < 10; summoner++){
        csvSummonerIds = csvSummonerIds.concat( "," + currentGameJSON.participants[summoner].summonerId);
    }
    leagueData = getSummonersLeagueData(csvSummonerIds);
    console.log("done loading information");
}

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
           if(summonerInfo != false){
                for (var first in summonerInfo){
                    summonerBasicInfo = summonerInfo[first];
                }
            }
        },
        async:false
    });
    return summonerBasicInfo;
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