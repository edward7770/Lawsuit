<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
?>
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
	<div class="sidebar-header">
		<div class="sidebar-logo">
			<a href="index.html">
				<img src="assets/img/logo.png" class="img-fluid logo" alt="">
			</a>
			<a href="index.html">
				<img src="assets/img/logo-small.png" class="img-fluid logo-small" alt="">
			</a>
		</div>
	</div>
	<div class="sidebar-inner slimscroll"  >
		<div id="sidebar-menu" class="sidebar-menu">
			<ul>
			<!-- Settings -->
			<?php 
				//session_start();
				//$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				function url()
				{
					$server_name = explode("/", $_SERVER['SCRIPT_NAME']);
					return sprintf(
					"%s://%s%s",
					isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
					$_SERVER['HTTP_HOST'],
				'/' .$server_name[1]
				);
	}
	$domain=url();
	///// echo $domain;
	$server_name = explode("/", $_SERVER['SCRIPT_NAME']);
	
	////print_r($server_name);
	//echo $server_name[2];
	$search ='/'.$server_name[2];
	////$search =$domain;
	//////echo $search;
	define("search", $_SERVER['REQUEST_URI']);
	$set_url_array=array();
	array_push($set_url_array,"/restricted.php");
	/////array_push($set_url_array,"/dashboard-home.php");
	include_once('config/conn.php');
	
	$sql = "SELECT w.webpageId,w.webpageDisplayname_en ,w.webpageDisplayname_$language as webpageDisplayname ,w.isParent ,w.isShownOnMenu,w.parentWebpageId,w.url, i.iconHtml
	FROM tbl_webpages w 
	INNER JOIN tbl_role r ON r.roleId=:roleId
	LEFT OUTER JOIN tbl_role_webpages rw ON w.webpageId = rw.webpageId AND rw.roleId =r.roleId AND rw.isActive=1
	LEFT JOIN tbl_icons i ON i.iconId=w.icon
	WHERE w.isActive =1 AND rw.isActive=1
	ORDER BY w.menuOrderby";
	
	$stmt=$dbo->prepare($sql);
	$stmt->bindParam(":roleId",$_SESSION['roleId'],PDO::PARAM_INT);
	if($stmt->execute())
	{
		$resultSideBar = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
		$is_ul_start=0;
		$ul_end='</ul>';
		$li_start='<li>';
		$li_end='</li>';
		$ul_start='<li class="submenu">';
		$parentWebpageId=0;
		foreach($resultSideBar as $index=> $row)
		{
			if($row['url']!=='')
				array_push($set_url_array,$row['url']);
			if ($row['isShownOnMenu']==1)
			{
				if(empty($row['webpageDisplayname']))
					$row['webpageDisplayname']=$row['webpageDisplayname_en'];
				$webpage_id=$row['webpageId'];
				$ul_start='<ul>';
				$ul_end='</ul>';
				$url=$row['url'];
				
				if($row['url']=='') 
				$url="<a href='#'>";
				else 
				$url='<a href="'.$domain.$row['url'].'">';
				
				$li_end='</li>';
				
				$menu='';	$container='';		$parent='';		$child='';
				
				if($row['url']=='')
				{
					$arrow='<span class="menu-arrow"></span>';
					$li_start='<li class="submenu">';
				
				}
				else 
				{
					$arrow='';
					$li_start='<li>';
				
				}
				if($row['parentWebpageId']==0)
				{
					if($is_ul_start==1) $parent.='</ul> </li>';
					$menu.='<i class="'.$row['iconHtml'].'"></i><span>'.$row['webpageDisplayname'].'</span>
					'.@$arrow.'</a>';
					$parent.=$container.$li_start.$url.$menu;
					if($row['url']!='')
						$parent.=$li_end;
					else $child.=$ul_start;
					$is_ul_start=0;
				}
				else 
				{
					$child.=$li_start.$url.'<i class="'.$row['iconHtml'].'"></i>'.$row['webpageDisplayname'].'</a>'.$li_end;
					$is_ul_start=1;
				}
				echo $parent;
				echo $child;
			}
			
		}
?>
</ul>
</div>
</div>
</div>
<!-- /Sidebar -->
			