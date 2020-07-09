<?
if(array_values(explode("/",$_SERVER['REQUEST_URI']))[1] != "API"){
	$homepage = 'http://'.$_SERVER['HTTP_HOST']."/".array_values(explode("/",$_SERVER['REQUEST_URI']))[1];
}
else{
	$homepage = 'http://'.$_SERVER['HTTP_HOST'];
}
//---------------------------//
if(isset($_POST['Backup'])){
//---------------------------//
	if(!isset($_POST['user'])){echo '!!! No Backupname !!!';}
	else if(!isset($_POST['hiddenItem'])){echo '!!! No Progress Data !!!';}
//---------------------------//
	else if(isset($_POST['user']) && isset($_POST['hiddenItem'])){
		$user = $_POST['user'];
		$progrss = $_POST['hiddenItem'];
		// $user." : ".$progrss;
		$percomic = explode(',',$progrss);
		$Backup = array();
		foreach ($percomic as $comic){
				$comicname = array_values(explode(':',$comic))[0];
				$lastseen = array_values(explode(':',$comic))[1];
				$Backup[$comicname] = $lastseen;
			}
		//
		echo 'Backup reading progress to '.$user.'.json<br>';
		//print_r($Backup);
		file_put_contents('../config/ProgressBackup/'.$user.'.json',json_encode($Backup));
		echo "<SCRIPT>window.localStorage.setItem('動漫J神-BackupName', '".$user."');</SCRIPT>";
		echo '<meta http-equiv="refresh" content="1;url='.$homepage.'" />';
		}
}
//---------------------------//
else if(isset($_POST['NewBackup'])){
//---------------------------//
	if(!isset($_POST['Newuser'])){echo '!!! No Backupname !!!';}
	else if(!isset($_POST['NewhiddenItem'])){echo '!!! No Progress Data !!!';}
//---------------------------//
	else if(isset($_POST['Newuser']) && isset($_POST['NewhiddenItem'])){
		$user = $_POST['Newuser'];
		$progrss = $_POST['NewhiddenItem'];
		// $user." : ".$progrss;
		$percomic = explode(',',$progrss);
		$Backup = array();
		foreach ($percomic as $comic){
				$comicname = array_values(explode(':',$comic))[0];
				$lastseen = array_values(explode(':',$comic))[1];
				$Backup[$comicname] = $lastseen;
			}
		//
		echo 'Backup reading progress to '.$user.'.json<br>';
		//print_r($Backup);
		file_put_contents('../config/ProgressBackup/'.$user.'.json',json_encode($Backup));
		echo "<SCRIPT>window.localStorage.setItem('動漫J神-BackupName', '".$user."');</SCRIPT>";
		echo '<meta http-equiv="refresh" content="1;url='.$homepage.'" />';
		}
}
//---------------------------//
else if(isset($_POST['Restore'])){
	echo 'Restore reading progress from '.$_POST['Backupfile'].'<br>';
	$RestoreFilePath = '../config/ProgressBackup/'.$_POST['Backupfile'];
	$RestoreProgress = json_decode(file_get_contents($RestoreFilePath), true);
	echo "<script>localStorage.clear();";
	foreach ($RestoreProgress as $comic => $lastseen){
		echo "window.localStorage.setItem('".$comic."', '".$lastseen."');";
	}
	echo "</script>";
//---------------------------//
	//print_r($RestoreProgress);
	echo '<meta http-equiv="refresh" content="1;url='.$homepage.'" />';
}
//---------------------------//
else if(isset($_POST['OtherRestore'])){
	echo 'Restore reading progress from '.$_POST['OtherBackupfile'].'<br>';
	$RestoreFilePath = '../config/ProgressBackup/'.$_POST['OtherBackupfile'];
	$RestoreProgress = json_decode(file_get_contents($RestoreFilePath), true);
	echo "<script>localStorage.clear();";
	foreach ($RestoreProgress as $comic => $lastseen){
		echo "window.localStorage.setItem('".$comic."', '".$lastseen."');";
	}
	echo "</script>";
//---------------------------//
	//print_r($RestoreProgress);
	echo '<meta http-equiv="refresh" content="1;url='.$homepage.'" />';
}
else {echo 'Invalid Input';}
?>