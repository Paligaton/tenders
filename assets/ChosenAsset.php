<?php

namespace app\plugins_code\tenders\assets;

use yii\web\AssetBundle;

class ChosenAsset extends AssetBundle
{
    public $sourcePath = '../plugins_code/tenders/assets/chosen/';
    public $css = [
        'chosen.css',
    ];
    public $js = [
        'chosen.jquery.js',
    ];
	public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',		
	];
}