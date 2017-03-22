<?php
	getHeader();
?>

<div class="row">
	<div class="container">
		<h1>User Info</h1>
	</div>
</div>

<div class="row">
	<div class="container">
		<?php if( !empty( $user ) ) { ?>
			<div class="package-wrapper">
				<div class="package-list-item clearfix">
					<div class="col-dt-2 text-center">
						<?= $user->id ?>
					</div>
					<!-- /.col-dt-2 -->
					<div class="col-dt-2 text-center">
						<?= $user->firstName ?>
					</div>
					<!-- /.col-dt-2 -->
					<div class="col-dt-2 text-center">
						<?= $user->lastName ?>
					</div>
					<!-- /.col-dt-2 -->
					<div class="col-dt-2 text-center">
						<?= $user->email ?>
					</div>
					<!-- /.col-dt-2 -->
					<div class="col-dt-2 text-center">
						<?= $user->addressId ?>
					</div>
					<!-- /.col-dt-2 -->
				</div>
			</div>
		<?php } ?>
	</div>
</div>

<?php
	getFooter();
?>


