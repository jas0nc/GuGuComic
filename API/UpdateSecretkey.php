<?php
$result = '<h3>The Secretkey has been updated, this result page will be closed in 15 seconds.</h3><br>';
//------------------------------------------//
$jpglinktoday = $_POST['Secretkey'];

//update secretkey to secretkey.txt
file_put_contents(str_replace('/API','',__DIR__).'/API/secretkey.txt',$jpglinktoday,LOCK_EX);

//show result in UI
$result = 'Secretkey => '.file_get_contents(str_replace('/API','',__DIR__).'/API/secretkey.txt');

//save this update to UpdateLog.txt
$oldlog = file_get_contents(str_replace('/API','',__DIR__).'/config/ComicData/UpdateLog.txt');		
$newlog = $result.' ['.date("Y-m-d").']<br>';
file_put_contents(str_replace('/API','',__DIR__).'/config/ComicData/UpdateLog.txt',$newlog.$oldlog,LOCK_EX);

//------------------------------------------//
//Close Tab
echo $result;
echo '<SCRIPT>setTimeout("self.close()", 15000 ) // after 5 seconds</SCRIPT>';
exit;
?>