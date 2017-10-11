
<div class="row" style="overflow: auto">
    <div class="small-12 columns">
        <div class="panel">
            <h4>Detail</h4>
            <hr />
            <?php
            $this->widget('BGridView', [
                'id' => 'menu-grid',
                'dataProvider' => $model->search(true), // $subMenu = true
                'filter' => $model,
                'columns' => [
                    [
                        'class' => 'BDataColumn',
                        'name' => 'nama',
                        'header' => '<span class="ak">N</span>ama',
                        'accesskey' => 'n',
                        'type' => 'raw',
                        'value' => [$this, 'renderLinkToUbahSub'],
                    ],
                    'keterangan',
                    [
                        'name' => 'parentNama',
                        'value' => '$data->namaParent'
                    ],
                    'link',
                    'icon',
                    [
                        'name' => 'urutan',
                        'htmlOptions' => ['class' => 'rata-kanan']
                    ],
                    [
                        'name' => 'level',
                        'htmlOptions' => ['class' => 'rata-kanan']
                    ],
                    [
                        'name' => 'status',
                        'value' => '$data->namaStatus',
                        'filter' => Menu::listStatus()
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>