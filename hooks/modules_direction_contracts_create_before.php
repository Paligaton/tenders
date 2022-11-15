<?php
	if (isset($_GET['tender_id']))
	{
		if ($_GET['tender_id'] > 0)
		{
			// фиксируем информацию что договор создан на основании тендера
			$params = [
				'tender_id' => $_GET['tender_id'],
				'contract_id' => $contract_id,
			];
			Yii::$app->db->createCommand()->insert('plugins_tenders_and_contracts', $params)->execute();
		}
	}
?>