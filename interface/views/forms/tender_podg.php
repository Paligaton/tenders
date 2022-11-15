<form method="POST">
	<input type="hidden" name="action" value="save2">
	
	<div class="form-group">
		<label>Дата окончания подачи заявок на участие</label>
		<?php

        use app\plugins_code\tenders\models\Statuses;

        if ($params['tender']['deadline_for_filing_an_application_for_participation'] == 0)
			{
				$deadline_for_filing_an_application_for_participation = '';
			}
			else
			{
				$deadline_for_filing_an_application_for_participation = date('d.m.Y H:i',$params['tender']['deadline_for_filing_an_application_for_participation']);
			}
		?>
		<input type="text" name="deadline_for_filing_an_application_for_participation" class="form-control dateTimePicker" value="<?=$deadline_for_filing_an_application_for_participation?>">
	</div>	
	
	<div class="form-group">
		<label>Дата проведения тендера</label>
		<?php
			if ($params['tender']['date_start'] == 0)
			{
				$date_start = '';
			}
			else
			{
				$date_start = date('d.m.Y H:i', $params['tender']['date_start']);
			}
		?>
		<input type="text" name="date_start" class="form-control dateTimePicker" value="<?=$date_start?>" required >
	</div>	
	<div class="form-group">
		<div class="row">
			<div class="col-md-6">
				<label>Тендерная площадка</label>
				<select name="platform_id" class="form-control chosen">
					<?php foreach ($params['platforms'] as $platform) { ?>
						<option value="<?=$platform['id']?>" <?=($params['tender']['platform_id'] == $platform['id'] ? 'selected' : '')?>><?=$platform['name']?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-6">
				<label>Или введите название новой платформы</label>
				<input type="text" name="platform_string" class="form-control">
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label>Начальная сумма проведения тендера</label>
		<input type="number" step="0.01" name="tender_summ" class="form-control" value="<?=$params['tender']['tender_summ']?>">
	</div>	
	<div class="form-group">
		<label>Сумма участия / минимальная сумма участия</label>
		<input type="number" step="0.01" name="tender_summ_min" class="form-control" value="<?=$params['tender']['tender_summ_min']?>">
	</div>	

	<div class="form-group">
		<label>Заказчик</label>
		<input type="text" name="organization_name" class="form-control" value="<?=htmlspecialchars($params['tender']['organization_name'])?>">
	</div>
    <div class="form-group">
        <label>Выберите направление</label>
        <select name="direction_id" class="form-control chosen">
            <?php foreach ($params['directions'] as $direction) { ?>
                <option value="<?=$direction['id']?>" <?php echo ($direction['id'] == $params['tender']['direction_id']) ? 'selected' : ''; ?>>
                    <?=$direction['name']?>
                </option>
            <?php } ?>
        </select>
    </div>
	<div class="form-group">
		<label>Ссылка на тендер</label>
		<input type="text" name="platform_link" class="form-control" value="<?=$params['tender']['platform_link']?>">
	</div>
        <?php if ($params['tender']['tender_status']> 4) {
			if($params['tender']['date_contract']==0)
			{$date_contract = time();}else
			{
			$date_contract = date('d.m.Y H:i', $params['tender']['date_contract']);
			}?>


    <div class="form-group">
				<label>Дата заключения договра</label>
				<input type="text" name="date_contract" class="form-control dateTimePicker" value="<?=$date_contract?>">
			</div>
			
			<?php } ?>

        <?php	if (Yii::$app->loadAccess->access(['plugins', 'tenders', 'edit_status'], 'bool'))
        {

            ?>
            <div class="form-group">
                <label>Статус</label>
                <select name="tender_status" class="form-control chosen">
                    <?php
                    $stats=new Statuses();
                    foreach ($stats->statuses as $key=>$row) { ?>
                        <option value="<?=$key?>" <?php echo ($key == $params['tender']['tender_status']) ? 'selected' : ''; ?>><?=$row['name']?></option>
                    <?php } ?>
                </select>
            </div>
            <?php

        }?>

	
	<div class="form-group">
		<input type="submit" class="btn btn-success btn-sm" value="Изменить">
	</div>
</form>