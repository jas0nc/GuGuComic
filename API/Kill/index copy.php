<?
//Load Comic SN array
$ComicLinkArr = json_decode(file_get_contents('ComicLinkArr.json'), true);
$CompletedComicLinkArr = json_decode(file_get_contents('CompletedComicLinkArr.json'), true);
if (isset($_POST['URLs'])){
	$result = '<h3>The comic(s) has been downloaded, this result page will be closed in 15 seconds.</h3><br>';
	$urls = preg_split("/[\s,]+/", $_POST['URLs']);
	foreach ($urls as $url){
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
		$html = $html[2];
		//Get image link
		$jpg = explode('<img src="',$html);
		$jpg = $jpg[1];
		$jpg = explode('" border="',$jpg);
		$jpg = $jpg[0];
		$jpg = explode('/',$jpg);
		$jpglink = 'http://'.$jpg[2].'/'.$jpg[3].'/'.$jpg[4].'/'.$jpg[5].'/';
		$ComicSN = $jpg[4];
		//Get Total Page Number
		$pages = explode('下一頁',$html);
		$pages = $pages[0];
		$pages = explode('第',$pages);
		$pages = explode(' 頁',end($pages));
		$pages = $pages[0];
		//Error check
		if (empty($url) || empty($pages) || empty($issue) || empty($issuename)){
			echo 'invalid link'.'<br>Chapter URL: '.$url.'<br>Comic Name: '.$name.'<br>Issue Number: '.$issuename.'<br>Max page number: '.$pages.'<br>Page link: '.$jpglink;
			exit;
			}
		// Desired folder structure
		$structure = __DIR__ . '/'.$name.'/'.$issuename.'/';
		//Create folder
		if (!file_exists($structure)) {
			if (!mkdir($structure, 0777, true)) {
				die('Failed to create folders..');
			}
		}
		//Check Comic SN is in array
		if (array_key_exists($name, $ComicLinkArr)){
			echo 'Comic '.$name.' is already in Array';
			$resortarray = $ComicLinkArr[$name];
			unset($ComicLinkArr[$name]);
			$ComicLinkArr[$name] = $resortarray;
			file_put_contents("ComicLinkArr.json",json_encode($ComicLinkArr));
		}
		else {
			$ComicLinkArr = array_merge($ComicLinkArr, array($name => $ComicSN));
			file_put_contents("ComicLinkArr.json",json_encode($ComicLinkArr));
			echo 'Comic '.$name.'\'s SN has been added into array<br>';
		}
		//Download Cover photo
		$Coverpath = __DIR__ . '/'.$name.'/'.'Cover'.'.jpg';
		if (!file_exists($Coverpath)){
			file_put_contents($Coverpath, fopen('http://img.cartoonmad.com/ctimg/'.$ComicSN.'.jpg', 'r'));
			$result .= 'Downloaded new cover for the comic<br>';
		}
		//Download pages
		for ($i = 1; $i <= $pages; $i++) {
			$filename = $structure.sprintf('%03d', $i).'.jpg';
			if (file_exists($filename) && filesize($filename) > 100){
				$result .= '<br>'.$filename.' is exist, skipping download the '.$jpglink.sprintf('%03d', $i).'.jpg';
			}
			else {
				file_put_contents($filename, fopen($jpglink.sprintf('%03d', $i).'.jpg', 'r'));
				$result .= $jpglink.sprintf('%03d', $i).'.jpg'.' has been downloaded to '.$filename.'<br>';
			}
		}
	}
	//Close Tab
	echo $result;
	echo '<SCRIPT>setTimeout("self.close()", 15000 ) // after 5 seconds</SCRIPT>';
	exit;
}
?>
<html>
<head>
<title>動漫J神</title>
<style>
* {
  padding: 0;
  margin: 0;
}
.fit {
  max-width: 100%;
  max-height: 100%;
}
.center {
  display: block;
  margin: auto;
}
</style>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" language="JavaScript">
function set_body_height()
{
    var wh = $(window).height();
    $('body').attr('style', 'height:' + wh + 'px;');
}
$(document).ready(function() {
    set_body_height();
    $(window).bind('resize', function() { set_body_height(); });
});
</script>
<!-- Define the Right Viewport -->
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no" />

<!-- Set Up the App Icon -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="AppIcon/apple-touch-icon-144x144.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="AppIcon/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="AppIcon/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon-precomposed" href="AppIcon/apple-touch-icon-precomposed.png">
<link rel="icon" href="AppIcon/apple-touch-icon.png">

<!-- Load It Like A Native App -->
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">

<!-- Customize the Startup Screen -->
<link rel="apple-touch-startup-image" href="https://s3.amazonaws.com/mikemai/assets/img/ios/l/apple-touch-startup-image-320x460.png"media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 1)">
<link rel="apple-touch-startup-image" href="https://s3.amazonaws.com/mikemai/assets/img/ios/h/apple-touch-startup-image-640x920.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2)">
<link rel="apple-touch-startup-image" href="https://s3.amazonaws.com/mikemai/assets/img/ios/h/apple-touch-startup-image-640x1096.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)">
<link rel="apple-touch-startup-image" href="https://s3.amazonaws.com/mikemai/assets/img/ios/l/apple-touch-startup-image-768x1004.png" media="(device-width: 768px) and (device-height: 1024px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 1)">
<link rel="apple-touch-startup-image" href="https://s3.amazonaws.com/mikemai/assets/img/ios/l/apple-touch-startup-image-748x1024.png" media="(device-width: 768px) and (device-height: 1024px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 1)">
<link rel="apple-touch-startup-image" href="https://s3.amazonaws.com/mikemai/assets/img/ios/h/apple-touch-startup-image-1536x2008.png" media="(device-width: 768px) and (device-height: 1024px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)">
<link rel="apple-touch-startup-image" href="https://s3.amazonaws.com/mikemai/assets/img/ios/h/apple-touch-startup-image-1496x2048.png" media="(device-width: 768px) and (device-height: 1024px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)">

<!-- Add to HomeScreen Reminder -->
<link rel="stylesheet" type="text/css" href="ATH/style/addtohomescreen.css">
<script src="ATH/src/addtohomescreen.js"></script>
<script>
addToHomescreen();
</script>
<!-- Navigate Within the Web App -->
<script>(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")</script>   
</head>
<body bgcolor="#B0C4DE">
<div align="center">
<h1>動漫J神</h1>
<p><i>A mirror site for Cartoonmad.com</i></p>
<a href="javascript:window.location.reload();"><button>刷新頁面</button></a> / 
<a href="index.php"><button>返回首頁</button></a> / 
<a href="?Downloader"><button>下載新漫畫</button></a>
<?
$comic = $_GET['Comic'];
$chap = $_GET['Chapter'];
//Chapter Page
if (isset($chap)){
	$chaps = get_dirs($comic.'/');
	$keys = array_keys($chaps);
	$pages = glob($comic.'/'.$chaps[$keys[$chap]].'/'.'*.jpg');
	//write history to HTML DOM
	echo "<script>
	window.localStorage.setItem('".$comic."', '".$chaps[$keys[$chap]]."');
	</script>";
	//chapter navigator Start
	echo ' / <a href="?Comic='.$comic.'">'.'<button>返回: '.$comic.'</button></a><br>';
	echo '<table width="100%"><tr>';
	echo '<td align="left">';
	if (isset($chaps[$keys[$chap-1]])){echo '<a href="?Comic='.$comic.'&Chapter='.$keys[$chap-1].'">上一話: '.$chaps[$keys[$chap-1]].'</a>';}
	echo '</td><td align="center">';
	echo $chaps[$keys[$chap]];
	echo '</td><td align="right">';
	if (isset($chaps[$keys[$chap+1]])){echo '<a href="?Comic='.$comic.'&Chapter='.$keys[$chap+1].'">下一話: '.$chaps[$keys[$chap+1]].'</a>';}
	echo '</td>';
	echo '</tr></table>';
	//chapter navigator end
	foreach($pages as $page){
		//echo $page;
		echo '<img id="the_pic" class="center fit" src="'.'./'.$page.'"><br>';
	}
	//chapter navigator Start
	echo '<table width="100%"><tr>';
	echo '<td align="left">';
	if (isset($chaps[$keys[$chap-1]])){echo '<a href="?Comic='.$comic.'&Chapter='.$keys[$chap-1].'">上一話: '.$chaps[$keys[$chap-1]].'</a>';}
	echo '</td><td align="center">';
	echo $chaps[$keys[$chap]];
	echo '</td><td align="right">';
	if (isset($chaps[$keys[$chap+1]])){echo '<a href="?Comic='.$comic.'&Chapter='.$keys[$chap+1].'">下一話: '.$chaps[$keys[$chap+1]].'</a>';}
	echo '</td>';
	echo '</tr></table>';
	echo '<a href="?Comic='.$comic.'">'.'<button>返回: '.$comic.'</button></a> / ';
	//chapter navigator end
}
//Comic Page
else if (isset($comic)){
	if (isset($ComicLinkArr[$comic])){$comicSN=$ComicLinkArr[$comic];}
	if (isset($CompletedComicLinkArr[$comic])){$comicSN=$CompletedComicLinkArr[$comic];}
	echo '<br><h3>'.$comic.'</h3><p><a href="http://www.cartoomad.com/comic/'.$comicSN.'" target="_blank">在漫畫狂上觀看(SN:'.$comicSN.')</a></p>';
	echo '<a href="?sDownloader='.$comicSN.'&scomic='.$comic.'"><button>檢查本漫畫的更新</button></a><br>';
	echo '<br><table><tr>
	';
	$i = 0;
	$chaps = get_dirs($comic.'/');
	$keys = array_keys($chaps);
	foreach(array_reverse(array_keys($keys)) as $k){
		$i ++;
		echo '<td width="20%">';
		echo '&nbsp;<a id="h'.$k.'" href="?Comic='.$comic.'&Chapter='.$keys[$k].'">'.$chaps[$keys[$k]].'</a>&nbsp;<br>';
		echo '</p></td>';
		echo '
		<script>
		if (window.localStorage.getItem("'.$comic.'")=="'.$chaps[$keys[$k]].'")
		{
		col=document.getElementById("h'.$k.'");
		col.style.color="#FF0000";
		}
		</script>
		';
		switch($i){
			case 5:
			echo '</tr><tr>';
			$i = 0;
			break;
		}
	}
	echo '</tr></table><br>';
}
//Downloader
else if (isset($_GET['Downloader'])){
echo '<br><br><h3>下載新漫畫:</h3>';
echo '	<form name="Downloader" action="index.php" method="POST" target="_blank" align="center">
			  <textarea cols="50" rows="10" name="URLs">'.'</textarea><br>
			  <input type="submit" value="Submit">
			</form>
			<br>';
echo '<a href="API/AutoUpdater.php" target="_blank"><button>全部更新</button></a>';
echo '<div>自動更新紀錄:<br>'.nl2br(file_get_contents("API/UpdateLog.txt")).'</div>';
echo '<a href="API/ClearLog.php" target="_blank"><button>全部清除</button></a>';
echo '<br><br>';

}
//Single comic Downloader
else if (isset($_GET['sDownloader'])){
$newcahpters = '';
$newcahpterscount = 0;
$ThischaptercountArr = array();
$comicname = $_GET['scomic']; 
$comicSN = $_GET['sDownloader'];
	$Thischaptercount = 0;
	$url = 'http://www.cartoonmad.com/comic/'.$comicSN.'.html';
	$html = urldecode(file_get_contents($url));
	$html = mb_convert_encoding($html,'utf-8','Big5');
	if (empty($html)){echo 'blank html';exit;}
	//Parsing SourceCode
	$chapterlist = array_values(explode('<table width="706" cellpadding="0" cellspacing="0" border="0">',$html))[2];
	$chapterlist = array_values(explode('<td background="/image/content_box5.gif" width="10">',$chapterlist))[0];
	$chapterlist = preg_split("/<a href=/", $chapterlist);
	unset($chapterlist[0]);
	foreach ($chapterlist as $chapter){
		$schapter = array_values(explode('</a>',$chapter))[0];
		$schapterlink = array_values(explode(' target=_blank>',$schapter))[0];
		$schaptername = array_values(explode(' target=_blank>',$schapter))[1];
		$schapterpath = __DIR__ . '/'.$comicname. '/'.$schaptername;
		if (!is_dir($schapterpath)){
			$newcahpterscount ++;
			$Thischaptercount ++;
			$newcahpters .= 'http://www.cartoomad.com'.$schapterlink.' ';
		}
	}
	$ThischaptercountArr = array_merge($ThischaptercountArr, array($comicname.'('.$comicSN.')' => $Thischaptercount));
	
	echo ' / <a href="?Comic='.$comicname.'">'.'<button>返回: '.$comicname.'</button></a>';
	echo '<br><br><h3>更新漫畫:</h3>';
	$i = 1;
	foreach ($ThischaptercountArr as $comicnamesn => $Thischaptercount){
		echo $comicnamesn.' 有 '.$Thischaptercount.' 個更新。';
		$i ++;
	}	
	if (!empty($newcahpters)) {
		echo '是否現在更新?';
	echo '	<form name="Bulk Downloader" action="index.php" method="POST" target="_blank" align="center">
				  <input type="hidden" name="URLs" value="'.$newcahpters.'">
				  <input type="submit" value="馬上更新">
				</form>';
	}
	else {
		if (strpos($html, 'chap9.gif') !== false) {echo '漫畫已完載，已搬移到已完結列表。';
		$Completedname = $comicname;
		//Check Comic SN is in array
			if (array_key_exists($Completedname, $CompletedComicLinkArr)){
				unset ($ComicLinkArr[$Completedname]);
				file_put_contents("ComicLinkArr.json",json_encode($ComicLinkArr));
			}
			else {
				$CompletedComicLinkArr = array_merge($CompletedComicLinkArr, array($Completedname => $ComicLinkArr[$Completedname]));
				file_put_contents("CompletedComicLinkArr.json",json_encode($CompletedComicLinkArr));	
				unset ($ComicLinkArr[$Completedname]);
				file_put_contents("ComicLinkArr.json",json_encode($ComicLinkArr));
			}
		}
		else {echo '暫時未有漫畫可更新';}
	}
	echo '<br>';
}
//Comic Manager
else if (isset($_GET['ComicManager'])){
	echo '<table border="1" width="90%">';
	echo '<tr><th>漫畫名稱</th><th>更新到</th><th>標題數目(卷/話)</th><th>狀態</th></tr>';
	ComicListrow($ComicLinkArr,'連載中');
	ComicListrow($CompletedComicLinkArr,'已完結');
	echo '</table><br>';
}
//Front Page
else {
	$i = 0;
	echo '<br><h3>漫畫列表(連載中):</h3><br>';
	echo '<table><tr>';
	foreach(array_reverse($ComicLinkArr) as $comic => $comicSN){
		$i ++;
		$lastchap = end(get_dirs($comic.'/'));
		if (!empty($lastchap)){
			echo '<td width="20%" align="center" valign="top">
			';
			echo '<a href="?Comic='.$comic.'">
			';
			echo '<img src="'.$comic.'/'.'Cover.jpg'.'" width="90%" alt="'.$comic.'" /><br>
			';
			echo '<p style="font-size:15px">'.$comic.'</p></a>
			<p style="font-size:11px" id="'.$comic.'"></p><br>
			';
			echo '
			<script>
			if (window.localStorage.getItem("'.$comic.'") != null)
			{
				if (window.localStorage.getItem("'.$comic.'")=="'.$lastchap.'")
				{
				col=document.getElementById("'.$comic.'");
				col.style.color="#336699";
				document.getElementById("'.$comic.'").innerHTML = "觀看到:"+window.localStorage.getItem("'.$comic.'")+"<br>但暫時未有更新";
				}
				if (window.localStorage.getItem("'.$comic.'")!="'.$lastchap.'")
				{
				col=document.getElementById("'.$comic.'");
				col.style.color="#ff3300";
				document.getElementById("'.$comic.'").innerHTML = "觀看到:"+window.localStorage.getItem("'.$comic.'")+"<br>更新到:'.$lastchap.'";
				}
			}
			else
			{
				col=document.getElementById("'.$comic.'");
				col.style.color="#ff99cc";
				document.getElementById("'.$comic.'").innerHTML = "未有觀看記錄";
			}
			</script>
			';
			echo '</td>';
			switch($i){
				case 5:
				echo '</tr><tr>';
				$i = 0;
				break;
			}	
		}
	}
	$i = 0;
	echo '</tr></table><br>';
	echo '<br><h3>漫畫列表(已完結):</h3><br>';
	echo '<table><tr>';
	foreach(array_reverse($CompletedComicLinkArr) as $comic => $comicSN){
		$i ++;
		$lastchap = end(get_dirs($comic.'/'));
		if (!empty($lastchap)){
			echo '<td width="20%" align="center" valign="top">
			';
			echo '<a href="?Comic='.$comic.'">
			';
			echo '<img src="'.$comic.'/'.'Cover.jpg'.'" width="90%" alt="'.$comic.'" /><br>
			';
			echo '<p style="font-size:15px">'.$comic.'</a></p>
			<p style="font-size:11px" id="'.$comic.'"></p><br>
			';
			echo '
			<script>
			if (window.localStorage.getItem("'.$comic.'") != null)
			{
				if (window.localStorage.getItem("'.$comic.'")=="'.$lastchap.'")
				{
				col=document.getElementById("'.$comic.'");
				col.style.color="#00ffcc";
				document.getElementById("'.$comic.'").innerHTML = "已完結";
				}
				if (window.localStorage.getItem("'.$comic.'")!="'.$lastchap.'")
				{
				col=document.getElementById("'.$comic.'");
				col.style.color="#ff3300";
				document.getElementById("'.$comic.'").innerHTML = "觀看到:"+window.localStorage.getItem("'.$comic.'")+"<br>更新到:'.$lastchap.'";
				}
			}
			else
			{
				col=document.getElementById("'.$comic.'");
				col.style.color="#ff99cc";
				document.getElementById("'.$comic.'").innerHTML = "未有觀看記錄";
			}
			</script>
			';
			echo '</td>';
			switch($i){
				case 5:
				echo '</tr><tr>';
				$i = 0;
				break;
			}	
		}
	}
	echo '</tr></table><br>';
}
?>
<a href="javascript:window.location.reload();"><button>刷新頁面</button></a> / 
<a href="index.php"><button>返回首頁</button></a> / 
<a href="?Downloader"><button>下載新漫畫</button></a> / 
<a href="?ComicManager"><button>漫畫列表管理</button></a>
<br>
<!--Footer-->
<div>
<p align="center">This website is for my personal PHP study only. &copy;Jason Chan</p>
	</body>
	</html>
<?
//Function to scan folders
function get_dirs($path = '.') {
    $dirs = array();
    foreach (new DirectoryIterator($path) as $file) {
        if ($file->isDir() && !$file->isDot()) {
			$dirs[] = $file->getFilename();
        }
    }
	asort($dirs);
	//$key=array_search('@eaDir',$dirs);
	//unset ($dirs[$key]);
	$dirs = array_values($dirs);
    return $dirs;
}
function ComicListrow($ComicArr,$Status){
	foreach(array_reverse($ComicArr) as $comic => $comicSN){
		$lastchap = end(get_dirs($comic.'/'));
		if (!empty($lastchap)){
			echo '<tr><td>';
			echo $comic;
			echo '</td><td>';
			echo $lastchap;
			echo '</td><td>';
			echo '共 '.count(glob($comic.'/*',GLOB_ONLYDIR)).' 個';
			echo '</td><td>';
			echo $Status;
			//echo '</td><td>';
			//echo ;
			echo '</td></tr>';
		}
	}
}
?>