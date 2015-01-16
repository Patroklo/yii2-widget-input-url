<?php


namespace cyneek\yii2\widget\urlparser;

use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

/**
 * UrlParser Widget
 *
 * @author Joseba Juaniz <joseba.juaniz@gmail.com>
 * @since 1.0
 */
class UrlParser extends ActiveForm
{

	var $source;

	/** @var \yii\db\ActiveRecord */
	var $model;

	/** @var  string */
	var $attribute;

	/** @var string */
	var $url_separator = '-';

	/* @var ActiveForm */
	var $form = NULL;

	/** @var string */
	var $field_id = '';

	/** @var string  */
	var $reference_id = '';

	/**
	 * only call this method after a form closing and
	 *    when user hasn't used in the widget call the parameter $form
	 *    this adds to every form in the view the field validation.
	 *
	 * @param array $config
	 * @return string
	 * @throws \yii\base\InvalidConfigException
	 */
	static function addManualValidation($config = [])
	{

		if (!array_key_exists('model', $config) || !array_key_exists('attribute', $config))
		{
			throw new InvalidParamException('Config array must have a model and attribute.');
		}

		$view = Yii::$app->getView();
		$field_id = Html::getInputId($config['model'], $config['attribute']);
		$view->registerJs('$("#' . $field_id . '").urlParser("launchValidation");');
	}

	/**
	 * Initializes the field.
	 */
	public function init()
	{
		$view = $this->getView();

		UrlParserAsset::register($view);

		parent::init();
	}



	protected function inputFieldReference()
	{
		/** @var ActiveForm $form */
		if (is_null($this->form))
		{
			$form = $this;
		} else
		{
			$form = $this->form;
		}

		$r  = $form->field($this->model, $this->attribute)->begin();
		$r .= Html::activeLabel($this->model, $this->attribute); // label
		$r .= Html::beginTag('div', ['class' => 'input-group form-group']);
		$r .= Html::activeTextInput($this->model, $this->attribute, ['readonly' => TRUE, 'class' => 'form-control']); // field
		$r .= Html::beginTag('div', ['class' => 'input-group-btn']);
		$r .= Html::button('', ['id' => 'uri_button', 'class' => 'glyphicon glyphicon-pencil btn']); // button
		$r .= Html::endTag('div');
		$r .= Html::endTag('div');
		$r .= Html::error($this->model, $this->attribute, ['class' => 'help-block']); // error
		$r .= $form->field($this->model, $this->attribute)->end();

		$view = $this->getView();
		$view->registerJs('jQuery("#' . $this->field_id . '").urlParser("init", $("#' . $this->reference_id . '"), "' . $this->url_separator . '");');

		return $r;
	}


	protected function inputFieldNoReference()
	{
		/** @var ActiveForm $form */
		if (is_null($this->form))
		{
			$form = $this;
		} else
		{
			$form = $this->form;
		}

		$r  = $form->field($this->model, $this->attribute)->begin();
		$r .= Html::activeLabel($this->model, $this->attribute); // label
		$r .= Html::beginTag('div', ['class' => 'form-group']);
		$r .= Html::activeTextInput($this->model, $this->attribute, ['class' => 'form-control']); // field
		$r .= Html::endTag('div');
		$r .= Html::error($this->model, $this->attribute, ['class' => 'help-block']); // error
		$r .= $form->field($this->model, $this->attribute)->end();

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

		if (is_null($this->source))
		{
			$r = $this->inputFieldNoReference();
		}
		else
		{
			$this->reference_id = Html::getInputId($this->source['model'], $this->source['attribute']);
			$r = $this->inputFieldReference();
		}

		// if developer hasn't passed a form object, then we will store the attributes to make a call
		// to manualValidation() method after the form closing
		if (is_null($this->form))
		{
			$attributes = Json::encode($this->attributes);
			$view = $this->getView();
			$view->registerJs('$("#' . $this->field_id . '").urlParser("addValidation", ' . $attributes . ');');
		}

		return $r;
	}
}