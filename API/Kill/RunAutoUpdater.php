<div id="result">
</div>

<script>
var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
  if (this.readyState == 4 && this.status == 200) {
    document.getElementById("result").innerHTML = this.responseText;
  }
};
xhttp.open("GET", "API/AutoUpdater.php", true);
xhttp.send();
</script>

<?
//exec('/usr/bin/php /var/services/web/cartoonmad_web/API/AutoUpdater.php >> /var/services/web/cartoonmad_web/config/ComicData/UpdateLog.txt 2>&1');
//header('Location: ' . $_SERVER['HTTP_REFERER']);
//exit;
?>

