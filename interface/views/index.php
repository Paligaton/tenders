<?php
	$this->title = 'Тендерная система';
	$this->params['breadcrumbs'][] = ['label' => $this->title];
	use kartik\date\DatePicker;
	app\plugins_code\sales_department\assets\DateRangePickerAsset::register($this);
?>

<div class="card">
	<div class="card-header ch-alt">
		<div class="row">
			<div class="col-md-8">
				<h2><?=$this->title?></h2>
			</div>
			<div class="col-md-4" style="text-align: right;">
                <form method="POST" action="/plugins?plug_name=tenders&action=excel">
                <?php if (Yii::$app->loadAccess->access(['plugins', 'tenders', 'excel'], 'bool')) { ?>

                    От: <input type="date" id="date" name="date_begin"/> До: <input type="date" id="date" name="date_end"/> <input type="submit" class="btn btn-success btn-sm" value="Excel">

                <?php } ?>
                <?php if (Yii::$app->loadAccess->access(['plugins', 'tenders', 'create_tenders'], 'bool')) { ?>
					<a href="/plugins?plug_name=tenders&action=create" class="btn btn-success btn-sm">+</a>
				<?php } ?>
                </form>
			</div>
		</div>
	</div>
	<div class="card-body card-padding">
		<form method="GET">
			<input type="hidden" name="plug_name" value="tenders">
			<input type="hidden" name="action" value="index">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th style='width: 50px; max-width: 50px;'>
							#
						</th>
						<th style='width: 105px; max-width: 105px;'>
							Площадка
						</th>
						<th style='width: 220px; max-width: 220px;'>
							Организация
						</th>
						<th style='width: 145px; max-width: 145px;'>
							Направление
						</th>
						<th style="width: 125px; max-width: 125px;">
							Дата окончания подачи на участие
						</th>						
						<th style="width: 125px; max-width: 125px;">
							Дата проведения тендера
						</th>
						<th style="width: 100px; max-width: 100px;">
							Сумма участия
						</th>
						<th style='width: 125px; max-width: 125px;'>
							Статус
						</th>	
						<th style="width:200px; max-width: 200px;">
							Пользователи
						</th>					
					</tr>
					<tr>
						<th style="font-weight: normal; width: 50px; max-width: 50px;"></th>
						<th style="font-weight: normal; width: 100px; max-width: 100px; vertical-align: middle;">
							<select name="filter[platform_id]" class="form-control">
								<option></option>
								<?php foreach ($params['platforms'] as $platform) { ?>
									<?php
										$selected = false;
										if ($params['filter']['platform_id'] != '')
										{
											if ($platform['id'] == $params['filter']['platform_id'])
											{
												$selected = true;
											}
										}
									?>	
									<option value="<?=$platform['id']?>" <?=($selected ? 'selected' : '')?>>
										<?=$platform['name']?>
									</option>									
								<?php } ?>
							</select>
						</th>
						<th style="font-weight:normal; 'width: 220px; max-width: 220px;     text-align: center;">						
							<input style='width: 80%;'type="text" class="form-control" name="filter[client]" value="<?=htmlspecialchars($params['filter']['client'])?>"> 

						</th>
						<th style="font-weight: normal; width: 145px; max-width: 145px; vertical-align: middle;">
							<select name="filter[direction_id]" class="form-control">
								<option></option>
								<?php foreach ($params['directions'] as $direction) { ?>
									<?php
										$selected = false;
										if ($params['filter']['direction_id'] != '')
										{
											if ($direction['id'] == $params['filter']['direction_id'])
											{
												$selected = true;
											}
										}
									?>	
									<option value="<?=$direction['id']?>" <?=($selected ? 'selected' : '')?>>
										<?=$direction['name']?>
									</option>									
								<?php } ?>
							</select>						
						</th>					
						<th style="font-weight: normal; width: 125px; max-width: 125px;"  >
						<input type="text" name="filter[deadline_range]" value="<?=$params['filter']['deadline_range']?>" class="form-control datepicker"></th>
						<th style="font-weight: normal; width: 125px; max-width: 125px"><input type="text" name="filter[date_start_range]" value="<?=$params['filter']['date_start_range']?>" class="form-control datepicker"></th>
						<th style="font-weight: normal; width: 100px; max-width: 100px"></th>
						<th style="font-weight: normal; width: 170px; max-width: 170px; vertical-align: middle;">
							<select name="filter[status_id]" class="form-control">
								<option value=""></option>
								<?php foreach ($params['Statuses']->statuses as $status_id => $status) { ?>
									<?php
										
										$selected = false;
										if ($params['filter']['status_id'] != '')
										{
											if ($status_id == $params['filter']['status_id'])
											{
												$selected = true;
											}
										}
									?>
									<option value="<?=$status_id?>" <?=($selected ? 'selected' : '')?>>
										
										<?=$status['name']?>
									</option>
								<?php } ?>
							</select>
						</th>	
						<th style="font-weight: normal; width: 200px; max-width: 200px; vertical-align: middle;">
							<input type="submit" class="btn btn-sm btn-primary" value="Найти">
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = (($params['page']*$params['count_in_page'])-$params['count_in_page'])+1;
					?>
					<?php foreach ($params['tenders'] as $tender) { ?>
						<?php
							$td_style = 'color:'.$tender->getStatus()['color'].';background:'.$tender->getStatus()['bg'].';word-break: break-all';
						?>
						<tr>
							<td style="<?=$td_style?>">
								<?=$tender->id?>
							</td>
							<td style="<?=$td_style?>;">
                                <span>
                                <?php if (Yii::$app->loadAccess->access(['plugins', 'tenders', 'edit'], 'bool')) { ?>
                                    <a  class="edit-btn " href=<?="/plugins?plug_name=tenders&action=edit&id=".$tender->id?>><i class="zmdi zmdi-edit" style=""></i></a>
                                <?php } ?>
								</span><a href="<?="/plugins?plug_name=tenders&action=view&id=".$tender->id?>"><?=$tender->getPlatformName()?></a>
								<br><b> <a target="_blank" href="<?=$tender->platform_link?>">Ссылка на тендер</a></b>
								<!--<?php if ($tender->deadline_for_filing_an_application_for_participation > 0) { ?>
									<br><b>Дата окончания подачи заявок на участие:</b><br>
									<?=date('d.m.Y H:i', $tender->deadline_for_filing_an_application_for_participation)?>
								<?php } ?>-->	
							</td>
							<td style="<?=$td_style?>">
								<?=$tender->getOrganization()?>
							</td>
							<td style="<?=$td_style?>">
								<?=$tender->direction['name']?>
							</td>							
							<td style="<?=$td_style?>">
								<?php if ($tender->deadline_for_filing_an_application_for_participation == 0) { ?>
									<span style="color:red">Не задано</span>
								<?php } else { ?>
									<?=date('d.m.Y', $tender->deadline_for_filing_an_application_for_participation)?>
								<?php } ?>									
							</td>
							<td style="<?=$td_style?>">
								<?php if ($tender->date_start == 0) { ?>
									<span style="color:red">Не задано</span>
								<?php } else { ?>
									<?=date('d.m.Y H:i', $tender->date_start)?>
								<?php } ?>									
							</td>
							<td style="<?=$td_style?>">
								<?php if ($tender->tender_summ_min == '') { ?>
									<span style="color:red">Не задано</span>
								<?php } else { ?>
									<?=Yii::$app->formatter->asCurrency($tender->tender_summ_min)?>
								<?php } ?>									
							</td>
							<td style="<?=$tender->getStatus()['bckclr'].';word-break: break-all'?>">
								<span style=<?=$tender->getStatus()['txtclr']?>><?=$tender->getStatus()['str']?></span>
							</td>	
							<td style="<?=$td_style?>">			
								<?=$tender->getUsers($params['users'])?>
								<?php	if (Yii::$app->loadAccess->access(['plugins', 'tenders', 'delete'], 'bool'))
									{
										echo "<a href=/plugins?plug_name=tenders&action=delete&id=".$tender->id." onclick=\"return confirm('Вы уверены что желаете удалить тендер?')\"><i class='zmdi zmdi-delete'></i></a>";
								?>
									<?php
									}?>								
								<?php if (isset($params['comments'][$tender->id])) { 					
										$comment = $params['comments'][$tender->id];
										$user_comment = $params['users'][$comment['user_id']];
									?>
									<div class="comment" style="    border-radius: 15px;background: #e8e8e8;padding: 10px 10px 0px 10px;margin: 10px 0px 10px 0px;float: left;width: 100%;">
										<p><b><?=$user_comment['name']?></b> <small><?=date('d.m.Y H:i:s', $comment['time'])?></small></p>
										<p><?=$comment['text']?></p>
										<div style="clear:both"></div>
									</div>
								<?php } ?>
							</td>					
						</tr>	
						<?php
							$i++; 
						?>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<?php
			if ($params['count_page'] > 1)
			{
				$start = $params['page'] - 5;
				if ($start <= 0)
				{
					$start = 1;
				}
				$end = $params['page'] + 5;
				if ($end > $params['count_page'])
				{
					$end = $params['count_page'];
				}

				?>
					<ul class="pagination">
				<?php
					for ($i = $start; $i <= $end; $i++)
					{
						$url = '/plugins?plug_name=tenders&action=index&page='.$i.'&filter[status_id]='.$params['filter']['status_id'];
						?>
							<li class="<?=($i == $params['page'] ? "active" : "")?>">
								<a href="<?=$url?>"><?=$i?></a>
							</li>
						<?php
					}
				?>
					</ul>
				<?php
			}
		?>				
	</div>
</div>
<script>
	window.onload = function()
	{
		/*
		$('.datepicker').daterangepicker(
			{
				timePicker: true,
				format: 'DD.MM.YYYY',
				locale:'ru',
				locale: {
      				format: 'DD.MM.YYYY'
   	 			},
			}
		);
		*/
		$('.datepicker').daterangepicker({ "locale": {
        "format": "DD.MM.YYYY",
        "separator": "-",
        "applyLabel": "Сохранить",
        "cancelLabel": "Назад",
        "daysOfWeek": [
            "Вс",
            "Пн",
            "Вт",
            "Ср",
            "Чт",
            "Пт",
            "Сб"
        ],
        "monthNames": [
            "Январь",
            "Февраль",
            "Март",
            "Апрель",
            "Май",
            "Июнь",
            "Июль",
            "Август",
            "Сентябрь",
            "Октябрь",
            "Ноябрь",
            "Декабрь"
        ],
        "firstDay": 1
    }, 
	timePickerIncrement: 30, 
	format: 'DD.MM.YYYY h:mm A', 
	autoUpdateInput: false,
	showDropdowns: true,
	//startDate: moment().startOf('month'),
    //endDate: moment().startOf('day'),
	
});
$('.datepicker').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD.MM.YY') + '-' + picker.endDate.format('DD.MM.YY'));
  });
	}

</script>