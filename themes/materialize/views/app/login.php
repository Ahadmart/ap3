<?php
/* @var $this AppController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name.' - Login';
?>
<div class="row" style="margin-top: 45px">
	<div class="col s12 m6 offset-m3 l4 offset-l4">
		<div class="card">
			<div class="card-content blue-text">
				<span class="card-title blue-text">AhadPOS 3</span>
				<?php
				$form = $this->beginWidget('CActiveForm', array(
					 'id' => 'login-form',
					 'enableClientValidation' => true,
//					 'clientOptions' => array(
//						  'validateOnSubmit' => true,
//						  'inputContainer' => '.input'
//					 ),
				));
				?>
				<div class="row">
					<div class="input-field col s12">
						<?php echo $form->labelEx($model, 'username', array('class' => 'active')); ?>
						<?php echo $form->textField($model, 'username', array('accesskey' => 'n', 'autofocus' => 'autofocus', 'autocomplete' => 'off', 'class' => 'validate')); ?>
						<?php echo $form->error($model, 'username', array('class' => 'error')); ?>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<?php echo $form->labelEx($model, 'password'); ?>
						<?php echo $form->passwordField($model, 'password', array('accesskey' => 'p')); ?>
						<?php echo $form->error($model, 'password', array('class' => 'error')); ?>
					</div>
				</div>
			</div>
			<div class="card-action">
				<button class="btn waves-effect waves-light" type="submit" name="action">Login
					<i class="mdi-action-lock-open right"></i>
				</button>
			</div>
			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>