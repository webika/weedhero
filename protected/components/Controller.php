<?php
class Controller extends CController {

    protected $_res;

    public function init() {
        //eval(base64_decode('JHRyYW5zID0gWWlpOjphcHAoKS0+Y2FjaGUtPmdldCgnbW0nKTsNCiRrZXkgPSBNTVNldHRpbmdzRm9ybTo6Z2V0UGFyYW0oJ2xpY2Vuc2Vfa2V5Jyk7DQppZiAoKCR0aGlzLT5sYXlvdXQgPT09ICdmcm9udCcgJiYgKGVtcHR5KCR0cmFucykgfHwgJHRyYW5zWydrZXknXSAhPT0gJGtleSkpIHx8DQoJKCR0aGlzLT5sYXlvdXQgPT09ICdhZG1pbicgJiYgaXNzZXQoJF9QT1NUWydNTVNldHRpbmdzRm9ybSddKSAmJiAoZW1wdHkoJHRyYW5zKSB8fCAkdHJhbnNbJ2tleSddICE9PSAka2V5KSkpIHsNCgkkaGFuZGxlID0gY3VybF9pbml0KCdodHRwOi8vd3BtZW51bWFrZXIuY29tL2NvbnRyb2wvc3RhdHVzL2NoZWNrJyk7DQoJY3VybF9zZXRvcHQoJGhhbmRsZSwgQ1VSTE9QVF9DT05ORUNUVElNRU9VVCwgMzApOw0KCWN1cmxfc2V0b3B0KCRoYW5kbGUsIENVUkxPUFRfUkVUVVJOVFJBTlNGRVIsIHRydWUpOw0KCWN1cmxfc2V0b3B0KCRoYW5kbGUsIENVUkxPUFRfU1NMX1ZFUklGWVBFRVIsIGZhbHNlKTsNCgljdXJsX3NldG9wdCgkaGFuZGxlLCBDVVJMT1BUX1BPU1QsIHRydWUpOw0KCWN1cmxfc2V0b3B0KCRoYW5kbGUsIENVUkxPUFRfUE9TVEZJRUxEUywgYXJyYXkoDQoJCSdrZXknID0+IGlzc2V0KCRfUE9TVFsnTU1TZXR0aW5nc0Zvcm0nXSkgPyAkX1BPU1RbJ01NU2V0dGluZ3NGb3JtJ11bJ2xpY2Vuc2Vfa2V5J10gOiAka2V5LA0KCQknZG9tYWluJyA9PiAkX1NFUlZFUlsnU0VSVkVSX05BTUUnXSwNCgkpKTsNCgkkdGhpcy0+X3JlcyA9IENKU09OOjpkZWNvZGUoY3VybF9leGVjKCRoYW5kbGUpKTsNCgljdXJsX2Nsb3NlKCRoYW5kbGUpOw0KCWlmICghZW1wdHkoJHRoaXMtPl9yZXNbJ3RpbWUnXSkgJiYgZW1wdHkoJHRoaXMtPl9yZXNbJ2Vycm9ycyddKSkgew0KCQkkZGlmZiA9IChpbnQpJHRoaXMtPl9yZXNbJ3RpbWUnXSAtIHRpbWUoKTsNCgkJaWYgKCRkaWZmID4gMCkgew0KCQkJWWlpOjphcHAoKS0+Y2FjaGUtPnNldCgnbW0nLCBhcnJheSgndGltZScgPT4gJHRoaXMtPl9yZXNbJ3RpbWUnXSwgJ2tleScgPT4gJGtleSksICRkaWZmKyg2MCo2MCoyNCkpOw0KCQkJJHRyYW5zID0gWWlpOjphcHAoKS0+Y2FjaGUtPmdldCgnbW0nKTsNCgkJfQ0KCX0gZWxzZWlmIChjb3VudCgkdGhpcy0+X3Jlc1snZXJyb3JzJ10pKSB7DQoJCVlpaTo6YXBwKCktPmNhY2hlLT5kZWxldGUoJ21tJyk7DQoJfQ0KfQ0KaWYgKCFlbXB0eSgkdHJhbnNbJ3RpbWUnXSkgJiYgJHRyYW5zWydrZXknXSA9PSAka2V5KSB7DQoJJHRoaXMtPl9yZXMgPSAnPHA+QWN0aXZlIHVudGlsOiA8c3Ryb25nPicuZGF0ZSgnaiBGIFknLCAkdHJhbnNbJ3RpbWUnXSkuJzwvc3Ryb25nPjwvcD4nOw0KfQ0KZWxzZWlmICghZW1wdHkoJHRyYW5zWydrZXknXSkgJiYgJHRyYW5zWydrZXknXSAhPSAka2V5KSB7DQoJJHRoaXMtPl9yZXMgPSAnPHAgY2xhc3M9ImVycm9yIj48c3Ryb25nPldyb25nIGxpY2Vuc2Uga2V5PC9zdHJvbmc+PC9wPic7DQp9DQplbHNlaWYgKCR0aGlzLT5fcmVzWydlcnJvcnMnXSkgew0KCSRlcnJvcnMgPSAkdGhpcy0+X3Jlc1snZXJyb3JzJ107DQoJJHRoaXMtPl9yZXMgPSAnJzsNCglmb3JlYWNoICgkZXJyb3JzIGFzICRlcnJvcikgew0KCQkkdGhpcy0+X3JlcyAuPSAnPHAgY2xhc3M9ImVycm9yIj48c3Ryb25nPicuJGVycm9yLic8L3N0cm9uZz48L3A+JzsNCgl9DQp9DQplbHNlIHsNCgkkdGhpcy0+X3JlcyA9ICc8cCBjbGFzcz0iZXJyb3IiPjxzdHJvbmc+V3JvbmcgbGljZW5zZSBrZXk8L3N0cm9uZz48L3A+JzsNCn0='));

        parent::init();
        Yii::app()->session->open();
        $order = Yii::app()->session['mm_order'];
        $location = MMLocation::model()->findByPk($order['location']);
        if ($location == NULL) {
            $location = MMLocation::model()->findByPk(Helpers::getFirstLocation());
            if ($location == NULL) {
                //throw new CHttpException(503, Yii::t('_', 'There is no locations added for these menus'));
                //return;
            }
        }
        date_default_timezone_set($location->timezone);
        $curversion = '1.1';
        $dbversion = MMSettingsForm::getParam('dbversion');
        if (!$dbversion) {
            $command = Yii::app()->db->createCommand();
            $command->insert(MMSettingsForm::getTableNames('settings'), array('name' => 'dbversion', 'value' => '1.2'));
        }
        $dbversion = MMSettingsForm::getParam('dbversion');
        if ($dbversion != $curversion) {
            switch ($dbversion) {
                case '0.9':
                    $command = Yii::app()->db->createCommand();
                    $command->insert(MMSettingsForm::getTableNames('settings'), array('name' => 'enable_payments_paypal', 'value' => '0'));
                    $command->insert(MMSettingsForm::getTableNames('settings'), array('name' => 'apiSignature', 'value' => ''));
                    $command->insert(MMSettingsForm::getTableNames('settings'), array('name' => 'apiPassword', 'value' => ''));
                    $command->insert(MMSettingsForm::getTableNames('settings'), array('name' => 'apiUsername', 'value' => ''));
                    $command->insert(MMSettingsForm::getTableNames('settings'), array('name' => 'enable_delivery', 'value' => '1'));
                    $command->alterColumn(MMSettingsForm::getTableNames('orders'), 'promo_code', 'TEXT NOT NULL');
                    $command->addColumn(MMSettingsForm::getTableNames('order_items'), 'itemid', 'INT UNSIGNED NOT NULL');
                    if (Yii::app()->db->schema->getTable(MMSettingsForm::getTableNames('coupons')) === null)
                        {
                            $command->createTable(MMSettingsForm::getTableNames('coupons'), array(
                                'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                                'name' => 'VARCHAR(255) NOT NULL',
                                'code' => 'VARCHAR(255) NOT NULL',
                                'amount' => "DECIMAL(10,2) NOT NULL DEFAULT '0'",
                                'type' => "TINYINT UNSIGNED NOT NULL DEFAULT '0'",
                                'cfrom' => 'TIMESTAMP NOT NULL',
                                'cto' => 'TIMESTAMP NOT NULL',
                                'active' => "TINYINT UNSIGNED NOT NULL DEFAULT '0'",
                            ), 'ENGINE=INNODB CHARACTER SET ' . MM_DB_CHARSET . ' COLLATE ' . MM_DB_COLLATE);
                        }
                    $command->update(MMSettingsForm::getTableNames('settings'), array('value' => '1.0'), 'name=:name', array(':name' => 'dbversion'));
                break;
                case '1.0':
                    $command = Yii::app()->db->createCommand();
                    $command->insert(MMSettingsForm::getTableNames('settings'), array('name' => 'item_mode', 'value' => '0'));
                    $command->insert(MMSettingsForm::getTableNames('settings'), array('name' => 'enable_social', 'value' => '1'));
                    $command->update(MMSettingsForm::getTableNames('settings'), array('value' => '1.1'), 'name=:name', array(':name' => 'dbversion'));
                break;
                case '1.1':
                    $command = Yii::app()->db->createCommand();
                    $command->addColumn(MMSettingsForm::getTableNames('orders'), 'total_sum', 'DECIMAL(10,2) NOT NULL');
                    $command->update(MMSettingsForm::getTableNames('settings'), array('value' => '1.2'), 'name=:name', array(':name' => 'dbversion'));
                break;
            }
        }
    }
}