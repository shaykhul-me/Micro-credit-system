<?php
	if(isset($_GET['area']) && isset($_GET['action'])){
		$targetpage = $_GET['area'].'/'.$_GET['action'].'.php';
		$menu = $_GET['area'].'/'.'menu.php';
		//$submenu = $_GET['area'].'/'.'submenu.php';	
	}
	elseif(!isset($_GET['area']) && isset($_GET['action'])){
        // $one=explode(".", $_GET['action']);
		$targetpage = $_GET['action'].'.php';
		//$menu ='menu.php';	
		//$submenu = $_GET['action'].'/'.'submenu.php';		
	}
	elseif(isset($_GET['area']) && !isset($_GET['action'])){
		$targetpage = $_GET['area'].'/index.php';
		$menu = $_GET['area'].'/'.'menu.php';
		//$submenu = $_GET['action'].'/'.'submenu.php';			
	}
	else
	{
		$targetpage = 'content.php';
      
  	    // $menu = 'menu.php';
		//$submenu = 'home/submenu.php';
	}
?>
