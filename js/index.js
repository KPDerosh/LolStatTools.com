(function(){
	var index = angular.module('index', ['ngRoute']);
	
	//Configure the routing
	index.config(['$routeProvider', function ($routeProvider) {
		$routeProvider.when("/", {						//Home
			templateUrl : "pages/form.html", 
			controller : "MainController"
		}).when("/analysis", {				//Analysis
			templateUrl : "pages/analysis.html", 
			controller : "AnalysisController"
		}).when("/matchHistory/:summoner/:region", {
			templateUrl : "pages/matchHistory.html",
			controller : "MatchHistoryController"
		});
	}]);

	//Create a summoner service
	index.service('summoner', ['$http', '$log', function($http, $log){
		//Variables for service
		var self = this;
		this.enteredName = "";
		this.enteredRegion = "";
		this.summonerInformation = {};

		//Load this summoners league once we get the summoners id.
		this.loadLeague = function(){
			return $http({
				url:'./Php/Analysis/getLeagues.php',
				method:'POST',
				params:{
					sumIDs : self.summonerInformation.id,
					region : self.enteredRegion
				}
			});
		}

		//Load the current game if there is one.
		this.loadCurrentGame = function(){
			return $http({
				url    : 'Php/Analysis/currentGame.php',
				method : 'POST',
				params : {
					sumID : self.summonerInformation.id,
					region : self.enteredRegion
				}
			});
		}

		//Load summoner id and stuff.
		this.loadSummonerInformation = function(){
			return $http({
				url:'./Php/Analysis/sumId.php',		//call php file.
				method:'POST',						//Post the params so php can $_GET them.
				params:{
					sumName : this.enteredName, 
					region  : this.enteredRegion
				}
			});
		}
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

	//Main controller used for getting the persons input
	index.controller('MainController', ['$scope', '$http', '$location', '$log', 'summoner',  function($scope, $http, $location, $log, summoner){
		$scope.summonerName = "";
		$scope.region = "-1";
		$http.get('Php/regions.php').success(function(data){
			$scope.regions = data["regions"];
		});

		//Submit the form.
		$scope.submitForm = function(){
			summoner.enteredName = $scope.summonerName;
			summoner.enteredRegion = $scope.region;
			$location.path('/analysis');
		}
	}]);

	//Analysis Controller
	index.controller('AnalysisController', ['$scope', '$http', '$location', '$anchorScroll', '$routeParams', '$log', 'summoner', function($scope, $http, $location, $anchorScroll, $routeParams, $log, summoner){
		//========================Dragon data variables=========================
		$scope.isInGame = false;
		$scope.loading = true;

		$scope.$watch('summonerLeague', function(){
			summoner.summonerLeague = $scope.summonerLeague;
		});
		$scope.$watch('currentGame', function(){
			summoner.currentGame = $scope.currentGame;
		})
		$scope.$watch('leagues', function(){
			summoner.leagues = $scope.leagues;
		});
		summoner.loadSummonerInformation().then(function success(response){
			var returnJSON = response.data;
			for(var key in returnJSON){
				if(returnJSON.hasOwnProperty(key)){
					summoner.summonerInformation = returnJSON[key];
				}
			}
			summoner.loadLeague().then(function success(leagueResponse){
				$scope.summonerLeague = leagueResponse["data"];
			});
			summoner.loadCurrentGame().then(function successCallback(response){		//Getting the current game information was a success!
				$scope.currentGame = response["data"];
				if(response["data"] != undefined && response["data"] != ""){			//While the data is undefined we show the loading div. If the data is defined we switch to not showing it.
					//Get the comma separated list of summoner ids that way we can get an entry list for each summoners league and rank status.
					var csvSummonerIds = "";
				    var csvSummonerIds = csvSummonerIds.concat($scope.currentGame.participants[0].summonerId);
				    for(var index = 1; index < 10; index++){
				        csvSummonerIds = csvSummonerIds.concat( "," + $scope.currentGame.participants[index].summonerId);
				    }

				    //Make a promise to get the league of all of the summoners based on the id's from the current game promise response.
				    $http({
						url : './Php/Analysis/getLeagues.php',
						method : 'POST',
						params : {
							sumIDs : csvSummonerIds,
							region : summoner.enteredRegion
						}
					}).then(function success(response){
						$scope.leagues = response["data"];
						$scope.isInGame = true;
					});	
				} else {

				}
			}, function error(response){					//There was an error getting the current game information
			});
			$scope.simpleSumInfo = summoner.summonerInformation;
			$scope.loading = false;
		});
		//Make call to php file which get's data from mongo and returns as a json object.
		$http.get('Php/championData.php').success(function(response){
			$scope.championData = response;
		}, function error(){
		});

		//Get summonerSpell data.
		$http.get('Php/itemData.php').success(function(response){
			$scope.itemData = response["data"];
		}, function error(){
		});

		$scope.bringUpGameOverlay = function(){
			$('#gameOverlay').show();
		}
		$scope.closeGameOverlay = function(){
			$('#gameOverlay').hide();
		}
	}]);
})();
