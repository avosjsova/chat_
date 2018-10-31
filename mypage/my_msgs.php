<?php 
	include 'madrid.php';
	$connection=mysql_connect("192.168.2.226", "student", "stu") or die("Cant connect to database server");
	mysql_select_db("test") or die("Could not select database");
	$LG = $_COOKIE['id_users'];
	if (!$LG)
	{
		//header('Location: index.php');
		exit();
	}
	$staff_q = "SELECT * FROM staff WHERE login ='".$LG."' LIMIT 1";
	$staff_r = mysql_query($staff_q) or die("Query failed");
$res = mysql_fetch_assoc($staff_r);
?>
<html style="font-family:calibri;background:#EEEEEE;color:#741147;">
<head>
	<title>
		<? echo UTF("Сообщения пользователя ").' '.$res['name'];?>
	</title>
</head>

<body>
	<form name="my_msgs" method="post" action="my_msgs.php?act=inbox" align="center">
	<table width="741" align="center">
				<tbody>
				<tr>
					<td align="right">
					<table cellpadding="10" align="center">
						<tbody>
							<!-- кнопочки слева-->
							<tr>
								<td align="right">
									<div onMouseOver="MOver(this)" onMouseOut="MOut(this)" style="color:#147741" onclick=go("profile.php",my_msgs)><? echo UTF("Главная страница");?></div>
								</td>
								<td align="right">
									<div onMouseOver="MOver(this)" onMouseOut="MOut(this)" style="color:#147741" onclick=go("my_msgs.php?act=inbox",my_msgs)><? echo UTF("Мои сообщения");?></div>
								</td>
								<td align="right">
									<div onMouseOver="MOver(this)" onMouseOut="MOut(this)" style="color:#147741" onclick=go("send_msg.php",my_msgs)  ><? echo UTF("Отправить сообщение");?></div>
								</td>
<?php
	$content = '';
	if($res['type'])
		$content .= '<td align="right">
						<div onMouseOver="MOver(this)" onMouseOut="MOut(this)" style="color:#147741" onclick=go("search.php",my_msgs)  >'.UTF("Поиск сообщений").'</div>
					</td>';	
	$content .= '					<td align="right">
										<input  style="color:#147741" onMouseOver="MOver(this)" onMouseOut="MOut(this)"  name="lg" align="right" type="button" value="'.UTF("Выйти").'" onClick=go("logout.php",my_msgs)>
									</td>
								</tr>
								</tbody>
								</table>
								</td>
								</tr>
								<tr>
									<td align = "center">
										<h1 align="center">'.UTF("Сообщения пользователя ").' '.$res['name'].'</h1>
									</td>
								</tr>
								<tr cellspacing=10>
									<td align="center">
										<div ><input onMouseOver="MOver(this)" onMouseOut="MOut(this)" name="mode" id="mode" type="button" value="'.UTF("Просмотр исходящих сообщений").'" onClick="change_flg(this,tbl_msgs)"></div>
									</td>
								</tr>
								</tbody>
								</table>
	';
	$flg = $_GET['act'];
	if(!$flg) $flg = 'inbox';
	$s = ($flg == "outbox")?("id_sender"):("id_reciever");
	$t = ($flg == "outbox")?("id_reciever"):("id_sender");

	$msgs_q = "SELECT * FROM messages WHERE ".$s." = '".$res['id_staff']."'";
	$msgs_r = mysql_query($msgs_q);
	$k_msgs = 0;
	while($msgs_check = mysql_fetch_array($msgs_r))
	{	
		$k_msgs++;
	}
	$content .= '
					<table id="tbl_msgs" align="center" cellpadding="5" style="background:#C2C2C2;color:#147741;width:1000" border="1">
					<tbody>';
	if ($k_msgs)
	{	
		$msgs_q = "SELECT * FROM messages WHERE ".$s." = '".$res['id_staff']."' ORDER BY sdate DESC";
		$msgs_r = mysql_query($msgs_q);
		$content .= '
							<table name="tbl_msgs" align="center" cellpadding="5" style="background:#B1B1B1;color:#147741;width:1000">
							<tbody>
							<tr>
								<td width="50">
									'.UTF("Время").'
								</td>
								<td width="100">
									'.UTF("Сотрудник").'
								</td>
								<td width="100">
									'.UTF("Проект").'
								</td>
								<td width = "250">
									'.UTF("Текст").'
								</td>
							</tr>';
		while($msgs = mysql_fetch_array($msgs_r))
		{
			$dt = date("d.m.Y  H:i:s",strtotime($msgs['sdate']));
			$stf_q = "SELECT * FROM staff WHERE id_staff = '".$msgs[$t]."' LIMIT 1";
			$stf_r = mysql_query($stf_q);
			$stf = mysql_fetch_assoc($stf_r);
			
			$pr_q = "SELECT * FROM project WHERE id_project = '".$msgs['id_project']."' LIMIT 1";
			$pr_r = mysql_query($pr_q);
			$pr = mysql_fetch_assoc($pr_r);
			$arr_txt = str_split($msgs['text']);
			$txt = '';
			for($i=0;$i<strlen($msgs['text']);$i++)
			{
				$txt .= $arr_txt[$i];
				if(ord($arr_txt[$i]) == 10)
					$txt .= '<br/>';
			}
			$bckgrnd = $msgs['read_msg']?'#EAEAEA':'#AEAEAE'; 			
			$content .='<tr onclick="read('.$msgs['id_message'].')" name="'.$msgs['id_message'].'" style="background:'.$bckgrnd.';color:#770077" onMouseOver="X(this,\'#CCCCCC\')" onMouseOut="X(this,\''.$bckgrnd.'\')">
							<td valign="top">
								'.$dt.'
							</td>
							<td valign="top">
								'.$stf['name'].'
							</td>
							<td valign="top">
								'.$pr['name'].'
							</td>
							<td valign="top">
								'.$txt.'
							</td>
						</tr>
			';
		}
	}
	else
		$content .= '
		<tr style="background:#EAEAEA;color:#770077">
		<td colspan=4 valign="center" align="center"><h3 style="color:#147741">'.UTF('У данного пользователя пока нет сообщений').'</h3> </td></tr>';
	$content .= '
		</tbody>
		</table>';
	echo $content;
?>
	</form>
	<script language="javascript" src="madrid.js">
	</script>
	<script>
	<? 
	$a = UTF('Просмотр входящих сообщений');
	$b = UTF('Просмор исходящих сообщений');
	$md = ($flg == 'outbox')?$a:$b; 
	$fll = $k>0;
	echo 'document.my_msgs.mode.value="'.$md.'";';
	if($_GET['act']!='inbox' && $_GET['act']!='outbox' && isset($_GET['act']))
	{
		$read_msg = $_GET['act'];
		$rd = mysql_fetch_assoc(mysql_query("SELECT * FROM messages WHERE id_message='".$read_msg."' LIMIT 1"));
		$flag = !$rd['read_msg'];
		$empt = '';
		$rdt = $rd['read_msg']?$empt:date('Y-m-d H:i:s');
		$q = "UPDATE messages SET rdate='".$rdt."',read_msg = '".$flag."' WHERE id_message='".$read_msg."'";
		mysql_query($q);
		$rt = mysql_fetch_assoc(mysql_query("SELECT * FROM messages WHERE id_message='".$read_msg."' LIMIT 1"));
		//echo 'alert("'.$rt['read_msg'].'");';
		echo 'document.my_msgs.action = "my_msgs.php?act=inbox"; document.my_msgs.submit();';
	}
	?>
		function change_flg(that,other)
		{	
			other.innerHTML = '';
			mode = that.value;
			<?  
				$act = $_GET['act'];
				if(!$act) $act = 'inbox';
			   $str = ($act == 'inbox')?('outbox'):('inbox');
			   echo 'document.my_msgs.action="my_msgs.php?act='.$str.'";document.my_msgs.submit();';
			?>
		}
		function X(that, bclr)
		{
			that.style.background = bclr;
		}
		function read(that)
		{
			<?
				if ($_GET['act']=='inbox')
					echo 'document.my_msgs.action="my_msgs.php?act="+that; document.my_msgs.submit();';
			?>
		}
	</script>
	</body>	
</html>