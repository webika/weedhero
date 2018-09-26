<?php
    $cat_pict = MMSettingsForm::getParam('cat_pict');
    $item_pict = MMSettingsForm::getParam('items_pict');
    $enable_social = MMSettingsForm::getParam('enable_social');
    $item_view = MMSettingsForm::getParam('item_mode');
?>
<div class="wpmm-menu-image-wrap">
    <div class="wpmm-menu-time">
        <?php if ($menu->time_from == $menu->time_to)
                echo 'Any time';
            else
                echo strtolower(Helpers::GetHours($menu->time_from)) . ' - ' . strtolower(Helpers::GetHours($menu->time_to)) ?>
    </div>
    <?php if (file_exists(MM_UPLOADS_DIR. DIRECTORY_SEPARATOR .'menu' . $menu->id . '.jpg')) : ?>
        <img class="wpmm-menu-image" src="<?php echo MM_UPLOADS_URL . '/menu' . $menu->id . '.jpg' ?>" alt="<?php echo $menu->name ?>" />
        <div class="wpmm-menu-caption"><?php echo $menu->name ?></div>
    <?php else : ?>
        <img class="wpmm-menu-image" src="<?php echo 'images/defaultmenu.jpg' ?>" />
        <div class="wpmm-menu-caption"><?php echo $menu->name ?></div>
    <?php endif ?>
</div>

<?php if(!empty($menu->description)) : ?>
    <div class="wpmm-menu-description">
        <p><?php echo $menu->description ?></p>
    </div>
<?php endif ?>

<?php foreach ($menu->categories as $category) : ?>


    <div class="wpmm-category">
     <?php if($category->image && $cat_pict){ ?>
        <div class="wpmm-category-wrap">
            <h2 class="wpmm-category-header"><?php echo $category->name ?></h2>
            <a href="<?php echo MM_UPLOADS_URL . '/' . $category->image ?>" alt="<?php echo $category->name ?>" rel="wpmm-lightbox" title="<?php echo $category->name ?>">
                <img class="wpmm-category-image" src="<?php echo MM_UPLOADS_URL . '/thumb_' . $category->image ?>" alt="<?php echo $category->name ?>" />
            </a>
            <?php }  else {?>
            <div class="wpmm-category-wrap wpmm-cat-wrap-image">
                <h2 class="wpmm-category-header wpmm-no-cat-image"><?php echo $category->name ?></h2>
            <?php } ?>
            <?php if(!empty($category->description)) : ?>
                <?php if($category->image && $cat_pict) {?>
                <div class="wpmm-category-description">
                    <p><?php echo $category->description ?></p>
                </div>
                <?php } else { ?>
                    <div class="wpmm-category-description wpmm-no-cat-image">
                        <p><?php echo $category->description ?></p>
                    </div>
                <?php } ?>
            <?php endif ?>
        </div>

        <?php foreach ($category->items as $item) : ?>

            <?php if ($item->published) : ?>

                <?php if($item_view == '1' && !Yii::app()->mobileDetect->isMobile()){ ?>

            <div class="wpmm-item<?php echo ($item->image && $item_pict) ? '' : ' nopic' ?> wpmm-table-item" data-id="<?php echo $item->id ?>" data-new="new">
                    <table border="0" width="100%" data-id="<?php echo $item->id ?>">
                        <tr>
                          <?php if ($item->image && $item_pict) : ?>
                            <td width="50">
                            <a href="<?php echo MM_UPLOADS_URL . '/' . $item->image ?>" alt="<?php echo $item->name ?>" rel="wpmm-lightbox" title="<?php echo $item->name ?>">
                                <img class="wpmm-item-image" src="<?php echo MM_UPLOADS_URL . '/thumb_' . $item->image ?>" alt="<?php echo $item->name ?>" />
                            </a>
                            </td>
                          <?php endif ?>
                            <td style="text-align: left" <?php if(!Yii::app()->mobileDetect->isMobile() && $enable_social){ ?> width="40%" <?php } ?>>
                                <p class="wpmm-item-header"><?php echo $item->name ?></p>
                            </td>
                                <?php if(!Yii::app()->mobileDetect->isMobile() && $enable_social){
                                    $params = parse_url("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                                    $params1=$_GET;
                                    $params1['wpmm-id'] = $item->id;
                                    $paramString = $params['scheme'].'://'.$params['host'];
                                    if(!empty($params['path'])) $paramString.=rtrim($params['path'],'/');
                                    $paramString .= '/?'.http_build_query($params1);
                                    $social_link= $paramString;
                                ?>
                            <td style="text-align: left">
                                <div class="wpmm-social" style="float: right">
                                    <div class="fb-like" data-href="<?php echo $social_link;  ?>" data-send="false" data-layout="button_count" data-width="45" data-show-faces="false"></div>
                                    <a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-url="<?php echo $social_link; ?>" data-count="none" data-text="I like <?php echo $item->name ?>">Tweet</a>
                                </div>
                            </td>
                                <?php } ?>

                            <td width="50">
                                <p class="wpmm-item-price">$<?php echo $item->price ?></p>
                            </td>
                            <td width="50" align="left">
                                <a class="wpmm-item-buy" href="#" data-id="<?php echo $item->id ?>" data-new="new">Add</a>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php } else {
                $params = parse_url("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                $params1=$_GET;
                $params1['wpmm-id'] = $item->id;
                $paramString = $params['scheme'].'://'.$params['host'];
                if(!empty($params['path'])) $paramString.=rtrim($params['path'],'/');
                $paramString .= '/?'.http_build_query($params1);
                $social_link= $paramString;
            ?>
                <div class="wpmm-item<?php echo ($item->image && $item_pict) ? '' : ' nopic' ?>" data-id="<?php echo $item->id ?>" data-new="new">
                    <p class="wpmm-item-header"><?php echo $item->name ?></p>
                    <hr class="wpmm-item-hr">
                    <div class="wpmm-item-left">
                        <?php if ($item->image && $item_pict) : ?>
                        <a href="<?php echo MM_UPLOADS_URL . '/' . $item->image ?>" alt="<?php echo $item->name ?>" rel="wpmm-lightbox" title="<?php echo $item->name ?>">
                            <img class="wpmm-item-image" src="<?php echo MM_UPLOADS_URL . '/thumb_' . $item->image ?>" alt="<?php echo $item->name ?>" />
                        </a>
                        <?php endif ?>
                        <a class="wpmm-item-buy" href="#" data-id="<?php echo $item->id ?>" data-new="new">Add</a>
                        <p class="wpmm-item-price">$<?php echo $item->price ?></p>
                    </div>
                    <div class="wpmm-item-right">
                        <div class="wpmm-item-description">
                            <p><?php echo $item->description ?></p>
                        </div>
                        <?php if(!Yii::app()->mobileDetect->isMobile() && $enable_social){ ?><div class="wpmm-social">
                            <div class="fb-like" data-href="<?php echo $social_link; ?>" data-send="false" data-layout="button_count" data-width="45" data-show-faces="false"></div>
                            <a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-url="<?php echo $social_link; ?>" data-count="none" data-text="I like <?php echo $item->name ?>">Tweet</a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <?php endif  ?>

        <?php endforeach  ?>
    </div>
<?php endforeach  ?>