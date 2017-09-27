<div class="row">
    <div class="small-12 columns">
        <h4>Preview</h4>
        <!--<pre>-->
            <?php // print_r($subMenuTreeList); ?>
        <!--</pre>-->
        <?php

        function markUp($menus)
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
                    'url' => $menu['url'],
                        ] +
                        $options;
                if (!empty($menu['items'])) {
                    $result[$menu['id']]['items'] = markUp($menu['items']);
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
                    'items' => markUp($subMenuTreeList)
                        ]
                )
                ?>
            </section>
        </nav>
    </div>
</div>
<br />