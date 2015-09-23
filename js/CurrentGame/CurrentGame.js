(function(){
	var CurrentGame = angular.module('currentGame', []);
	var navContent = {
		title: "LOL Statistics Made Ez(real)",
	};
	var pages =
	[
		{
			name:"Current Game",
			link:"./index.php"
		},
		{
			name:"profile",
			link:"./profile.php"
		},
		{
			name:"Summoner Stats",
			link:"./SummonerStats/sumStats.php"
		},
	];
	

	CurrentGame.controller('NavController', function(){
		this.nav = navContent;
		this.pages = pages
	});

})();