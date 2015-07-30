<?php
/* @var $this AppController */

$this->pageTitle = 'Login Details';

$this->boxHeader['small'] = $this->pageTitle;
$this->boxHeader['normal'] = $this->pageTitle;
?>
<?php
if (Yii::app()->user->isGuest) :
	?>
	<p>
		Silahkan login untuk mengakses aplikasi
	</p>
	<?php
else :
	?>
	<p>
		<span>Login </span><span class="tebal"><?php echo Yii::app()->user->name; ?></span>
		<br />
		<span>Nama </span><span class="tebal"><?php echo Yii::app()->user->namaLengkap; ?></span>
		<br />
		<?php
		if (isset(Yii::app()->user->lastLogon)):
			?>
			<span>Login terakhir </span><span class="tebal"><?php echo Yii::app()->user->lastLogon.' dari '.Yii::app()->user->lastIpaddress; ?></span>
			<?php
		endif;
		?>
		<br />
		<span>Hak Akses </span><?php
		$first = true;
		foreach ($roles as $role) :
			?><span class="tebal">
				<?php
				if (!$first) {
					echo ', ';
				}
				$first = false;
				echo $role['itemname'];
				?>
			</span><?php
		endforeach;
		?>
	</p>
<?php
endif;
?>