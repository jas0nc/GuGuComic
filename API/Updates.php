<?php
echo '<br><br><h3>更新Secretkey:</h3>';
$jpglinktoday = file_get_contents(__DIR__.'/secretkey.txt');
//echo '<p>現時Secretkey : '.$jpglinktoday.'</p>';
echo '	<form name="SecretkeyUpdater" action="index.php" method="POST" target="_blank" align="center">
			  Secretkey : <textarea cols="40" rows="1" name="Secretkey">'.$jpglinktoday.'</textarea><br>
			  <input type="submit" value="Update">
			</form>
			<br>';
echo '<br><h3>下載新漫畫:</h3>';
echo '<p>put in url of main page of comic from <a href="https://www.cartoonmad.com" target="_blank">cartoonmad</a>, </p>';
echo '<p>eg. <a href="https://www.cartoonmad.com/comic/1152.html" target="_blank">https://www.cartoonmad.com/comic/1152.html</a></p>';
echo '<p>(To download multible comics at once, seperate each comic link by new line)</p>';
echo '	<form name="Downloader" action="index.php" method="POST" target="_blank" align="center">
			  <textarea cols="50" rows="10" name="URLs">'.'</textarea><br>
			  <input type="submit" value="Submit">
			</form>
			<br>';
//echo '<a href="API/RunAutoUpdater.php" target="_blank"><button>全部更新</button></a>';
echo '<div><h3>自動更新紀錄:</h3><a href="API/ClearLog.php"><button>全部清除</button></a><br>'.nl2br(file_get_contents(__DIR__ .'/../config/ComicData/UpdateLog.txt')).'</div>';
echo '<br><br>';
echo 'Total Cached storage:'.folderSize (__DIR__.'/../temp')."MB";
echo '<br>';
echo '<a href="API/ClearLog.php"><button>全部清除</button></a>';
echo '<br><br>';

function folderSize ($dir)
{
    $size = 0;
    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : folderSize($each);
    }
    return round($size/1024/1024,0);
}
?>
<!--<script>
var xhttp = new XMLHttpRequest();
xhttp.open("GET", "API/AutoUpdater.php", true);
xhttp.send();
</script>-->