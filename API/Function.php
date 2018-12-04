<?php
//Function to scan folders
function get_dirs($path = '.') {
	$dirs = array();
	foreach (new DirectoryIterator($path) as $file) {
		if ($file->isDir() && !$file->isDot()) {
			$dirs[] = $file->getFilename();
		}
	}
	if(($key = array_search("@eaDir", $dirs)) !== false) {
    unset($dirs[$key]);
    }
    asort($dirs);
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