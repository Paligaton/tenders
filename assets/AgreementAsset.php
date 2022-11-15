<?php

namespace app\plugins_code\tenders\assets;

use yii\web\AssetBundle;

class AgreementAsset extends AssetBundle
{
    public $sourcePath = '../plugins_code/tenders/assets/AgreementAsset/';
    public $css = [
		'css/style.css',
	];
    public $js = [
        'js/script.js',
    ];
	public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',		
	];
}