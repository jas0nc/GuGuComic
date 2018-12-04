<?php
$comicname = '刀劍神域少女們的樂章';
$comicSN = 3694;
	//-----------------------------//
	//Get Comichome page sourcecode
	$url = 'http://www.cartoonmad.com/comic/'.$comicSN.'.html';
	$html = urldecode(file_get_contents($url));
	$html = mb_convert_encoding($html,'utf-8','Big5');
	if (empty($html)){echo 'blank html';exit;}
	//-----------------------------//
	//Download Comic Description
	//$result .=  date("Y-m-d H:i:s"). ' Cover Check: ';
	$Infopath = '/volume1/web/cartoonmad/Comic/'.$comicname.'/'.'Info.txt';
	//if (!file_exists($Infopath)){
		$MetaInfo = get_meta_tags($url);
		$Info =  mb_convert_encoding($MetaInfo[description],'utf-8','Big5');
		print_r($Info); 
		file_put_contents($Infopath, $comicname.' ('.$comicSN.')<br>'.$url);
		//$result .=  'Downloaded Comic Description for '.$comicname.' : '.$Info[description].'<br>';
	//}
	//else {
		//$result .=  'Comic Description for '.$comicname.' already exist<br>';
	//}
//-----------------------------//
//Debug only
echo $result;
exit;