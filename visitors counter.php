<html>
<head>
<title>Visitor Counter</title>
<style>
*{margin:0px;padding:0px}
body{text-align:center;width:66%;margin:auto;background:url(../background2.png); font-family:Helvetica, Arial, sans-serif}
h1 { font-family: Helvetica, Arial, sans-serif; text-align: center; font-size:60px; margin-top:80px; color:#fff;
     text-shadow: 2px 2px 0px rgba(255,255,255,.7),5px 7px opx rgba(0, 0, 0, 0.1); 
}
.container{height:250px; width:31%; float:left; margin-top:40px}
.round{width:160px; height:160px; border-radius:50%; background:#FFF; margin:20px auto}
.values{color:#888; line-height:3.8em; font-size:40px}
	p{font-size:32px; color:#FFF; font-weight:bold}
	</style>
	<script type="text/javascript" src="../jquery.min.js"</script>
	<script type="text/javascript">
	$(document).ready(function(){
		setInterval(function()
		{
			$.ajax
			({
				type:'post',
				url:'',
				data:{
					get_online_visitor:"online_visitor",
				},
				success:function(response){
					if(response!="")
					{
						$("#online_visitor_val").html(response);
					}
				}
			});
		},10000)
	});
	</script>
	</head>
	<body>
	
	<h1>Visitor Counter</h1>
	<?php
	session_start();
	$_SESSION['session']=session_id();
	
	$host="localhost";
	$username="root";
	$password="";
	$databasename="demo";
	$connection=mysqli_connect($host,$username,$password,$databasename);
	$db=mysqli_select_db($databasename);
	
	
	function total_online()
	{
		$current_time=time();
		$timeout=$current_time - (60);
		
		$session_exist = mysqli_query("SELECT session FROM total_visitors WHERE session='".$_SESSION['session']."'");
		$session_check = mysqli_num_rows($session_exist);
		
		if($session_check==0 && $_SESSION['session']!="")
		{
			mysqli_query("INSERT INTO total_visitors values('','".$_SESSION['session']."','".$current_time."')");
		}
		else
		{
			$sql = mysqli_query("UPDATE total_visitors SET time='".time()."' WHERE session='".$_SESSION['session']."'");
		}
		
		$select_total = mysqli_query("SELECT * FROM total_visitors WHERE time>='$timeout'");
		$total_online_visitors = mysqli_num_rows($select_total);
		return $total_online_visitors;
	}
	
	if(isset($_POST['get_online_visitors']))
	{
		$total_online=total_online();
		echo $total_online;
		exit();
	}
	?>
	
	<?php
	// TO Get Total online Visitors
	$total_online_visitors=total_online();
	
	// To Get Total visitors
	$total_visitors = mysqli_query("SELECT * FROM total_visitors");
	$total_visitors = mysqli_num_rows($total_visitors);
	
	//To insert page View and Select Total pageview
	$user_ip=$_SERVER['REMOTE_ADDR'];
	$page=$_SERVER['PHP_SELF'];
	mysqli_query("insert into pageviews values('','$page','$user_ip')");
	$pageviews = mysqli_query("SELECT * FROM pageviews");
	$total_pageviews = mysqli_num_rows($pageviews);
	?>
	
	<div class="container">
	<div class="round"><p class="values"><?php echo $total_visitors;?></p></div>
	<p>Total Visitors</p>
	</div>
	<div class="container">
	<div class="round"><p class="values" id="online_visitor_val"><?php echo $total_online_visitors;?></p></div>
	<p>Visitors online</p>
	</div>
	<div class="container">
	<div class="round"><p class="values"><?php echo $total_pageviews;?></p></div>
	<p>Total pageviews</p>
	</div>
	
	</body>
	</html>
	
	