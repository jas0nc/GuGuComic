<?php
if (isset($ComicLinkArr[$comic])){$comicSN=$ComicLinkArr[$comic];}
if (isset($CompletedComicLinkArr[$comic])){$comicSN=$CompletedComicLinkArr[$comic];}
echo '<br><h3>'.$comic.'</h3>';
echo '<p><a href="http://www.cartoonmad.com/comic/'.$comicSN.'.html" target="_blank">在漫畫狂上觀看(SN:'.$comicSN.')</a></p>';
$Thischaptercount = 0;
//-----------------------------//
$CBZlist = glob(__DIR__.'/../CBZ/'.$comic.'/*.cbz');
foreach($CBZlist as $CBZfile){
	$CBZChap = str_replace(__DIR__.'/../CBZ/'.$comic.'/'.$comic.' - ','',$CBZfile);
	$CBZChap = str_replace('.cbz','',$CBZChap);
	$newCBZlist[] = $CBZChap;
	}
		$combinechaps = $newchapters = $newCBZlist;
echo '<script>var x = 1; t = 1;</script>';
//----------------//
$keys = array_keys($combinechaps);
$nomissingchapter = true;
echo '<table style="background-color:white"><tr>';
foreach(array_reverse(array_keys($keys)) as $k){
	$i ++;
	$CBZpath = __DIR__.'/../CBZ/'.$comic.'/'.$comic.' - '.$combinechaps[$keys[$k]].'.cbz';
	echo '<td width="20%">';
	echo '&nbsp;<a id="h'.$k.'" href="?Comic='.$comic.'&Chapter='.$keys[$k];
	if (file_exists($CBZpath)){ echo '&Chaptername='.$combinechaps[$keys[$k]];}
	echo '">'.$combinechaps[$keys[$k]].'</a>';
	if (file_exists($CBZpath)){ echo '<img height="15" id="CBZ_Ready" src="API/icon/CBZ.png">'; }
	echo '&nbsp;<br>';
	echo '</p></td>';
	echo '
		<script>
		if (window.localStorage.getItem("'.$comic.'") == "'.$combinechaps[$keys[$k]].'"){
			x = 0;
			col=document.getElementById("h'.$k.'");
			col.style.color="#FF0000";
		}
		</script>
		';
	switch($i){
	case 5:
		echo '
		</tr><tr>';
		$i = 0;
		break;
	}
}
		//-----------------------------//
echo '</tr></table><br>';
?>