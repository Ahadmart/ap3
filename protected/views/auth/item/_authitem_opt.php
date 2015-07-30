<option>Pilih satu..</option>
<?php
if ($authItem['role']):
	?>	<optgroup label="Roles">
		<?php
		foreach ($authItem['role'] as $item) :
			?>
			<option value="<?php echo $item['name']; ?>"><?php echo $item['name']; ?></option>
			<?php
		endforeach;
		?>
	</optgroup>
	<?php
endif;
if ($authItem['task']):
	?>
	<optgroup label="Tasks">
		<?php
		foreach ($authItem['task'] as $item) :
			?>
			<option value="<?php echo $item['name']; ?>"><?php echo $item['name']; ?></option>
			<?php
		endforeach;
		?>
	</optgroup>
	<?php
endif;
if ($authItem['operation']):
	?>
	<optgroup label="Operations">
		<?php
		foreach ($authItem['operation'] as $item) :
			?>
			<option value="<?php echo $item['name']; ?>"><?php echo $item['name']; ?></option>
			<?php
		endforeach;
		?>
	</optgroup>
	<?php

endif;
//eof