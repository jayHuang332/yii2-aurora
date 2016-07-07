<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace jackh\aurora\assets\bundles;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AuroraAsset extends AssetBundle
{
    public $sourcePath = '@jackh/aurora/assets';
    public $baseUrl    = '@web';
    public $css        = [
        'styles/aurora.css',
    ];
    public $js = [
        'scripts/aurora.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
