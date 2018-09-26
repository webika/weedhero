<?php
class AdminController extends Controller {

    public $layout = 'admin';

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('index'),
                'users' => array('*'),
            ),
            array('allow',
                'roles' => array('admin'),
            ),
            array('deny'),
        );
    }

    public function init() {
        parent::init();
        Yii::app()->bootstrap;
        Yii::app()->clientScript->registerCoreScript('jquery')
                                ->registerCssFile(Yii::app()->baseUrl.'/css/wpmm-bootstrap.css')
                                ->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap-yii.css')
                                ->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap-box.css')
                                ->registerCssFile(Yii::app()->baseUrl.'/css/wpmm-admin.css')
                                ->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.flot.min.js',CClientScript::POS_HEAD)
                                ->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.flot.pie.min.js',CClientScript::POS_HEAD)
                                ->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.flot.resize.min.js',CClientScript::POS_HEAD)
                                ->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.form.js',CClientScript::POS_HEAD);
    }

    public function actionIndex() {
        if (Yii::app()->user->role === 'admin') {
            $this->redirect(array('//admin/dashboard'));
        } else {
            $model = new MMAdminForm();
            $this->render('index', array('model' => $model));
        }
    }

    public function actionDashboard(){
        $model = new MMChartForm();
        $model->location = 0;
        $model->daterange=date('m').'/01/'.date('Y').' - '.date('m').'/'.date("t").'/'.date('Y');
        $this->render('dashboard',array('model'=>$model));
    }

    public function actionDashboardCharts(){
        if (isset($_POST['charts'])) {
            $daterange = $_POST['daterange'];
            $location = $_POST['location'];
            $this->renderPartial('_dashboardcharts', array('daterange'=>$daterange, 'location'=>$location));
        }
    }

    public function actionOrders() {
        $model = new MMOrder('search');
        $model->unsetAttributes();
        if (isset($_GET['MMOrder']))
            $model->setAttributes($_GET['MMOrder']);
        $this->render('orders', array('model' => $model));
    }

    public function actionCoupons() {
        $model = new MMCoupons('search');
        $model->unsetAttributes();
        if (isset($_GET['MMCoupons']))
            $model->setAttributes($_GET['MMCoupons']);
        $this->render('coupons', array('model' => $model));
    }

    public function actionRelationalOrder() {
        if (Yii::app()->request->isAjaxRequest) {
            $id = Yii::app()->getRequest()->getParam('id');
            $model = MMOrder::model()->findByPk($id);
            if ($model->addresses === null) {
                $model='No address provided.';
            }
            $this->renderPartial('_relationalorder', array(
                'model' => $model,
            ));
            die();
        }
    }

    public function actionSaveStatus() {
        YiiBase::import('ext.YiiMail.YiiMailMessage');
        if (Yii::app()->request->isAjaxRequest) {
            $id = Yii::app()->getRequest()->getPost('pk');
            $status = Yii::app()->getRequest()->getPost('value');
            $model = MMOrder::model()->findByPk($id);
            if ($model === null) {
                throw new CHttpException(404, 'The requested page does not exist.');
                return $model;
            }
            $prev_status = $model->payment_status;
            $model->payment_status = $status;
            if($model->save(false)){
                $shopemail = $model->location->l_email;
                if(empty($shopemail)) $shopemail='noemail@noemail.mail';
                $shopname = $model->location->name;
                if(empty($shopname)) $shopname='Unknown shop';
                if($status == 'canceled'){
                    if($model->customer){
                        $message = new YiiMailMessage;
                        $message->setBody('<b>Good Day</b><br>Your order #:'.$model->id.' was <b>canceled</b> by a restaraunt !<br> For refund, and other questions please contact us by contacts below. <br>Thank you for using our service!'.Helpers::shopDetails($model->location), 'text/html', 'utf-8');
                        $message->addTo($model->customer->c_mail);
                        $message->setFrom(array($shopemail => $shopname));
                        $message->setReplyTo(array($shopemail => $shopname));
                        $message->setSubject($shopname .' order #:'. $model->id.' Cancel notice');
                        Yii::app()->mail->send($message);
                    }
                } else if($status == 'paused'){
                    if($model->customer){
                        $message = new YiiMailMessage;
                        $message->setBody('<b>Good Day</b><br>Your order #:'.$model->id.' was <b>paused</b> by a restaraunt !<br> For refund, and other questions please contact us by contacts below. <br>Thank you for using our service!'.Helpers::shopDetails($model->location), 'text/html', 'utf-8');
                        $message->addTo($model->customer->c_mail);
                        $message->setFrom(array($shopemail => $shopname));
                        $message->setReplyTo(array($shopemail => $shopname));
                        $message->setSubject($shopname .' order #:'. $model->id.' Pause notice');
                        Yii::app()->mail->send($message);
                    }
                }
                if($prev_status == 'paused' && ($status == 'ordered' || $status == 'completed')){
                    if($model->customer){
                        $message = new YiiMailMessage;
                        $message->setBody('<b>Good Day</b><br>Your order #:'.$model->id.' was <b>resumed</b> by a restaraunt !<br> For refund, and other questions please contact us by contacts below. <br>Thank you for using our service!'.Helpers::shopDetails($model->location), 'text/html', 'utf-8');
                        $message->addTo($model->customer->c_mail);
                        $message->setFrom(array($shopemail => $shopname));
                        $message->setReplyTo(array($shopemail => $shopname));
                        $message->setSubject($shopname .' order #:'. $model->id.' Resume notice');
                        Yii::app()->mail->send($message);
                    }
                }
            }
            die();
        }
    }

    public function actionViewOrder() {
        $id = Yii::app()->getRequest()->getParam('id');
        $model = MMOrder::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
            return $model;
        }
        $this->render('vieworder', array('model' => $model));
    }

    public function actionMenus() {
        $model = new MMMenu('search');
        $model->unsetAttributes();
        $model->searchCategory = new MMLocation();
        $model->searchCategory->unsetAttributes();
        if (Yii::app()->request->isAjaxRequest && $_GET['ajax'] == 'menu-grid') {
            $model->searchCategory->name = $_GET['MMLocation']['name'];
        }
        if (isset($_GET['MMMenu']))
            $model->setAttributes($_GET['MMMenu']);
        Yii::app()->clientScript->registerScriptFile('//code.jquery.com/ui/1.8.24/jquery-ui.min.js');
        $this->render('menus', array('model' => $model));
    }

    public function actionCategories() {
        $model = new MMCategory('search');
        $model->unsetAttributes();
        $model->searchMenu = new MMMenu();
        $model->searchMenu->unsetAttributes();
        if (Yii::app()->request->isAjaxRequest && $_GET['ajax'] == 'Categories-grid') {
            $model->searchMenu->name = $_GET['MMMenu']['name'];
        }
        if (isset($_GET['MMCategory']))
            $model->setAttributes($_GET['MMCategory']);
        $this->render('categories', array('model' => $model));
    }

    public function actionItems() {
        $model = new MMItem('search');
        $model->unsetAttributes();
        $model->searchCategory = new MMCategory();
        $model->searchCategory->unsetAttributes();
        if (Yii::app()->request->isAjaxRequest && $_GET['ajax'] == 'Items-grid') {
            $model->searchCategory->name = $_GET['MMCategory']['name'];
        }
        if (isset($_GET['MMItem']))
            $model->setAttributes($_GET['MMItem']);
        $this->render('items', array('model' => $model));
    }

    public function actionCustomers() {
        $model = new MMCustomer('search');
        $model->unsetAttributes();
        if (isset($_GET['MMCustomer']))
            $model->setAttributes($_GET['MMCustomer']);
        $this->render('customers', array('model' => $model));
    }

    public function actionAddGroup(){
        if (Yii::app()->request->isAjaxRequest) {
            $model = new MMGroup();
                $divid = uniqid();
                if (isset($_POST['gid']) && ($_POST['gid'] > 0) && isset($_POST['iid']) && ($_POST['iid'] > 0)) {
                    $gid = $_POST['gid'];
                    $iid = $_POST['iid'];
                } else {
                    $gid = 'group' . $divid;
                    $iid = 0;
                }
                echo '<div class="grpfrm">';
                echo "<div id='group{$divid}'><label>Group Name: </label>";
                echo CHtml::activeTextField($model, "name[]") . '<label> Group type: </label>';
                echo CHtml::activeDropDownList($model, "type[]", $model->getTypeList($_POST['ItemType']));
                echo CHtml::activeHiddenField($model, "id[]", array('value' => $gid));
                echo CHtml::activeHiddenField($model, "item_id[]", array('value' => $iid));
                $this->widget('bootstrap.widgets.TbButton', array(
                    'label' => '',
                    'type' => 'danger',
                    'size' => 'mini',
                    'icon' => 'remove white',
                    'url' => '',
                    'id' => 'group' . $divid,
                    'htmlOptions' => array('onClick' => "jQuery('#group{$divid}').parent().remove();", 'style' => 'margin:2px; margin-bottom:10px;', 'rel' => 'tooltip', 'title' => 'Remove Group',
                    ),
                ));
                echo '</div>';
                echo '<div id="' . $divid . '" class="wp-attrib"></div>';
                $uid = uniqid('form');
                echo "<div id='{$uid }'>";
                echo Chtml::hiddenField('g_id', 'group' . $divid);
                echo Chtml::hiddenField('attribure_items', 'items');
                $this->widget('bootstrap.widgets.TbButton', array(
                    'label' => 'Add Item',
                    'url' => '',
                    'htmlOptions' => array('onClick' => "jQuery.ajax({'type':'POST','success':function( data ) {
                            // handle return data
                            jQuery('#{$divid}').append(data);
                            var actual = jQuery('#{$divid}').parent().find('select');
                                if(jQuery(actual).find(':selected').text() == 'Single-choise'){
                                if(jQuery('#{$divid}').parent().find('#attr input:checked').length == 0) {
                                jQuery('#{$divid}').parent().find('#attr input:checkbox').attr('checked', false);
                                jQuery('#{$divid}').parent().find('#attr input:checkbox:first').attr('checked', true);
                                    }
                            }
                          },
                        'url':'" . Yii::app()->createUrl('admin/additem') . "','cache':false,
                        'data':jQuery('#{$uid}').children().serialize()+'&action='+ encodeURIComponent('admin')+'&" . Yii::app()->request->csrfTokenName . "='+encodeURIComponent('" . Yii::app()->request->csrfToken . "')
                        });"
                    ),
                ));
                echo '</div></div>';
                Yii::app()->end();
        } else
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionAddItem(){
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['g_id'])) {
                    $g_id = $_POST['g_id'];
                }
                $model = new MMAttribute();
                $divid = uniqid('item');
                echo "<div id='attr'><label>Name: </label>";
                echo CHtml::activeTextField($model, "name[]") . '<label>Price: </label>';
                echo CHtml::activeTextField($model, "price[]", array('style' => 'width:50px;')) . ' ';
                echo CHtml::CheckBox("checked_id_" . $divid, false, array('uncheckValue' => 0));
                echo CHtml::activeHiddenField($model, "group_id[]", array('value' => $g_id));
                echo CHtml::activeHiddenField($model, "id[]", array('value' => $divid));
                $this->widget('bootstrap.widgets.TbButton', array(
                    'label' => '',
                    'type' => 'danger',
                    'size' => 'mini',
                    'icon' => 'remove white',
                    'url' => '',
                    'id' => $divid,
                    'htmlOptions' => array('onClick' => "jQuery('#{$divid}').parent().remove();", 'style' => 'margin:6px;', 'rel' => 'tooltip', 'title' => 'Remove Attribute',
                    ),
                ));
                echo '<br></div>';
                echo '<script type="text/javascript">';
                $script = <<<SCRIPT
jQuery(".grpfrm select").change(function(){
    if(jQuery(this).find(':selected').text() == 'Single-choise'){
        jQuery(this).parent().parent().find('#attr input:checkbox').attr('checked', false);
        jQuery(this).parent().parent().find('#attr input:checkbox:first').attr('checked', true);
    } else {
        jQuery(this).parent().parent().find('#attr input:checkbox').attr('checked', false);
    }
    });
jQuery(".grpfrm input:checkbox").click(function(){
    //alert(jQuery(this).parent().parent().parent().find(":selected").text());
var typegroup = jQuery(this).parent().parent().parent().find(":selected").text();
var parent_id= jQuery(this).parent().parent().parent().find(":selected").parent();
    if(typegroup == "Single-choise"){
       jQuery(this).parent().parent().find("input:checkbox").attr('checked', false);
       jQuery(this).attr('checked',true);
    }
});
SCRIPT;
                echo $script;
                echo '</script>';
                Yii::app()->end();
        } else
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionDelAttrib() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if (Yii::app()->request->isAjaxRequest) {
                $model = MMAttribute::model()->findByPk($id);
                if (!$model) {
                    throw new CHttpException(404, 'The requested page does not exist.');
                    return $model;
                }
                $model->delete();
            }
            else
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
        else
            return false;
    }

    public function actionDelGroup() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if (Yii::app()->request->isAjaxRequest) {
                $model = MMGroup::model()->findByPk($id);
                if ($model === null) {
                    throw new CHttpException(404, 'The requested page does not exist.');
                    return $model;
                }
                $model->delete();
            }
            else
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
        else
            return false;
    }

    public function actionLocations(){
        $model = new MMLocation('search');
        $model->unsetAttributes();
        if (isset($_GET['MMLocation'])) {
            $model->setAttributes($_GET['MMLocation']);
        }
        $this->render('locations', array('model' => $model));
    }

    public function actionCreateLocation() {
        $model = new MMLocation;
        if (isset($_POST['MMLocation'])) {
            $model->setAttributes($_POST['MMLocation']);
            $model->gmap = serialize($_POST['MMLocation']['map']);
            $model->l_calendar = serialize($_POST['MMLocation']['work_time']);
            if (isset($_POST['ajax']) && isset($_POST['ajax_validation'])) {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            if ($model->save()) {
                if (isset($_POST['apply']) && $_POST['apply'] == 'true') {
                    $url = Yii::app()->createUrl('admin/editlocation', array('id' => $model->id));
                    $this->redirect($url);
                } else {
                    $url = Yii::app()->createUrl('admin/locations');
                    $this->redirect($url);
                    return;
                }
                return;
            }
        }
        $this->render('editlocation', array('model' => $model, 'iscreate' => true));
    }

    public function actionEditLocation() {
        if (isset($_GET['id'])) {
            $model = MMLocation::model()->findByPk($_GET['id']);
            if ($model === null) {
                throw new CHttpException(404, 'The requested page does not exist.');
                return $model;
            }
            if (isset($_POST['MMLocation'])) {
                $model->setAttributes($_POST['MMLocation']);
                $model->gmap = serialize($_POST['MMLocation']['map']);
                $model->l_calendar = serialize($_POST['MMLocation']['work_time']);
                $model->zip_list = serialize($_POST['MMLocation']['zipcodes']);
                if (isset($_POST['ajax']) && isset($_POST['ajax_validation'])) {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
                }
                //echo '<pre>'.var_dump($_POST['MMLocation']).'</pre>';die();
                if ($model->save()) {

                }
                if (isset($_POST['apply']) && $_POST['apply'] == 'true') {
                    $url = Yii::app()->createUrl('admin/editlocation', array('id' => $model->id));
                    $this->redirect($url);
                } else {
                    $url = Yii::app()->createUrl('admin/locations');
                    $this->redirect($url);
                }
                return;
            }
        }
        $this->render('editlocation', array('model' => $model, 'iscreate' => false));
    }

    public function actionDeleteLocation() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if (Yii::app()->request->isPostRequest) {
                $model = MMLocation::model()->findByPk($id);
                if ($model === null) {
                    throw new CHttpException(404, 'The requested page does not exist.');
                    return $model;
                }
                $model->delete();
                if (!isset($_GET['ajax'])) {
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin/locations'));
                    return;
                }
            }
            else
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionUpdateMenu() {
        if (isset($_GET['id'])) {
            $upload = new MMUploadForm();
            $model = MMMenu::model()->findByPk($_GET['id']);
            if ($model === null) {
                throw new CHttpException(404, 'The requested page does not exist.');
                return $model;
            }
            if (isset($_POST['MMMenu'])) {
                $model->setAttributes($_POST['MMMenu']);
                $model->locations = $_POST['MMMenu']['locations'];
                if (isset($_POST['ajax']) && isset($_POST['ajax_validation'])) {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
                }
                if ($model->save()) {
                    if (isset($_POST['MMUploadForm'])) {
                        $upload->setAttributes($_POST['MMUploadForm']);
                        $file = CUploadedFile::getInstance($upload, 'file');
                        if ($file != NULL) {
                            $dir = MM_UPLOADS_DIR;
                            $filepath = $dir . DIRECTORY_SEPARATOR . 'menu' . $model->id . '.' . $file->getExtensionName();
                            $file->saveAs($filepath);
                            Yii::import('application.extensions.EWideImage.EWideImage');
                            EWideImage::load($filepath)
                                    ->resize(80, 80, 'outside', 'any')
                                    ->crop('left', 'bottom', 80, 80)
                                    ->saveToFile($dir . DIRECTORY_SEPARATOR . 'thumb_menu' . $model->id . '.' . $file->getExtensionName());
                            $size = getimagesize($filepath);
                            if( ($size[0]/$size[1]) < 3.58 ){
                            $heigth=$size[0]/3.58;
                            $res=($heigth*100)/$size[1];
                            EWideImage::load($filepath)
                                    ->crop('center', 'center', '100%', $res.'%')
                                    ->saveToFile($filepath);
                            }
                            $model->image = 'menu' . $model->id . '.' . $file->getExtensionName();
                            $model->save(false);
                        }
                    }
                    Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                    if (isset($_POST['apply']) && $_POST['apply'] == 'true') {
                        $url = Yii::app()->createUrl('admin/updatemenu',array('id'=>$model->id));
                        $this->redirect($url);
                    } else {
                        $url = Yii::app()->createUrl('admin/menus');
                        $this->redirect($url);
                    }
                    return;
                }
            }
            $this->render('editmenu', array('model' => $model, 'upload' => $upload, 'iscreate' => false));
        }
    }

    public function actionCreateMenu() {
        $model = new MMMenu;
        $upload = new MMUploadForm;
        if (isset($_POST['MMMenu'])) {
            $model->setAttributes($_POST['MMMenu']);
            $model->locations = $_POST['MMMenu']['locations'];
            if (isset($_POST['ajax']) &&  isset($_POST['ajax_validation'])) {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            if ($model->save()) {
                if (isset($_POST['MMUploadForm'])) {
                    $upload->setAttributes($_POST['MMUploadForm']);
                    $file = CUploadedFile::getInstance($upload, 'file');
                    if ($file != NULL) {
                        $dir = MM_UPLOADS_DIR;
                        $filename = 'menu' . $model->id . '.' . $file->getExtensionName();
                        $filepath = $dir . DIRECTORY_SEPARATOR . $filename;
                        if ($file->saveAs($filepath)) {
                            Yii::import('application.extensions.EWideImage.EWideImage');
                            EWideImage::load($filepath)
                                    ->resize(80, 80, 'outside', 'any')
                                    ->crop('left', 'bottom', 80, 80)
                                    ->saveToFile($dir . DIRECTORY_SEPARATOR . 'thumb_menu' . $model->id . '.' . $file->getExtensionName());
                            $size = getimagesize($filepath);
                            if( ($size[0]/$size[1]) < 3.58 ){
                            $heigth=$size[0]/3.58;
                            $res=($heigth*100)/$size[1];
                            EWideImage::load($filepath)
                                    ->crop('center', 'center', '100%', $res.'%')
                                    ->saveToFile($filepath);
                            }
                            $model->image = $filename;
                            $model->save(false);
                        }
                    }
                }
                Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                if (isset($_POST['apply']) && $_POST['apply'] == 'true') {
                        $url = Yii::app()->createUrl('admin/updatemenu',array('id'=>$model->id));
                        $this->redirect($url);
                    } else {
                        Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                         $url = Yii::app()->createUrl('admin/menus');
                         $this->redirect($url);
                        return;
                    }
                return;
            }
        }
        $this->render('editmenu', array('model' => $model, 'upload' => $upload, 'iscreate' => true));
    }

    public function actionDeleteMenu() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if (Yii::app()->request->isPostRequest) {
                $model = MMMenu::model()->findByPk($id);
                if ($model === null) {
                    throw new CHttpException(404, 'The requested page does not exist.');
                    return $model;
                }
                $model->delete();
                if (!isset($_GET['ajax'])) {
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin/menus'));
                    return;
                }
            }
            else
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionSortMenu() {
        if (isset($_POST['items']) && is_array($_POST['items'])) {
            $cur_items = MMMenu::model()->findAllByPk($_POST['items'], array('order' => 'sort_order DESC'));
            for ($i = 0; $i < count($_POST['items']); $i++) {
                $item = MMMenu::model()->findByPk($_POST['items'][$i]);
                if ($item->sort_order != $cur_items[$i]->sort_order) {
                    $item->sort_order = $cur_items[$i]->sort_order;
                    $item->save();
                }
            }
        }
    }

    public function actionFlagMenu($pk, $name, $value) {
        $model = MMMenu::model()->findByPk($pk);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
            return $model;
        }
        $model->{$name} = $value;
        $model->save(false);
        if (!Yii::app()->request->isAjaxRequest) {
            $this->redirect(Yii::app()->createUrl('admin/menus'));
        }
    }

    public function actionFlagCoupon($pk, $name, $value) {
        $model = MMCoupons::model()->findByPk($pk);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
            return $model;
        }
        $model->{$name} = $value;
        $model->save(false);
        if (!Yii::app()->request->isAjaxRequest) {
            $this->redirect(Yii::app()->createUrl('admin/coupons'));
        }
    }

    public function actionCreateCoupon() {
        $upload = new MMUploadForm;
        $model = new MMCoupons;
        if (isset($_POST['MMCoupons'])) {
            $model->setAttributes($_POST['MMCoupons']);
            $model->items = $_POST['MMCoupons']['items'];
            if (isset($_POST['ajax']) &&  isset($_POST['ajax_validation'])) {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            if ($model->save()) {

                if (isset($_POST['MMUploadForm'])) {
                    $upload->setAttributes(''/*$_POST['MMUploadForm']*/);
                    $file = CUploadedFile::getInstance($upload, 'file');
                    if ($file != NULL) {
                        $dir = MM_UPLOADS_DIR . DIRECTORY_SEPARATOR . 'coupon' . $model->id . '.' . $file->getExtensionName();
                        $file->saveAs($dir);
                        chmod($dir, 0777);
                        $model->image = 'coupon' . $model->id . '.' . $file->getExtensionName();
                        $model->save(false);
                    }
                }

                Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                if (isset($_POST['apply']) && $_POST['apply'] == 'true') {
                        $url = Yii::app()->createUrl('admin/editcoupon',array('id'=>$model->id));
                        $this->redirect($url);
                    } else {
                        Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                         $url = Yii::app()->createUrl('admin/coupons');
                         $this->redirect($url);
                        return;
                    }
                return;
            }
        }
        $this->render('editcoupon', array('model' => $model, 'upload' => $upload, 'iscreate' => true));
    }

    public function actionEditCoupon() {
        $upload = new MMUploadForm;
        $model = MMCoupons::model();
        if (isset($_GET['id'])) {

            $model = MMCoupons::model()->findByPk($_GET['id']);
            if ($model === null) {
                throw new CHttpException(404, 'The requested page does not exist.');
                return $model;
            }
            if (isset($_POST['MMCoupons'])) {
                $model->setAttributes($_POST['MMCoupons']);
                $model->items = $_POST['MMCoupons']['items'];
                if (isset($_POST['ajax']) && isset($_POST['ajax_validation'])) {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
                }
                if ($model->save()) {

                    if (isset($_POST['MMUploadForm'])) {
                        $upload->setAttributes(''/*$_POST['MMUploadForm']*/);
                        $file = CUploadedFile::getInstance($upload, 'file');
                        if ($file != NULL) {
                            $dir = MM_UPLOADS_DIR . DIRECTORY_SEPARATOR . 'coupon' . $model->id . '.' . $file->getExtensionName();
                            $file->saveAs($dir);
                            chmod($dir, 0777);
                            $model->image = 'coupon' . $model->id . '.' . $file->getExtensionName();
                            $model->save(false);
                        }
                    }

                    Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                    if (isset($_POST['apply']) && $_POST['apply'] == 'true') {
                        $url = Yii::app()->createUrl('admin/editcoupon', array('id' => $model->id));
                        $this->redirect($url);
                    } else {
                        $url = Yii::app()->createUrl('admin/coupons');
                        $this->redirect($url);
                    }
                    return;
                }
            }
        }
        $this->render('editcoupon', array('model' => $model, 'upload'=>$upload, 'iscreate' => false));
    }

    public function actionDelPictureMenu() {
        if (Yii::app()->request->isAjaxRequest) {

            if (isset($_GET['id'])) {

                $model = MMMenu::model()->findByPk($_GET['id']);
                if ($model === null) {
                    throw new CHttpException(404, 'The requested page does not exist.');
                    return $model;
                }
                $dir = MM_UPLOADS_DIR . DIRECTORY_SEPARATOR;
                if (is_file($dir . $model->image)) {

                    chmod($dir . $model->image, 0777);
                    unlink($dir . $model->image);
                    if (is_file($dir . 'thumb_' . $model->image)) {
                        chmod($dir . 'thumb_' . $model->image, 0777);
                        unlink($dir . 'thumb_' . $model->image);
                    }
                }
                $model->image = '';
                $model->save(false);
                return true;
            }
            else
                return false;
        }
        else
            return false;
    }

    public function actionFlagCat() {
        if (isset($_GET['pk']) && isset($_GET['name']) && isset($_GET['value'])) {
            $pk = $_GET['pk'];
            $name = $_GET['name'];
            $value = $_GET['value'];

            $model = MMCategory::model()->findByPk($pk);
            if ($model === null) {
                throw new CHttpException(404, 'The requested page does not exist.');
                return $model;
            }
            $model->{$name} = $value;
            $model->save(false);
            return true;
            if (!Yii::app()->request->isAjaxRequest) {
                $url = Yii::app()->createUrl('admin/categories');
                $this->redirect($url);
            }
        }
        else
            return false;
    }

    public function actionFlagItem() {
        if (isset($_GET['pk']) && isset($_GET['name']) && isset($_GET['value'])) {
            $pk = $_GET['pk'];
            $name = $_GET['name'];
            $value = $_GET['value'];

            $model = MMItem::model()->findByPk($pk);
            if ($model === null) {
                throw new CHttpException(404, 'The requested page does not exist.');
                return $model;
            }
            $model->{$name} = $value;
            $model->save(false);
            return true;
            if (!Yii::app()->request->isAjaxRequest) {
                $url = Yii::app()->createUrl('admin/items');
                $this->redirect($url);
                return;
            }
        }
        else
            return false;
    }

    public function actionCreateCategory() {
        $model = new MMCategory();
        $upload = new MMUploadForm();

        if (isset($_POST['MMCategory'])) {
            $model->setAttributes($_POST['MMCategory']);
             if (isset($_POST['ajax']) && isset($_POST['ajax_validation'])) {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            if ($model->save()) {
                if (isset($_POST['MMUploadForm'])) {
                    $upload->setAttributes($_POST['MMUploadForm']);
                    $file = CUploadedFile::getInstance($upload, 'file');
                    if ($file != NULL) {
                        $dir = MM_UPLOADS_DIR . DIRECTORY_SEPARATOR . 'cat' . $model->id . '.' . $file->getExtensionName();
                        $file->saveAs($dir);
                        chmod($dir, 0777);
                        Yii::import('application.extensions.EWideImage.EWideImage');
                        EWideImage::load($dir)
                                ->resize(80, 80, 'outside', 'any')
                                ->crop('left', 'bottom', 80, 80)
                                ->saveToFile(MM_UPLOADS_DIR . DIRECTORY_SEPARATOR . 'thumb_cat' . $model->id . '.' . $file->getExtensionName());
                        $model->image = 'cat' . $model->id . '.' . $file->getExtensionName();
                        $model->save(false);
                    }
                }
                Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                if (isset($_POST['apply']) && $_POST['apply'] == 'true') {
                        $url = Yii::app()->createUrl('admin/updatecategory',array('id'=>$model->id));
                        $this->redirect($url);
                    } else {
                        Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                        $url = Yii::app()->createUrl('admin/categories');
                        $this->redirect($url);
                        return;
                    }
                return;
            }
        }
        $this->render('editcategory', array('model' => $model, 'upload' => $upload, 'iscreate' => true));
    }

    public function actionUpdateCategory() {
        if (isset($_GET['id'])) {
            $upload = new MMUploadForm;
            $model = MMCategory::model()->findByPk($_GET['id']);
            if ($model === null) {
                throw new CHttpException(404, 'The requested page does not exist.');
                return $model;
            }

            if (isset($_POST['MMCategory'])) {
                 if (isset($_POST['ajax']) && isset($_POST['ajax_validation'])) {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
                 }
                $model->setAttributes($_POST['MMCategory']);

                if ($model->save()) {
                    if (isset($_POST['MMUploadForm'])) {
                        $upload->setAttributes($_POST['MMUploadForm']);
                        $file = CUploadedFile::getInstance($upload, 'file');
                        if ($file != NULL) {
                            $dir = MM_UPLOADS_DIR . DIRECTORY_SEPARATOR . 'cat' . $model->id . '.' . $file->getExtensionName();
                            $file->saveAs($dir);
                            chmod($dir, 0777);
                            Yii::import('application.extensions.EWideImage.EWideImage');
                            EWideImage::load($dir)
                                    ->resize(80, 80, 'outside', 'any')
                                    ->crop('left', 'bottom', 80, 80)
                                    ->saveToFile(MM_UPLOADS_DIR . DIRECTORY_SEPARATOR . 'thumb_cat' . $model->id . '.' . $file->getExtensionName());
                            $model->image = 'cat' . $model->id . '.' . $file->getExtensionName();
                            $model->save(false);
                        }
                    }
                    Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                    if (isset($_POST['apply']) && $_POST['apply'] == 'true') {
                        $url = Yii::app()->createUrl('admin/updatecategory',array('id'=>$model->id));
                        $this->redirect($url);
                    } else {
                        Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                        $url = Yii::app()->createUrl('admin/categories',array('id'=>$model->id));
                        $this->redirect($url);
                    }
                    return;
                }
            }
            $this->render('editcategory', array('model' => $model, 'upload' => $upload, 'iscreate' => false));
        }
    }

    public function actionDeleteCategory() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if (Yii::app()->request->isPostRequest) {
                $model = MMCategory::model()->findByPk($id);
                if ($model === null) {
                    throw new CHttpException(404, 'The requested page does not exist.');
                    return $model;
                }
                $model->delete();
                if (!isset($_GET['ajax'])) {
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin/categories'));
                    return;
                }
            }
            else
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDeleteCoupon() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if (Yii::app()->request->isPostRequest) {
                $model = MMCoupons::model()->findByPk($id);
                if ($model === null) {
                    throw new CHttpException(404, 'The requested page does not exist.');
                    return $model;
                }
                $model->delete();
                if (!isset($_GET['ajax'])) {
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin/coupons'));
                    return;
                }
            }
            else
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDelPictureCategory() {
        if (Yii::app()->request->isAjaxRequest) {

            if (isset($_GET['id'])) {

                $model = MMCategory::model()->findByPk($_GET['id']);
                if ($model === null) {
                    throw new CHttpException(404, 'The requested page does not exist.');
                    return $model;
                }
                $dir = MM_UPLOADS_DIR . DIRECTORY_SEPARATOR;
                if (is_file($dir . $model->image)) {

                    chmod($dir . $model->image, 0777);
                    unlink($dir . $model->image);
                    if (is_file($dir . 'thumb_' . $model->image)) {
                        chmod($dir . 'thumb_' . $model->image, 0777);
                        unlink($dir . 'thumb_' . $model->image);
                    }
                }
                $model->image = '';
                $model->save(false);
                return true;
            }
            else
                return false;
        }
        else
            return false;
    }

    public function actionDelPictureCoupon() {
        if (Yii::app()->request->isAjaxRequest) {

            if (isset($_GET['id'])) {

                $model = MMCoupons::model()->findByPk($_GET['id']);
                if ($model === null) {
                    throw new CHttpException(404, 'The requested page does not exist.');
                    return $model;
                }
                $dir = MM_UPLOADS_DIR . DIRECTORY_SEPARATOR;
                if (is_file($dir . $model->image)) {

                    chmod($dir . $model->image, 0777);
                    unlink($dir . $model->image);
                }
                $model->image = '';
                $model->save(false);
                return true;
            }
            else
                return false;
        }
        else
            return false;
    }

    public function actionCreateItem() {
        $upload = new MMUploadForm;
        $model = new MMItem();
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
            return $model;
        }
        if (isset($_POST['MMItem'])) {

            $model->setAttributes($_POST['MMItem']);

            if (isset($_POST['ajax']) && isset($_POST['ajax_validation']) && (int)$_POST['ajax_validation'] > 0) {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
            }

            $model->categories = $_POST['MMItem']['categories'];

            $group_to_save = array();
            $attribute_to_save = array();
            $normalized_attr = array();
            $all_valid = true;
            $success_saving_all = true;

            if (isset($_POST['MMGroup'])) {
                for ($i = 0; $i < count($_POST['MMGroup']['name']); $i++) {

                    $submitted_group['name'] = $_POST['MMGroup']['name'][$i];
                    $submitted_group['type'] = $_POST['MMGroup']['type'][$i];
                    $submitted_group['id'] = $_POST['MMGroup']['id'][$i];
                    $submitted_group['item_id'] = $_POST['MMGroup']['item_id'][$i];

                    if ($submitted_group['name'] == '') {
                        continue;
                    }

                    $group = new MMGroup();

                    $group->setAttributes($submitted_group);

                    if (isset($_POST['MMAttribute'])) {
                        $normalized_attr[] = $submitted_group['id'];
                    }

                    if (!$group->validate()) {
                        $all_valid = false;
                    } else {
                        $group_to_save[] = $group;
                    }
                }
                if (isset($_POST['MMAttribute'])) {
                    for ($i = 0; $i < count($_POST['MMAttribute']['name']); $i++) {
                        $new_group = false;
                        $submitted_attribute['name'] = $_POST['MMAttribute']['name'][$i];
                        $submitted_attribute['price'] = $_POST['MMAttribute']['price'][$i];
                        $submitted_attribute['group_id'] = $_POST['MMAttribute']['group_id'][$i];
                        $submitted_attribute['id'] = $_POST['MMAttribute']['id'][$i];
                        $submitted_attribute['checked_id'] = $_POST['checked_id_' . $submitted_attribute['id']];

                        $cont = true;
                        foreach ($group_to_save as $group1) {
                            if ($group1->id == $submitted_attribute['group_id']) {
                                $cont = false;
                                break;
                            }
                        }

                        if ($submitted_attribute['name'] == '' && $cont == true) {
                            continue;
                        }

                        $attribute = new MMAttribute();

                        $attribute->setAttributes($submitted_attribute);

                        if (!$attribute->validate()) {
                            $all_valid = false;
                        } else {
                            if (!is_numeric($attribute->price)) {
                            }
                            $attribute_to_save[] = $attribute;
                        }
                    }
                }
            }
            if ($all_valid && $model->validate()) {
                $trans = Yii::app()->db->beginTransaction();
                try {


                    $model->save(false);

                    if (isset($_POST['MMUploadForm'])) {
                        $upload->setAttributes($_POST['MMUploadForm']);
                        $file = CUploadedFile::getInstance($upload, 'file');
                        if ($file != NULL) {
                            $dir = MM_UPLOADS_DIR . DIRECTORY_SEPARATOR . 'item' . $model->id . '.' . $file->getExtensionName();
                            $file->saveAs($dir);
                            chmod($dir, 0777);
                            Yii::import('application.extensions.EWideImage.EWideImage');
                            EWideImage::load($dir)
                                    ->resize(80, 80, 'outside', 'any')
                                    ->crop('left', 'bottom', 80, 80)
                                    ->saveToFile(MM_UPLOADS_DIR . DIRECTORY_SEPARATOR . 'thumb_item' . $model->id . '.' . $file->getExtensionName());
                            $model->image = 'item' . $model->id . '.' . $file->getExtensionName();
                            $model->save(false);
                        }
                    }

                    foreach ($group_to_save as $count => $group) {

                        $group->item_id = $model->id;
                        $group->save(false);

                        foreach ($attribute_to_save as $attrib) {
                            if ($attrib->group_id == $normalized_attr[$count]) {
                                $attrib->group_id = $group->id;
                                $attrib->save(false);
                            }
                        }
                    }

                    $trans->commit();
                } catch (Exception $e) {

                    $trans->rollback();
                    Yii::log("Error occurred while saving (update scenario) item or its 'groups'. Rolling back... . Failure reason as reported in exception: " . $e->getMessage(), CLogger::LEVEL_ERROR, __METHOD__);
                    Yii::app()->user->setFlash('error', '<strong>ERROR!</strong> Data was not saved.');
                    $success_saving_all = false;
                }
                if ($success_saving_all) {
                    if (isset($_POST['apply']) && $_POST['apply'] == 'true') {
                        $url = Yii::app()->createUrl('admin/updateitem',array('id'=>$model->id));
                        $this->redirect($url);
                    } else {
                        Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                        $url = Yii::app()->createUrl('admin/items');
                        $this->redirect($url);
                        return;
                    }
                }
            }
        }

        $this->render('edititem', array('model' => $model, 'upload' => $upload, 'groups' => (isset($group_to_save)) ? $group_to_save : '', 'isnew' => true));
    }

    public function actionUpdateItem() {
        if (isset($_GET['id'])) {
            $upload = new MMUploadForm;
            $model = MMItem::model()->findByPk($_GET['id']);

            if ($model === null) {
                throw new CHttpException(404, 'The requested page does not exist.');
                return $model;
            }
            if (isset($_POST['MMItem'])) {
                if (isset($_POST['ajax']) && isset($_POST['ajax_validation'])) {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
                 }
                $model->setAttributes($_POST['MMItem']);
                $model->categories = $_POST['MMItem']['categories'];
                $group_to_save = array();
                $attribute_to_save = array();
                $normalized_attr = array();
                $all_valid = true;
                $success_saving_all = true;

                if (isset($_POST['MMGroup'])) {
                    for ($i = 0; $i < count($_POST['MMGroup']['name']); $i++) {
                        $new_group = false;
                        $submitted_group['name'] = $_POST['MMGroup']['name'][$i];
                        $submitted_group['type'] = $_POST['MMGroup']['type'][$i];
                        $submitted_group['id'] = $_POST['MMGroup']['id'][$i];
                        $submitted_group['item_id'] = $_POST['MMGroup']['item_id'][$i];

                        if ($submitted_group['name'] == '') {
                            continue;
                        }
                        if (is_numeric($submitted_group['id'])) {
                            $group = MMGroup::model()->findByPk($submitted_group['id']);
                            if ($group->item->id != $model->id) {
                                Yii::log("Attempts to update Group with an id of {$group->id} but it belongs to an Artist with an id of {$group->item->id}" .
                                        " and not 'this' item with id = {$model->id}", CLogger::LEVEL_ERROR, __METHOD__);
                                throw new CHttpException(500, "Error occurred");
                            }
                        } else {
                            $group = new MMGroup();
                            $new_group = true;
                        }

                        $group->setAttributes($submitted_group);
                        if ($new_group == true)
                            $group->item_id = $model->id;

                        if (isset($_POST['MMAttribute'])) {
                            $normalized_attr[] = $submitted_group['id'];
                        }

                        if (!$group->validate()) {
                            $all_valid = false;
                        } else {
                            $group_to_save[] = $group;
                        }
                    }
                    if (isset($_POST['MMAttribute'])) {
                        for ($i = 0; $i < count($_POST['MMAttribute']['name']); $i++) {
                            $new_group = false;
                            $submitted_attribute['name'] = $_POST['MMAttribute']['name'][$i];
                            $submitted_attribute['price'] = $_POST['MMAttribute']['price'][$i];
                            $submitted_attribute['group_id'] = $_POST['MMAttribute']['group_id'][$i];
                            $submitted_attribute['id'] = $_POST['MMAttribute']['id'][$i];
                            $submitted_attribute['checked_id'] = $_POST['checked_id_' . $submitted_attribute['id']];
                            $cont = true;
                            foreach ($group_to_save as $group1) {
                                if ($group1->id == $submitted_attribute['group_id']) {
                                    $cont = false;
                                    break;
                                }
                            }

                            if ($submitted_attribute['name'] == '' && $cont == true) {
                                continue;
                            }

                            if (is_numeric($submitted_attribute['id'])) {
                                $attribute = MMAttribute::model()->findByPk($submitted_attribute['id']);
                            } else {
                                $attribute = new MMAttribute();
                            }

                            $attribute->setAttributes($submitted_attribute);

                            if (!$attribute->validate()) {
                                $all_valid = false;
                            } else {
                                if (!is_numeric($attribute->price)) {
                                }
                                $attribute_to_save[] = $attribute;
                            }
                        }
                    }
                }
                if ($all_valid && $model->validate()) {
                    $trans = Yii::app()->db->beginTransaction();
                    try {


                        $model->save(false);

                        if (isset($_POST['MMUploadForm'])) {
                            $upload->setAttributes($_POST['MMUploadForm']);
                            $file = CUploadedFile::getInstance($upload, 'file');
                            if ($file != NULL) {
                                $dir = MM_UPLOADS_DIR . DIRECTORY_SEPARATOR . 'item' . $model->id . '.' . $file->getExtensionName();
                                $file->saveAs($dir);
                                chmod($dir, 0777);
                                Yii::import('application.extensions.EWideImage.EWideImage');
                                EWideImage::load($dir)
                                        ->resize(80, 80, 'outside', 'any')
                                        ->crop('left', 'bottom', 80, 80)
                                        ->saveToFile(MM_UPLOADS_DIR . DIRECTORY_SEPARATOR . 'thumb_item' . $model->id . '.' . $file->getExtensionName());
                                $model->image = 'item' . $model->id . '.' . $file->getExtensionName();
                                $model->save(false);
                            }
                        }

                        foreach ($group_to_save as $count => $group) {
                            $group->save(false);
                            foreach ($attribute_to_save as $attrib) {
                                if ($attrib->group_id == $normalized_attr[$count]) {
                                    $attrib->group_id = $group->id;
                                    $attrib->save(false);
                                }
                            }
                        }

                        $trans->commit();
                    } catch (Exception $e) {

                        $trans->rollback();
                        Yii::log("Error occurred while saving (update scenario) item or its 'groups'. Rolling back... . Failure reason as reported in exception: " . $e->getMessage(), CLogger::LEVEL_ERROR, __METHOD__);
                        Yii::app()->user->setFlash('error', '<strong>ERROR!</strong> Data was not saved.');
                        $success_saving_all = false;
                        echo 'error';
                    }
                    if ($success_saving_all) {
                        if (isset($_POST['apply']) && $_POST['apply'] == 'true') {
                            $url = Yii::app()->createUrl('admin/updateitem',array('id'=>$model->id));
                            $this->redirect($url);
                        } else {
                            Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                            $url = Yii::app()->createUrl('admin/items');
                            $this->redirect($url);
                        }
                    }
                }
            }
            else {
                $group_to_save = $model->params;
            }

            $this->render('edititem', array('model' => $model, 'upload' => $upload, 'groups' => (isset($group_to_save)) ? $group_to_save : '', 'isnew' => false));
        }
    }

    public function actionDeleteItem() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if (Yii::app()->request->isPostRequest) {
                $model = MMItem::model()->findByPk($id);
                if ($model === null) {
                    throw new CHttpException(404, 'The requested page does not exist.');
                    return $model;
                }
                $model->delete();
                if (!isset($_GET['ajax'])) {
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin/items'));
                    return;
                }
            }
            else
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDelPictureItem() {
        if (Yii::app()->request->isAjaxRequest) {

            if (isset($_GET['id'])) {

                $model = MMItem::model()->findByPk($_GET['id']);
                if ($model === null) {
                    throw new CHttpException(404, 'The requested page does not exist.');
                    return $model;
                }
                $dir = MM_UPLOADS_DIR . DIRECTORY_SEPARATOR;
                if (is_file($dir . $model->image)) {

                    chmod($dir . $model->image, 0777);
                    unlink($dir . $model->image);
                    if (is_file($dir . 'thumb_' . $model->image)) {
                        chmod($dir . 'thumb_' . $model->image, 0777);
                        unlink($dir . 'thumb_' . $model->image);
                    }
                }
                $model->image = '';
                $model->save(false);
                return true;
            }
            else
                return false;
        }
        else
            return false;
    }

    public function actionViewCustomer(){
        if (isset($_GET['id'])) {
            $model = MMCustomer::model()->findByPk($_GET['id']);
            if ($model === null) {
                throw new CHttpException(404, 'The requested page does not exist.');
                return $model;
            }

            if (isset($_POST['MMCustomer'])) {
                $model->setAttributes($_POST['MMCustomer']);
                if (isset($_POST['ajax']) && isset($_POST['ajax_validation'])) {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
                 }
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                    if (isset($_POST['apply']) && $_POST['apply'] == 'true') {
                        $url = Yii::app()->createUrl('admin/viewcustomer',array('id'=>$model->id));
                        $this->redirect($url);
                    } else {
                        Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                        $url = Yii::app()->createUrl('admin/customers');
                        $this->redirect($url);
                    }
                    return;
                }
            }
            $this->render('editcustomer', array('model' => $model));
        }
    }

    public function actionUpdateAddress(){
        if (isset($_GET['id'])) {
            $model = MMAddress::model()->findByPk($_GET['id']);
            if ($model === null) {
                throw new CHttpException(404, 'The requested page does not exist.');
                return $model;
            }

            if (isset($_POST['MMAddress'])) {
                if (isset($_POST['ajax']) && isset($_POST['ajax_validation'])) {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
                 }
                $model->setAttributes($_POST['MMAddress']);

                if ($model->save()) {
                    Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                    if (isset($_POST['apply']) && $_POST['apply'] == 'true') {
                        $url = Yii::app()->createUrl('admin/updateaddress',array('id'=>$model->id));
                        $this->redirect($url);
                    } else {
                        Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                        $url = Yii::app()->createUrl('admin/viewcustomer',array('id'=>$model->customer_id));
                        $this->redirect($url);
                    }
                    return;
                }
            }
            $this->render('editaddress', array('model' => $model));
        }
    }

    public function actionDeleteAddress(){
        if (isset($_GET['id'])) {
            $model = MMAddress::model()->findByPk($_GET['id']);
            if ($model === null) {
                throw new CHttpException(404, 'The requested page does not exist.');
                return $model;
            }
            $customer=$model->customer_id;
            $model->delete();
            $url = Yii::app()->createUrl('admin/viewcustomer',array('id'=>$customer));
            $this->redirect($url);
        }
    }

    protected function performAjaxValidation($models) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($models);
            Yii::app()->end();
        }
    }

    public function actionDeleteThemeSetting() {
        if (Yii::app()->request->isAjaxRequest) {

            if (isset($_GET['id'])) {
                $dir = realpath(Yii::app()->getBasePath() . "/../../themes/" . $_GET['id'] . "/");
                Helpers::rmdirr($dir);
                return true;
            }
            else
                return false;
        }
        else
            return false;
    }

    public function actionSettings() {
        $model = new MMSettingsForm();
        $worktime = unserialize(MMSettingsForm::getParam('work_time'));
        if (!empty($_POST['MMSettingsForm']) && $_POST['MMSettingsForm']['file']) {
            $model->file = $_POST['MMSettingsForm']['file'];
            $dir = realpath(Yii::app()->getBasePath() . "/../images/uploads/temp") . "/";
            $dst = realpath(Yii::app()->getBasePath() . "/../../themes/");
            if (($file = CUploadedFile::getInstance($model, 'file')) != NULL) {
                if ($file->getExtensionName() != 'zip') {
                    throw new CHttpException(500, 'Theme file must be a valid Zip archive.');
                    return $model;
                }
                $file->saveAs($dir . $file->getName());
                chmod($dir . $file->getName(), 0777);
                $archive = new PclZip($dir . $file->getName());
                if ($archive != NULL) {
                    $name = basename($file->getName(), '.zip');
                    $list = $archive->extract(PCLZIP_OPT_PATH, $dst, PCLZIP_OPT_SET_CHMOD, 0777);
                    if ($list == 0) {
                        die("Unrecoverable error, code " . $archive->errorCode());
                    }
                    Helpers::rmdirr($dir, $dir);
                } else {
                    throw new CHttpException(500, 'Zip archives are not supported by the hoster, please extract manualy');
                    return $model;
                }
            }
        }
        $settings = Yii::app()->db->createCommand()
                ->select('name, value')
                ->from(MMSettingsForm::getTableNames('settings'))
                ->where("name IN('" . implode("','", array_keys($model->attributes)) . "')")
                ->queryAll();
        foreach ($settings as $setting) {
            $model->{$setting['name']} = $setting['value'];
        }

        $this->render('settings', array(
            'model' => $model,
            'ziplist' => empty($settings['zip_list']) ? null : unserialize($settings['zip_list']),
            'worktime'=> $worktime,
        ));
    }

    public function actionResetPassword(){
        if (Yii::app()->request->isPostRequest) {
            $username = $_POST['reset_pass'];
            $user = MMCustomer::model()->findByPk($username);
            if ($user != NULL) {
               $newpass = Helpers::randomPassword();
               $user->password = $newpass;
               if ($user->save()) {
                  YiiBase::import('ext.YiiMail.YiiMailMessage');
                  $location = MMLocation::model()->findByPk(Helpers::getFirstLocation());
                  $shopemail = $location->l_email;
                  if(empty($shopemail)) $shopemail='noemail@noemail.mail';
                  $shopname = $location->name;
                  if(empty($shopname)) $shopname='Unknown shop';
                  $message = new YiiMailMessage;
                  $message->setBody('<b>Good Day</b><br>You have requested password reset from our service. Your new password is: <br><b>' . $newpass . '</b><br>Thank you for using our service!'.Helpers::shopDetails($location), 'text/html', 'utf-8');
                  $message->addTo($user->c_mail);
                  $message->setFrom(array($shopemail => $shopname));
                  $message->setReplyTo(array($shopemail => $shopname));
                  $message->setSubject("Your new password");
                  Yii::app()->mail->send($message);
                  echo 'succsess';
                      }
                  }
            Yii::app()->end();
            }
            else
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionResendBill(){
        if (Yii::app()->request->isPostRequest) {
            $id = $_POST['resend_bill'];
            $model = MMOrder::model()->findByPk($id);
            if ($model != NULL) {
                Helpers::sendOrderToEmail($model, $model->customer->c_mail,$model->location,false);
                echo 'succsess';
            }
            Yii::app()->end();
            }
            else
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionSaveSettings(){
        if (isset($_POST['MMSettingsForm'])) {
                $settings = new MMSettingsForm();
                $settingsvalues = Yii::app()->db->createCommand()
                        ->select('name, value')
                        ->from(MMSettingsForm::getTableNames('settings'))
                        ->where("name IN('" . implode("','", array_keys($settings->attributes)) . "')")
                        ->queryAll();

                foreach ($settingsvalues as $setting) {
                    $settings->{$setting['name']} = $setting['value'];
                }
                foreach ($settings->attributes as $name => $value) {
                    if ($name == 'themes' || $name == 'zip_list' || $name == 'inner_texture' || $name == 'body_texture' || $name == 'logo_image' || $name== 'admin_hash'
                            || $name == 'admin_salt' || $name =='admin_login' || $name == 'admin_email' || $name == 'dbversion'
                            )
                        continue;
                    if ($name == 'work_time') {
                        $settings->work_time = serialize($_POST['MMSettingsForm']['work_time']);
                        continue;
                    }
                    if($name == 'license_key'){
                        $settings->license_key = trim($_POST['MMSettingsForm']['license_key']);
                        continue;
                    }
                    $settings->$name = is_array($_POST['MMSettingsForm'][$name]) ? $_POST['MMSettingsForm'][$name][0] : $_POST['MMSettingsForm'][$name];
                }
                $settings->save();
                Yii::app()->end();
            }
        elseif (isset($_POST['refresh_key'])) {
                echo $this->_res;
                if (substr($this->_res, 3, 6) !== 'Active')
                    echo '<a href="http://wpmenumaker.com/pricing" target="_blank">Purchase WPMenuMaker</a>';
                Yii::app()->end();
        }
    }

    public function actionAddZipcodeLoc(){
                $zipcode = $_POST['addzipcode'];
                $ziplist = $_POST['ziplist'];
                if (Helpers::zipcodevalidate($zipcode)) {
                    $ziplisttemp = $ziplist;
                    $ziplist[] = $zipcode;
                    $ziplist = array_unique($ziplist);
                    if ($ziplist != $ziplisttemp) {
                        sort($ziplist, SORT_NUMERIC);
                    }
                } else {
                    $errors['zipcode'][0] = 'Incorrect ZIP code format';
                }
                $this->renderPartial(
                        '//admin/_ziptableloc', array(
                    'ziplist' => $ziplist,
                    'error' => $errors,
                ));
       Yii::app()->end();
    }

    /*public function actionAddZipcode(){
        $settings = new MMSettingsForm('addzipcode');
                $settings->zipcode = $_POST['addzipcode'];
                $ziplist = unserialize(MMSettingsForm::getParam('zip_list'));
                if ($settings->validate(array('zipcode'))) {
                    $ziplisttemp = $ziplist;
                    $ziplist[] = $settings->zipcode;
                    $ziplist = array_unique($ziplist);
                    if ($ziplist != $ziplisttemp) {
                        sort($ziplist, SORT_NUMERIC);
                        $settings->zip_list = serialize($ziplist);
                        if (!$settings->save('zip_list'))
                            exit('ZIP code saving error');
                    }
                }
                $this->renderPartial(
                        '//admin/_ziptable', array(
                    'ziplist' => $ziplist,
                    'error' => $settings->errors,
                ));
       Yii::app()->end();
    }

    public function actionRemoveZipcode(){
        $settings = new MMSettingsForm();
        $ziplist = unserialize(MMSettingsForm::getParam('zip_list'));
        $ziplist = array_diff($ziplist, array((int) $_POST['removezipcode']));
        $settings->zip_list = serialize($ziplist);
        if ($settings->save('zip_list')) {
            $this->renderPartial('//admin/_ziptable', array(
                        'ziplist' => $ziplist,
                    ));
            Yii::app()->end();
       } else {
          exit('ZIP code saving error');
       }
       Yii::app()->end();
    }*/

    public function actionUploadPictureTexture(){
            $dir = realpath(Yii::app()->getBasePath() . "/../images/uploads") . "/";
            $valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
            if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
            {
                $name = $_FILES['texture_body']['name'];
                $size = $_FILES['texture_body']['size'];
                if(strlen($name))
                {
                    list($txt, $ext) = explode(".", $name);
                    if(in_array($ext,$valid_formats))
                    {
                        if($size<(1024*1024)) // Image size max 1 MB
                        {
                            $actual_image_name = "bodytexture.".$ext;
                            $tmp = $_FILES['texture_body']['tmp_name'];
                            if(move_uploaded_file($tmp, $dir.$actual_image_name))
                            {
                                chmod($dir.$actual_image_name, 0777);
                                $settings = new MMSettingsForm();
                                $settings->body_texture=$actual_image_name;
                                if ($settings->save('body_texture')) {
                                    echo "<img src='".Yii::app()->baseUrl . "/" .MM_UPLOADS_URL .'/'.$actual_image_name."' class='mm_thumb'>";
                                } else echo 'Error!';
                            }
                            else
                            echo "failed";
                        }
                        else
                        echo "Image file size max 1 MB";
                    }
                    else
                    echo "Invalid file format..";
                }
                else
                    echo "Please select image..!";
                Yii::app()->end();
            }
            Yii::app()->end();
    }

    public function actionUploadPictureTextureIn(){
            $dir = realpath(Yii::app()->getBasePath() . "/../images/uploads") . "/";
            $valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
            if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
            {
                $name = $_FILES['inner_texture']['name'];
                $size = $_FILES['inner_texture']['size'];
                if(strlen($name))
                {
                    list($txt, $ext) = explode(".", $name);
                    if(in_array($ext,$valid_formats))
                    {
                        if($size<(1024*1024)) // Image size max 1 MB
                        {
                            $actual_image_name = "inner_texture.".$ext;
                            $tmp = $_FILES['inner_texture']['tmp_name'];
                            if(move_uploaded_file($tmp, $dir.$actual_image_name))
                            {
                                chmod($dir.$actual_image_name, 0777);
                                $settings = new MMSettingsForm();
                                $settings->inner_texture=$actual_image_name;
                                if ($settings->save('inner_texture')) {
                                    echo "<img src='".Yii::app()->baseUrl . "/" .MM_UPLOADS_URL .'/'.$actual_image_name."' class='mm_thumb'>";
                                } else echo 'Error!';
                            }
                            else
                            echo "failed";
                        }
                        else
                        echo "Image file size max 1 MB";
                    }
                    else
                    echo "Invalid file format..";
                }
                else
                    echo "Please select image..!";
                Yii::app()->end();
            }
            Yii::app()->end();
    }

    public function actionUploadPictureLogo(){
            $dir = realpath(Yii::app()->getBasePath() . "/../images/uploads") . "/";
            $valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
            if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
            {
                $name = $_FILES['logo_image']['name'];
                $size = $_FILES['logo_image']['size'];
                if(strlen($name))
                {
                    list($txt, $ext) = explode(".", $name);
                    if(in_array($ext,$valid_formats))
                    {
                        if($size<(1024*1024)) // Image size max 1 MB
                        {
                            $actual_image_name = "logo_image.".$ext;
                            $tmp = $_FILES['logo_image']['tmp_name'];
                            if(move_uploaded_file($tmp, $dir.$actual_image_name))
                            {
                                chmod($dir.$actual_image_name, 0777);
                                $settings = new MMSettingsForm();
                                $settings->logo_image=$actual_image_name;
                                if ($settings->save('logo_image')) {
                                    echo "<img src='".Yii::app()->baseUrl . "/" .MM_UPLOADS_URL .'/'.$actual_image_name."' class='mm_thumb'>";
                                } else echo 'Error!';
                            }
                            else
                            echo "failed";
                        }
                        else
                        echo "Image file size max 1 MB";
                    }
                    else
                    echo "Invalid file format..";
                }
                else
                    echo "Please select image..!";
                Yii::app()->end();
            }
            Yii::app()->end();
    }

    public function actionAccess(){
        $model = new MMSettingsForm();
        $settingsvalues = Yii::app()->db->createCommand()
                ->select('name, value')
                ->from(MMSettingsForm::getTableNames('settings'))
                ->where("name IN('" . implode("','", array_keys($model->attributes)) . "')")
                ->queryAll();
        foreach ($settingsvalues as $setting) {
            if ($setting['name'] == 'admin_hash' || $setting['name'] == 'admin_salt')
                continue;
            $model->{$setting['name']} = $setting['value'];
        }
        if (isset($_POST['MMSettingsForm'])) {
            if (!empty($_POST['MMSettingsForm']['admin_login']))
                $model->admin_login = strip_tags(trim($_POST['MMSettingsForm']['admin_login']));
            if (!empty($_POST['MMSettingsForm']['admin_email']))
                $model->admin_email = strip_tags(trim($_POST['MMSettingsForm']['admin_email']));
            if ($model->save('admin_login') && $model->save('admin_email')) {
                Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved Login and Email.');
            }
            if (!empty($_POST['MMSettingsForm']['admin_hash']) && !empty($_POST['MMSettingsForm']['admin_salt'])) {
                if ($_POST['MMSettingsForm']['admin_hash'] == $_POST['MMSettingsForm']['admin_salt']) {
                    $model->admin_salt = '$1$' . substr(sha1(uniqid()), 0, 10) . '$';
                    $model->admin_hash = crypt($_POST['MMSettingsForm']['admin_hash'], $model->admin_salt);
                    if ($model->save('admin_salt') && $model->save('admin_hash')) {
                        Yii::app()->user->setFlash('success', '<strong>Data Saved!</strong> Successfully saved entered data.');
                    } else {
                        Yii::app()->user->setFlash('error', '<strong>Error!</strong> Please try again.');
                    }
                } else {
                    Yii::app()->user->setFlash('error', '<strong>Error!</strong> Password fields do not match');
                }
            }
        }
        $model->admin_hash = '';
        $model->admin_salt = '';
        $this->render('access', array('model' => $model));
    }

    public function actionResetPasswordAdmin(){
        if (Yii::app()->request->isPostRequest) {
            $user = MMSettingsForm::getParam('admin_email');
            if (!empty($user)) {
               $newpass = Helpers::randomPassword();
               $model = new MMSettingsForm();
               $model->admin_salt = '$1$'.substr(sha1(uniqid()),0,10).'$';
               $model->admin_hash = crypt($newpass, $model->admin_salt);
               if ($model->save('admin_hash') && $model->save('admin_salt')) {
                  YiiBase::import('ext.YiiMail.YiiMailMessage');
                  $location = MMLocation::model()->findByPk(Helpers::getFirstLocation());
                  $shopemail = $location->l_email;
                  if(empty($shopemail)) $shopemail='noemail@noemail.mail';
                  $shopname = $location->name;
                  if(empty($shopname)) $shopname='Unknown shop';
                  $message = new YiiMailMessage;
                  $message->setBody('<b>Good Day</b><br>You have requested password reset for administrator account. Your new password is: <br><b>' . $newpass . '</b><br>'.Helpers::shopDetails(), 'text/html', 'utf-8');
                  $message->addTo($user);
                  $message->setFrom(array($shopemail => $shopname));
                  $message->setReplyTo(array($shopemail => $shopname));
                  $message->setSubject("Password reset");
                  Yii::app()->mail->send($message);
                  echo 'succsess';
                      }
                  }
            Yii::app()->end();
            }
            else
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
        $this->redirect(array('//admin'));
    }

    public function actionLogout() {
        if (!Yii::app()->user->isGuest) {
            Yii::app()->user->logout();
        }
        $this->redirect(array('//admin'));
    }


}
