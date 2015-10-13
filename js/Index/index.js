(function(){
	var index = angular.module('index', []);
	
	index.directive('navbar', function(){
		return {
			restrict: 'E',
			templateUrl: 'Navbar/navbar.html',
			controller: function(){
				this.sections = ["Home"];
				this.tab = 1;
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
	
	index.controller('NavController', function(){
		this.nav = {
			title: "LOL Statistics Made Ez(real)",
		};
		this.pages = [
			{
				name:"Current Game",
				link:"./index.php"
			},
			{
				name:"Profile",
				link:"./profile.php"
			},
			{
				name:"Summoner Stats",
				link:"./SummonerStats/sumStats.php"
			},
		];
	});

	index.controller('FormController', function(){
		this.formInfo = {
			namePlaceholder:"Enter your Summoner Name",
			buttonName:"Analyze Summoner"
		};
		this.regions =
		[
			{
				name:"Please select a region",
				value:"-1"
			},
			{
				name:"NA - North America",
				value:"NA1"
			},
			{
				name:"EU - Europe",
				value:"EUW1"
			},
			{
				name:"EU - Europe Nordic & East",
				value:"EUN1"
			},
			{
				name:"BR - Brazil",
				value:"BR1"
			},
			{
				name:"KR - Korea",
				value:"KR"
			},
			{
				name:"LAN - Latin America North",
				value:"LA1"
			},
			{
				name:"LAS - Latin America South",
				value:"LA2"
			},
			{
				name:"OCE - Oceania",
				value:"OC1"
			},
			{
				name:"RU - Russia",
				value:"RU"
			},
			{
				name:"TR - Turkey",
				value:"TR"
			}
		];
	});
})();