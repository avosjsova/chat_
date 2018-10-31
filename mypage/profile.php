<html style="font-family:calibri;background:#EEEEEE;color:#741147;">

<?php
	include 'madrid.php';
	$connection=mysql_connect("192.168.2.226", "student", "stu") or die("Cant connect to database server");
	mysql_select_db("test") or die("Could not select database");
	$LG = $_COOKIE['id_users'];
	if (!$LG)
	{
		header('Location: index.php');
		exit();
	}
	$lol = UTF('Проект-1');
	//mysql_query("UPDATE project SET name = '".$lol."' WHERE id_project = 1");
	$staff_q = "SELECT * FROM staff WHERE login ='".$LG."' LIMIT 1";
	$staff_r = mysql_query($staff_q) or die("Query failed");
	$res = mysql_fetch_assoc($staff_r);
	
	$spec_q = "SELECT * FROM spec WHERE id_spec='".$res['id_spec']."'LIMIT 1";
	$spec_r = mysql_query($spec_q);
	$spec = mysql_fetch_assoc($spec_r);
	
	$proj_q = "SELECT * FROM projects WHERE id_staff='".$res['id_staff']."'";
	$proj_r = mysql_query($proj_q);

	$proj_s = "";
	$k_proj = 0;
	
	$stf = htmlspecialchars($res['name']);
	while($proj = mysql_fetch_array($proj_r))
	{
		$pr_q = "SELECT * FROM project WHERE id_project='".$proj['id_project']."'";
		$pr_r = mysql_query($pr_q);
		$pr = mysql_fetch_assoc($pr_r);
		$proj_s .= $pr['name'].'; ';
		$k_proj++;
	}
	
	$stf = mb_convert_encoding($stf,'utf-8','cp1251');
	$content = "<head>
	<title> ".UTF('Пользователь ').$res['name']."</title>
	</head>";
	$content .='<body>
	<form name="msgs" method="post" action="profile.php">
		<table width="741" align="center">
			<tbody>
				<tr>
					<td align="right">
					<table cellpadding="10" border="0" align="center">
						<tbody>
							<!-- кнопочки слева-->
							<tr>
								<td align="right">
									<div style="color:#147741" onMouseOver="MOver(this)" onMouseOut="MOut(this)" onclick=go("profile.php",msgs)>'.UTF("Главная страница").'</div>
								</td>
								<td align="right">
									<div style="color:#147741" onMouseOver="MOver(this)" onMouseOut="MOut(this)" onclick=go("my_msgs.php?act=inbox",msgs)>'.UTF("Мои сообщения").'</div>
								</td>
								<td align="right">
									<div style="color:#147741" onMouseOver="MOver(this)" onMouseOut="MOut(this)" onclick=go("send_msg.php",msgs)  >'.UTF("Отправить сообщение").'</div>
								</td>';
	if($res['type'])
		$content .= '<td align="right">
					<div style="color:#147741" onMouseOver="MOver(this)" onMouseOut="MOut(this)" onclick=go("search.php",msgs)  >'.UTF("Поиск сообщений").'</div>
				</td>';
	$content .= '
									<td align="right">
										<input  style="color:#147741" onMouseOver="MOver(this)" onMouseOut="MOut(this)" name="lg" align="right" type="submit" value="'.UTF("Выйти").'" onClick=go("logout.php",msgs)>
									</td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
					<tr>
						<td  align="center" colspan="3" align="center">
							<h1>'.UTF("Привет, ").$res['name'].UTF("!").'</h1>
						</td>
					</tr>
					<tr>
					<td align="center">
						<h2 style="color:#147741;backgorund:#00FF00;">'.UTF("Профиль пользователя").'</h2>
					</td>
					</tr>
					<tr>
						<td align="center">
							<table style="background:#BBEEBB" width="350" border = "1" cellpadding="10">
							<tbody>
								<tr name="nick" onMouseOver="X(this,\'#AAEEAA\')" onMouseOut="X(this,\'#BBEEBB\')">
									<td align="right">
										'.UTF("Логин").':
									</td>
									<td>
										'.$res['login'].'
									</td>
								</tr>
								
								<tr name="name" onMouseOver="X(this,\'#AAEEAA\')" onMouseOut="X(this,\'#BBEEBB\')">
									<td align="right">
										'.UTF("Имя Фамилия").':
									</td>
									<td>
										'.$res['name'].'
									</td>
								</tr>
								<tr name="prof" onMouseOver="X(this,\'#AAEEAA\')" onMouseOut="X(this,\'#BBEEBB\')">
									<td align="right">
										'.UTF("Профессия").':
									</td>
									<td class="proj" >
										'.$spec['name'].'
									</td>
								</tr>';
	if($k_proj)							
		$content .= '<tr name="proj" onMouseOver="X(this,\'#AAEEAA\')" onMouseOut="X(this,\'#BBEEBB\')">
			<td align="right" width="50">
				'.UTF("Текущие проекты").':
			</td>
			<td class="proj" >
				'.$proj_s.'
			</td>
		</tr>';
	$content .= '</tbody>
					</table>
					</td>
				</tr>
		</tbody>
		</table>
	</form>
	<script language="javascript" src="madrid.js">
	</script>
	<script>
	function X(that, bclr)
	{
		that.style.background = bclr;
	}
	</script>
	</body>';
	echo $content;
?>
</html>