(function(){
	var Analyzer = angular.module('analyzer', []);
	
	Analyzer.directive('navbar', function(){
		return {
			restrict: 'E',
			templateUrl: 'Navbar/navbar.html',
			controller: function(){
				this.sections = [
				{
					linkedTo:"currentGame", 
					name:"Current Game"
				},
				{
					linkedTo:"matchHistory",
					name:"Match History"
				}];
				this.tab = 0;
				this.selectTab = function(setTab){
					this.tab = setTab;
				};
				this.isSelected = function(checkTab){
					return this.tab === checkTab;
				};
			},
			controllerAs:'panel'
		};
	});

	Analyzer.controller('CurrentGameController', ['$scope', '$http', function($scope, $http){
		$scope.currentGame;
		$scope.isInGame=false;
		$http({
			url:'./Php/Analysis/sumId.php',
			method:'POST',
			params:{
				sumName: getUrlVars()["sumName"], 
				region:getUrlVars()["region"]
			}
			
		}).then(function successCallback(response){
			$scope.simpleSumInfo = response["data"];

			if(response != false){
                for (var first in $scope.simpleSumInfo){
                    $scope.simpleSumInfo = $scope.simpleSumInfo[first];
                }
            }
			/*after we get this then we chain with another promise to get current game data and set that to a scope.*/
			$http({
				url:'./Php/Analysis/currentGame.php',
				method:'POST',
				params:{
					sumID:$scope.simpleSumInfo.id,
					region:getUrlVars()["region"]
				}
			}).then(function successCallback(response){
				$scope.currentGame = response["data"];
				if(response["data"] != undefined){
					$scope.isInGame=true;
				}
				var csvSummonerIds = "";
			    var csvSummonerIds = csvSummonerIds.concat(response["data"].participants[0].summonerId);
			    for(var summoner = 1; summoner < 10; summoner++){
			        csvSummonerIds = csvSummonerIds.concat( "," + response["data"].participants[summoner].summonerId);
			    }
			    $http({
					url:'./Php/Analysis/getLeagues.php',
					method:'POST',
					params:{
						sumIDs:csvSummonerIds,
						region:getUrlVars()["region"]
					}
				}).then(function successCallback(response){
					$scope.leagues = response["data"];				    
				}, function error(response){

				});
			}, function error(response){

			});
		}, function errorCallback(response){

		});

		//Store the champion data in this controller
		$http({
			url:'./jsonData/championData.json'
		}).then(function successCallback(response){
			console.log(response["data"]);
			$scope.championData = response["data"];
		}, function error(response){

		});
	}]);

})();

function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	vars[key] = value;
	});
	return vars;
}