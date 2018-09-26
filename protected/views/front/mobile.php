<div data-role="collapsible-set" data-theme="c" data-content-theme="d">
    <?php
        foreach ($menus as $key => $menu) {
            if ($menu->published) {
                $tabs .= '<div data-role="collapsible"><h3>'.$menu->name.'</h3>'
                        .$this->renderPartial('_menu', array('menu' => $menu), true)
                        .'</div>';
            }
        }
        echo $tabs;
    ?>
</div>
<div id="wpmm-form">
    <?php $this->renderPartial('_form') ?>
</div>