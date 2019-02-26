<?php
$result = '<h3>The comic(s) has been downloaded, this result page will be closed in 15 seconds.</h3><br>';
//------------------------------------------//
//$urls = preg_split("/[\s,]+/", $_POST['URLs']);
//echo $_POST['URLs'];
$urls = explode("\n", $_POST['URLs']);
//print_r($urls);exit;
foreach ($urls as $url){
	$url = str_replace(' ','',$url);
	echo '"'.$url.'"<br>';
	if (empty($url)){continue;}
	else{
	//get $ComicSN
	$comicSN = end(explode('/',$url));
	$comicSN = array_values(explode('.',$comicSN))[0];
	if (empty($comicSN)){continue;}
	//$result .=  $url.'<br>'; 
	//$result .= $comicSN.'<br>'; 
	$html = urldecode(file_get_contents($url));
	$html = mb_convert_encoding($html,'utf-8','Big5');
	if (empty($html)){continue;}
	//get $comicname	
	$MetaInfo = get_meta_tags($url);
	$keywords =  mb_convert_encoding($MetaInfo[keywords],'utf-8','Big5');
	$comicname = array_values(explode(',',$keywords))[0];
	if (empty($comicname)){continue;}
	//$result .= $comicname.'<br>';
	include('UpdateScript.php');
	$result .= $comicname.'['.$comicSN.']'.
	' - 已新增到資料庫 - '.
	updatescript($comicname, $comicSN, $ComicLinkArr,$LastChatperArr).
	'<br>';
	}
}
//------------------------------------------//
//Close Tab
echo $result;
echo '<SCRIPT>setTimeout("self.close()", 15000 ) // after 5 seconds</SCRIPT>';
exit;
?>