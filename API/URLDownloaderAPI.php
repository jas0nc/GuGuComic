<?php
$result = '<h3>The comic(s) has been downloaded, this result page will be closed in 15 seconds.</h3><br>';
//------------------------------------------//
$urls = preg_split("/[\s,]+/", $_POST['URLs']);
foreach ($urls as $url){
	if (empty($url)){continue;}
	else{
	//get $ComicSN
	$comicSN = end(explode('comic/',$url));
	$comicSN = array_values(explode('.',$comicSN))[0];
	//$result .=  $url.'<br>'; 
	//$result .= $comicSN.'<br>'; 
	$html = urldecode(file_get_contents($url));
	$html = mb_convert_encoding($html,'utf-8','Big5');
	//get $comicname	
	$MetaInfo = get_meta_tags($url);
	$keywords =  mb_convert_encoding($MetaInfo[keywords],'utf-8','Big5');
	$comicname = array_values(explode(',',$keywords))[0];
	//$result .= $comicname.'<br>';
	include('UpdateScript.php');
	$result .= $comicname.'['.$comicSN.']'.
	' - 已新增 - '.
	updatescript($comicname, $comicSN, $ComicLinkArr,$LastChatperArr).
	'<br>';
	}
}
//------------------------------------------//
//Close Tab
echo $result;
//echo '<SCRIPT>setTimeout("self.close()", 15000 ) // after 5 seconds</SCRIPT>';
exit;
?>