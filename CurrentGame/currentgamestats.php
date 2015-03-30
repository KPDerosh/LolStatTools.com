<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cuddly Assassin</title>
    <link rel="stylesheet" href="../css/main.css">
    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.2.min.js"></script>
</head>

<body>
    <div id="sideBarNav" class="sideBarNav">
        <div id="wrapper">
            <div id="header">LOL Stat Tools</div>
        </div>
        <ul style="list-style-type:none">
            <a id="homeLink" href="../index.php">
                <li class="listItem">Home</li>
            </a>
            <a id="currentGameLink" href="#">
                <li class="listItem">Current Game</li>
            </a>
        </ul>
    </div>  

    
    <div id="summonerNameInput" style="position:fixed; left:15%; top:0; bottom:0; right:0; margin:auto">
        <input type=text id="summonerName" placeholder="Summoner Name" type="submit"  onKeyDown="if(event.keyCode==13) getStats();">

    </div>
    
</body>
</html>