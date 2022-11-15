<?php

namespace app\plugins_code\tenders\assets;

use yii\web\AssetBundle;

class TinyMCEAsset extends AssetBundle
{
    public $sourcePath = '../plugins_code/tenders/assets/TinyMCEAsset/';
    public $css = [
		
    ];
    public $js = [
        'js/tinymce.min.js',
		'js/script.js',
    ];
	public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',		
	];
}