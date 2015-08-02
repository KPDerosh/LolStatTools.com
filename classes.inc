<?php
//Create summoner class.
class Summoner {

    var $sumName;
    var $sumID;

    //Champion Info
    var $championID;
    var $championName;
    var $championGames;
    var $championKills;
    var $championDeaths;
    var $championAssists;
    var $championWins;
    var $championLosses;
    //Summoner spellid
    var $spell1;
    var $spell2;

    var $seasonString = "SEASON2015";

    var $summonerRankedChampionStatsJSON;

    //Ranked info
    var $tier;
    var $division;
    var $points;
    var $rankedWins;
    var $rankedLosses;

    function Summoner(){

    }

    function getSumName(){
        return $this->sumName;
    }
    function setSumName($sumName){
        $this->sumName = $sumName;
    }

    function getSumID(){
        return $this->sumID;
    }
    function setSumID($sumID){
        $this->sumID = $sumID;
    }

    function getChampionID(){
        return $this->championID;
    }
    function setChampionID($championID){
        $this->championID = $championID;
    }

    function getChampionName(){
        return $this->championName;
    }
    function setChampionName($championName){
        $this->championName = $championName;
    }

    function getChampionGames(){
        return $this->championGames;
    }
    function setChampionGames($championGames){
        $this->championGames = $championGames;
    }

    function getChampionKills(){
        return $this->championKills;
    }
    function setChampionKills($championKills){
        $this->championKills = $championKills;
    }

    function getChampionDeaths(){
        return $this->championDeaths;
    }
    function setChampionDeaths($championDeaths){
        $this->championDeaths = $championDeaths;
    }

    function getChampionAssists(){
        return $this->championAssists;
    }
    function setChampionAssists($championAssists){
        $this->championAssists = $championAssists;
    }

    function getChampionWins(){
        return $this->championWins;
    }
    function setChampionWins($championWins){
        $this->championWins = $championWins;
    }

    function getChampionLosses(){
        return $this->championLosses;
    }
    function setChampionLosses($championLosses){
        $this->championLosses = $championLosses;
    }

    function getSpell1(){
        return $this->spell1;
    }
    function setSpell1($spell1){
        $this->spell1 = $spell1;
    }

    function getspell2(){
        return $this->spell2;
    }
    function setspell2($spell2){
        $this->spell2 = $spell2;
    }

    function getSummonerRankedChampionStatsJSON(){
        return $this->summonerRankedChampionStatsJSON;
    }
    function setSummonerRankedChampionStatsJSON($summonerRankedChampionStatsJSON){
        $this->summonerRankedChampionStatsJSON = $summonerRankedChampionStatsJSON;
    }

    function getTier(){
        return $this->tier;
    }
    function setTier($tier){
        $this->tier = $tier;
    }

    function getDivision(){
        return $this->division;
    }
    function setDivision($division){
        $this->division = $division;
    }

    function getPoints(){
        return $this->points;
    }
    function setPoints($points){
        $this->points = $points;
    }

    function getRankedWins(){
        return $this->rankedWins;
    }
    function setRankedWins($rankedWins){
        $this->rankedWins = $rankedWins;
    }

    function getRankedLosses(){
        return $this->rankedLosses;
    }
    function setRankedLosses($rankedLosses){
        $this->rankedLosses = $rankedLosses;
    }

}