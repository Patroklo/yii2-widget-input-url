<?php


namespace cyneek\yii2\widget\urlparser;

use Yii;
use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * UrlParser Widget
 *
 * @author Joseba Juaniz <joseba.juaniz@gmail.com>
 * @since 1.0
 */
class UrlParser extends Widget
{
	/** @var array */
	var $source;

	/** @var \yii\db\ActiveRecord */
	var $model;

	/** @var  string */
	var $attribute;

	/** @var string */
	var $url_separator = '-';

	/* @var ActiveForm */
	//var $form = NULL;

	/** @var string */
	protected $field_id = '';

	/** @var string */
	protected $reference_id = '';

	/** @var boolean */
	var $enableClientValidation;


	/**
	 * Initializes the field.
	 */
	public function init()
	{

		if (is_null($this->model) || is_null($this->attribute))
		{
			throw new InvalidParamException('Widget must have a defined model and attribute.');
		}

		$view = $this->getView();

		UrlParserAsset::register($view);

		parent::init();
	}


	protected function inputFieldReference()
	{
		$r = '';
		$r .= Html::beginTag('div', ['class' => 'input-group form-group']);
		$r .= Html::activeTextInput($this->model, $this->attribute, ['readonly' => TRUE, 'class' => 'form-control']); // field
		$r .= Html::beginTag('div', ['class' => 'input-group-btn']);
		$r .= Html::button('', ['id' => 'uri_button', 'class' => 'glyphicon glyphicon-pencil btn']); // button
		$r .= Html::endTag('div');
		$r .= Html::endTag('div');

		$view = $this->getView();
		$view->registerJs('jQuery("#' . $this->field_id . '").urlParser("init", $("#' . $this->reference_id . '"), "' . $this->url_separator . '");');

		return $r;
	}


	protected function inputFieldNoReference()
	{
		$r = '';
		$r .= Html::beginTag('div', ['class' => 'form-group']);
		$r .= Html::activeTextInput($this->model, $this->attribute, ['class' => 'form-control']); // field
		$r .= Html::endTag('div');

		$view = $this->getView();
		$view->registerJs('jQuery("#' . $this->field_id . '").urlParser("init", "' . $this->url_separator . '");');

		return $r;
	}


	/**
	 * Renders the field.
	 */
	public function run()
	{
		$this->field_id = Html::getInputId($this->model, $this->attribute);

		if (is_null($this->source)) {
			$r = $this->inputFieldNoReference();
		} else {
			$this->reference_id = Html::getInputId($this->source['model'], $this->source['attribute']);
			$r = $this->inputFieldReference();
		}

		return $r;
	}
}