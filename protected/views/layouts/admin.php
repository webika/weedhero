<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo MMSettingsForm::getParam('app_name'); ?></title>
        <meta name="description" content="">
        <meta name="robots" content="noindex, nofollow">
        <link rel="shortcut icon" href="<?php echo Yii::app()->getBaseUrl(true) ?>/images/favicon.png" type="image/png">
    </head>

    <body>
        <div id="wpmm">
            <?php if (Yii::app()->user->role === 'admin') : ?>
                <div class="mm-navbar">
                    <div id="mm-logo"></div>
                    <?php
                    $this->widget('bootstrap.widgets.TbNavbar', array(
                        'brand' => false,
                        'fixed' => false,
                        'htmlOptions' => array('style' => 'position:relative;'),
                        'items' => array(
                            array(
                                'class' => 'bootstrap.widgets.TbMenu',
                                'items' => array(
                                    array('label' => 'Dashboard', 'url' => Yii::app()->getBaseUrl(true) . '/admin/dashboard', 'active' => $this->action->id == 'dashboard'),
                                    array('label' => 'Orders', 'url' => Yii::app()->getBaseUrl(true) . '/admin/orders', 'active' => $this->action->id == 'orders'),
                                    array('label' => 'Menus', 'url' => Yii::app()->getBaseUrl(true) . '/admin/menus', 'active' => $this->action->id == 'menus'),
                                    array('label' => 'Categories', 'url' => Yii::app()->getBaseUrl(true) . '/admin/categories', 'active' => $this->action->id == 'categories'),
                                    array('label' => 'Items', 'url' => Yii::app()->getBaseUrl(true) . '/admin/items', 'active' => $this->action->id == 'items'),
                                    array('label' => 'Customers', 'url' => Yii::app()->getBaseUrl(true) . '/admin/customers', 'active' => $this->action->id == 'customers'),
                                    array('label' => 'Locations', 'url' => Yii::app()->getBaseUrl(true) . '/admin/locations', 'active' => $this->action->id == 'locations'),
                                    array('label' => 'Settings', 'url' => Yii::app()->getBaseUrl(true) . '/admin/settings', 'active' => $this->action->id == 'settings'),
                                    array('label' => 'Access', 'url' => Yii::app()->getBaseUrl(true) . '/admin/access', 'active' => $this->action->id == 'access'),
                                    array('label' => 'Logout', 'url' => Yii::app()->getBaseUrl(true) . '/admin/logout', 'linkOptions' => array('class' => 'btn', 'style' => 'height: 8px; margin: 5px 0 3px 5px; line-height: 9px;')),
                                )
                            )
                        )
                    ))
                    ?>
                </div>
            <?php endif ?>
            <?php echo $content ?>
        </div>
    </body>
</html>
