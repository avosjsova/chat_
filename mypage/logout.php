<?php

$connection=mysql_connect("192.168.2.226", "student", "stu") or die("Cant connect to database server");
mysql_select_db("test") or die("Could not select database");
$LG = $_COOKIE['id_users'];
mysql_query("UPDATE staff SET hash=' ' WHERE login ='".$LG."'"); 
				 
# Ставим куки 
setcookie('is_logged', 'FALSE',time()-60*60*24*30);
setcookie('id_users', "",time()-60*60*24*30);
setcookie('hash', "", time()-60*60*24*30); 

header('Location: index.php');
?>