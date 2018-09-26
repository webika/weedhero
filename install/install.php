<?php
defined('INST_BASE_DIR') or die();
define('INST_BASE_URL', "http://" . $_SERVER['HTTP_HOST'] . substr($_SERVER['PHP_SELF'], 0, -9));
require_once INST_BASE_DIR. 'install' . DIRECTORY_SEPARATOR . 'FTP' . DIRECTORY_SEPARATOR .'Socket.php';
// Prossesing data
$data = array();
$data['db_prefix'] = 'mm_';
$data['db_host'] = 'localhost';
$data['ftp_host'] = 'localhost';
$data['ftp_path'] = '/';
$can_db = false;
$can_user = false;
$errors = array();
$db = false;
$ftp_mode = false;
$ftp_stream = false;
$ftp_atall=false;
if (isset($_POST['application_name'])) {
    $data['application_name'] = trim(strip_tags($_POST['application_name']));
    if (empty($data['application_name']))
        $errors['app'][] = 'Application Title is required !';
}
if (isset($_POST['DBForm'])) {
    require_once INST_BASE_DIR . 'install' . DIRECTORY_SEPARATOR . 'mysql.php';
    $data['db_user'] = trim(strip_tags($_POST['DBForm']['db_user']));
    $data['db_pass'] = trim(strip_tags($_POST['DBForm']['db_pass']));
    $data['db_name'] = trim(strip_tags($_POST['DBForm']['db_name']));
    $data['db_prefix'] = trim(strip_tags($_POST['DBForm']['db_prefix']));
    $data['db_host'] = trim(strip_tags($_POST['DBForm']['db_host']));
    if (empty($data['db_host']))
        $data['db_host'] = 'localhost';
        $connection_information = array(
            'host' => $data['db_host'],
            'user' => $data['db_user'],
            'pass' => $data['db_pass'],
            'db' => $data['db_name']
        );
    try {
        $db = new mysql($connection_information);
        if (!$db)
            throw new Exception('Error Connecting');
        $can_db = true;
    } catch (Exception $e) {
        $errors['db'][] = 'Db connection error, please check DB input !';
        $can_db = false;
    }
}
if (isset($_POST['FTPForm'])) {
    $ftp_atall=true;
    if (isset($_POST['FTPForm']['enable_ftp']) && $_POST['FTPForm']['enable_ftp'] == '1') {
        $data['enable_ftp'] = trim(strip_tags($_POST['FTPForm']['enable_ftp']));
        $data['ftp_user'] = trim(strip_tags($_POST['FTPForm']['ftp_user']));
        $data['ftp_pass'] = trim(strip_tags($_POST['FTPForm']['ftp_pass']));
        $data['ftp_host'] = trim(strip_tags($_POST['FTPForm']['ftp_host']));
        $ftp_stream = @ftp_connect($data['ftp_host']);
        if ($ftp_stream) {
            $ftp_mode = true;
            if (!@ftp_login($ftp_stream, $data['ftp_user'], $data['ftp_pass'])) {
                $errors['ftp'][] = 'Wrong login or password provided !';
                $ftp_mode = false;
            }
        }
        else
            $errors['ftp'][] = 'Wrong ftp host provided !';
    }
}
if (isset($_POST['AdminForm'])) {
    $data['admin_user'] = trim(strip_tags($_POST['AdminForm']['admin_user']));
    $data['admin_pass'] = trim(strip_tags($_POST['AdminForm']['admin_pass']));
    $data['admin_pass_repeat'] = trim(strip_tags($_POST['AdminForm']['admin_pass_repeat']));
    $data['admin_email'] = trim(strip_tags($_POST['AdminForm']['admin_email']));
    $can_user = true;
    if ($data['admin_pass'] != $data['admin_pass_repeat']) {
        $errors['admin'][] = 'Passwords do not match !';
        $can_user = false;
    }
}
define('INST_TABLE_PREFIX', $data['db_prefix']);
require_once INST_BASE_DIR . 'install' . DIRECTORY_SEPARATOR . 'db.php';

// Setting options --------------------------------------

if ($can_user) {
    $salt = '$1$' . substr(sha1(uniqid()), 0, 10) . '$';
    $hash = crypt($data['admin_pass'], $salt);
    $params['admin_login'] = $data['admin_user'];
    $params['admin_salt'] = $salt;
    $params['admin_hash'] = $hash;
    $params['admin_email'] = $data['admin_email'];
}
$params['app_name'] = $data['application_name'];

// Writing database------------------------

if ($can_db && $db != false) {
    foreach ($tables as $table => $query) {
        $table = INST_TABLE_PREFIX . $table;
        if (count($db->query("SHOW TABLES LIKE '" . $table . "'", false, false)) != 1) {
            $db->execute($query);
        }
    }
    if (count($db->query("SHOW TABLES LIKE '" . INST_TABLE_PREFIX . "settings'", false, false)) == 1) {
        $tablename = INST_TABLE_PREFIX . "settings";
        $db->execute("TRUNCATE TABLE `$tablename`");
        $last = key(array_reverse($params));
        $settings = "INSERT INTO `{$tablename}` (`name`, `value`) VALUES ";
        foreach ($params as $name => $value) {
            $settings .= "('$name','$value')" . ($name === $last ? '' : ',');
        }
        $db->execute($settings) or die('There where errors');
    }
}

// End Writing Database------------------------

// Setting Ftp permissions---------------------
$base_ftp_path='';
if ($ftp_stream != false && $ftp_mode == true) {
    $base_ftp_path = ftp_dir_loop($ftp_stream ,'');
    if(empty($base_ftp_path)){
        chmod_R_FTP('assets', 0777, 0777, $ftp_stream);
        chmod_R_FTP('protected' . DIRECTORY_SEPARATOR . 'runtime', 0777, 0777, $ftp_stream);
        chmod_R_FTP('images', 0777, 0777, $ftp_stream);
        chmod_R_FTP('themes', 0777, 0777, $ftp_stream);
    }
} else if ($ftp_mode == false && $ftp_atall == true) {
    chmod_R(MM_ASSETS, 0777, 0777);
    chmod_R(MM_RUNTIME, 0777, 0777);
    chmod_R(MM_UPLOADS, 0777, 0777);
    chmod_R(MM_THEMES, 0777, 0777);
}
// End FTP permissions-------------------------

// Writing config file to base dir-------------

if ($can_db && $db) {
    $stringData = "<?php \n";
    $stringData.="define('MM_TABLE_PREFIX', '" . $data['db_prefix'] . "'); \n";
    $stringData.="define('MM_DB_HOST', '" . $data['db_host'] . "'); \n";
    $stringData.="define('MM_DB_NAME', '" . $data['db_name'] . "'); \n";
    $stringData.="define('MM_DB_USER', '" . $data['db_user'] . "'); \n";
    $stringData.="define('MM_DB_PASS', '" . $data['db_pass'] . "'); \n";
    $stringData.="define('AUTHORIZENET_SANDBOX', false); \n";
    $stringData.=" ?>";
    if(!$fh = fopen($config, 'w')) $errors['global'][]='Cant create a config file !';
    fwrite($fh, $stringData);
    fclose($fh);
    if ($ftp_stream && $ftp_mode === true) {
        if(!empty($base_ftp_path)){
            ftp_chmod($ftp_stream, 0777, $base_ftp_path. DIRECTORY_SEPARATOR .'config.php');
        } else {
            ftp_chmod($ftp_stream, 0777, 'config.php');
        }
    } else {
        chmod($config, 0777);
    }
    echo '<script>window.location.replace("'.INST_BASE_URL.'")</script>';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>WeedHero Install</title>
        <meta name="description" content="">
        <meta name="robots" content="noindex, nofollow">
        <link rel="shortcut icon" href="../images/favicon.png" type="image/png">
        <link rel="stylesheet" type="text/css" href="<?php echo INST_BASE_URL ?>css/wpmm-bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo INST_BASE_URL ?>css/bootstrap-yii.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo INST_BASE_URL ?>css/bootstrap-box.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo INST_BASE_URL ?>css/wpmm-admin.css" />
        <script type="text/JavaScript" src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
        <script type="text/JavaScript" src="<?php echo INST_BASE_URL ?>js/jquery.validate.min.js"></script>
        <style>
            body{
                background-color: #FFF;
            }
        </style>
    </head>

    <body>
        <div class="container" width="1024">
            <table class="table table-bordered">
                <tr>
                    <td align="center">
                        <div class="navbar"><div class="navbar-inner"><legend>MenuMaker Online Ordering installation</legend></div></div>
                        <form id="install_form" class="form-horizontal" method="POST" action="<?php INST_BASE_URL . '/index.php' ?>">
                            <div align="center">
                                <?php echo '<img src="' . INST_BASE_URL . "/images/makerlogo.png" . '" alt="' . $params['vendor_name'] . '" id="login-logo" />'; ?>
                            </div>
                            <legend>Welcome</legend>
                            <p>Welcome to the MenuMaker Online Ordering installation process! Just fill in the information below and you’ll be on your way to using MenuMaker.</p>
                            <?php if (isset($errors['global'])) { ?>
                                <div class="alert alert-error">
                                    <?php
                                    foreach ($errors['global'] as $error)
                                        echo $error . '<br>';
                                    ?>
                                </div>
                                <?php } ?>
                            <legend>Application</legend>
                            <p>Please provide the following information. You can always change these settings later.</p>
                                <?php if (isset($errors['app'])) { ?>
                                <div class="alert alert-error">
                                    <?php
                                    foreach ($errors['app'] as $error)
                                        echo $error . '<br>';
                                    ?>
                                </div>
                                <?php } ?>
                            <div class="control-group">
                                <label class="control-label" for="application_name">Application Title<span class="required">*</span></label>
                                <div class="controls">
                                    <input type="text" id="application_name" name="application_name" placeholder="Application Title" <?php if (!empty($data['application_name'])) { ?>value="<?php echo $data['application_name'] . '"'; } ?>>
                                           <span class="help-inline">Text that will be displayed as a title of Your shop.</span>
                                </div>
                            </div>
                            <legend>Database</legend>
                            <p>Below you should enter your database connection details. If you’re not sure about these, contact your host.</p>
                                <?php if (isset($errors['db'])) { ?>
                                    <div class="alert alert-error">
                                        <?php
                                        foreach ($errors['db'] as $error)
                                            echo $error . '<br>';
                                        ?>
                                    </div>
                                <?php } ?>
                            <div class="control-group">
                                <label class="control-label" for="db_user">Database username<span class="required">*</span></label>
                                <div class="controls">
                                    <input type="text" id="db_user" name="DBForm[db_user]" placeholder="Database username" <?php if (!empty($data['db_user'])) { ?>value="<?php echo $data['db_user'] . '"'; } ?>>
                                           <span class="help-inline">Your MySQL username.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="db_pass">Database password<span class="required">*</span></label>
                                <div class="controls">
                                    <input type="password" id="db_pass" name="DBForm[db_pass]" placeholder="Database password" <?php if (!empty($data['db_pass'])) { ?>value="<?php echo $data['db_pass'] . '"'; } ?>>
                                           <span class="help-inline">Your MySQL password.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="db_name">Database name<span class="required">*</span></label>
                                <div class="controls">
                                    <input type="text" id="db_name" name="DBForm[db_name]" placeholder="Database name" <?php if (!empty($data['db_name'])) { ?>value="<?php echo $data['db_name'] . '"';} ?>>
                                           <span class="help-inline">The name of the database you want to run MenuMaker in.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="db_host">Database host<span class="required">*</span></label>
                                <div class="controls">
                                    <input type="text" id="db_host" name="DBForm[db_host]" placeholder="Database host" <?php if (!empty($data['db_host'])) { ?>value="<?php echo $data['db_host'] . '"';} ?>>
                                           <span class="help-inline">You should be able to get this info from your web host, if localhost does not work.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="db_prefix">Table prefix<span class="required">*</span></label>
                                <div class="controls">
                                    <input type="text" id="db_prefix" name="DBForm[db_prefix]" placeholder="Table prefix" <?php if (!empty($data['db_prefix'])) { ?>value="<?php echo $data['db_prefix'] . '"'; } ?>>
                                           <span class="help-inline">The prefix of MenuMaker database tables for multiple application in one database.</span>
                                </div>
                            </div>
                            <legend>Ftp connection</legend>
                            <p>Ftp connection is required to set proper file permissions for MenuMaker to run. You can also set them manually from any other ftp client later.</p>
                                <?php if (isset($errors['ftp'])) { ?>
                                    <div class="alert alert-error">
                                        <?php
                                        foreach ($errors['ftp'] as $error)
                                            echo $error . '<br>';
                                        ?>
                                    </div>
                                <?php } ?>
                            <div class="control-group" style="margin-left:60px;">
                                <label class="checkbox">
                                    <input type="checkbox" value="1" id="enable_ftp" name="FTPForm[enable_ftp]" <?php if ($data['enable_ftp'] == '1') echo 'checked="checked"'; ?>>
                                    Use FTp to set file permissions, otherwise will attempt to set them via php methods.
                                </label>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="ftp_user">FTP username<span class="required">*</span></label>
                                <div class="controls">
                                    <input type="text" id="ftp_user" name="FTPForm[ftp_user]" placeholder="FTP username" <?php if (!empty($data['ftp_user'])) { ?>value="<?php echo $data['ftp_user'] . '"'; } ?>>
                                           <span class="help-inline">Your FTP login.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="ftp_pass">FTP password<span class="required">*</span></label>
                                <div class="controls">
                                    <input type="password" id="ftp_pass" name="FTPForm[ftp_pass]" placeholder="FTP password" <?php if (!empty($data['ftp_pass'])) { ?>value="<?php echo $data['ftp_pass'] . '"'; } ?>>
                                           <span class="help-inline">Your FTP password.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="ftp_host">FTP host<span class="required">*</span></label>
                                <div class="controls">
                                    <input type="text" id="ftp_host" name="FTPForm[ftp_host]" placeholder="FTP host" <?php if (!empty($data['ftp_host'])) { ?>value="<?php echo $data['ftp_host'] . '"'; } ?>>
                                           <span class="help-inline">You should be able to get this info from your web host, if localhost does not work.</span>
                                </div>
                            </div>
                            <legend>MenuMaker Administrator</legend>
                            <p>Please provide the following information. You can always change these settings later.</p>
                                <?php if (isset($errors['admin'])) { ?>
                                    <div class="alert alert-error">
                                        <?php
                                        foreach ($errors['admin'] as $error)
                                            echo $error . '<br>';
                                        ?>
                                    </div>
                                <?php } ?>
                            <div class="control-group">
                                <label class="control-label" for="admin_user">Admin login<span class="required">*</span></label>
                                <div class="controls">
                                    <input type="text" id="admin_user" name="AdminForm[admin_user]" placeholder="Admin login" <?php if (!empty($data['admin_user'])) { ?>value="<?php echo $data['admin_user'] . '"'; } ?>>
                                           <span class="help-inline">MenuMaker admin login.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="admin_pass">Admin Password<span class="required">*</span></label>
                                <div class="controls">
                                    <input type="password" id="admin_pass" name="AdminForm[admin_pass]" placeholder="Admin Password" <?php if (!empty($data['admin_pass'])) { ?>value="<?php echo $data['admin_pass'] . '"'; } ?>>
                                           <span class="help-inline">MenuMaker admin password.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="admin_pass_repeat">Repeat Password<span class="required">*</span></label>
                                <div class="controls">
                                    <input type="password" id="admin_pass_repeat" name="AdminForm[admin_pass_repeat]" placeholder="Repeat Password">
                                    <span class="help-inline">... and repeat password.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="admin_email">Admin Email</label>
                                <div class="controls">
                                    <input type="text" id="admin_email" name="AdminForm[admin_email]" placeholder="Admin Email" <?php if (!empty($data['admin_email'])) { ?>value="<?php echo $data['admin_email'] . '"'; } ?>>
                                           <span class="help-inline">Admin email.</span>
                                </div>
                            </div>
                            <div align="center">
                                <div class="control-group">
                                    <button type="submit" class="btn btn-primary btn-block">Install</button>
                                </div>
                            </div>
                        </form>
                    </td></tr>
            </table>
        </div>
        <script>
            $(document).ready(function() {
                $("#install_form").validate({
                    rules: {
                        application_name: {
                            required: true
                        },
                        'DBForm[db_user]': {
                            required: true
                        },
                        'DBForm[db_pass]': {
                            required: true
                        },
                        'DBForm[db_name]': {
                            required: true
                        },
                        'DBForm[db_host]': {
                            required: true
                        },
                        'DBForm[db_prefix]': {
                            required: true
                        },
                        'FTPForm[ftp_user]': {
                            required: "#enable_ftp:checked"
                        },
                        'FTPForm[ftp_pass]': {
                            required: "#enable_ftp:checked"
                        },
                        'FTPForm[ftp_host]': {
                            required: "#enable_ftp:checked"
                        },
                        'AdminForm[admin_user]': {
                            required: true
                        },
                        'AdminForm[admin_pass]': {
                            required: true
                        },
                        'AdminForm[admin_pass_repeat]': {
                            equalTo: "#admin_pass"
                        },
                        'AdminForm[admin_email]': {
                            email: true
                        }
                    }
                });
            });
        </script>
    </body>
</html>
