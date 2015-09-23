<!DOCTYPE html>
<html ng-app="index">
    <head>
        <title>Cuddly Assassin</title>
        <!-- other scripts !-->
        <script type="text/javascript" src="./js/angular.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.2.min.js"></script>
        <link rel="stylesheet" href="./css/Bootstrap/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="./css/PowerTip/jquery.powertip.css">
        <script src="./js/PowerTip/jquery.powertip.js"></script>
        
        <!-- custom stuff!-->
        <link rel="stylesheet" href="./css/Index/leagueoflegendsmain.css">
        <script type="text/javascript" src="./js/Index/index.js"></script>
    </head>

    <body>
        <!--Navigation bar !-->
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container" ng-app="navbar" ng-controller="NavController as nav">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">{{nav.nav.title}}</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li ng-repeat="pages in nav.pages"><a href={{pages.link}}>{{pages.name}}</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <form method="get" action="./currentGame.php" ng-controller="FormController as form">
            <!-- Have the user enter in summoner stats. !-->
            <div id="userSelectContainer" style="margin:auto; margin-bottom: 20px; width:550px" >
                <!-- Summoner name input !-->
                <div id="summonerInput" style="display:inline-block" class="summonerInputBox">
                    <input type="text" id="summonerName" name="sumName" class="form-control" placeholder={{form.content.namePlaceholder}}>
                </div> 

                <!-- Select the region !--> 
                <div id="regionSelectContainer" style="display:inline-block" class="regionSelectContainer" ng-controller="RegionController as regions"> 
                    <select id="regionSelect" name="region" class="form-control">
                        <option  ng-repeat="region in regions.regions" value={{region.value}}>{{region.name}}</option>
                    </select>
                </div>
                <div id="loadGame" style="display:inline-block">
                    <input type="submit" value={{form.content.buttonName}} class="btn btn-default" style="margin:auto;">
                </div>
            </div>  
        </form>
        
        <script src="./js/Bootstrap/bootstrap.min.js"></script>
    </body>
</html>
