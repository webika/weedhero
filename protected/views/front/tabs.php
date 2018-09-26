<div id="wpmm-menus" class="<?php echo $this->menus_class ? $this->menus_class : 'form-outside' ?>">
    <?php
    $tabs = '<div id="wpmm-menu-tabs">';
        if (!$this->single_menu) {
            $tabs .= '<ul id="wpmm-menu-list">';
            foreach ($menus as $key => $menu) {
                $tabs .= '<li>
                            <a href="#wpmm-tab-' . ($key+1) . '"' . ($key == 0 ? ' class="active"' : '') . ' data-num="'.$key.'" >'
                                .$menu->name .
                            '</a>
                        </li>';
            }
            $tabs .= '</ul>';
        }
        // Контент вкладок
        if(MMSettingsForm::getParam('tabs_effect') == 'slide'){
            $tabs .= "<div id='wpmm-slide-container'><div id='wpmm-slider'>";
        foreach ($menus as $key => $menu) {
            if ($menu->published) {
                $tabs .= '<div id="wpmm-tab-' . ($key+1) . '" data-num="'.$key.'" class="wpmm-tab-content visible" style="float:left; padding-left:5px; padding-right:5px;">'
                        .$this->renderPartial('_menu', array('menu' => $menu), true)
                        .'</div>';
            }
        }
            $tabs .= "</div></div>";
        } else {
            $tabs .= "<div id='wpmm-fade-container'>";
            foreach ($menus as $key => $menu) {
                if ($menu->published) {
                    $tabs .= '<div id="wpmm-tab-' . ($key+1) . '" data-num="'.($key+1).'" class="wpmm-tab-content' . ($key == 0 ? ' visible"' : '"') . '>'
                            .$this->renderPartial('_menu', array('menu' => $menu), true)
                            .'</div>';
                }
            }
            $tabs .= "</div>";
        }
    $tabs .= '</div>';
    echo $tabs; ?>
</div>
<div id="wpmm-form" class="<?php echo $this->form_class ?>">
    <?php $this->renderPartial('_form') ?>
</div>