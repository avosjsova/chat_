<?php   /* Performing SQL query */
	include 'madrid.php';
	$connection=mysql_connect("192.168.2.226", "student", "stu") or die("Cant connect to database server");
	mysql_select_db("test") or die("Could not select database");
	function generateCode($length=6) 
	{ 
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789"; 
		$code = ""; 
		$clen = strlen($chars) - 1;   
		while (strlen($code) < $length) { 
				$code .= $chars[mt_rand(0,$clen)];   
		} 
		return $code; 
	}

	$flag = false;
	$LG = $_POST["log_in"];

	if($LG)
	{	
			$PSS = $_POST["pass"];
			$list_q = "SELECT * FROM staff WHERE login ='".$LG."' LIMIT 1";
			$list_r = mysql_query($list_q) or die("Query failed");
			$res = mysql_fetch_assoc($list_r);
			$s = $res['login'];
			if($res['pass'] ==  $PSS)
			{
				# Генерируем случайное число и шифруем его 
				$hash = md5(generateCode(10)); 

				# Записываем в БД новый хеш авторизации и IP 
				mysql_query("UPDATE staff SET hash='".$hash."' WHERE login ='".$LG."'"); 
				 
				# Ставим куки 
				setcookie('is_logged', 'TRUE');
				setcookie('id_users', $res["login"]);
				setcookie("hash", $hash, time()+60*60*24*30);         
				# Переадресовываем браузер на страницу проверки нашего скрипта 
				
				header('Location: profile.php');
				exit;
			}
	}
	if (isset($_COOKIE['id_users']) and isset($_COOKIE['hash'])) 
	{    
		$query = mysql_query("SELECT * FROM staff WHERE login = '".$_COOKIE['id_users']."' LIMIT 1"); 
		$userdata = mysql_fetch_assoc($query); 
		if(($userdata['hash'] != $_COOKIE['hash']) or ($userdata['login'] != $_COOKIE['id_users']))
		{ 
			setcookie('is_logged', 'FALSE',time()-60*60*24*30);
			setcookie('id_users', "",time()-60*60*24*30);
			setcookie('hash', "",time()-60*60*24*30);
			header('Location: index.php');
		} 
		else 
		{ 
			header('Location: profile.php');
			exit;
	   } 
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>

	<head>
			<title> <? echo UTF('ГосНИИПП');?> </title>
			<!--<link rel="stylesheet" type="text/css" href="css/index.css" />-->
	</head>

	<body style="
					font-family: calibri;
					background: #EEEEEE;
					color: #741147;" 
		  onload="document.authrization.login.focus();"> 
		<!-- Присваиваю фокус в поле ввода имени пользователя -->
		<form  name="authrization" action="index.php" method="post">

			<table id="a" align = "center" cellspacing="200">

				<tbody>
					<tr>
						<td>
						  <table id="b" cellpadding="0" cellspacing="20" style="background:#BFBFBF;color:#741147;" width="300">
							  <tbody>
							  <tr style = "color:#147741">
									<td colspan=2 align="center" class="auth_title"><? echo UTF("Авторизация");?></td>
							   </tr>
							  <tr>
								<td align="right"><? echo UTF("Логин");?></td>
								<td>
								  <input name="log_in" id="lg" style="width:96%" class="auth" type="text" />
								</td>
							  </tr>
							  <tr>
								<td align="right"><? echo UTF("Пароль");?></td>
								<td>
								  <input name="pass" id="ps" style="width:96%" class="auth" type="password" />
								</td>
							  </tr>
							  <tr>
								<td colspan=2 align="right">
								  <input value="<? echo UTF("Войти");?>" style="width:40%;" name="submit" id="auth_submit_button" type="submit" onClick="authrization.submit()"/>
								</td>
							  </tr>
							  </tbody>
						  </table>
						</td>
					</tr>
				</tbody>

			</table>

		</form>
		<script>
	</script>
	</body>
</html>