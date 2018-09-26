<?php
defined('INST_BASE_DIR') or die();
define('INST_BASE_URL', "http://" . $_SERVER['HTTP_HOST'] . substr($_SERVER['PHP_SELF'], 0, -9));
require_once INST_BASE_DIR. 'install' . DIRECTORY_SEPARATOR . 'FTP' . DIRECTORY_SEPARATOR .'Socket.php';
//Prossesing data
$data = array();
$data['ftp_host'] = 'localhost';
$data['ftp_path'] = '/';
$errors = array();
$ftp_mode = false;
$ftp_stream = false;
$ftp_atall = false;
if (isset($_POST['FTPForm'])) {
    $ftp_atall = true;
    if (isset($_POST['FTPForm']['enable_ftp']) && $_POST['FTPForm']['enable_ftp'] == '1') {
        $data['enable_ftp'] = trim(strip_tags($_POST['FTPForm']['enable_ftp']));
        $data['ftp_user'] = trim(strip_tags($_POST['FTPForm']['ftp_user']));
        $data['ftp_pass'] = trim(strip_tags($_POST['FTPForm']['ftp_pass']));
        $data['ftp_host'] = trim(strip_tags($_POST['FTPForm']['ftp_host']));
        $ftp_stream = ftp_connect($data['ftp_host']);
        if ($ftp_stream) {
            $ftp_mode = true;
            if (!@ftp_login($ftp_stream, $data['ftp_user'], $data['ftp_pass'])) {
                $errors['ftp'][] = 'Wrong login or password provided !';
                $ftp_mode = false;
            }
        } else
            $errors['ftp'][] = 'Wrong ftp host provided !';
    }
}
define('INST_TABLE_PREFIX', $data['db_prefix']);
require_once INST_BASE_DIR . 'install' . DIRECTORY_SEPARATOR . 'db.php';
// Setting Ftp premissions---------------------
if ($ftp_stream != false && $ftp_mode == true) {
    $temp = '';
    $temp = ftp_dir_loop($ftp_stream ,'');
    if(empty($temp)){
       chmod_R_FTP('assets', 0777, 0777, $ftp_stream);
       chmod_R_FTP('protected' . DIRECTORY_SEPARATOR . 'runtime', 0777, 0777, $ftp_stream);
       chmod_R_FTP('images', 0777, 0777, $ftp_stream);
       chmod_R_FTP('themes', 0777, 0777, $ftp_stream);
    }
    ftp_close($ftp_stream);
    echo '<script>window.location.replace("'.INST_BASE_URL.'")</script>';
} else if ($ftp_mode == false && $ftp_atall) {
    chmod_R(MM_ASSETS, 0777, 0777);
    chmod_R(MM_RUNTIME, 0777, 0777);
    chmod_R(MM_UPLOADS, 0777, 0777);
    chmod_R(MM_THEMES, 0777, 0777);
}
// End Ftp premissions-------------------------
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
                        <div class="navbar"><div class="navbar-inner"><legend>Writing permission required!</legend></div></div>
                        <form id="install_form" class="form-horizontal" method="POST" action="<?php INST_BASE_URL . '/index.php' ?>">
                            <div align="center">
                                <?php echo '<img src="' . INST_BASE_URL . "/images/makerlogo.png" . '" alt="' . $params['vendor_name'] . '" id="login-logo" />'; ?>
                            </div>
                                <legend>Ftp connection</legend>
                                <p>FTP connection is required to set proper file permissions for MenuMaker to run.<br> You can also set them manually from any other FTP client later.</p>
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
                                        Use FTP to set file permissions, otherwise will attempt to set them via php methods.
                                    </label>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="ftp_user">Ftp username<span class="required">*</span></label>
                                    <div class="controls">
                                        <input type="text" id="ftp_user" name="FTPForm[ftp_user]" placeholder="Ftp username" <?php if (!empty($data['ftp_user'])) { ?>value="<?php echo $data['ftp_user'] . '"';
                                } ?>>
                                               <span class="help-inline">Your FTP login.</span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="ftp_pass">Ftp password<span class="required">*</span></label>
                                    <div class="controls">
                                        <input type="password" id="ftp_pass" name="FTPForm[ftp_pass]" placeholder="Ftp password" <?php if (!empty($data['ftp_pass'])) { ?>value="<?php echo $data['ftp_pass'] . '"';
                                } ?>>
                                               <span class="help-inline">Your FTP password.</span>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="ftp_host">Ftp host<span class="required">*</span></label>
                                    <div class="controls">
                                        <input type="text" id="ftp_host" name="FTPForm[ftp_host]" placeholder="Ftp host" <?php if (!empty($data['ftp_host'])) { ?>value="<?php echo $data['ftp_host'] . '"';
                                } ?>>
                                               <span class="help-inline">You should be able to get this info from your web host, if localhost does not work.</span>
                                    </div>
                                </div>
                                <div align="center">
                                    <div class="control-group">
                                        <button type="submit" class="btn">Set permissions</button>
                                    </div>
                                </div>
                                <p>Please make sure that following directories have writing permission:</p>
                                <ul>
                                    <li><?php echo MM_ASSETS ?></li>
                                    <li><?php echo MM_RUNTIME ?></li>
                                    <li><?php echo MM_UPLOADS ?></li>
                                    <li><?php echo MM_THEMES ?></li>
                                </ul>
                                <p>Writing permissions can be assigned using FTP client application or other tool available on your web hosting service.</p>
                        </form>
                    </td></tr>
            </table>
        </div>
        <script>
            $(document).ready(function(){
                $("#install_form").validate({
                    rules: {
                        'FTPForm[ftp_user]': {
                            required: "#enable_ftp:checked"
                        },
                        'FTPForm[ftp_pass]': {
                            required: "#enable_ftp:checked"
                        },
                        'FTPForm[ftp_host]': {
                            required: "#enable_ftp:checked"
                        }
                    }
                });
            });
        </script>
    </body>
</html>
