<?php

	app\plugins_code\tenders\assets\ChosenAsset::register($this);
	app\plugins_code\tenders\assets\DateTimePickerAsset::register($this);

	$this->title = 'Редактирование тендера';
	$this->params['breadcrumbs'][] = ['label' => 'Тендерная система', 'url' => '/plugins?plug_name=tenders&action=index'];
	$this->params['breadcrumbs'][] = ['label' => 'Тендер № '.$params['tender']['id'], 'url' => '/plugins?plug_name=tenders&action=view&id='.$params['tender']['id']];
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
	<div class="card-header ch-alt">
		<h2><?=$this->title?></h2>
	</div>
	<div class="card-body card-padding">
		<?php if (in_array($params['tender']['tender_status'], [0])) { ?>
			<?php echo $this->render('../../../../plugins_code/tenders/interface/views/forms/tender_start', [
				'params' => $params,
			]); ?>
		<?php } else { ?>
			<?php echo $this->render('../../../../plugins_code/tenders/interface/views/forms/tender_podg', [
				'params' => $params,
			]); ?>					
		<?php }  ?>		
	</div>
</div>