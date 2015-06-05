<?php
	if (is_ajax()) {
		if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
	    	$action = $_POST["action"];
	    	switch($action) { //Switch case for value of action
	      		case "getChampionID()": getChampionID(); break;
	      		case "getSummonerID()": getSummonerID(); break;
	      		case "getRanked5v5Solo()": getRanked5v5Solo(); break;
            case "getCurrentGame()": getCurrentGame();break;
            case "getStats()": getStats();break;
            case "getLeague()": getLeague(); break;
	    	}
  		}
	}
	//Function to check if the request is an AJAX request
	function is_ajax() {
  		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

  function getLeague(){
    header("Content-type: application/json");
      $sumID = $_POST["sumID"];
      $region = $_POST["region"];
      $currentGameStats = file_get_contents('https://na.api.pvp.net/api/lol/na/v2.5/league/by-summoner/'.$sumID.'/entry?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
      $data = json_encode($currentGameStats);
      echo $data;
  }
  function getCurrentGame(){
    header("Content-type: application/json");
      $sumID = $_POST["sumID"];
      $region = $_POST["region"];
      $currentGameStats = file_get_contents('https://na.api.pvp.net/observer-mode/rest/consumer/getSpectatorGameInfo/' . $region . '/' . $sumID . '?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
      $data = json_encode($currentGameStats);
      echo $data;
  }
	//Function to get list of champions and their id's.
	function getChampionID(){
		header("Content-type: application/json");
  		$url = 'https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9';
  		$championData = file_get_contents('https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
  		$data = json_encode($championData);
  		echo $data;
	}

	//Get SummonerID
	function getSummonerID(){
		header("Content-type: application/json");
  		$sumName = $_POST["sumName"];
      $region = $_POST["region"];
      switch($region) { //Switch case for value of action
            case "NA1": $region = "na"; break;
      }
  		$summonerID = file_get_contents('https://na.api.pvp.net/api/lol/'. $region .'/v1.4/summoner/by-name/'. $sumName .'?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
  		$data = json_encode($summonerID);
  		echo $data;
	}

  function getStats(){
    header("Content-type: application/json");
      $sumID = $_POST["sumID"];
      $region = $_POST["region"];
      switch($region) { //Switch case for value of action
            case "NA1": $region = "na"; break;
      }
      $currentGameStats = file_get_contents('https://'.$region.'.api.pvp.net/api/lol/na/v1.3/stats/by-summoner/'.$sumID.'/ranked?season=SEASON2015&api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
      $data = json_encode($currentGameStats);
      echo $data;
  }

	function getRanked5v5Solo(){
		header("Content-type: application/json");
  		$sumID = $_POST["sumID"];
  		$queueType = "RANKED_SOLO_5x5";
  		//echo $sumID . ', ' . $championList . ', ' . $queueType;
  		if($queueType === "RANKED_SOLO_5x5"){
  			$matchHistoryData = file_get_contents('https://na.api.pvp.net/api/lol/na/v2.2/matchhistory/' . $sumID . '?&beginIndex=0&endIndex=15&api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
  			$data = json_encode($matchHistoryData);
  			echo $data;
  		} else {
  			$matchHistoryData = file_get_contents('https://na.api.pvp.net/api/lol/na/v1.3/game/by-summoner/'. $sumID . '/recent?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
  			$data = json_encode($matchHistoryData);
  			echo $data;
  		}
  		
	}

?>
