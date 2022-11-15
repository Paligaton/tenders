<?php
	use app\models\Alerts;
	$request = Yii::$app->request;
	
	$id = $request->get('id');
	
	$params = ['deleted' => 1];
	Yii::$app->db->createCommand()->update('plugins_tenders', $params, 'id='.$id)->execute();
	
	$Alerts = new Alerts();
	$Alerts->user_id = \Yii::$app->user->id;
	$Alerts->type = "success";
	$Alerts->text = "Тендер удален успешно";
	$Alerts->date_create = time();
	$Alerts->date_view = 0;
	$Alerts->save(false);

	header('Location: /plugins?plug_name=tenders&action=index');
	exit();	
	
?>