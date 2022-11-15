<?php

namespace app\plugins_code\tenders\assets;

use yii\web\AssetBundle;

class DateTimePickerAsset extends AssetBundle
{
    public $sourcePath = '../plugins_code/tenders/assets/DateTimePicker/';
    public $css = [
        'css/bootstrap-datetimepicker.min.css',
    ];
    public $js = [
        'js/moment-with-locales.js',
        'js/bootstrap-datetimepicker.min.js',
		'js/script.js',
    ];
}