<?php

$this->widget('BGridView', array(
    'id' => 'user-grid',
    'dataProvider' => $user->search(),
    'filter' => $user,
    'columns' => array(
        array(
            'name' => 'nama_lengkap',
            'filter' => '<input name="User[nama_lengkap]" maxlength="100" type="text" autocomplete="off" />',
            'value' => array($this, 'renderLinkPilihUser'),
            'type' => 'raw',
        ),
        array(
            'name' => 'nama',
            'filter' => false
        )
    ),
));
