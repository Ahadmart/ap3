<div class="row">
    <div class="small-12 columns">
        <div class="panel" style="height: 500px">
            <h4>Preview</h4>
            <hr />
            <!--<pre>-->
            <?php // print_r($subMenuTreeList); ?>
            <!--</pre>-->
            <?php

            function markUp($rootMenu, $menus)
            {
                $result = NULL;
                foreach ($menus as $menu) {
                    $options = [];
                    if (!empty($menu['items'])) {
                        $options = [
                            'itemOptions' => ['class' => 'has-dropdown'],
                            'submenuOptions' => ['class' => 'dropdown'],
                        ];
                    }
                    $result[$menu['id']] = [
                        'label' => $menu['label'],
                        'url' => Yii::app()->createUrl('menu/ubah', ['id' => $rootMenu->id, 'subId' => $menu['id']]),
                            ] +
                            $options;
                    if (!empty($menu['items'])) {
                        $result[$menu['id']]['items'] = markUp($rootMenu, $menu['items']);
                    }
                }
                return $result;
            }
            ?>
            <nav class="top-bar" data-options="is_hover: true" data-topbar>
                <section class="top-bar-section">
                    <?php
                    $this->widget('zii.widgets.CMenu', [
                        'activateParents' => true,
                        'encodeLabel' => false,
                        'id' => '',
                        'items' => markUp($rootMenu, $subMenuTreeList)
                            ]
                    )
                    ?>
                </section>
            </nav>
        </div>
    </div>
</div>