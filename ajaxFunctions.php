<?php
	if (is_ajax()) {
		if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
	    	$action = $_POST["action"];
	    	switch($action) { //Switch case for value of action
	      		case "getChampionID()": getChampionID(); break;
	      		case "getSummonerID()": getSummonerID(); break;
	      		case "getRanked5v5Solo()": getRanked5v5Solo(); break;
            case "getCurrentGame()": getCurrentGame();break;
	    	}
  		}
	}
	//Function to check if the request is an AJAX request
	function is_ajax() {
  		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

  function getCurrentGame(){
    header("Content-type: application/json");
      $sumName = $_POST["sumId"];
      $region = $_POST["region"];
      $currentGameStats = file_get_contents('https://na.api.pvp.net/observer-mode/rest/consumer/getSpectatorGameInfo/' . $region . '/' . $sumName . '?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
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
  		$summonerID = file_get_contents('https://na.api.pvp.net/api/lol/'. $region .'/v1.4/summoner/by-name/'. $sumName .'?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
  		$data = json_encode($summonerID);
  		echo $data;
	}

	function getRanked5v5Solo(){
		header("Content-type: application/json");
  		$sumID = $_POST["sumID"];
  		$championList = $_POST["championList"];
  		$queueType = "RANKED_SOLO_5x5";
  		//echo $sumID . ', ' . $championList . ', ' . $queueType;
  		if($queueType === "RANKED_SOLO_5x5"){
  			$matchHistoryData = file_get_contents('https://na.api.pvp.net/api/lol/na/v2.2/matchhistory/' . $sumID . '?championIds=' . $championList . '&beginIndex=0&endIndex=15&api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
  			$data = json_encode($matchHistoryData);
  			echo $data;
  		} else {
  			$matchHistoryData = file_get_contents('https://na.api.pvp.net/api/lol/na/v1.3/game/by-summoner/'. $sumID . '/recent?api_key=6955669d-0d51-41b0-8b09-c05f4a0468e9');  
  			$data = json_encode($matchHistoryData);
  			echo $data;
  		}
  		
	}

?>
