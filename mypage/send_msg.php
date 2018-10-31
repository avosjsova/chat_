<? include 'madrid.php';
?>
<html style="font-family:calibri;background:#EEEEEE;color:#741147">
<head>
	<title> <? echo UTF('Новое сообщение'); ?> </title>
</head>
<body onload="document.send_msg.text_msg.focus();">
	<form name="send_msg" method="post">
		<table width="741" align="center">
		<tbody>
			<tr>
				<td align="right">
					<table cellpadding="10" align="center">
						<tbody>
							<!-- кнопочки слева-->
							<tr>
								<td align="right">
									<div id="profile" onMouseOver="MOver(this)" onMouseOut="MOut(this)" style="color:#147741" onclick=go("profile.php",send_msg)><? echo UTF("Главная страница");?></div>
								</td>
								<td align="right">
									<div style="color:#147741" onMouseOver="MOver(this)" onMouseOut="MOut(this)" onclick=go("my_msgs.php",send_msg)><? echo UTF("Мои сообщения");?></div>
								</td>
								<td align="right">
									<div style="color:#147741" onMouseOver="MOver(this)" onMouseOut="MOut(this)" onclick=go("send_msg.php",send_msg)><? echo UTF("Отправить сообщение");?></div>
								</td>
								<?php
									$connection=mysql_connect("192.168.2.226", "student", "stu") or die("Cant connect to database server");
									mysql_select_db("test") or die("Could not select database");
									$LG = $_COOKIE['id_users'];
									$staff_q = "SELECT * FROM staff WHERE login ='".$LG."' LIMIT 1";
									$staff_r = mysql_query($staff_q) or die("Query failed");
									$res = mysql_fetch_assoc($staff_r);
									$content = '';
									if($res['type'])
										$content .= '<td align="right">
														<div style="color:#147741" onMouseOver="MOver(this)" onMouseOut="MOut(this)" onclick=go("search.php",send_msg)>'.UTF("Поиск сообщений").'</div>
													</td>';	
									echo $content;
								?>
								<td align="right">
									<input name="lg"  style="color:#147741" onMouseOver="MOver(this)" onMouseOut="MOut(this)" align="right" type="submit" value="<? echo UTF("Выйти");?>" onClick=go("logout.php",send_msg)>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		<tr>
			<td align = "center">
				<h1><? echo UTF("Выберите проект и сотрудника");?></h1>
			</td>
		</tr>
		<tr>
		<td>
		<table align="center" cellspacing="15">
				<tbody>
					<tr>
						<td align="right" style="color:#147741">
							<? echo UTF("Проект");?>
						</td>
							<td>
								<select onMouseOver="MOver(this)" onMouseOut="MOut(this)" style="color:#147741;width:500" onchange="proj(this,stf_id)" name="id_project">;
								<?
									$content = '';
									if($res['type'])
									{
										$proj_q = "SELECT * FROM project";
										$proj_r = mysql_query($proj_q);
										// Загрузка списка проектов
										$k = -1;
										while($proj = mysql_fetch_array($proj_r))
										{
											$k++;
											$content .= '<option value="'.$proj["id_project"].'">'.$proj["name"].'</option>';
											if(!$k)
												$SLCTD_PR = $proj['id_project'];
										}
									}
									else
									{									
										$proj_q = "SELECT * FROM projects WHERE id_staff = '".$res['id_staff']."'";
										$proj_r = mysql_query($proj_q);
										// Загрузка списка проектов
										$k = -1;
										while($proj = mysql_fetch_array($proj_r))
										{
											$k++;
											$pr_q = "SELECT * FROM project WHERE id_project = '".$proj['id_project']."' LIMIT 1";
											$pr_r = mysql_query($pr_q);
											$pr = mysql_fetch_assoc($pr_r);
											$content .= '<option value="'.$pr["id_project"].'">'.$pr["name"].'</option>';
											if(!$k)
												$SLCTD_PR = $pr['id_project'];
										}
									}
									echo $content; 
								?>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right" style="color:#147741">
								<? echo UTF("Сотрудник");?>
							</td>
							<td>
							<select onMouseOver="MOver(this)" onMouseOut="MOut(this)" style = "color:#147741;width:500" name="stf_id" onChange = "proj()">
							<?
								$content = '';
								$tmp = $SLCTD_PR;
								$SLCTD_PR = $_POST["id_project"];
								if (!$SLCTD_PR)
									$SLCTD_PR = $tmp;
								if(isset($_GET['act']))
									$SLCTD_PR = $_GET['act'];
								$TXT = htmlspecialchars($_POST['text_msg']);
								//echo $TXT;
								$stf_k = 0;
								if($SLCTD_PR)
								{
									// Выводим список всех сотрудников, кроме авторизованного пользователя
									$list_q = "SELECT * FROM projects WHERE id_project = '".$SLCTD_PR."' AND (id_staff!='".$res['id_staff']."')";
									$list_r = mysql_query($list_q);
									while($list = mysql_fetch_array($list_r))
									{
										$stf_k++;
										$stf_q = "SELECT * FROM staff WHERE id_staff = '".$list['id_staff']."' LIMIT 1";
										$stf_r = mysql_query($stf_q);
										$stf = mysql_fetch_assoc($stf_r);
										$content .= '<option value="'.$stf["id_staff"].'">'.$stf['name'].'</option>';
									}
								}
								echo $content;
							?>
								</select>
							</td>
						</tr>
								<?
									$dt = date('Y-m-d H:i:s');
									if($TXT && $_POST['send']=='Sending...')
									{
										$stf = $_POST['stf_id'];
										$ins_q = "INSERT INTO messages(id_sender,id_reciever,id_project,sdate,text) VALUES ('".$res['id_staff']."','".$stf."','".$SLCTD_PR."','".$dt."','".$TXT."')";
										$ins_r = mysql_query($ins_q);
									}
								?>	
						<tr height="147">
							<td align="right" valign="top" style="color:#147741">
								<? echo UTF("Текст");?>
								<td width="500">
									<textarea name="text_msg" row="10" style="height:147;width:500"></textarea>
								</td>
							</td>
						</tr>
						<tr>
							<td>
								<td align="right">
									<input style="color:#147741" onMouseOver="MOver(this)" onMouseOut="MOut(this)" value="<? echo UTF("Отправить");?>" style="width: 30%;" name="send" id="send_submit_button" type="submit" onclick="sended(this)">
								</td>
							</td>
						</tr>
		</tbody>
		</table>
	</form>
	<script language="javascript" src="madrid.js">
	</script>
	<script>
	<?
		if(isset($_GET['act']))
		{
			echo 'document.send_msg.id_project.value = "'.$_GET['act'].'";';
		}	
	?>
	a = document.send_msg.id_project.value;
	document.send_msg.action = "send_msg.php?act="+a;
	
	function sended(that)
	{
		alert("<?echo UTF("Сообщение успешно отправлено!");?>");
		that.value = "Sending...";
	}
	function proj(that,other)
	{
		document.send_msg.action = "send_msg.php?act="+that.value;
		document.send_msg.submit();
	}
	</script>
	</body>
</html>