<h3>閱讀進度備份:</h3>
<br>
備份:
<form action="API/BackupDBAPI.php" method="post" align="center">
  	New Backup Name: 
	<input type="text" name="Newuser" id="Newuser" placeholder="XXXBackup">
	<input type="hidden" name="NewhiddenItem" id="hiddenItem">
  	<input type="submit" name="NewBackup" value="儲存">     
</form>
<br>

還原:
<form action="API/BackupDBAPI.php" method="post" align="center">
<?
foreach (glob('config/ProgressBackup/*.json') as $filename) {
	$BackedupProgress = json_decode(file_get_contents($filename), true);
	$displayname = end(explode('/',$filename));
	echo 'Saved Backup Name: <a target="Blank" href="API/ViewBackupDB.php?BackupName='.$displayname.'">'.$displayname.'</a> (Last update on: '.date ("Y-m-d H:i:s", filemtime ($filename)).')';
	echo '
		<input type="hidden" name="OtherBackupfile" value="'.$displayname.'">
		<input type="submit" name="OtherRestore" value="下載"><br>';
}
?>
</form><br> 


<script>
var Progress = [];
for(var i=0, len=localStorage.length; i<len; i++) {
	var key = localStorage.key(i);
    var value = localStorage[key];
    Progress.push(key+":"+value);
}
	var elem = document.getElementById("NewhiddenItem"); // Get text field
	elem.value = Progress; // Change field	
	var elem = document.getElementById("Newuser"); // Get text field
	elem.value = window.localStorage.getItem("動漫J神-BackupName"); // Change field
</script>