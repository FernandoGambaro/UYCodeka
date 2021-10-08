<?php global $data ?>
		<script type="text/javascript">var base_url = "<?php URI::BASE_URL(); ?>";</script>
<?php if(isset($data['js'])) : ?>
<?php foreach ($data['js'] as $key => $row) : ?>
		<script type="text/javascript" src="<?php URI::script($row) ?>"></script>
<?php endforeach ?>
<?php endif ?>
<?php if(isset($data['list'])) : ?><script type="text/javascript"><?=$data['list'];?></script><?php endif ?>
</body>
</html>