<?php
if (isset($_POST['DeleteArrayKey'])){
	$comicname = $_POST['comicname'];
	unset($ComicLinkArr[$comicname]);
	file_put_contents(__DIR__.'/../config/ComicData/ComicLinkArr.json',json_encode($ComicLinkArr));
	unset($LastChatperArr[$comicname]);
	file_put_contents(__DIR__.'/../config/ComicData/LastChatper.json',json_encode($LastChatperArr));
	echo '<h3>'.$comicname.'已自動更新中刪除</h3>';
}
if (isset($_POST['DeleteComicKey'])){
	$comicname = $_POST['comicname'];
	$src = './Comic/'.$comicname;
    rrmdir($src);
	echo '<h3><<'.$comicname.'>> 已刪除</h3>';	
}
function rrmdir($src) {
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}
?>
<?php
echo '<table border="1" width="90%" style="background-color:white">';
echo '<tr><th>漫畫名稱</th><th>更新到</th><th>從自動更新中刪除</th></tr>';
//------------------------------------------//
foreach(array_reverse($ComicLinkArr) as $comic => $comicSN){
	$lastchap = $LastChatperArr[$comic];
	//if (!empty($lastchap)){
		echo '<tr><td>';
		echo '<a href="?Comic='.$comic.'"><img src="http://img.cartoonmad.com/ctimg/'.$comicSN.'.jpg'.'" height="50" alt="'.$comic.'" />'.$comic.'</a>';
		echo '</td><td>';
		echo $lastchap;
		echo '</td><td>';
		echo '<form action="index.php?ComicManager" method="post" align="center">
		<input type="hidden" name="comicname" value="'.$comic.'">
		<input type="submit" name="DeleteArrayKey" value="封存漫畫"></form>';
		echo '</td></tr>';
	//}
}
//------------------------------------------//
echo '</table><br>';
?>