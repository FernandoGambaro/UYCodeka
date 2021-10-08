<?php global $data; ?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo (isset($data['title']) ? $data['title'] : "") ?></title>
	<title>Login</title>
<?php if(isset($data['css'])) : ?>
<?php foreach ($data['css'] as $key => $row) : ?>
		<link rel="stylesheet" type="text/css" href="<?php URI::style($row); ?>">
<?php endforeach ?>
<?php endif ?>
</head>
<body>
	<div class="ui labeled icon inverted blue menu">
		<a class="active item white text">
			<h4 class="ui icon header ">
				AttendTracks
				<div class="ui sub header unsettext">Attendance Tracking</div>
			</h4>
		</a>
		<a class="item" href="?p=form">
			<i class="list layout icon"></i> Member List
		</a>
		<a class="item" href="?p=add">
			<i class="add user icon"></i> Member Form
		</a>
		<div class="right menu">
			<a class="item">
				<i class="user icon"></i>
				<span class="name"><?=$_SESSION['user']['username']; ?></span>
			</a>
			<a class="item" href="?p=logout">
				<i class="power off icon"></i> Logout
			</a>
		</div>
	</div>
	<div class="ui stackable doubling grid">
		<div class="sixteen wide column">
			<div class="ui vertical segment">
				<div class="ui container">
					<h2 class="ui logo blue header">
						<i class="<?=$data['icon'];?> icon"></i>
						<?=$data['title'];?>
					</h2>
				</div>
			</div>