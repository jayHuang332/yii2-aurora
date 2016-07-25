<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace jackh\aurora;

use jackh\aurora\assets\bundles\QuillAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * A Bootstrap 3 enhanced version of [[\yii\widgets\ActiveField]].
 *
 * This class adds some useful features to [[\yii\widgets\ActiveField|ActiveField]] to render all
 * sorts of Bootstrap 3 form fields in different form layouts:
 *
 * - [[inputTemplate]] is an optional template to render complex inputs, for example input groups
 * - [[horizontalCssClasses]] defines the CSS grid classes to add to label, wrapper, error and hint
 *   in horizontal forms
 * - [[inline]]/[[inline()]] is used to render inline [[checkboxList()]] and [[radioList()]]
 * - [[enableError]] can be set to `false` to disable to the error
 * - [[enableLabel]] can be set to `false` to disable to the label
 * - [[label()]] can be used with a `boolean` argument to enable/disable the label
 *
 * There are also some new placeholders that you can use in the [[template]] configuration:
 *
 * - `{beginLabel}`: the opening label tag
 * - `{labelTitle}`: the label title for use with `{beginLabel}`/`{endLabel}`
 * - `{endLabel}`: the closing label tag
 * - `{beginWrapper}`: the opening wrapper tag
 * - `{endWrapper}`: the closing wrapper tag
 *
 * The wrapper tag is only used for some layouts and form elements.
 *
 * Note that some elements use slightly different defaults for [[template]] and other options.
 * You may want to override those predefined templates for checkboxes, radio buttons, checkboxLists
 * and radioLists in the [[\yii\widgets\ActiveForm::fieldConfig|fieldConfig]] of the
 * [[\yii\widgets\ActiveForm]]:
 *
 * - [[checkboxTemplate]] the template for checkboxes in default layout
 * - [[radioTemplate]] the template for radio buttons in default layout
 * - [[horizontalCheckboxTemplate]] the template for checkboxes in horizontal layout
 * - [[horizontalRadioTemplate]] the template for radio buttons in horizontal layout
 * - [[inlineCheckboxListTemplate]] the template for inline checkboxLists
 * - [[inlineRadioListTemplate]] the template for inline radioLists
 *
 * Example:
 *
 * ```php
 * use yii\bootstrap\ActiveForm;
 *
 * $form = ActiveForm::begin(['layout' => 'horizontal']);
 *
 * // Form field without label
 * echo $form->field($model, 'demo', [
 *     'inputOptions' => [
 *         'placeholder' => $model->getAttributeLabel('demo'),
 *     ],
 * ])->label(false);
 *
 * // Inline radio list
 * echo $form->field($model, 'demo')->inline()->radioList($items);
 *
 * // Control sizing in horizontal mode
 * echo $form->field($model, 'demo', [
 *     'horizontalCssClasses' => [
 *         'wrapper' => 'col-sm-2',
 *     ]
 * ]);
 *
 * // With 'default' layout you would use 'template' to size a specific field:
 * echo $form->field($model, 'demo', [
 *     'template' => '{label} <div class="row"><div class="col-sm-4">{input}{error}{hint}</div></div>'
 * ]);
 *
 * // Input group
 * echo $form->field($model, 'demo', [
 *     'inputTemplate' => '<div class="input-group"><span class="input-group-addon">@</span>{input}</div>',
 * ]);
 *
 * ActiveForm::end();
 * ```
 *
 * @see \yii\bootstrap\ActiveForm
 * @see http://getbootstrap.com/css/#forms
 *
 * @author Michael Härtl <haertl.mike@gmail.com>
 * @since 2.0
 */
class ActiveField extends \yii\widgets\ActiveField {
	/**
	 * @var boolean whether to render [[checkboxList()]] and [[radioList()]] inline.
	 */
	public $inline = false;
	/**
	 * @var string|null optional template to render the `{input}` placeholder content
	 */
	public $inputTemplate; // = '<span class="form-control-before"><i class="fa fa-user"></i></span>{input}';
	/**
	 * @var array options for the wrapper tag, used in the `{beginWrapper}` placeholder
	 */
	public $wrapperOptions = [];
	/**
	 * @var null|array CSS grid classes for horizontal layout. This must be an array with these keys:
	 *  - 'offset' the offset grid class to append to the wrapper if no label is rendered
	 *  - 'label' the label grid class
	 *  - 'wrapper' the wrapper grid class
	 *  - 'error' the error grid class
	 *  - 'hint' the hint grid class
	 */
	public $horizontalCssClasses;
	/**
	 * @var string the template for checkboxes in default layout
	 */
	public $checkboxTemplate = "{beginCheckboxWrapper}\n{beginLabel}\n{input}\n<div class=\"checkbox-label\"><i class=\"fa fa-check\"></i>{endCheckboxWrapper}\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>";
	/**
	 * @var string the template for radios in default layout
	 */
	public $radioTemplate = "<div class=\"radio\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>";
	/**
	 * @var string the template for checkboxes in horizontal layout
	 */
	public $horizontalCheckboxTemplate = "{beginWrapper}\n{beginCheckboxWrapper}\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n{endCheckboxWrapper}\n{error}\n{endWrapper}\n{hint}";
	/**
	 * @var string the template for radio buttons in horizontal layout
	 */
	public $horizontalRadioTemplate = "{beginWrapper}\n<div class=\"radio\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n</div>\n{error}\n{endWrapper}\n{hint}";
	/**
	 * @var string the template for inline checkboxLists
	 */
	public $inlineCheckboxListTemplate = "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}";
	/**
	 * @var string the template for inline radioLists
	 */
	public $inlineRadioListTemplate = "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}";
	/**
	 * @var boolean whether to render the error. Default is `true` except for layout `inline`.
	 */
	public $enableError = true;
	/**
	 * @var boolean whether to render the label. Default is `true`.
	 */
	public $enableLabel = true;

	public $inputBefore;

	public $inputFeedback = false;

	public $placeholder = true;

	/**
	 * @inheritdoc
	 */
	public function __construct($config = []) {
		$layoutConfig = $this->createLayoutConfig($config);
		$config = ArrayHelper::merge($layoutConfig, $config);
		parent::__construct($config);

		if ($this->placeholder) {
			$attribute = $this->model->getAttributeLabel($this->attribute);
			// $placeholder = Html::encode($this->model->getAttributeLabel($attribute));

			// $this->inputOptions["placeholder"] = $placeholder;
			$this->inputOptions["placeholder"] = $attribute;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function render($content = null) {
		if ($content === null) {
			if ($this->inputBefore) {
				$inputBefore = '{beginWrapper}' . Html::tag('span', $this->inputBefore, ["class" => "form-control-before"]);
				$this->template = strtr($this->template, ['{beginWrapper}' => $inputBefore]);
			}
			if ($this->inputFeedback) {
				$feedback = Html::tag('span', '<i class="fa fa-check"></i><i class="fa fa-times"></i>', ["class" => "form-control-feedback"]) . '{endWrapper}';
				$this->template = strtr($this->template, ['{endWrapper}' => $feedback]);
				$this->options['class'] = 'form-group has-feedback';
			}
			if (!isset($this->parts['{beginWrapper}'])) {
				$options = $this->wrapperOptions;
				$tag = ArrayHelper::remove($options, 'tag', 'div');
				$this->parts['{beginWrapper}'] = Html::beginTag($tag, $options);
				$this->parts['{endWrapper}'] = Html::endTag($tag);
			}
			if ($this->enableLabel === false) {
				$this->parts['{label}'] = '';
				$this->parts['{beginLabel}'] = '';
				$this->parts['{labelTitle}'] = '';
				$this->parts['{endLabel}'] = '';
			} elseif (!isset($this->parts['{beginLabel}'])) {
				$this->renderLabelParts();
			}
			if ($this->enableError === false) {
				$this->parts['{error}'] = '';
			}
			if ($this->inputTemplate) {
				$input = isset($this->parts['{input}']) ?
				$this->parts['{input}'] : Html::activeTextInput($this->model, $this->attribute, $this->inputOptions);
				$this->parts['{input}'] = strtr($this->inputTemplate, ['{input}' => $input]);
			}
		}
		return parent::render($content);
	}

	/**
	 * @inheritdoc
	 */
	public function checkbox($options = [], $enclosedByLabel = true) {
		if ($enclosedByLabel) {
			if (!isset($options['template'])) {
				$this->template = $this->form->layout === 'horizontal' ?
				$this->horizontalCheckboxTemplate : $this->checkboxTemplate;
			} else {
				$this->template = $options['template'];
				unset($options['template']);
			}

			$value = Html::getAttributeValue($this->model, $this->attribute);
			if (!array_key_exists('value', $options)) {
				$options['value'] = '1';
			}
			$checked = "$value" === "{$options['value']}";

			$this->parts['{beginCheckboxWrapper}'] = Html::beginTag("div", ["class" => $checked ? "checkbox active" : "checkbox", "data-toggle" => "checkbox"]);
			$this->parts['{endCheckboxWrapper}'] = Html::endTag("div");

			if (isset($options['label'])) {
				$this->parts['{labelTitle}'] = $options['label'];
			}
			if ($this->form->layout === 'horizontal') {
				Html::addCssClass($this->wrapperOptions, $this->horizontalCssClasses['offset']);
			}
			$this->labelOptions['class'] = null;
		}

		return parent::checkbox($options, false);
	}

	/**
	 *	options => [
	 *		onValue => "true",
	 *		offValue => "false"
	 *	]
	 */

	public function switch ($options = []) {
		$options["onValue"] = isset($options["onValue"]) ? $options["onValue"] : 1;
		$options["offValue"] = isset($options["offValue"]) ? $options["offValue"] : 0;
		$options = array_merge($this->inputOptions, $options);
		$this->adjustLabelFor($options);
		$input = Html::activeHiddenInput($this->model, $this->attribute, $options);
		$switch_label = Html::tag("div", Html::tag("div", "", ["class" => "switchbutton"]), ["class" => "switch-label"]);
		// var_dump($this->attribute);
		$switch = Html::tag("div", $input . $switch_label, [
			"class" => "switch" . ($this->model->getAttribute($this->attribute) == $options["onValue"] ? " active" : ""),
			"data-toggle" => "switch",
			"data-on-value" => $options["onValue"],
			"data-off-value" => $options["offValue"],
		]);

		$this->parts['{input}'] = $switch;
		return $this;
	}
	/**
	 * @inheritdoc
	 */
	public function radio($options = [], $enclosedByLabel = true) {
		if ($enclosedByLabel) {
			if (!isset($options['template'])) {
				$this->template = $this->form->layout === 'horizontal' ?
				$this->horizontalRadioTemplate : $this->radioTemplate;
			} else {
				$this->template = $options['template'];
				unset($options['template']);
			}
			if (isset($options['label'])) {
				$this->parts['{labelTitle}'] = $options['label'];
			}
			if ($this->form->layout === 'horizontal') {
				Html::addCssClass($this->wrapperOptions, $this->horizontalCssClasses['offset']);
			}
			$this->labelOptions['class'] = null;
		}

		return parent::radio($options, false);
	}

	/**
	 * @inheritdoc
	 */
	public function checkboxList($items, $options = []) {
		if ($this->inline) {
			if (!isset($options['template'])) {
				$this->template = $this->inlineCheckboxListTemplate;
			} else {
				$this->template = $options['template'];
				unset($options['template']);
			}
			if (!isset($options['itemOptions'])) {
				$options['itemOptions'] = [
					'labelOptions' => ['class' => 'checkbox-inline'],
				];
			}
		} elseif (!isset($options['item'])) {
			$options['item'] = function ($index, $label, $name, $checked, $value) {
				return '<div class="row"><div class="checkbox' . ($checked ? " active" : "") . '" data-toggle="checkbox">' . Html::checkbox($name, $checked, ['value' => $value]) .
				'<div class="checkbox-label"><i class="fa fa-check"></i></div></div>' . Html::tag('label', $label) . '</div>';
			};
		}
		parent::checkboxList($items, $options);
		return $this;
	}

	/**
	 * @inheritdoc
	 *
	 */
	public function radioList($items, $options = []) {
		if ($this->inline) {
			if (!isset($options['template'])) {
				$this->template = $this->inlineRadioListTemplate;
			} else {
				$this->template = $options['template'];
				unset($options['template']);
			}
			if (!isset($options['itemOptions'])) {
				$options['itemOptions'] = [
					'labelOptions' => ['class' => 'radio-inline'],
				];
			}
		} elseif (!isset($options['item'])) {
			// update to Aurora
			$options['item'] = function ($index, $label, $name, $checked, $value) {
				return '<div class="row"><div class="radio" data-toggle="radio">' . Html::radio($name, $checked, ['value' => $value]) .
				'<label class="radio-label"></label></div>' . Html::tag('label', $label) . '</div>';
			};
		}
		parent::radioList($items, $options);
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function label($label = null, $options = []) {
		if (is_bool($label)) {
			$this->enableLabel = $label;
			if ($label === false && $this->form->layout === 'horizontal') {
				Html::addCssClass($this->wrapperOptions, $this->horizontalCssClasses['offset']);
			}
		} else {
			$this->enableLabel = true;
			$this->renderLabelParts($label, $options);
			parent::label($label, $options);
		}
		return $this;
	}

	/**
	 * @param boolean $value whether to render a inline list
	 * @return static the field object itself
	 * Make sure you call this method before [[checkboxList()]] or [[radioList()]] to have any effect.
	 */
	public function inline($value = true) {
		$this->inline = (bool) $value;
		return $this;
	}

	/**
	 * @param array $instanceConfig the configuration passed to this instance's constructor
	 * @return array the layout specific default configuration for this instance
	 */
	protected function createLayoutConfig($instanceConfig) {
		$config = [
			'hintOptions' => [
				'tag' => 'p',
				'class' => 'help-block',
			],
			'errorOptions' => [
				'tag' => 'p',
				'class' => 'help-block help-block-error',
			],
			'inputOptions' => [
				'class' => 'form-control',
			],
		];

		$layout = $instanceConfig['form']->layout;

		if ($layout === 'horizontal') {
			$config['template'] = "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}";
			$cssClasses = [
				'offset' => 'col-sm-offset-3',
				'label' => 'col-sm-3',
				'wrapper' => 'col-sm-6',
				'error' => '',
				'hint' => 'col-sm-3',
			];
			if (isset($instanceConfig['horizontalCssClasses'])) {
				$cssClasses = ArrayHelper::merge($cssClasses, $instanceConfig['horizontalCssClasses']);
			}
			$config['horizontalCssClasses'] = $cssClasses;
			$config['wrapperOptions'] = ['class' => $cssClasses['wrapper']];
			$config['labelOptions'] = ['class' => 'control-label ' . $cssClasses['label']];
			$config['errorOptions'] = ['class' => 'help-block help-block-error ' . $cssClasses['error']];
			$config['hintOptions'] = ['class' => 'help-block ' . $cssClasses['hint']];
		} elseif ($layout === 'inline') {
			$config['labelOptions'] = ['class' => 'sr-only'];
			$config['enableError'] = false;
		}

		return $config;
	}

	public function datepickerInput($options = []) {
		$options = array_merge($this->inputOptions, $options);
		$this->adjustLabelFor($options);
		$options = ArrayHelper::merge($options, ["data-toggle" => "datepicker"]);
		$this->parts['{input}'] = Html::activeTextInput($this->model, $this->attribute, $options);

		return $this;
	}

	public function editorInput($options = []) {
		$options = array_merge($this->inputOptions, $options);
		$this->adjustLabelFor($options);
		$input = Html::activeHiddenInput($this->model, $this->attribute, $options);
		$toolbar = $this->form->view->render('@jackh/aurora/QuillToolbar.php', ["options" => $options]);
		$toolbar = Html::tag("div", $toolbar, ["id" => 'quill-toolbar']);
		$editor = $toolbar . '<div id="quill-editor" style="min-height: 300px"></div>';

		QuillAsset::register($this->form->view);

		$name = isset($options['name']) ? $options['name'] : Html::getInputName($this->model, $this->attribute);

		$this->form->view->registerJs('
            var quill = new Quill("#quill-editor", {
                theme: "snow",
                modules: {
                    toolbar: "#quill-toolbar",
                    "image-tooltip": true,
                    "link-tooltip": true
                }
            }); ' .
			(isset($options["upload"]) ? ('jQuery(\'#quill-toolbar [data-toggle="upload"]\').upload(' . $options["upload"] . ');') : '') .
			'var quill_input = jQuery("[name=\'' . $name . '\']").data("quill", quill);
            quill.setHTML(quill_input.val())
            var isEditorEmpty = false, lastcursor = 0
            if(quill.getHTML()=="<div><br></div>") {
                quill.setHTML("<div style=color:#CCCCCC>' . $options["placeholder"] . '</div>")
                isEditorEmpty = true
            }
            quill.on("selection-change", function(range) {
                if (range) {
                    if(isEditorEmpty) {
                        quill.setHTML("")
                        isEditorEmpty = false
                    }
                    if (range.start == range.end) {
                        lastcursor = range.start
                    }
                } else {
                    if(quill.getHTML()=="<div><br></div>") {
                        quill.setHTML("<div style=color:#CCCCCC>' . $options["placeholder"] . '</div>")
                        isEditorEmpty = true
                    }
                }
            });
            quill.on("text-change", function(delta, source) {
            	range = quill.getSelection()
				if (range && range.start == range.end) {
					lastcursor = range.start
                }
                if (source == "api") {
                    if(quill.getHTML()=="<div style=color:#CCCCCC>' . $options["placeholder"] . '</div>" ) {
                        quill_input.val("")
                    }
                } else if (source == "user") {
                	console.log("user", delta)
                }
                quill_input.val(quill.getHTML())
            });
        ');

		$this->parts['{input}'] = $input . $editor;
		return $this;
	}

	/**
	 * @param string|null $label the label or null to use model label
	 * @param array $options the tag options
	 */
	protected function renderLabelParts($label = null, $options = []) {
		$options = array_merge($this->labelOptions, $options);
		if ($label === null) {
			if (isset($options['label'])) {
				$label = $options['label'];
				unset($options['label']);
			} else {
				$attribute = Html::getAttributeName($this->attribute);
				$label = Html::encode($this->model->getAttributeLabel($attribute));
			}
		}
		if (!isset($options['for'])) {
			$options['for'] = Html::getInputId($this->model, $this->attribute);
		}
		$this->parts['{beginLabel}'] = Html::beginTag('label', $options);
		$this->parts['{endLabel}'] = Html::endTag('label');
		if (!isset($this->parts['{labelTitle}'])) {
			$this->parts['{labelTitle}'] = $label;
		}
	}

}
