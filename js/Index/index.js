(function(){
	var index = angular.module('index', []);
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
			name:"Profile",
			link:"./profile.php"
		},
		{
			name:"Summoner Stats",
			link:"./SummonerStats/sumStats.php"
		},
	];
	

	index.controller('NavController', function(){
		this.nav = navContent;
		this.pages = pages
	});

	var formContent = 
		{
			namePlaceholder:"Enter your Summoner Name",
			buttonName:"Get Game"
		};
	index.controller('FormController', function(){
		this.content = formContent;
	});

	var regions = 
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
	index.controller('RegionController', function(){
		this.regions = regions;
	});
})();