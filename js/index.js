(function(){
	var index = angular.module('index', ['ngRoute']);
	
	//Configure the routing
	index.config(['$routeProvider', function ($routeProvider) {
		$routeProvider.when("/", {						//Home
			templateUrl : "pages/form.html", 
			controller : "MainController"
		}).when("/analysis/:summoner/:region", {				//Analysis
			templateUrl : "pages/analysis.html", 
			controller : "AnalysisController"
		}).when("/matchHistory/:summoner/:region", {
			templateUrl : "pages/matchHistory.html",
			controller : "MatchHistoryController"
		});
	}]);

	index.directive('navbar', function(){
		return {
			restrict: 'E',
			templateUrl: 'Navbar/navbar.html',
			controller: function(){
				
			},
			controllerAs:'panel'
		};
	});

	index.controller('MainController', ['$scope', '$http', '$location', function($scope, $http, $location){
		$scope.summonerName = "";
		$scope.region = "-1";
		$http.get('Php/regions.php').success(function(data){
			$scope.regions = data["regions"];
			console.log(data["regions"]);
		});

		//Submit the form.
		$scope.submitForm = function(){
			$location.path('/analysis/' + $scope.summonerName + '/' + $scope.region);
		}
	}]);

	index.controller('AnalysisController', ['$scope', '$http', '$location', '$anchorScroll', '$routeParams', '$log', function($scope, $http, $location, $anchorScroll, $routeParams, $log){
		//========================Dragon data variables=========================
		$scope.dataDragonVersion = "5.14.1";
		$scope.dataDragonChampionURL = 'http://ddragon.leagueoflegends.com/cdn/' + $scope.dataDragonVersion + '/img/champion/';
		$scope.dataDragonItemURL = "http://ddragon.leagueoflegends.com/cdn/" + $scope.dataDragonVersion + "/img/item/";

		$scope.summonerName = $routeParams.summoner;
		$scope.region = $routeParams.region;
		$scope.isInGame = false;
		$scope.loading = true;

		//Make call to php file which get's data from mongo and returns as a json object.
		$http.get('Php/championData.php').success(function(response){
			$scope.championData = response;
		}, function error(){
			$log.error("Could not get championData");
		});

		//Get summonerSpell data.
		$http.get('Php/itemData.php').success(function(response){
			$scope.itemData = response["data"];
		}, function error(){
			$log.error("Could not get item Data");
		});

		$scope.bringUpGameOverlay = function(){
			$('#gameOverlay').show();
		}
		$scope.closeGameOverlay = function(){
			$('#gameOverlay').hide();
		}
		//A promise to get the summoners id for the give name in the url variables.
		$http({
			url:'./Php/Analysis/sumId.php',		//call php file.
			method:'POST',						//Post the params so php can $_GET them.
			params:{
				sumName : $scope.summonerName, 
				region  : $scope.region
			}
		}).then(function successCallback(response){		//On success of getting the data for the summoners id.

			var returnJSON = response.data;
			for(var key in returnJSON){
				if(returnJSON.hasOwnProperty(key)){
					$scope.simpleSumInfo = returnJSON[key];
				}
			}

			if($scope.simpleSumInfo != undefined){
				$http({
					url:'./Php/Analysis/getLeagues.php',
					method:'POST',
					params:{
						sumIDs:$scope.simpleSumInfo.id,
						region:$scope.region
					}
				}).then(function successCallback(response){
					$scope.summonerLeague = response["data"];
					$scope.loading = false;				    
				}, function error(response){				//There was an error getting the league information
					createErrorDiv("There was an error getting league information for summoners.");
				});
			} else {
				$log.error("Error in getting simple sum infor");
			}
            
			//After we receive the data then we can get the current game information to populate the front end with data.
			$http({
				url    : 'Php/Analysis/currentGame.php',
				method : 'POST',
				params : {
					sumID : $scope.simpleSumInfo.id,
					region : $scope.region
				}
			}).then(function successCallback(response){		//Getting the current game information was a success!
				$scope.currentGame = response["data"];		//Variable to store the current game information.

				if(response["data"] != undefined && response["data"] != ""){			//While the data is undefined we show the loading div. If the data is defined we switch to not showing it.
					$scope.isInGame = true;
					//Get the comma separated list of summoner ids that way we can get an entry list for each summoners league and rank status.
					var csvSummonerIds = "";
				    var csvSummonerIds = csvSummonerIds.concat($scope.currentGame.participants[0].summonerId);
				    for(var summoner = 1; summoner < 10; summoner++){
				        csvSummonerIds = csvSummonerIds.concat( "," + $scope.currentGame.participants[summoner].summonerId);
				    }

				    //Make a promise to get the league of all of the summoners based on the id's from the current game promise response.
				    $http({
						url : './Php/Analysis/getLeagues.php',
						method : 'POST',
						params : {
							sumIDs : csvSummonerIds,
							region : $scope.region
						}
					}).then(function successCallback(response){
						$scope.leagues = response["data"];				    
					}, function error(response){				//There was an error getting the league information
						createErrorDiv("There was an error getting league information for summoners.");
					});
				} else {

				}

				
			}, function error(response){					//There was an error getting the current game information
				$log.error("Could not get simple summoner information like id, name, and level");
			});
		}, function error(response){						//There was an error getting the summoners id.
			alert("Unable to find sumId.php");
		});
		console.log($scope);
	}]);

})();
