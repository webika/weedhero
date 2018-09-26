<?php

class MMUploadForm extends CFormModel {

    public $file;
    public $textfile;

    public function rules() {
        return array(
            array('file', 'file', 'allowEmpty'=>true),
        );
    }

}

?>