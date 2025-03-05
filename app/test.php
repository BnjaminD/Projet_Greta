
<?php
$dir = "images/profile/";
echo "Permissions du dossier: " . substr(sprintf('%o', fileperms($dir)), -4);
echo "<br>Propriétaire: " . posix_getpwuid(fileowner($dir))['name'];
echo "<br>Groupe: " . posix_getgrgid(filegroup($dir))['name'];
?>