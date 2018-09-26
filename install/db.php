<?php
defined('INST_BASE_DIR') or die();
$params = array(
            'license_key' => '',
            'theme' => 'light',
            'layout' => 'vertical',
            'zip_type' => '2',
            'zip_self'=>'',
            'zip_list'=>'',
            'vendor_name'=>'',
            'vendor_email'=>'',
            'vendor_city'=>'',
            'vendor_state'=>'',
            'vendor_street'=>'',
            'vendor_phone'=>'',
            'vendor_fax'=>'',
            'tax_rate'=>'0',
            'delivery_charge'=>'0',
            'discount' => '0',
            'min_order' => '0',
            'item_sort_field'=>'id',
            'item_sort_order'=>'ASC',
            'cart_position'=>'inside-left',
            'cart_type'=>'1',
            'tabs_effect' => 'fade',
            'cat_pict'=>'1',
            'items_pict'=>'1',
            'cat_sort_order'=>'ASC',
            'cat_sort_field'=>'id',
            'work_time'=>'a:7:{i:0;a:3:{s:6:"active";s:1:"1";s:4:"from";s:1:"9";s:2:"to";s:2:"18";}i:1;a:3:{s:6:"active";s:1:"1";s:4:"from";s:1:"9";s:2:"to";s:2:"18";}i:2;a:3:{s:6:"active";s:1:"1";s:4:"from";s:1:"9";s:2:"to";s:2:"18";}i:3;a:3:{s:6:"active";s:1:"1";s:4:"from";s:1:"9";s:2:"to";s:2:"18";}i:4;a:3:{s:6:"active";s:1:"1";s:4:"from";s:1:"9";s:2:"to";s:2:"18";}i:5;a:3:{s:6:"active";s:1:"1";s:4:"from";s:1:"9";s:2:"to";s:2:"18";}i:6;a:3:{s:6:"active";s:1:"1";s:4:"from";s:1:"9";s:2:"to";s:2:"15";}}',
            'timezone'=>'America/New_York',
            'api_login_id'=>'******',
            'transaction_key'=>'******',
            'enable_payments' => '0',
            'enable_efax' => '0',
            'efax_login_id' => '',
            'efax_username' => '',
            'efax_password' => '',
            'powered_by' => '0',
            'body_texture' => 'bodytexture.jpg',
            'out_color' => '#dedede',
            'out_texture' => '0',
            'inner_texture' => 'inner_texture.jpg',
            'in_color' => '#FFF',
            'in_texture' => '0',
            'in_width'=>'1130',
            'in_position'=>'center',
            'in_shadow'=>'1',
            'logo_position'=>'center',
            'enable_logo'=>'1',
            'logo_image'=>'logo_image.png',
            'admin_login'=>'admin',
            'admin_salt'=>'$1$9cc75fc41e$',
            'admin_hash'=>'$1$9cc75fc4$2hguDBb/hfkts1TNvL1sA0',
            'admin_email'=>'',
            'enable_footer'=>'1',
            'footer_text'=>'<b>WeedHero</b>. All rights reserved ©',
            'powered_by'=>'1',
            'header_text'=>'<meta name="description" content="MenuMaker Online Ordering">',
            'app_name'=>'Menu-Maker Online Ordering',
            'enable_payments_paypal'=>'0',
            'apiSignature'=>'',
            'apiPassword'=>'',
            'apiUsername'=>'',
            'enable_delivery'=>'1',
            'item_mode'=>'0',
            'enable_social'=>'1'
    );
$tables = array(
            'menus' => "CREATE TABLE `" . INST_TABLE_PREFIX. 'menus' . "` (
                        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                        `name` VARCHAR(255) NOT NULL ,
                        `description` TEXT NULL ,
                        `image` VARCHAR(255) NULL ,
                        `time_from` TINYINT NOT NULL ,
                        `time_to` TINYINT NOT NULL ,
                        `sort_order` INT UNSIGNED NOT NULL ,
                        `published` TINYINT UNSIGNED NOT NULL DEFAULT '1',
                        PRIMARY KEY  (`id`) )
                        ENGINE = INNODB
                        CHARACTER SET " . MM_DB_CHARSET . "
                        COLLATE " . MM_DB_COLLATE,
            'categories' => "CREATE TABLE `" . INST_TABLE_PREFIX .'categories' . "` (
                        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                        `name` VARCHAR(255) NOT NULL ,
                        `description` TEXT NULL ,
                        `image` VARCHAR(255) NULL ,
                        `published` TINYINT UNSIGNED NOT NULL DEFAULT '1',
                        PRIMARY KEY  (`id`) )
                        ENGINE = INNODB
                        CHARACTER SET " . MM_DB_CHARSET . "
                        COLLATE " . MM_DB_COLLATE,
            'items' => "CREATE TABLE `" . INST_TABLE_PREFIX .'items' . "` (
                        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                        `name` VARCHAR(255) NOT NULL ,
                        `description` TEXT NULL ,
                        `image` VARCHAR(255) NULL ,
                        `size` VARCHAR(45) NULL ,
                        `price` DECIMAL(10,2) NOT NULL ,
                        `type` SMALLINT UNSIGNED NOT NULL DEFAULT '0' ,
                        `published` TINYINT UNSIGNED NOT NULL DEFAULT '1',
                        PRIMARY KEY  (`id`) )
                        ENGINE = INNODB
                        CHARACTER SET " . MM_DB_CHARSET . "
                        COLLATE " . MM_DB_COLLATE,
            'addresses' => "CREATE TABLE `" . INST_TABLE_PREFIX .'addresses' . "` (
                        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                        `city` VARCHAR(255) NOT NULL ,
                        `state` CHAR(2) NOT NULL ,
                        `address` VARCHAR(255) NOT NULL ,
                        `location` VARCHAR(255) NOT NULL ,
                        `order_id` INT UNSIGNED NULL ,
                        `customer_id` INT UNSIGNED NOT NULL ,
                        PRIMARY KEY  (`id`) )
                        ENGINE = INNODB
                        CHARACTER SET " . MM_DB_CHARSET . "
                        COLLATE " . MM_DB_COLLATE,
            'menu_categories' => "CREATE TABLE `" . INST_TABLE_PREFIX . 'menu_categories' . "` (
                        `menu_id` INT UNSIGNED NOT NULL ,
                        `category_id` INT UNSIGNED NOT NULL ,
                        PRIMARY KEY  (`menu_id`, `category_id`) )
                        ENGINE = INNODB
                        CHARACTER SET " . MM_DB_CHARSET . "
                        COLLATE " . MM_DB_COLLATE,
            'category_items' => "CREATE TABLE `" . INST_TABLE_PREFIX .'category_items' . "` (
                        `category_id` INT UNSIGNED NOT NULL ,
                        `item_id` INT UNSIGNED NOT NULL ,
                        PRIMARY KEY  (`category_id`, `item_id`) )
                        ENGINE = INNODB
                        CHARACTER SET " . MM_DB_CHARSET . "
                        COLLATE " . MM_DB_COLLATE,
            'groups' => "CREATE TABLE `" . INST_TABLE_PREFIX .'groups' . "` (
                        `id` INT UNSIGNED NOT NULL auto_increment,
                        `name` varchar(255) NOT NULL,
                        `type` TINYINT NOT NULL,
                        `item_id` INT UNSIGNED NOT NULL,
                        PRIMARY KEY  (`id`)
                      ) ENGINE = InnoDB
                        CHARACTER SET " . MM_DB_CHARSET . "
                        COLLATE " . MM_DB_COLLATE,
            'attributes' => "CREATE TABLE `" . INST_TABLE_PREFIX .'attributes' . "` (
                        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                        `name` VARCHAR(255) NOT NULL ,
                        `price` DECIMAL(10,2) NOT NULL ,
                        `checked_id` TINYINT UNSIGNED NOT NULL DEFAULT '0',
                        `group_id` INT UNSIGNED NOT NULL ,
                        PRIMARY KEY  (`id`) )
                        ENGINE = INNODB
                        CHARACTER SET " . MM_DB_CHARSET . "
                        COLLATE " . MM_DB_COLLATE,
            'orders' => "CREATE TABLE `" . INST_TABLE_PREFIX .'orders' . "` (
                        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                        `time_ordered` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        `time_delivery` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
                        `payment_type` ENUM('cash','credit_card') NOT NULL ,
                        `payment_status` ENUM('ordered','completed','paused','canceled') NOT NULL DEFAULT 'ordered' ,
                        `payment` TINYINT UNSIGNED NOT NULL DEFAULT '0',
                        `delivery_type` ENUM('Delivery','Pickup') NOT NULL DEFAULT 'Delivery' ,
                        `tip_type` ENUM('percent','amount') NOT NULL DEFAULT 'percent' ,
                        `tip` DECIMAL(10,2) NOT NULL DEFAULT '0',
                        `promo_code` TEXT NOT NULL ,
                        `notes` TEXT NOT NULL ,
                        `params` TEXT NOT NULL ,
                        `total_sum` DECIMAL(10,2) NOT NULL ,
                        `trans_id` VARCHAR(255) NOT NULL ,
                        `address_id` INT UNSIGNED NOT NULL ,
                        `customer_id` INT UNSIGNED NOT NULL ,
                        `location_id` INT UNSIGNED NOT NULL ,
                        PRIMARY KEY  (`id`) )
                        ENGINE = INNODB
                        CHARACTER SET " . MM_DB_CHARSET . "
                        COLLATE " . MM_DB_COLLATE,
            'coupons' =>"CREATE TABLE `" . INST_TABLE_PREFIX .'coupons' . "` (
                          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                          `name` varchar(255) NOT NULL,
                          `code` varchar(255) NOT NULL,
                          `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
                          `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
                          `cfrom` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                          `cto` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                          `active` tinyint(3) unsigned NOT NULL DEFAULT '0',
                          `description` text,
                          `image` text,
                          `minimum_order` decimal(10,2) DEFAULT NULL,
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB 
                        CHARACTER SET " . MM_DB_CHARSET . "
                        COLLATE " . MM_DB_COLLATE,
            'coupon_items' => "CREATE TABLE `" . INST_TABLE_PREFIX .'coupon_items' . "` (
                      `coupon_id` int(10) unsigned NOT NULL,
                      `item_id` int(10) unsigned NOT NULL,
                      PRIMARY KEY (`coupon_id`,`item_id`),
                      KEY `fk_mm_coupon_items_iid_idx` (`item_id`),
                      CONSTRAINT `fk_mm_coupon_items_cid` FOREIGN KEY (`coupon_id`) REFERENCES `mm_coupons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                      CONSTRAINT `fk_mm_coupon_items_iid` FOREIGN KEY (`item_id`) REFERENCES `mm_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                    ",
            'order_items' => "CREATE TABLE `" . INST_TABLE_PREFIX .'order_items' . "` (
                        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                        `order_id` INT UNSIGNED NOT NULL ,
                        `itemid` INT UNSIGNED NOT NULL ,
                        `item_name` VARCHAR(255) NOT NULL ,
                        `item_price` DECIMAL(10,2) NOT NULL ,
                        `instructions` TEXT NOT NULL ,
                        `attribs` LONGTEXT NOT NULL ,
                        PRIMARY KEY  (`id`) )
                        ENGINE = INNODB
                        CHARACTER SET " . MM_DB_CHARSET . "
                        COLLATE " . MM_DB_COLLATE,
            'locations' => "CREATE TABLE `" . INST_TABLE_PREFIX .'locations' . "` (
                        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                        `name` VARCHAR(255) NOT NULL ,
                        `timezone` VARCHAR(255) NOT NULL ,
                        `address` VARCHAR(255) NOT NULL ,
                        `phone` VARCHAR(50) NOT NULL ,
                        `fax` VARCHAR(50) NOT NULL ,
                        `l_email` VARCHAR(255) NOT NULL ,
                        `gmap` TEXT NOT NULL ,
                        `l_calendar` TEXT NOT NULL ,
                        `zip_list` TEXT NULL ,
                        PRIMARY KEY  (`id`) )
                        ENGINE = INNODB
                        CHARACTER SET " . MM_DB_CHARSET . "
                        COLLATE " . MM_DB_COLLATE,
            'location_menus' => "CREATE TABLE `" . INST_TABLE_PREFIX .'location_menus' . "` (
                        `location_id` INT UNSIGNED NOT NULL ,
                        `menu_id` INT UNSIGNED NOT NULL ,
                        PRIMARY KEY  (`location_id`, `menu_id`) )
                        ENGINE = INNODB
                        CHARACTER SET " . MM_DB_CHARSET . "
                        COLLATE " . MM_DB_COLLATE,
            'settings' => "CREATE TABLE `" . INST_TABLE_PREFIX .'settings' . "` (
                        `name` VARCHAR(255) NOT NULL ,
                        `value` TEXT NOT NULL ,
                        PRIMARY KEY  (`name`) )
                        ENGINE = INNODB
                        CHARACTER SET " . MM_DB_CHARSET . "
                        COLLATE " . MM_DB_COLLATE,
            'customers' => "CREATE TABLE `" . INST_TABLE_PREFIX .'customers' . "` (
                        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                        `c_mail` VARCHAR(255) NOT NULL ,
                        `name` VARCHAR(255) NOT NULL ,
                        `surname` VARCHAR(255) NOT NULL ,
                        `password` VARCHAR(255) NOT NULL ,
                        `salt` VARCHAR(255) NOT NULL ,
                        `phone` VARCHAR(255) NOT NULL ,
                        PRIMARY KEY  (`id`) )
                        ENGINE = INNODB
                        CHARACTER SET " . MM_DB_CHARSET . "
                        COLLATE " . MM_DB_COLLATE,
        );
function ftp_dir_loop($ftp_stream ,$path) {
    $ftpContents = ftp_nlist($ftp_stream, $path);
    foreach ( $ftpContents as $file ) {
        if (strpos($file, '.') === false) {
            ftp_dir_loop($ftp_stream, $path. DIRECTORY_SEPARATOR .$file);
        }
        if ($file == BASE_FOLDER) {
            ftp_chdir($ftp_stream, $path. DIRECTORY_SEPARATOR .$file);
            if(!is_writable(BASE_DIR)){
                ftp_chmod($ftp_stream, 0777, $path. DIRECTORY_SEPARATOR .$file);
            }
            chmod_R_FTP($path. DIRECTORY_SEPARATOR .$file. DIRECTORY_SEPARATOR .'assets', 0777, 0777, $ftp_stream);
            chmod_R_FTP($path. DIRECTORY_SEPARATOR .$file. DIRECTORY_SEPARATOR .'protected' . DIRECTORY_SEPARATOR . 'runtime', 0777, 0777, $ftp_stream);
            chmod_R_FTP($path. DIRECTORY_SEPARATOR .$file. DIRECTORY_SEPARATOR .'images', 0777, 0777, $ftp_stream);
            chmod_R_FTP($path. DIRECTORY_SEPARATOR .$file. DIRECTORY_SEPARATOR .'themes', 0777, 0777, $ftp_stream);
            return $path. DIRECTORY_SEPARATOR .$file;
        }
    }
}
function chmod_R($path, $filemode, $dirmode) {
    if (is_dir($path) ) {
        if (!@chmod($path, $dirmode)) {
            $dirmode_str=decoct($dirmode);
            return;
        }
        $dh = opendir($path);
        while (($file = readdir($dh)) !== false) {
            if($file != '.' && $file != '..') {  // skip self and parent pointing directories
                $fullpath = $path.'/'.$file;
                chmod_R($fullpath, $filemode,$dirmode);
            }
        }
        closedir($dh);
    } else {
        if (is_link($path)) {
            return;
        }
        if (!@chmod($path, $filemode)) {
            $filemode_str=decoct($filemode);
            return;
        }
    }
}
function chmod_R_FTP($path, $filemode, $dirmode, $stream) {
    if (is_dir($path) ) {
        if (!ftp_chmod($stream, $dirmode, $path)) {
            $dirmode_str=decoct($dirmode);
            echo "Failed applying filemode '$dirmode_str' on directory '$path'\n";
            echo "  `-> the directory '$path' will be skipped from recursive chmod\n";
            return;
        }
        $dh = opendir($path);
        while (($file = readdir($dh)) !== false) {
            if($file != '.' && $file != '..') {  // skip self and parent pointing directories
                $fullpath = $path.'/'.$file;
                chmod_R_FTP($fullpath, $filemode,$dirmode, $stream);
            }
        }
        closedir($dh);
    } else {
        if (is_link($path)) {
            return;
        }
        if (!ftp_chmod($stream, $filemode, $path)) {
            $filemode_str=decoct($filemode);
            echo "Failed applying filemode '$filemode_str' on file '$path'\n";
            return;
        }
    }
}
?>