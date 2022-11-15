<?php
	namespace app\plugins_code\tenders\models;
	
	use app\plugins_code\tenders\models\Statuses;
	
	class tender
	{
		
		public $view_btn_awaiting_tender = false;
		
		function __construct($row, $verifications, $directions, $users, $platforms, $files = [])
		{
			// списки
			$this->users = $users;
			$this->verification_all = $verifications;
			$this->platforms = $platforms;
			
			// типовые параметры объекта
			$this->id = $row['id'];
			$this->platform_id = $row['platform_id'];
			$this->tender_sum = $row['tender_sum'];
			$this->client_id = $row['client_id'];
			$this->tender_status = $row['tender_status'];
			$this->author_id = $row['author_id'];
			$this->verification_id = $row['verification_id'];
			$this->platform_link = $row['platform_link'];
			$this->direction_id = $row['direction_id'];
			$this->verification_user_id = $row['verification_user_id'];
			$this->date_start = $row['date_start'];
			$this->deadline_for_filing_an_application_for_participation = $row['deadline_for_filing_an_application_for_participation'];
			$this->tender_summ = $row['tender_summ'];
			$this->tender_summ_min = $row['tender_summ_min'];
			$this->organization_name = $row['organization_name'];
			$this->tender_victor = $row['tender_victor'];
			$this->tender_summ_victor = $row['tender_summ_victor'];
			$this->date_contract = $row['date_contract'];
			$this->row = $row;
			
			// считаемые параметры
			$this->direction = $directions[$this->direction_id];
			$this->verifications = [];
			foreach ($verifications as $verification)
			{
				if ($verification['direction_id'] == $this->direction_id)
				{
					$this->verifications[$verification['user_id']] = $verification['user_id'];
				}
			}
			
			if (count($files) > 0)
			{
				$file_contract = false;
				$file_tz = false;
				foreach ($files as $file)
				{
					if ($file['type'] == 1)
					{
						$file_contract = true;
					}
					if ($file['type'] == 2)
					{
						$file_tz = true;
					}					
				}
				if ($file_contract and $file_tz)
				{
					$this->view_btn_awaiting_tender = true;
				}
			}
			
		}
		
		/*
			0 - на проверке у проверяющего
			1 - не принимаем участие в тендере
			2 - подготовка документов к тендеру
			3 - ожидание начала аукциона, подведения результатов
			4 - тендер проигран
			5 - тендер выигран
			6 - услуги по тендеру оказаны
			7 - услуги по по тендеру оказываются
		*/
		function getStatus()
		{
			$str = '';
			$color = '';
			$bg = '';
			$txtclr = '';
			$bckclr = '';
			
			$Statuses = new Statuses();
			if($this->tender_status == 3){
				$txtclr = 'red';
			}
			else if ($this->tender_status == 5){
				$txtclr = 'green';
			}
			else if ($this->tender_status == 7 or $this->tender_status == 6 ){
				$bckclr = '#dff0d8';
			}
			else if ($this->tender_status == 4 OR $this->tender_status == 8){
				$bckclr = '#f2dede';
			}
			return [
				'str' => $Statuses->statuses[$this->tender_status]['name'],
				'color' => '',
				'bg' => $Statuses->statuses[$this->tender_status]['bg'],
				'txtclr' => 'color:'.$txtclr,
				'bckclr' => 'background:'.$bckclr
			];
		}
		
		function getUsers($users)
		{
			$arr = [];
			$arr[] = 'Автор: '.$users[$this->author_id]['name'];
			if (count($this->verifications))
			{
				$b = [];
				foreach ($this->verifications as $user_id)
				{
					$b[] = $this->users[$user_id]['name'];
				}
				//$arr[] = 'На согласовании у: '.implode(', ', $b);
			}
			return implode('<br>', $arr);
		}
		
		function getAuthor()
		{
			return $this->users[$this->author_id]['name'];
		}
		
		function getPlatformName()
		{
			return $this->platforms[$this->platform_id]['name'];
		}
		
		function getOrganization()
		{
			if ($this->client_id == 0)
			{
				if ($this->organization_name == '')
				{
					return '<span style="color:red">Организация не определена</span>';
				}
				else
				{
					return $this->organization_name;
				}
			}
			else
			{
				$sql = 'SELECT name FROM clients WHERE id = '.$this->client_id;
				$row = \Yii::$app->db->createCommand($sql)->queryOne();
				return '<a href="/clients/view?id='.$this->client_id.'">'.$row['name'].'</a>';
			}
		}
		
	}
	
?>