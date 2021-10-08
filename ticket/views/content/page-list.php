<?php include ('config/config.php'); ?>
<?php include('library/lib_users.php');
$users = new lib_users(); ?>
		<div class="ui vertical segment">
			<div class="ui container">
				<div class="ui sixteen wide column">
					<table class="ui very basic padded selectable celled table">
						<thead>
							<tr>
								<th>Member</th>
								<th>Contact Address</th>
								<th>Present</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php  foreach ($users->showUsers() as $row):?>
							<tr class="positive">
								<td>
									<h4 class="ui header">
										<i class="user icon"></i>
											<div class="content">
											<?=$row['firstName'].' '.$row['middleName'].' '.$row['lastName'];?>
											<div class="sub header">
											<b>
											<?=$row['course'].' - '.$row['year'];?>
											</b>
											</div>
										</div>
									</h4>
								</td>
								<td>
									<h4 class="ui header">
										<i class="browser icon"></i>
											<div class="content">
											<?=$row['phone'];?>
											<div class="sub header">
												<b><?=$row['address'];?></b>
											</div>
										</div>
									</h4>
								</td>
								<td>	
									<center>
										<i class="ui large <?=($row['status']== 'Present')? 'green checkmark':'red remove';?>  icon"></i>
									</center>
								</td>
								<td>	
									<button data-id="<?=$row['userId'];?>" data-type="edit" class="ui large circular facebook icon button">
									<i class="write icon"></i>
									</button>
									<button data-id="<?=$row['userId'];?>" data-type="remove" class="ui large circular youtube icon button">
									<i class="ban icon"></i></button>
								</td>
							</tr>
							<?php endforeach;?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
