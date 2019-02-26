<?
function updatescript($comicname, $comicSN, $ComicLinkArr,$LastChatperArr){
	$url = 'http://www.cartoonmad.com/comic/'.$comicSN.'.html';
	//echo $url;
	$html = urldecode(file_get_contents($url));
	$html = mb_convert_encoding($html,'utf-8','Big5');
	//Parsing SourceCode and get last chapter
	$chapterlist = end(explode('cellpadding="0" cellspacing="0" border="0">',$html));
	$chapterlist = array_values(explode('/image/content_box5.gif" width="10">',$chapterlist))[0];
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
			$debug .= $comicname.'['.$comicSN.'] - NULL<br>';
		}
		else {
			//$prefetchcomic = file_get_contents('http://'.$_SERVER['HTTP_HOST'].'?Comic='.$comicname); //try do prefetch
			//$ComicLinkArr
			$resortarray = $ComicLinkArr[$comicname];
			unset($ComicLinkArr[$comicname]);
			$ComicLinkArr[$comicname] = $resortarray;
			file_put_contents(__DIR__.'/../config/ComicData/ComicLinkArr.json',json_encode($ComicLinkArr),LOCK_EX);
			//$LastChatperArr
			unset($LastChatperArr[$comicname]);
			$LastChatperArr = array_merge($LastChatperArr, array($comicname => $lastchap));
			file_put_contents(__DIR__.'/../config/ComicData/LastChatper.json',json_encode($LastChatperArr),LOCK_EX);
			//$result
			$result .= $comicname.'['.$comicSN.'] - 更新到 - '.$lastchap.' - ['.date("Y-m-d").']<br>';
			//$result .= print_r($LastChatperArr).'<br>';
			$oldlog = file_get_contents(__DIR__ .'/../config/ComicData/UpdateLog.txt');		
			$newlog = $result;
			file_put_contents(__DIR__ .'/../config/ComicData/UpdateLog.txt',$newlog.$oldlog,LOCK_EX);
		}
	}
	else{
		//$ComicLinkArr
		$ComicLinkArr = array_reverse($ComicLinkArr );
		$ComicLinkArr = array_merge($ComicLinkArr, array($comicname => $comicSN));
		$ComicLinkArr = array_reverse($ComicLinkArr );
		file_put_contents(__DIR__.'/../config/ComicData/ComicLinkArr.json',json_encode($ComicLinkArr),LOCK_EX);
		//$LastChatperArr
		$LastChatperArr = array_merge($LastChatperArr, array($comicname => $lastchap));
		file_put_contents(__DIR__.'/../config/ComicData/LastChatper.json',json_encode($LastChatperArr),LOCK_EX);
		//$result
		$debug .= $comicname.'['.$comicSN.'] - 已新增 - '.$lastchap.'<br>';
	}
	return $lastchap;
}
?>