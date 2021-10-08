<?php include 'config/URI.php'; 
	include 'config/config.php';  
	include 'library/authentication.php'; ?>
<?php URI::session(); $auth = new Authentication();?>
<?php if(!empty($_POST)) $auth->check_auth($_POST); ?>
<?php if(isset($_SESSION['user']['isLoggedIn']) && $_SESSION['user']['isLoggedIn'] == true) URI::redirect("index.php?p=home"); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<?php $styles = [ "vendor/semantic.min", "modules/login", "modules/global" ];  ?>
	<?php foreach ($styles as $key):?>
		<link rel="stylesheet" type="text/css" href="<?php URI::style($key); ?>">
	<?php endforeach;?>
</head>
<body>
	<div class="ui middle aligned center aligned grid">
		<div class="ten wide column">
			<div class="ui tall stacked segment transluscent">
				<div class="white text">
					<h2 class="ui center aligned icon header">
						<i class="lock icon"></i>
						AttendTracks Web Application
					</h2>
					<div class="ui horizontal divider">Login to Enter</div>
				</div>
				<div class="ui vertical segment">
				<form class="ui form" method="post" action="login.php">
					<div class="field">
						<div class="ui left icon input">
							<i class="user icon"></i>
							<input type="text" required="true" name="txt_username" placeholder="Username">
						</div>
					</div>
					<div class="field">
						<div class="ui left icon input">
							<i class="lock icon"></i>
							<input type="password" required="true" name="txt_password" placeholder="Password">
						</div>
					</div>
	        				<button type="submit" class="ui fluid large facebook submit button">LOGIN</button>
				</form>
				</div>
			</div>
		</div>
	</div>
	<?php $js = ["vendor/jquery.min","vendor/semantic.min"]; ?>
	<?php foreach ($js as $key):?> <script src="<?php URI::script($key); ?>"></script>
	<?php endforeach; ?>
	<script type="text/javascript">var base_url = "<?php URI::BASE_URL(); ?>";</script>
</body>
</html>