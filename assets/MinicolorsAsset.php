<?php

namespace app\plugins_code\tenders\assets;

use yii\web\AssetBundle;

class MinicolorsAsset extends AssetBundle
{
    public $sourcePath = '../plugins_code/tenders/assets/minicolors/';
    public $css = [
        'jquery.minicolors.css',
		'style.css',
    ];
    public $js = [
        'jquery.minicolors.js',
		'script.js',
    ];
	public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',		
	];
}