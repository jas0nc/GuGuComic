<?
//Handle Session
session_Start();
if (isset($_GET['ClearSession'])){
	//session_destroy();
	$_SESSION['exists'] = true;
	echo '<meta http-equiv="refresh" content="1;url=http://'.$_SERVER['HTTP_HOST'].'" />';
	exit;
}
//------------------------------------------//
include('config/config.php');
//Load URL Direction
$comic = $_GET['Comic'];
$chap = $_GET['Chapter'];
$Chaptername = $_GET['Chaptername'];
?>

<html>
<head>
<title>動漫J神<?if(isset($_GET['Comic'])){echo ' - '.$comic;}if(isset($_GET['Chapter'])){echo ' - '.$Chap;}if(isset($_GET['Chaptername'])){echo ' - '.$Chaptername;}?></title>
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
#id_wrapper {
    /* 設定高度最小為100%, 如果內容區塊很多, 可以長大 */
    min-height: 100%;
    /* 位置設為relative, 作為footer區塊位置的參考 */
    position: relative;
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
<!-- <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no" />-->
<meta name="viewport" content="width=device-width; initial-scale=1.0" />

<!-- Set Up the App Icon -->
<link rel="apple-touch-icon" href="API/icon/touch-icon-iphone.png" />
<link rel="apple-touch-icon" sizes="76x76" href="API/icon/touch-icon-ipad.png" />
<link rel="apple-touch-icon" sizes="120x120" href="API/icon/touch-icon-iphone-retina.png" />
<link rel="apple-touch-icon" sizes="152x152" href="API/icon/touch-icon-ipad-retina.png" />

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

<!-- End Head -->
<body background="API/icon/bg.jpg" bgcolor="#B0C4DE">
<div align="center">
<h1>動漫J神</h1>
<p><i>A mirror site for Cartoonmad.com</i></p>
<a id="ComicManager-top" type="hidden" href="?ComicManager"><button>漫畫管理</button></a> 
<a href="javascript:window.location.reload();"><button>刷新頁面</button></a>
<a href="index.php"><button>返回首頁</button></a>
<a href="?BackupDB"><button>建立存檔</button></a> 
<a id="Downloader-top" type="hidden" href="?Downloader"><button>下載漫畫</button></a>
<div id="BackupNametop-div" style="display:none">
<form action="API/BackupDBAPI.php" method="post" align="center">
<p id="BackupNametop">未有存檔</p>
	<input type="hidden" name="user" id="usertop" value="">
	<input type="hidden" name="hiddenItem" id="hiddenItemtop">
	<input type="hidden" name="Backupfile" id="Backupfiletop" value="">
  	<input type="submit" name="Backup" id="BackupNametop-save" value="SAVE">     
	<input type="submit" name="Restore" id="BackupNametop-load" value="LOAD">
</form>
</div>
  </div> <!-- /id_header -->
  <div align="center">
<?
//------------------------------------------//
//include Function
include('API/Function.php');
//------------------------------------------//
//Load Comic SN array
//$ComicLinkArr = array();
$ComicLinkArr = json_decode(file_get_contents('config/ComicData/ComicLinkArr.json'), true);
$LastChatperArr = json_decode(file_get_contents('config/ComicData/LastChatper.json'), true);
//------------------------------------------//
//include API Pages
//------------------------------------------//
//Chapter Page
if (isset($chap) || isset($Chaptername)){include('API/Page.php');}
//------------------------------------------//
//Chapter List
else if (isset($comic)){include('API/Comic.php');}
//------------------------------------------//
//URL=>Download
else if (isset($_POST['Secretkey'])){include('API/UpdateSecretkey.php');}
//------------------------------------------//
//URL=>Download
else if (isset($_POST['URLs'])){include('API/URLDownloaderAPI.php');}
//------------------------------------------//
//Downloader
else if (isset($_GET['Downloader'])){include('API/Updates.php');}
//------------------------------------------//
//------------------------------------------//
//Comic Manager
else if (isset($_GET['ComicManager'])){include('API/ComicManager.php');}
//------------------------------------------//
//ProgressDB
else if (isset($_GET['BackupDB'])){include('API/BackupDB.php');}
//------------------------------------------//
//Front Page
else {
include('API/Library.php');
//include('API/MissingSN.php');
}
//------------------------------------------//
?>
</div> <!-- /id_content -->
<!--Footer-->
<footer align="center">
<a id="ComicManager-bot" type="hidden" href="?ComicManager"><button>漫畫管理</button></a> 
<a href="javascript:window.location.reload();"><button>刷新頁面</button></a>
<a href="index.php"><button>返回首頁</button></a>
<a href="?BackupDB"><button>建立存檔</button></a> 
<a id="Downloader-bot" type="hidden" href="?Downloader"><button>下載漫畫</button></a>
<div id="BackupNamebottom-div" style="display:none">
<form action="API/BackupDBAPI.php" method="post" align="center">
<p id="BackupNamebottom">未有存檔</p>
	<input type="hidden" name="user" id="userbottom" value="">
	<input type="hidden" name="hiddenItem" id="hiddenItembottom">
	<input type="hidden" name="Backupfile" id="Backupfilebottom" value="">
  	<input type="submit" name="Backup" id="BackupNamebottom-save" value="SAVE">     
	<input type="submit" name="Restore" id="BackupNamebottom-load" value="LOAD">
</form>
</div>
<p align="center">This website is for my personal PHP study only. &copy;Jason Chan.<br>Source code is available on <a href="https://github.com/jas0nc/GuGuComic">Github</a></p>
</footer>
	</body>
	</html>	
<!-- HTML5 Local Storage Function -->
<script>
var Progress = [];
for(var i=0, len=localStorage.length; i<len; i++) {
	var key = localStorage.key(i);
    var value = localStorage[key];
    Progress.push(key+":"+value);
}
	//----------------------------------//
	var elem = document.getElementById("hiddenItemtop"); // Get text field
	elem.value = Progress; // Change field	
	if (window.localStorage.getItem("動漫J神-BackupName") != null) {
		document.getElementById("BackupNametop-div").style.display = "block";
		document.getElementById("BackupNamebottom-div").style.display = "block";
	}
	var elem = document.getElementById("usertop"); // Get text field
	elem.value = window.localStorage.getItem("動漫J神-BackupName"); // Change field
	var elem = document.getElementById("Backupfiletop"); // Get text field
	elem.value = window.localStorage.getItem("動漫J神-BackupName")+".json"; // Change field
	document.getElementById("BackupNametop").innerHTML = window.localStorage.getItem("動漫J神-BackupName");
	//----------------------------------//
	var elem = document.getElementById("hiddenItembottom"); // Get text field
	elem.value = Progress; // Change field	
	var elem = document.getElementById("userbottom"); // Get text field
	elem.value = window.localStorage.getItem("動漫J神-BackupName"); // Change field
	var elem = document.getElementById("Backupfilebottom"); // Get text field
	elem.value = window.localStorage.getItem("動漫J神-BackupName")+".json"; // Change field
	document.getElementById("BackupNamebottom").innerHTML = window.localStorage.getItem("動漫J神-BackupName");
</script>
<script type="text/javascript"> 
if(window.localStorage.getItem("動漫J神-BackupName")=="<?echo $mainuserbackupfile;?>") { 
	document.getElementById("ComicManager-top").style.visibility = "visible";  
	document.getElementById("Downloader-top").style.visibility = "visible";  
	document.getElementById("ComicManager-bot").style.visibility = "visible";  
	document.getElementById("Downloader-bot").style.visibility = "visible"; 
} 
else{
	document.getElementById("ComicManager-top").style.visibility = "hidden";
	document.getElementById("Downloader-top").style.visibility = "hidden";
	document.getElementById("ComicManager-bot").style.visibility = "hidden";
	document.getElementById("Downloader-bot").style.visibility = "hidden";
}
</script>