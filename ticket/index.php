<?php 
	include('config/view.php');
	include('config/URI.php');
	URI::session();
	if(!isset($_SESSION['user']['isLoggedIn']) && $_SESSION['user']['isLoggedIn'] != true)
	URI::redirect("login.php"); 
	$page = (isset($_REQUEST['p']) && !empty($_REQUEST['p']) ? $_REQUEST['p'] : 'home');
	$scripts	= ["vendor/jquery.min", "vendor/semantic.min", "modules/global"];
	$styles		= ["vendor/semantic.min", "modules/global", "modules/home" ];
	switch ($page) {
		case 'home':
		default:
			$data = 	[
						'css'  =>$styles, 'js' =>$scripts,
						'title' => "Member List",  "active" => "home", "icon"=>"list layout" 
					];
			$content = "content/page-list";
		break;
		case 'add':
			$data = 	[
						'css'  =>$styles, 'js' =>$scripts,
						'title' => "Member Form",  "active" => "members", "icon"=>"add user"
					];
			$content = "content/page-form";
		break;
		case 'logout':
			URI::destroy();
			URI:: redirect('login.php');
		break;
			}
	view::make("layouts/content");
 ?>