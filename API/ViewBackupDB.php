<?php
$BackupName = 'http://'.$_SERVER['HTTP_HOST'].$_GET['BackupName'];
$BackupDBarr = json_decode(file_get_contents($BackupName), true);
//echo $BackupName;
//print_r($BackupDBarr);
echo '<table border="1" width="90%">';
echo '<tr><th>漫畫名稱</th><th>觀看進度(卷/話)</th></tr>';
foreach ($BackupDBarr as $comicname => $lastseen){
	echo '<tr><td>'.$comicname.'</td><td>'.$lastseen.'</td></tr>';
}
echo '</table><br>';?>