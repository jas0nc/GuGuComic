<?php
$testpage = "/2504/324/002.jpg"; //七原罪漫畫 - 324 話　- 002.jpg
$jpglinktoday = file_get_contents(__DIR__.'/secretkey.txt');
/*$downloadpage = fopen($jpglinktoday.$testpage, 'r');
$downloadallcomictest = str_replace('/API','',__DIR__).'/temp/downloadallcomictest.jpg';
file_put_contents($downloadallcomictest, $downloadpage);
if (filesize($downloadallcomictest) < 20000 || file_get_contents($downloadallcomictest) == file_get_contents(str_replace('/API','',__DIR__).'/temp/404.jpg')){
	echo 'jpglinktoday: {'.$jpglinktoday.'} is not correct.<br>Please update it in config.php.';
	//echo 'downloaded testpage size '.round(filesize(str_replace('/API','',__DIR__).'/temp/testingpage.jpg')/1024,0).'KB is less than 20KB<br>';
	//echo 'non-cached comic may not be shown properly.';
	//find new secretkey
	//$jpglinktoday = 'https://www.cartoonmad.com/home75372';
	//创建可抛出一个异常的函数
	function testsecretkey($key)
	 {
	 	$downloadpage_img = fopen('http://web4.cartoonmad.com/'.sprintf('%05d', $key).$testpage, 'r');
	 	$filename = str_replace('/API','',__DIR__).'/temp/testingpage'.sprintf('%05d', $key).'.jpg';
	 	file_put_contents($filename, $downloadpage_img);
		//if (!is_file($filename) || filesize($filename) < 20000)
	  if (!is_file($filename) || filesize($filename) < 20000 || file_get_contents($filename) == file_get_contents(str_replace('/API','',__DIR__).'/temp/404.jpg'))
	  {
	    $error = 'this secretkey '.$key.' is not correct;';
	    unlink($filename);
	    throw new Exception($error);
	  }
	 unlink($filename);
	 return true;
	 }

	//在 "try" 代码块中触发异常
	for ($i = 80000; $i >= 50000; $i--){
		try
		 {
			 if (testsecretkey($i)){
				 //If the exception is thrown, this text will not be shown
				 echo 'http://web4.cartoonmad.com/home'.sprintf('%05d', $i).' is correct;!!!!!!!!!<br>';
				 $jpglinktoday = 'http://web4.cartoonmad.com/home'.sprintf('%05d', $i);
				 $secretkeyfile = __DIR__.'/secretkey.txt';
				 $secretkeyfilebackup = __DIR__.'/secretkeybackup'.'/secretkey.'.date("Y-m-d").'.txt';
				 file_put_contents($secretkeyfilebackup, file_get_contents($secretkeyfile));
				 file_put_contents($secretkeyfile, $jpglinktoday);
				 break;
			 }
		 }
		//捕获异常
		catch(Exception $e)
		 {
		 echo 'Message: ' .$e->getMessage();
		 }
	}
}
else {echo 'Secretkey: '.$jpglinktoday.' is checked and correct.
';}
unlink($downloadallcomictest);*/
//$ComicLinkArr = array();
$ComicLinkArr = json_decode(file_get_contents(str_replace('/API','',__DIR__).'/config/ComicData/ComicLinkArr.json'), true);
$LastChatperArr = json_decode(file_get_contents(str_replace('/API','',__DIR__).'/config/ComicData/LastChatper.json'), true);

//$ComicLinkArr  = array('超能力者齊木楠雄的災難' => 2022);

$ComicLinkArrFile = str_replace('/API','',__DIR__).'/config/ComicData/ComicLinkArr.json';
$LasChapterFile = str_replace('/API','',__DIR__).'/config/ComicData/LastChatper.json';
$ComicLinkArrFileBackup = str_replace('/API','',__DIR__).'/config/ComicData/Backup/ComicLinkArr_'.date("Y-m-d H:i:s").'.json';
$LasChapterFileBackup = str_replace('/API','',__DIR__).'/config/ComicData/Backup/LastChatper_'.date("Y-m-d H:i:s").'.json';
if (!copy($ComicLinkArrFile, $ComicLinkArrFileBackup)) {
    echo "failed to backup ComicLinkArrFile.\n"; exit;
}
if (!copy($LasChapterFile, $LasChapterFileBackup)) {
    echo "failed to backup LasChapterFile.\n"; exit;
}

$i = 0;

foreach(array_reverse($ComicLinkArr) as $comic => $comicSN){ // for normal download
//foreach($ComicLinkArr as $comic => $comicSN){ //for reverse download
	echo 'Start Checking :'.$comic.'
';
	$i ++;
	$lastchap = $LastChatperArr[$comic];
    $Thischaptercount = 0;
    //-----------------------------//
	$url = 'http://www.cartoonmad.com/comic/'.$comicSN.'.html';
	$html = urldecode(file_get_contents($url));
	$html = mb_convert_encoding($html,'utf-8','Big5');
	//-----------------------------//
	//Parsing SourceCode
	//$chapterlist = array_values(explode('cellpadding="0" cellspacing="0" border="0">',$html))[3];
	$chapterlist = end(explode('cellpadding="0" cellspacing="0" border="0">',$html));
	$chapterlist = array_values(explode('<td background="/image/content_box5.gif" width="10">',$chapterlist))[0];
	$chapterlist = preg_split("/<a href=/", $chapterlist);
	unset($chapterlist[0]);
	asort($chapterlist);
	$newcahptersname = array();
	$chaps = array();
    $chappages = array();
	foreach (array_reverse($chapterlist) as $chapter){
		$schapter = array_values(explode('</a>',$chapter))[0];
		$schapterlink = array_values(explode(' target=_blank>',$schapter))[0];
		$schaptername = array_values(explode(' target=_blank>',$schapter))[1];
		$schapterpath = '/var/services/web/cartoonmad/Comic/'.$comicname. '/'.$schaptername;
		//-----------------------------//
		$schapterpage = array_values(explode('</a>',$chapter))[1];
		$schapterpage = array_values(explode('(',$schapterpage))[1];
		$schapterpage = array_values(explode('頁',$schapterpage))[0];
		//Add URL to download quere if chapter not exist
		$newcahptersname[] = $schaptername;
		$newcahpters = 'http://www.cartoonmad.com'.$schapterlink;
		$chaps[] = 'http://www.cartoonmad.com'.$schapterlink;
        $chappages[] = str_replace('&nbsp;','',$schapterpage);
		$lastchap = $schaptername;
	}
	$lastchap = current($newcahptersname);
	//echo $lastchap;exit;
	//update last chap array
	if ($lastchap != $LastChatperArr[$comic] && $lastchap != null){
		//$prefetchcomic = file_get_contents('http://'.$_SERVER['HTTP_HOST'].'?Comic='.$comicname); //try do prefetch
		//$ComicLinkArr
		$resortarray = $ComicLinkArr[$comic];
		unset($ComicLinkArr[$comic]);
		$ComicLinkArr[$comic] = $resortarray;
		file_put_contents(str_replace('/API','',__DIR__).'/config/ComicData/ComicLinkArr.json',json_encode($ComicLinkArr, JSON_UNESCAPED_UNICODE),LOCK_EX);
		//$LastChatperArr
		unset($LastChatperArr[$comic]);
		$LastChatperArr = array_merge($LastChatperArr, array($comic => $lastchap));
		file_put_contents(str_replace('/API','',__DIR__).'/config/ComicData/LastChatper.json',json_encode($LastChatperArr, JSON_UNESCAPED_UNICODE),LOCK_EX);
		//$result;
		$result .= $comic.'['.$comicSN.'] - 更新到 - '.$lastchap.' - ['.date("Y-m-d H:m").']<br>';
		//$result .= print_r($LastChatperArr).'<br>';
		$oldlog = file_get_contents(__DIR__ .'/../config/ComicData/UpdateLog.txt');
		$newlog = $result;
		file_put_contents(__DIR__ .'/../config/ComicData/UpdateLog.txt',$newlog.$oldlog,LOCK_EX);
	}
	//print_r($newcahptersname); print_r($chaps); continue;
	$keys = array_keys($chaps);
	foreach(array_keys($keys) as $k){
		$CBZpath = str_replace('/API','',__DIR__).'/CBZ/'.$comic.'/'.$comic.' - '.$newcahptersname[$keys[$k]].'.cbz';
		if(file_exists($CBZpath)){
			echo '   '.end(explode('/',$CBZpath)).' is exist, skip;
';
			continue;
			}
		else {
			echo '   '.end(explode('/',$CBZpath)).' is not exist, begin check;
';
            $jpg = end(explode('/',$chaps[$k]));
            $jpg = str_replace($comicSN,"",$jpg);
            //$jpg = str_replace(".html","",$jpg);
            //$jpg = substr($jpg,0,-7);
            $jpg = substr($jpg,0,4);
            $jpg = sprintf("%03d",$jpg);
            //echo "\n".$jpg."\n"; exit;
            $jpglink = $jpglinktoday.'/'.$comicSN.'/'.$jpg.'/';
            $pages = $chappages[$k];
			/*$html = urldecode(file_get_contents($chaps[$k]));
			$html = mb_convert_encoding($html,'utf-8','Big5');
			if (empty($html)){echo 'blank html<br>';exit;}
			if (strpos($html,'第 1 頁') === false) {
				echo '	Target page: '.$chaps[$k].' is blocked by an ads page,
	try new stuff!
';
				file_put_contents(str_replace('/API','',__DIR__).'/CBZ/'.$comic.'/cover.jpg', $downloadpage_img);
                $jpg = end(explode('/',$chaps[$k]));
                $jpg = substr($jpg,5,3);
                $jpglink = $jpglinktoday.'/'.$comicSN.'/'.$jpg.'/';
                $pages = $chappages[$k];
                //exit;
				}
			//Parsing SourceCode
			else {
                $html = explode('第 1 頁',$html);
                //Get Name & issue
                $name = explode('<title>',$html[0]);
                $name = array_values(explode('漫畫',$name[1]))[0];
                $name = str_replace(' ', '', $name);
                $issue = explode('<title>',$html[0]);
                $issue = explode(' - 第 ',$issue[1]);
                $issue = sprintf('%03d', array_values(explode(' 話 - ',$issue[1]))[0]);
                //echo $newcahptersname[$keys[$k]];exit;
                //Get link and pages
                $html = $html[2];//echo $html;
                //Get image link
                $jpg = explode('<img src="comicpic.asp?file=',$html);
                $jpg = $jpg[1];
                $jpg = explode(' border="',$jpg);
                $jpg = $jpg[0];
                $jpg = str_replace('001"','',$jpg);
                $jpg = str_replace('001&rimg=1"','',$jpg);
                //echo '   '.'   '.'jpg:'.$jpg.'';
                //$jpglink = $jpglinktoday.'/'.$comicSN.'/'.$jpg.'/';
                $jpglink = $jpglinktoday.$jpg;
                //echo '   '.'   '.'jpglink: '.$jpglink.'';
                //https://www.cartoonmad.com/75527/1152/961/002.jpg
                //https://www.cartoonmad.com/75527/2504/330/001.jpg

                //echo $jpg;
                //$jpglink = preg_replace('/\d+.jpg/', '', $jpg);
                //echo '   '.'   '.'jpglink: '.$jpglink.'';
                //exit;
                //$ComicSN = $jpg[4];
                //Get Total Page Number
                //$pages = explode('下一頁',$html);
                $pages = str_replace('下一卷','下一話',$html);
                $pages = explode('下一話',$pages);//echo $html;
                $pages = $pages[0];
                $pages = explode('第',$pages);
                $pages = explode(' 頁',end($pages));
                $pages = $pages[0];
            }*/
			//---------------------------------------------------------------------------------------//
			$keys = array_keys($chaps);
			//Download and show images
			$pageiscomplete = true;
			$pagemissing = "";
			echo '   '.'   '.'total page: '.$pages.' pages
';
			for ($i = 1; $i <= $pages; $i++) {
				$structure = str_replace('/API','',__DIR__).'/temp/'.$comic.'/';
				if (!file_exists($structure)) {
					mkdir($structure, 0777, true);
					echo '   '.'   '.'created folder:'.$structure.'
';
				}
				$filename = $structure.str_replace(' ','',$comic).'-'.$newcahptersname[$keys[$k]].'-'.sprintf('%03d', $i).'.jpg';
				if (is_file($filename) && filesize($filename) > 20000){
					echo '   '.'   '.'image exist: '.end(explode('/',$filename)).', skip;
';
					}
				else {
					//test if secretkey is usable
					$start_memory_img = memory_get_usage();
					$downloadpage_img = fopen($jpglink.sprintf('%03d', $i).'.jpg', 'r');
					$downloadpagesize_img = memory_get_usage() - $start_memory_img;
					file_put_contents($filename, $downloadpage_img);
					if (is_file($filename) && filesize($filename) > 20000)
					{
						echo '   '.'   '.'downloading image: '.end(explode('/',$filename)).', next;
';							//echo '<img id="the_pic" class="center fit" src="/temp/'.$comic.'/'.$comic.'-'.$newcahptersname[$keys[$chap]].'-'.sprintf('%03d', $i).'.jpg"><br>';
					}
					else if (is_file($filename) && file_get_contents($filename) != file_get_contents(str_replace('/API','',__DIR__).'/temp/404.jpg') && filesize($filename) > 1000){
						echo '   '.'   '.'downloading image: '.end(explode('/',$filename)).'(small but not 404), next;
';							//echo '<img id="the_pic" class="center fit" src="/temp/'.$comic.'/'.$comic.'-'.$newcahptersname[$keys[$chap]].'-'.sprintf('%03d', $i).'.jpg"><br>';
					}
					else if (file_get_contents($filename) == file_get_contents(str_replace('/API','',__DIR__).'/temp/404.jpg')){
						echo '   '.'   '.'download fail: '.end(explode('/',$filename)).', (404 error), break;
   '.'   '.'Please check if this image link is correct: '.$jpglink.sprintf('%03d', $i).'.jpg
';
						//echo 'download failed: <a href="'.$jpglink.sprintf('%03d', $i).'.jpg" target="_blank">'.$comic.'-'.$newcahptersname[$keys[$chap]].'-'.sprintf('%03d', $i).'.jpg</a>. ('.round(filesize($filename)/1024,0).'KB)<br>';
						$pageiscomplete = false;
						break;
					}
					else {
						echo '   '.'   '.'download fail: '.end(explode('/',$filename)).', file size ('.round(filesize($filename)/1024).'KB)too small, break;
   '.'   '.'Please check if this image link is correct: '.$jpglink.sprintf('%03d', $i).'.jpg
';
						//echo 'download failed: <a href="'.$jpglink.sprintf('%03d', $i).'.jpg" target="_blank">'.$comic.'-'.$newcahptersname[$keys[$chap]].'-'.sprintf('%03d', $i).'.jpg</a>. ('.round(filesize($filename)/1024,0).'KB)<br>';
						$pageiscomplete = false;
						break;
					}
				}
			}
			//create CBZ
			if(!$pageiscomplete) {
				echo '   '.'some page is still not downloaded.
'.'   '.'Please download it manually from :
'.'   '.$chaps[$k].'

';
			continue;
			} else {
				//echo "This chapter is complete, generating a CBZ file for backup.<br>";
				if (!file_exists(str_replace('/API','',__DIR__).'/CBZ/'.$comic)) {
					mkdir(str_replace('/API','',__DIR__).'/CBZ/'.$comic, 0777, true);
					}
				if (!file_exists($CBZpath)) {
					$zip = new ZipArchive;
					$opened = $zip->open( $CBZpath, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE );
					if( $opened !== true ){
						die("cannot open {$CBZpath} for writing.");
					} else {
						for ($i = 1; $i <= $pages; $i++) {
							$filename = $structure.str_replace(' ','',$comic).'-'.$newcahptersname[$keys[$k]].'-'.sprintf('%03d', $i).'.jpg';
							$filename = (string)$filename;
							if(!file_exists($filename)) {echo '   '.'   '.'Error: '.$filename.' not found
';}
							/*echo '   '.'   '.'adding '.$filename.' to '.$comic.'-'.$newcahptersname[$keys[$k]].'-'.sprintf('%03d', $i).'.jpg'.'
';*/
							$zip->addFile($filename,$comic.'-'.$newcahptersname[$keys[$k]].'-'.sprintf('%03d', $i).'.jpg');
						}
						//$zip->addFromString($comic.'-'.$newcahptersname[$keys[$k]].'.txt','total pages is '.$pages);
					}
					$zip->close();
					echo '   '.'zip creation ok
';
				if (file_exists($CBZpath)) {echo '   '.'CBZ Created: '.$comic.' - '.$newcahptersname[$keys[$k]].'.cbz'.';
';}
				else  {echo '   '.'All image downloaded but CBZ creation failed at: '.$CBZpath.';
';}
				for ($i = 1; $i <= $pages; $i++) {
					$filename = $structure.str_replace(' ','',$comic).'-'.$newcahptersname[$keys[$k]].'-'.sprintf('%03d', $i).'.jpg';
					if (file_exists($filename)){
							unlink($filename);
						}
					}
				}
			}
         }
     }
	if (file_exists(str_replace('/API','',__DIR__).'/temp/'.$comic)) {rmdir(str_replace('/API','',__DIR__).'/temp/'.$comic);}
	if(!file_exists(str_replace('/API','',__DIR__).'/CBZ/'.$comic.'/cover.jpg') || filesize(str_replace('/API','',__DIR__).'/CBZ/'.$comic.'/cover.jpg') < 20000){
		echo '   '.$comic.'/cover.jpg'.' is not exist, start download;
';
		$start_memory_img = memory_get_usage();
		$downloadpage_img = fopen('https://www.cartoonmad.com/cartoonimg/coimg/'.$comicSN.'.jpg', 'r');
		$downloadpagesize_img = memory_get_usage() - $start_memory_img;
		file_put_contents(str_replace('/API','',__DIR__).'/CBZ/'.$comic.'/cover.jpg', $downloadpage_img);
	}
    echo 'Finish check :'.$comic.'
';
}
exit;
?>
