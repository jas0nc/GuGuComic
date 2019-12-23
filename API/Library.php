<?php
if(isset($_SESSION['exists'])){
	$ComicLinkArrFile = __DIR__.'/../config/ComicData/ComicLinkArr.json';
	$LasChapterFile = __DIR__.'/../config/ComicData/LastChatper.json';
	$ComicLinkArrFileBackup = __DIR__.'/../config/ComicData/Backup/ComicLinkArr_'.date("Y-m-d H:i:s").'.json';
	$LasChapterFileBackup = __DIR__.'/../config/ComicData/Backup/LastChatper_'.date("Y-m-d H:i:s").'.json';
	if (!copy($ComicLinkArrFile, $ComicLinkArrFileBackup)) {
		echo "failed to backup ComicLinkArrFile.\n"; exit;
	}
	if (!copy($LasChapterFile, $LasChapterFileBackup)) {
		echo "failed to backup LasChapterFile.\n"; exit;
	}
}
$i = 0;
echo '<br><h3>漫畫列表(定期更新):'.count($ComicLinkArr).'套</h3><br>';
echo '<table width="100%hw"><tr>';
foreach(array_reverse($ComicLinkArr) as $comic => $comicSN){
	$i ++;
	$lastchap = $LastChatperArr[$comic];
	//if (!empty($lastchap)){
		echo '<td width="25%" align="center" valign="top">
			';
		echo '<a href="?Comic='.$comic.'">
			';
		echo '<img src="/../CBZ/'.$comic.'/cover.jpg'.'" width="95%" alt="'.$comic.'" /><br>
			';
		echo '<p style="font-size:15px">'.$comic.'</p></a>
			<p style="font-size:11px" id="'.$comic.'">(Checking Updates)<br></p>
			';
		if(isset($_SESSION['exists'])){
		echo '
			<script>
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			  if (this.readyState == 4 && this.status == 200) {
				if (window.localStorage.getItem("'.$comic.'") != null)
				{
					if (window.localStorage.getItem("'.$comic.'")==this.responseText)
					{
					col=document.getElementById("'.$comic.'");
					col.style.color="'.$nonewupdatecolor.'";
					document.getElementById("'.$comic.'").innerHTML = "觀看到:"+window.localStorage.getItem("'.$comic.'")+"<br>但暫時未有更新<br>";
					}
					if (window.localStorage.getItem("'.$comic.'")!=this.responseText)
					{
					col=document.getElementById("'.$comic.'");
					col.style.color="'.$newupdatecolor.'";
					document.getElementById("'.$comic.'").innerHTML = "觀看到:"+window.localStorage.getItem("'.$comic.'")+"<br>更新到:"+this.responseText+"<br>";
					}
				}
				else
				{
					col=document.getElementById("'.$comic.'");
					col.style.color="'.$notstartedcolor.'";
					document.getElementById("'.$comic.'").innerHTML = "未有觀看記錄<br>";
				}
			  }
			};
			xhttp.open("GET", "API/CheckUpdate.php?comicname='.$comic.'&ComicSN='.$comicSN.'", true);
			xhttp.send();
			</script>
			';
		}
		else{
		echo '
			<script>
			if (window.localStorage.getItem("'.$comic.'") != null)
			{
				if (window.localStorage.getItem("'.$comic.'")=="'.$lastchap.'")
				{
				col=document.getElementById("'.$comic.'");
				col.style.color="'.$nonewupdatecolor.'";
				document.getElementById("'.$comic.'").innerHTML = "觀看到:"+window.localStorage.getItem("'.$comic.'")+"<br>但暫時未有更新<br>";
				}
				if (window.localStorage.getItem("'.$comic.'")!="'.$lastchap.'")
				{
				col=document.getElementById("'.$comic.'");
				col.style.color="'.$newupdatecolor.'";
				document.getElementById("'.$comic.'").innerHTML = "觀看到:"+window.localStorage.getItem("'.$comic.'")+"<br>更新到:'.$lastchap.'<br>";
				}
			}
			else
			{
				col=document.getElementById("'.$comic.'");
				col.style.color="'.$notstartedcolor.'";
				document.getElementById("'.$comic.'").innerHTML = "未有觀看記錄<br>";
			}
			</script>
			';
		}
		echo '</td>';
		switch($i){
		case 4:
			echo '</tr><tr>';
			$i = 0;
			break;
		}
	//}
}
echo '</tr></table><br>';
session_destroy();
?>
