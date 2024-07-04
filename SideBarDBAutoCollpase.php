<?php 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$language=$_SESSION['lang'];
	function getURLDomain()
	{
		$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://'; // Get the protocol used (http or https)
		if(empty($protocol))
			$protocol="";
		$hostServer=$_SERVER['SERVER_NAME'];
		$port=':'.$_SERVER['SERVER_PORT'] ;
		if(empty($port) || $port=="443")
			$port="";
		$folderName=dirname($_SERVER['PHP_SELF']);
		
		if(empty($folderName))
			$folderName="";
		return $protocol.$hostServer.$port.$folderName;
	}
	$domain=getURLDomain();
	
	include_once('config/conn.php');
	
	$sql="CALL sp_getSidebarMenu(:roleId,:language)";
	$stmt=$dbo->prepare($sql);
	$stmt->bindParam(":roleId",$_SESSION['roleId'],PDO::PARAM_INT);
	$stmt->bindParam(":language",$language,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$resultSideBar = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	 // Get the query string
    /////$queryString = $_SERVER['QUERY_STRING'];
	define("search", '/'.basename($_SERVER['REQUEST_URI']));
	$urlSearch=search;
	$sql="CALL sp_getParentWebPageId(:url)";
	$stmt=$dbo->prepare($sql);
	$stmt->bindParam(":url",$urlSearch,PDO::PARAM_STR);
	if($stmt->execute())
	{
		$resultparentId= $stmt->fetchAll(PDO::FETCH_ASSOC);
		if($resultparentId)
		{
			////$webpageId=$resultparentId[0]['parentWebpageId'];
			$parentWebpageId=$resultparentId[0]['parentWebpageId'];
		}
	}
	else 
	{
		$errorInfo = $stmt->errorInfo();
		exit($json =$errorInfo[2]);
	}
	
	$set_url_array=array();
	array_push($set_url_array,"/Restricted.php");
	
	if($_SESSION['customerId']<0)
	{
?>
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
	<div class="sidebar-header">
	
		<div class="sidebar-logo">
			<a href="#">
				<?php
				if($_SESSION['lang']=='ar')
									echo '<img src="assets-rtl/img/upperLogo.png" class="img-fluid logo" alt="">';
								else if($_SESSION['lang']=='en')
									echo '<img src="assets/img/upperLogo.png" class="img-fluid logo" alt="">';
								
								?>
			</a>
		
		</div>
	</div>
	<br/><br/><br/>
	
	<div class="sidebar-inner slimscroll"  >
		<div id="sidebar-menu" class="sidebar-menu">
			<ul>
			<!-- Settings -->
	<?php 
	
	/*
	$sql = "SELECT w.webpageId,w.webpageDisplayname_en ,w.webpageDisplayname_$language as webpageDisplayname ,w.isParent ,w.isShownOnMenu,w.parentWebpageId,w.url, i.iconHtml
	FROM tbl_webpages w 
	INNER JOIN tbl_role r ON r.roleId=:roleId
	LEFT OUTER JOIN tbl_role_webpages rw ON w.webpageId = rw.webpageId AND rw.roleId =r.roleId AND rw.isActive=1
	LEFT JOIN tbl_icons i ON i.iconId=w.icon
	WHERE w.isActive =1 AND rw.isActive=1
	ORDER BY w.menuOrderby";
	*/
		$is_ul_start=0;
		$ul_end='</ul>';
		$li_start='<li>';
		$li_end='</li>';
		$ul_start='<li class="submenu">';
		foreach($resultSideBar as $index=> $row)
		{
			if($row['url']!=='')
				array_push($set_url_array,($row['url']));
			if ($row['isShownOnMenu']==1)
			{
				if(empty($row['webpageDisplayname']))
					$row['webpageDisplayname']=$row['webpageDisplayname_en'];
				$webpage_id=$row['webpageId'];
				$ul_start='<ul>';
				$ul_end='</ul>';
				$url=$row['url'];
				$class="";
				$activeMenu="";
				
				if((isset($parentWebpageId) && $parentWebpageId>0) && $parentWebpageId==$row['webpageId'])
				{
					$class='class="subdrop"';
					$ul_start='<ul style="display: block;">';
				}
				if($row['url']=='')
				{
					$url="<a href='#' $class>";
				}
				else
				{
					if($urlSearch==$row['url'])
					{
						$activeMenu="style='color: #fff;'";
						$url='<a href="'.$domain.$row['url'].'" '.$activeMenu.'>';
					}
					else 
						$url='<a href="'.$domain.$row['url'].'">';
				}
				
				$li_end='</li>';
				
				$menu='';	$parent='';		$child='';
				
				if($row['url']=='')
				{
					$arrow='<span class="menu-arrow"></span>';
					$li_start="<li class='submenu'>";
				
				}
				else 
				{
					$arrow='';
					$li_start='<li>';
				
				}
				if($row['parentWebpageId']==0)
				{
					if($is_ul_start==1) $parent.='</ul> </li>';
					$menu.='<i class="'.$row['iconHtml'].'"></i><span '.$activeMenu.'>'.$row['webpageDisplayname'].'</span>
					'.@$arrow.'</a>';
					$parent.=$li_start.$url.$menu;
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
	<?php }
	else 
	{
		foreach($resultSideBar as $row)
		{
			array_push($set_url_array,($row['url']));
		}
		
		echo '<style>.page-wrapper {
			margin-left: 0px;
			margin-right:0px;
		} 
		.header { left: 0px; right: 0px; }
		
		</style>';
	} ?>
<!-- /Sidebar -->

