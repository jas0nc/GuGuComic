<?php
//-----------------------------//
//Load ComicSN array
$ComicLinkArr = json_decode(file_get_contents(__DIR__.'/../config/ComicData/ComicLinkArr.json'), true);
//$LastChatperArr = array();
$LastChatperArr = json_decode(file_get_contents(__DIR__.'/../config/ComicData/LastChatper.json'), true);
//-----------------------------//
//Define Variables
$newcahpters = array();
$newcahptersname = array();
include('UpdateScript.php');
//-----------------------------//
//override Comic Array for signle comic request
if (isset($_GET['comicname']) && isset($_GET['ComicSN'])){
	$comicname = $_GET['comicname'];
	$comicSN = $_GET['ComicSN'];
	echo updatescript($comicname, $comicSN, $ComicLinkArr, $LastChatperArr);	
}
//Update check start
else{
	foreach($ComicLinkArr as $comicname => $comicSN){
	echo updatescript($comicname, $comicSN, $ComicLinkArr, $LastChatperArr);
	}
}
//-----------------------------//
exit;
//-----------------------------//
?>