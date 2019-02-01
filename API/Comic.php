<?php
if (isset($ComicLinkArr[$comic])){$comicSN=$ComicLinkArr[$comic];}
if (isset($CompletedComicLinkArr[$comic])){$comicSN=$CompletedComicLinkArr[$comic];}
echo '<br><h3>'.$comic.'</h3>';
echo '<p><a href="http://www.cartoonmad.com/comic/'.$comicSN.'.html" target="_blank">在漫畫狂上觀看(SN:'.$comicSN.')</a></p>';
$Thischaptercount = 0;
//-----------------------------//
//Get Comichome page sourcecode
$url = 'http://www.cartoonmad.com/comic/'.$comicSN.'.html';
$html = urldecode(file_get_contents($url));
$html = mb_convert_encoding($html,'utf-8','Big5');
$MetaInfo = get_meta_tags($url);
$Info =  mb_convert_encoding($MetaInfo['description'],'utf-8','Big5');
echo '<p>'.$Info.'</p>';
echo '<br><table style="background-color:white"><tr>
	';
//else {
	//$result .=  'Comic Description for '.$comicname.' already exist<br>';
//}
//$result .=  date("Y-m-d H:i:s"). ' [Finished-COMICINFO]<br>';
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
echo '<script>var x = 1; t = 1;</script>';
$keys = array_keys($chaps);
foreach(array_reverse(array_keys($keys)) as $k){
	$i ++;
	echo '<td width="20%">';
	echo '&nbsp;<a id="h'.$k.'" href="?Comic='.$comic.'&Chapter='.$keys[$k].'">'.$newcahptersname[$keys[$k]].'</a>&nbsp;<br>';
	echo '</p></td>';
	echo '
		<script>
		setTimeout(function(t) {
			if (window.localStorage.getItem("'.$comic.'") == "'.$newcahptersname[$keys[$k]].'"){
					x = 0;
					col=document.getElementById("h'.$k.'");
					col.style.color="#FF0000";
				}
			if (x == 1){
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {	
						col=document.getElementById("h'.$k.'");
						col.style.color="#009933";
					}
				};
				xhttp.open("GET", "?Comic='.$comic.'&Chapter='.$keys[$k].'&hotlink='.$hotlink.'", true);
				xhttp.send();
				col=document.getElementById("h'.$k.'");
				col.style.color="#FFA500";
			}
		}, t*500);
		t++;
		</script>
		';
	/*echo '
		<script>
		if (window.localStorage.getItem("'.$comic.'") == "'.$newcahptersname[$keys[$k-1]].'"){
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {	
					col=document.getElementById("h'.$k.'");
					col.style.color="#009933";
				}
			};
			xhttp.open("GET", "?Comic='.$comic.'&Chapter='.$keys[$k-1].'&hotlink='.$hotlink.'", true);
			xhttp.send();
		}
		else if (window.localStorage.getItem("'.$comic.'") == "'.$newcahptersname[$keys[$k]].'")
		{
		col=document.getElementById("h'.$k.'");
		col.style.color="#FF0000";
		}
		else if (window.localStorage.getItem("'.$comic.'") === null){
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {	
					col=document.getElementById("h'."0".'");
					col.style.color="#009933";
				}
			};
			xhttp.open("GET", "?Comic='.$comic.'&Chapter='.$keys[0].'&hotlink='.$hotlink.'", true);
			xhttp.send();
		}
		</script>
		';*/
	switch($i){
	case 5:
		echo '</tr><tr>';
		$i = 0;
		break;
	}
}
		//-----------------------------//
echo '</tr></table><br>';
?>