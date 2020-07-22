<?php
$result = '<h3>The Secretkey has been updated, this result page will be closed in 15 seconds.</h3><br>';
//------------------------------------------//
$jpglinktoday = $_POST['Secretkey'];
//echo '"'.$jpglinktoday.'"<br>';
file_put_contents(str_replace('/API','',__DIR__).'/API/secretkey.txt',$jpglinktoday,LOCK_EX);
$result = 'Secretkey has been updated to '.file_get_contents(__DIR__.'/secretkey.txt');
'<br>';


//------------------------------------------//
//Close Tab
echo $result;
echo '<SCRIPT>setTimeout("self.close()", 15000 ) // after 5 seconds</SCRIPT>';
exit;
?>