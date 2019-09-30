<?
if (isset($ComicLinkArr[$comic])){$comicSN=$ComicLinkArr[$comic];}
if (isset($CompletedComicLinkArr[$comic])){$comicSN=$CompletedComicLinkArr[$comic];}
//-----------------------------//
if(isset($Chaptername))
{
	echo "try list CBZ";
	$CBZlist = glob(__DIR__.'/../CBZ/'.$comic.'/*.cbz');
	foreach($CBZlist as $CBZfile){
		$CBZChap = str_replace(__DIR__.'/../CBZ/'.$comic.'/'.$comic.' - ','',$CBZfile);
		$CBZChap = str_replace('.cbz','',$CBZChap);
		$newCBZlist[] = $CBZChap;
	}
	asort($newCBZlist);
	$chaps = $newcahptersname = $newCBZlist;
	$keys = array_keys($newcahptersname);
}
else {
	//Get Comichome page sourcecode
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
	foreach ($chapterlist as $chapter){
		$schapter = array_values(explode('</a>',$chapter))[0];
		$schapterlink = array_values(explode(' target=_blank>',$schapter))[0];
		$schaptername = array_values(explode(' target=_blank>',$schapter))[1];
		$schapterpath = '/var/services/web/cartoonmad/Comic/'.$comicname. '/'.$schaptername;
		//-----------------------------//
		//Add URL to download quere if chapter not exist
		$newcahptersname[] = $schaptername;
		$newcahpters = 'http://www.cartoonmad.com'.$schapterlink;
		$chaps[] = 'http://www.cartoonmad.com'.$schapterlink;
	}
	if (isset($chap)){
		$url=$chaps[$chap];
		$html = urldecode(file_get_contents($url));
		$html = mb_convert_encoding($html,'utf-8','Big5');
		if (empty($html)){echo 'blank html<br>';}
		//Parsing SourceCode
		$html = explode('第 1 頁',$html);
		//Get Name & issue
		$name = explode('<title>',$html[0]);
		$name = array_values(explode('漫畫',$name[1]))[0];
		$name = str_replace(' ', '', $name);
		$issuename = explode('<title>',$html[0]);
		$issuename = array_values(explode(' - ',$issuename[1]))[1];
		$issue = explode('<title>',$html[0]);
		$issue = explode(' - 第 ',$issue[1]);
		$issue = sprintf('%03d', array_values(explode(' 話 - ',$issue[1]))[0]);
		$issuename = array_values(explode(' ',$issuename))[0].' '.$issue.' '.array_values(explode(' ',$issuename))[2];
		//Get link and pages
		$html = $html[2];//echo $html;
		//Get image link
		$jpg = explode('<img src="',$html);
		$jpg = $jpg[2];
		$jpg = explode('" border="',$jpg);
		$jpg = $jpg[0];
		$jpg = explode('/',$jpg);
		$jpglink = $jpglinktoday.'/'.$comicSN.'/'.$jpg[2].'/';
		//$ComicSN = $jpg[4];
		//Get Total Page Number
		//$pages = explode('下一頁',$html);
		$pages = str_replace('下一卷','下一話',$html);
		$pages = explode('下一話',$pages);//echo $html;
		$pages = $pages[0];
		$pages = explode('第',$pages);
		$pages = explode(' 頁',end($pages));
		$pages = $pages[0];
		//echo $pages;
	
		//---------------------------------------------------------------------------------------//
		//$chaps = get_dirs('./Comic/'.$comic.'/');
		$keys = array_keys($chaps);
	}
}
	//$pages = glob('./Comic/'.$comic.'/'.$chaps[$keys[$chap]].'/'.'*.jpg');
	//write history to HTML DOM
	echo "<script>
	window.localStorage.setItem('".$comic."', '".$newcahptersname[$keys[$chap]]."');
	</script>";
	//chapter navigator Start
	echo ' / <a href="?Comic='.$comic.'">'.'<button>返回: '.$comic.'</button></a><br>';
	echo '<table width="100%"><tr>';
	echo '<td align="left">';
	if (isset($chaps[$keys[$chap-1]])){echo '<a href="?Comic='.$comic.'&Chapter='.$keys[$chap-1];
	if (file_exists(__DIR__.'/../CBZ/'.$comic.'/'.$comic.' - '.$newcahptersname[$keys[$chap-1]].'.cbz')){ echo '&Chaptername='.$newcahptersname[$keys[$chap-1]];}
	echo '">上一話: '.$newcahptersname[$keys[$chap-1]].'</a>';}
	if (file_exists(__DIR__.'/../CBZ/'.$comic.'/'.$comic.' - '.$newcahptersname[$keys[$chap-1]].'.cbz')){ echo '<img height="15" id="CBZ_Ready" src="API/icon/CBZ.png">'; }
	echo '</td><td align="center">';
	echo $newcahptersname[$keys[$chap]];
	echo '</td><td align="right">';
	if (isset($chaps[$keys[$chap+1]])){echo '<a id="preload1" href="?Comic='.$comic.'&Chapter='.$keys[$chap+1];
	if (file_exists(__DIR__.'/../CBZ/'.$comic.'/'.$comic.' - '.$newcahptersname[$keys[$chap+1]].'.cbz')){ echo '&Chaptername='.$newcahptersname[$keys[$chap+1]];}
	echo '">下一話: '.$newcahptersname[$keys[$chap+1]].'</a>';}
	if (file_exists(__DIR__.'/../CBZ/'.$comic.'/'.$comic.' - '.$newcahptersname[$keys[$chap+1]].'.cbz')){ echo '<img height="15" id="CBZ_Ready" src="API/icon/CBZ.png">'; }
	echo '</td>';
	echo '</tr></table>';
	//chapter navigator end	
	//echo $jpglink.sprintf('%03d', 1);exit;
	//Download and show images
	$structure = __DIR__.'/../temp/';
	$pageiscomplete = true;
	$pagemissing = "";
	if(isset($Chaptername))
	{
		$CBZpath = __DIR__.'/../CBZ/'.$comic.'/'.$comic.' - '.urldecode($Chaptername).'.cbz';
		echo 'Cache from CBZ: '.end(explode('/',$CBZpath)).'<img height="15" id="CBZ_Ready" src="API/icon/CBZ.png">';
		$za = new ZipArchive(); 
		
		$za->open($CBZpath); 
		
		for( $i = 0; $i < $za->numFiles; $i++ ){ 
			
		    $im_string = $za->getFromIndex( $i ); 
		    $im = imagecreatefromstring($im_string);
		    
			ob_start(); 
			imagejpeg($im, NULL, 100 ); 
			imagedestroy( $im ); 
			$img = ob_get_clean(); 
		
			echo '<img id="the_pic" class="center fit" src="data:image/jpeg;base64,' . base64_encode( $img ).'"><br>'; //saviour line!
		}
	}
	else {
		for ($i = 1; $i <= $pages; $i++) {
			if ($hotlink == "False"){
				$structure = __DIR__.'/../temp/'.$comic.'/';
				if (!file_exists($structure)) {
				    mkdir($structure, 0777, true);
				}
				$filename = $structure.$comic.'-'.$newcahptersname[$keys[$chap]].'-'.sprintf('%03d', $i).'.jpg';
				if (is_file($filename) && filesize($filename) > 20000){
					echo '<img id="the_pic" class="center fit" src="/temp/'.$comic.'/'.$comic.'-'.$newcahptersname[$keys[$chap]].'-'.sprintf('%03d', $i).'.jpg"><br>';
					}
				else {
					//test if secretkey is usable
					$start_memory_img = memory_get_usage();
					$downloadpage_img = fopen($jpglink.sprintf('%03d', $i).'.jpg', 'r'); 
					$downloadpagesize_img = memory_get_usage() - $start_memory_img;
				    file_put_contents($filename, $downloadpage_img);
					if (is_file($filename) && filesize($filename) > 20000)
					{
						echo '<img id="the_pic" class="center fit" src="/temp/'.$comic.'/'.$comic.'-'.$newcahptersname[$keys[$chap]].'-'.sprintf('%03d', $i).'.jpg"><br>';
					}
					else {
						echo 'download failed: <a href="'.$jpglink.sprintf('%03d', $i).'.jpg" target="_blank">'.$comic.'-'.$newcahptersname[$keys[$chap]].'-'.sprintf('%03d', $i).'.jpg</a>. ('.round(filesize($filename)/1024,0).'KB)<br>';
						$pageiscomplete = false;
						$pagemissing .= 'download failed: <a href="'.$jpglink.sprintf('%03d', $i).'.jpg" target="_blank">'.$comic.'-'.$newcahptersname[$keys[$chap]].'-'.sprintf('%03d', $i).'.jpg</a>. ('.round(filesize($filename)/1024,0).'KB)<br>';
					}
				}		
			}
			else {
				echo '<img id="the_pic" class="center fit" src="'.$jpglink.sprintf('%03d', $i).'.jpg"><br>';
			}
			//$result .=  sprintf('%03d', $i).'.jpg ';
		}
			
		//create CBZ
		if($pageiscomplete){
			echo "This chapter is complete, generating a CBZ file for backup.<br>";
			if (!file_exists(__DIR__.'/../CBZ/'.$comic)) {
			    mkdir(__DIR__.'/../CBZ/'.$comic, 0777, true);
			}
			$CBZpath = __DIR__.'/../CBZ/'.$comic.'/'.$comic.' - '.$newcahptersname[$keys[$chap]].'.cbz';
			if (!file_exists($CBZpath)) {
				$zip = new ZipArchive;
				$zip->open($CBZpath, ZipArchive::CREATE);
				for ($i = 1; $i <= $pages; $i++) {
					$filename = $structure.$comic.'-'.$newcahptersname[$keys[$chap]].'-'.sprintf('%03d', $i).'.jpg';
					$zip->addFile($filename,$comic.'-'.$newcahptersname[$keys[$chap]].'-'.sprintf('%03d', $i).'.jpg');
				}
			$zip->close();
			echo "CBZ file has been saved.";
			for ($i = 1; $i <= $pages; $i++) {
				$filename = $structure.$comic.'-'.$newcahptersname[$keys[$chap]].'-'.sprintf('%03d', $i).'.jpg';
				if (file_exists($filename)){
						unlink($filename);
					}
			}
		} else {
			//$zip->open($CBZpath, ZipArchive::OVERWRITE);
			echo "CBZ file already exist";	
			for ($i = 1; $i <= $pages; $i++) {
				$filename = $structure.$comic.'-'.$newcahptersname[$keys[$chap]].'-'.sprintf('%03d', $i).'.jpg';
				if (file_exists($filename)){
						unlink($filename);
					}
				}
			}
		} else { 
			echo 'some page is still not downloaded.<br>
			Please download it manually from :'.$url.'<br>
			The page mssing are:<br>'.$pagemissing;
		}
		if (!file_exists(__DIR__.'/../CBZ/'.$comic.'/'.$comic.' - '.$newcahptersname[$keys[$chap+1]].'.cbz')){ 
			echo '
			<script>
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			  if (this.readyState == 4 && this.status == 200) {	
				col1=document.getElementById("preload1");
				col1.style.color="#009933";
				col2=document.getElementById("preload2");
				col2.style.color="#009933";
			  }
			};
			xhttp.open("GET", "?Comic='.$comic.'&Chapter='.$keys[$chap+1].'&hotlink='.$hotlink.'", true);
			xhttp.send();
			</script>
			';
		}
	}
//chapter navigator Start
echo '<table width="100%"><tr>';
echo '<td align="left">';
if (isset($chaps[$keys[$chap-1]])){echo '<a href="?Comic='.$comic.'&Chapter='.$keys[$chap-1];
if (file_exists(__DIR__.'/../CBZ/'.$comic.'/'.$comic.' - '.$newcahptersname[$keys[$chap-1]].'.cbz')){ echo '&Chaptername='.$newcahptersname[$keys[$chap-1]];}
echo '">上一話: '.$newcahptersname[$keys[$chap-1]].'</a>';}
if (file_exists(__DIR__.'/../CBZ/'.$comic.'/'.$comic.' - '.$newcahptersname[$keys[$chap-1]].'.cbz')){ echo '<img height="15" id="CBZ_Ready" src="API/icon/CBZ.png">'; }
echo '</td><td align="center">';
echo $newcahptersname[$keys[$chap]];
echo '</td><td align="right">';
if (isset($chaps[$keys[$chap+1]])){echo '<a id="preload2" href="?Comic='.$comic.'&Chapter='.$keys[$chap+1];
if (file_exists(__DIR__.'/../CBZ/'.$comic.'/'.$comic.' - '.$newcahptersname[$keys[$chap+1]].'.cbz')){ echo '&Chaptername='.$newcahptersname[$keys[$chap+1]];}
echo '">下一話: '.$newcahptersname[$keys[$chap+1]].'</a>';}
if (file_exists(__DIR__.'/../CBZ/'.$comic.'/'.$comic.' - '.$newcahptersname[$keys[$chap+1]].'.cbz')){ echo '<img height="15" id="CBZ_Ready" src="API/icon/CBZ.png">'; }
echo '</td>';
echo '</tr></table>';
echo '<a href="?Comic='.$comic.'">'.'<button>返回: '.$comic.'</button></a> / ';
//chapter navigator end
?>