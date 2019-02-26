<?php
//------------------------------------------//
$DownloadComic = get_dirs('./Comic/');
$DownloadComicKey = array_keys($DownloadComic);
$CompletedComic=array_diff($DownloadComic,array_keys($ComicLinkArr));
//------------------------------------------//
$i = 0;
//------------------------------------------//
echo '<br><h3>已下載漫畫(不會自動更新):'.count($CompletedComic).'套</h3><br>';
echo '<table width="100%hw"><tr>';
//------------------------------------------//
foreach($CompletedComic as $comic){
	$i ++;
	$lastchap = end(get_dirs('./Comic/'.$comic.'/'));
	if (!empty($lastchap)){
		echo '<td width="25%" align="center" valign="top">
			';
		echo '<a href="?Comic='.$comic.'">
			';
		echo '<img src="./Comic/'.$comic.'/'.'Cover.jpg'.'" width="90%" alt="'.$comic.'" /><br>
			';
		echo '<p style="font-size:15px">'.$comic.'</p></a>
			<p style="font-size:11px" id="'.$comic.'"></p><br>
			';
		//------------------------------------------//
		echo '
			<script>
			if (window.localStorage.getItem("'.$comic.'") != null)
			{
				if (window.localStorage.getItem("'.$comic.'")=="'.$lastchap.'")
				{
				col=document.getElementById("'.$comic.'");
				col.style.color="#737373";
				document.getElementById("'.$comic.'").innerHTML = "漫畫已完結<br>謝謝觀賞";
				}
				if (window.localStorage.getItem("'.$comic.'")!="'.$lastchap.'")
				{
				col=document.getElementById("'.$comic.'");
				col.style.color="#ff80aa";
				document.getElementById("'.$comic.'").innerHTML = "觀看到:"+window.localStorage.getItem("'.$comic.'")+"<br>更新到:'.$lastchap.'";
				}
			}
			else
			{
				col=document.getElementById("'.$comic.'");
				col.style.color="#b3ffd9";
				document.getElementById("'.$comic.'").innerHTML = "未有觀看記錄";
			}
			</script>
			';
		//------------------------------------------//
	}
	//------------------------------------------//
	echo '</td>';
	switch($i){
	case 4:
		echo '</tr><tr>';
		$i = 0;
		break;
	}
	//------------------------------------------//
}
//------------------------------------------//
echo '</tr></table><br>';
?>