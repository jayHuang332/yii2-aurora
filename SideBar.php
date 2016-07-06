<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace jackh\aurora;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 *
 * @see \yii\bootstrap\ActiveForm
 * @see http://getbootstrap.com/css/#forms
 *
 * @author Michael Härtl <haertl.mike@gmail.com>
 * @since 2.0
 */
class Sidebar extends Widget
{
    /**
     * @var string the name of the breadcrumb container tag.
     */
    public $column;

    public $options = ["class" => "aurora-sidebar"];

    /**
     * Renders the widget.
     */
    public function run()
    {
        /**
         * $key is tag content
         * $value is target url
         *
         * <nav class="hidden-print hidden-xs hidden-sm aurora-sidebar">
         *      <ul class="nav" id="accordion">
         *          <li>
         *              <a href="#" data-target="#navbar-cover-example" data-toggle="collapse" data-parent="#accordion">导航栏</a>
         *              <ul class="collapse" id="navbar-cover-example">
         *                   <li><a href="#navbar-cover-example">覆盖式导航栏</a></li>
         *                   <li><a href="#navbar-invade-example">侵占式导航栏</a></li>
         *              </ul>
         *          </li>
         *     </ul>
         * </nav>
         *
         * "column" => [
         *     "name" => [
         *         "url" => ""(String),
         *         "submenu" => [
         *             "name" => "url",
         *             "name" => "url",
         *             ...
         *         ]
         *     ],
         *     ...
         * ]
         */
        $menu         = [];
        $accordion_id = $this->getUid();
        foreach ($this->column as $name => $value) {
            $submenu = [];
            if (isset($value["submenu"]) && is_array($value["submenu"])) {
                foreach ($value["submenu"] as $subname => $options) {
                    if (is_string($options)) {
                        //url
                        $submenu[] = Html::tag("li", Html::a($subname, $options));
                    } else {
                        $submenu[] = Html::tag("li", Html::tag("a", $subname, $options));
                    }
                }
            }
            if (!empty($submenu)) {
                $submenu_id   = $this->getUid();
                $menu_options = [
                    "data-toggle" => "collapse",
                    "data-target" => "#" . $submenu_id,
                    "data-parent" => "#" . $accordion_id,
                ];
                if (isset($value["options"]) && is_array($value["options"])) {
                    $menu_options = array_merge($menu_options, $value["options"]);
                }
                $menu_content = Html::tag("a", $name, $menu_options) .
                Html::tag("i", "", ["class" => "fa fa-chevron-right"]) .
                Html::tag("ul", implode("", $submenu), ["class" => "submenu collapse", "id" => $submenu_id]);
                $menu[] = Html::tag("li", $menu_content);
            } else {
                $menu_options = isset($value["options"]) && is_array($value["options"]) ? $value["options"] : [];
                $menu_content = Html::tag("a", $name, $menu_options);
                $menu[]       = Html::tag("li", $menu_content);
            }
        }
        $menu = Html::tag("ul", implode("", $menu), ["id" => $accordion_id, "class" => "nav"]);
        return Html::tag("nav", $menu, $this->options);
    }

    protected function getUid()
    {
        SideBar::$autoIdPrefix = 'sidebar';
        return SideBar::$autoIdPrefix . SideBar::$counter++;
    }
}
