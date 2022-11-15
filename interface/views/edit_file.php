<?php
	$this->title = 'Редактирование файла';
	$this->params['breadcrumbs'][] = ['label' => 'Тендерная система', 'url' => '/plugins?plug_name=tenders&action=index'];
	$this->params['breadcrumbs'][] = ['label' => 'Тендер № '.$params['tender']->id, 'url' => '/plugins?plug_name=tenders&action=view&id='.$params['tender']->id];
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
	<div class="card-header ch-alt">
		<h2><?=$this->title?></h2>
	</div>
	<div class="card-body card-padding">
		<form method="POST" enctype="multipart/form-data">
			<input type="hidden" name="action" value="update">
			<div class="form-group">
				<label>Выберите файл</label>
				<input type="file" name="file" class="form-control">
				<p style="margin-top:10px;"><a href="<?=$params['file']['file']?>" target="_blank">Текущий файл</a></p>
			</div>
			<div class="form-group">
				<label>Выберите тип файла</label>
				<select class="form-control" name="type">
					<?php foreach ($params['TypeFiles']->names as $type_id => $type_name) { ?>
						<option value="<?=$type_id?>" <?=($params['file']['type'] == $type_id ? 'selected' : '')?>>
							<?=$type_name?>
						</option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-sm btn-success" value="Изменить">
			</div>
		</form>
	</div>
</div>