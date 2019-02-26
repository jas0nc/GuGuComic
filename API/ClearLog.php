<?
file_put_contents(__DIR__ .'/../config/ComicData/UpdateLog.txt', "");
//rrmdir(__DIR__.'/../temp') ;
header('Location: ' . $_SERVER['HTTP_REFERER']);
function rrmdir($dir) { 
	$objects = scandir($dir); 
	foreach ($objects as $object) { 
    	if ($object != "." && $object != "..") { 
        	if (is_dir($dir."/".$object)){
        		rrmdir($dir."/".$object);}
        	else{
        		unlink($dir."/".$object); }
		} 
	}
}
exit;
?>