<?php
$this->breadcrumbs=array(
	'File Infos'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List FileInfo', 'url'=>array('index')),
	array('label'=>'Create FileInfo', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('file-info-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage File Infos</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'file-info-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'org_file_path',
		'temp_file_path',
		'file_type_id',
		'checksum_created',
		'checksum',
		/*
		'virus_check',
		'dynamic_file',
		'last_modified',
		'problem_file',
		'user_id',
		'upload_file_id',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>