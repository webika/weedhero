<?php $this->pageTitle = Yii::app()->name . ' - Error' ?>
<h2>Error <?php echo $code; ?></h2>
<?php echo $code == 404 ? 'Requested page not found!' : CHtml::encode($message) ?>