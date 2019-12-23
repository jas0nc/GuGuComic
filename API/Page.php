<?
if (isset($ComicLinkArr[$comic])){$comicSN=$ComicLinkArr[$comic];}
if (isset($CompletedComicLinkArr[$comic])){$comicSN=$CompletedComicLinkArr[$comic];}
//-----------------------------//Comic.php

$CBZlist = glob(__DIR__.'/../CBZ/'.$comic.'/*.cbz');
foreach($CBZlist as $CBZfile){
	$CBZChap = str_replace(__DIR__.'/../CBZ/'.$comic.'/'.$comic.' - ','',$CBZfile);
	$CBZChap = str_replace('.cbz','',$CBZChap);
	$newCBZlist[] = $CBZChap;
	}
$combinechaps = $newchapters = $newCBZlist;

//asort($combinechaps);
$keys = array_keys($combinechaps);
//print_r($combinechaps);
//$pages = glob('./Comic/'.$comic.'/'.$chaps[$keys[$chap]].'/'.'*.jpg');
//write history to HTML DOM
echo "<script>
window.localStorage.setItem('".$comic."', '".$combinechaps[$keys[$chap]]."');
</script>";
//chapter navigator Start
echo ' / <a href="?Comic='.$comic.'">'.'<button>返回: '.$comic.'</button></a><br>';
echo '<table width="100%"><tr>';
echo '<td align="left">';
if (isset($combinechaps[$keys[$chap-1]])){echo '<a href="?Comic='.$comic.'&Chapter='.$keys[$chap-1].'&Chaptername='.$combinechaps[$keys[$chap-1]];}
echo '">上一話: '.$combinechaps[$keys[$chap-1]].'</a>'.'<img height="15" id="CBZ_Ready" src="API/icon/CBZ.png">'; 
echo '</td><td align="center">';
echo $combinechaps[$keys[$chap]];
echo '</td><td align="right">';
if (isset($combinechaps[$keys[$chap+1]])){echo '<a id="preload2" href="?Comic='.$comic.'&Chapter='.$keys[$chap+1].'&Chaptername='.$combinechaps[$keys[$chap+1]];}
echo '">下一話: '.$combinechaps[$keys[$chap+1]].'</a>'.'<img height="15" id="CBZ_Ready" src="API/icon/CBZ.png">'; 
echo '</td>';
echo '</tr></table>';
//chapter navigator end	
//echo $jpglink.sprintf('%03d', 1);exit;
//Download and show images
$structure = __DIR__.'/../temp/';
$pageiscomplete = true;
$pagemissing = "";
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
//chapter navigator Start
echo '<table width="100%"><tr>';
echo '<td align="left">';
if (isset($combinechaps[$keys[$chap-1]])){echo '<a href="?Comic='.$comic.'&Chapter='.$keys[$chap-1].'&Chaptername='.$combinechaps[$keys[$chap-1]];}
echo '">上一話: '.$combinechaps[$keys[$chap-1]].'</a>'.'<img height="15" id="CBZ_Ready" src="API/icon/CBZ.png">'; 
echo '</td><td align="center">';
echo $combinechaps[$keys[$chap]];
echo '</td><td align="right">';
if (isset($combinechaps[$keys[$chap+1]])){echo '<a id="preload2" href="?Comic='.$comic.'&Chapter='.$keys[$chap+1].'&Chaptername='.$combinechaps[$keys[$chap+1]];}
echo '">下一話: '.$combinechaps[$keys[$chap+1]].'</a>'.'<img height="15" id="CBZ_Ready" src="API/icon/CBZ.png">'; 
echo '</td>';
echo '</tr></table>';
echo '<a href="?Comic='.$comic.'">'.'<button>返回: '.$comic.'</button></a> / ';
//chapter navigator end
?>