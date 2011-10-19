<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	//array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<?php
if( !Yii::app()->user->isGuest )
{
	header("location: /");
}
?>

<h1>Register on ENL</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>