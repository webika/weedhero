<?php
//phpinfo();die;
error_reporting(E_ALL & ~E_NOTICE);
define('MM_DB_CHARSET', 'utf8');
define('MM_DB_COLLATE', 'utf8_general_ci');
define('INST_BASE_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('MM_ASSETS', INST_BASE_DIR . 'assets');
define('MM_RUNTIME', INST_BASE_DIR . 'protected' . DIRECTORY_SEPARATOR . 'runtime');
define('MM_UPLOADS', INST_BASE_DIR . 'images');
define('MM_THEMES', INST_BASE_DIR . 'themes');
define('MM_UPLOADS_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'uploads');
define('MM_UPLOADS_URL', 'images/uploads');
define('MM_AJAX_URL', 'ajax');
define('BASE_FOLDER',basename (dirname(__FILE__)));
define('AUTHORIZENET_SANDBOX', false);
$config = INST_BASE_DIR . 'config.php';
if (file_exists($config)) {
    require_once $config;
    if (is_writable(MM_ASSETS) && is_writable(MM_RUNTIME) && is_writable(MM_UPLOADS) && is_writable(MM_THEMES)) {
        defined('YII_DEBUG') or define('YII_DEBUG', true);
        defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
        $yii = dirname(__FILE__) . '/framework/yii.php';
        $config = dirname(__FILE__) . '/protected/config/main.php';
        require_once($yii);
        Yii::$enableIncludePath = false;
        Yii::createWebApplication($config)->run();
    } else {
        require_once INST_BASE_DIR. 'install' . DIRECTORY_SEPARATOR . 'ftp.php';
    }
} else {
    require_once INST_BASE_DIR . 'install' . DIRECTORY_SEPARATOR . 'install.php';
}
