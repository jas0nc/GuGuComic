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
//-----------------------------//
//Check new updates URL from ComicSN array
$debug = date("Y-m-d H:i:s").' [START-COMICSCAN]<br>';
$result = "";
//$result .= print_r($ComicLinkArr).'<br>';
//override Comic Array for signle comic request
if (isset($_GET['comicname']) && isset($_GET['ComicSN'])){
	$comicname = $_GET['comicname'];
	$ComicSN = $_GET['ComicSN'];
	$ComicLinkArr = array($comicname => $ComicSN);	
	}
//Update check start
foreach($ComicLinkArr as $comicname => $comicSN){
	$url = 'http://www.cartoonmad.com/comic/'.$comicSN.'.html';
	//echo $url;
	$html = urldecode(file_get_contents($url));
	$html = mb_convert_encoding($html,'utf-8','Big5');
	//Parsing SourceCode and get last chapter
	$chapterlist = end(explode('cellpadding="0" cellspacing="0" border="0">',$html));
	$chapterlist = array_values(explode('<td background="/image/content_box5.gif" width="10">',$chapterlist))[0];
	$chapter = end(preg_split("/<a href=/", $chapterlist));
	$schapter = array_values(explode('</a>',$chapter))[0];
	$schapterlink = array_values(explode(' target=_blank>',$schapter))[0];
	$schaptername = array_values(explode(' target=_blank>',$schapter))[1];
	$lastchap = $schaptername;
	if (array_key_exists($comicname, $ComicLinkArr)){
		if ($lastchap === $LastChatperArr[$comicname]){
		//$result
			$debug .= $comicname.'['.$comicSN.'] - 沒有更新<br>';
			//echo $comicname.'['.$comicSN.'] - 沒有更新<br>';exit;
		}
		else if($lastchap === null){
		//$result
			$result .= $comicname.'['.$comicSN.'] - NULL<br>';
		}
		else {
			//$ComicLinkArr
			$resortarray = $ComicLinkArr[$comicname];
			unset($ComicLinkArr[$comicname]);
			$ComicLinkArr[$comicname] = $resortarray;
			file_put_contents(__DIR__.'/../config/ComicData/ComicLinkArr.json',json_encode($ComicLinkArr));
			//$LastChatperArr
			unset($LastChatperArr[$comicname]);
			$LastChatperArr = array_merge($LastChatperArr, array($comicname => $lastchap));
			file_put_contents(__DIR__.'/../config/ComicData/LastChatper.json',json_encode($LastChatperArr));
			//$result
			$result .= $comicname.'['.$comicSN.'] - 更新到 - '.$lastchap.' - ['.date("Y-m-d").']<br>';
			//$result .= print_r($LastChatperArr).'<br>';
		}
	}
	else{
		//$ComicLinkArr
		$ComicLinkArr = array_merge($ComicLinkArr, array($comicname => $ComicSN));
		file_put_contents(__DIR__.'/../config/ComicData/ComicLinkArr.json',json_encode($ComicLinkArr));
		//$LastChatperArr
		$LastChatperArr = array_merge($LastChatperArr, array($comicname => $lastchap));
		file_put_contents(__DIR__.'/../config/ComicData/LastChatper.json',json_encode($LastChatperArr));
		//$result
		$debug .= $comicname.'['.$comicSN.'] - 已新增 - '.$lastchap.'<br>';
	}
}
//-----------------------------//
$debug .= date("Y-m-d H:i:s").' [FINISH-COMICSCAN]<br>';
//Print Result
echo $result;
//echo $debug;
exit;
//-----------------------------//
?>