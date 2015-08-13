<?php


namespace cyneek\yii2\widget\urlparser;

use Yii;
use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

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

    /** @var string */
    protected $field_id = '';

    /**
     * Regex definition used in the URI MaskedInput field
     *
     * @var string
     */
    protected $regex_definition = '[0-9A-Za-zñÑ_\-]';

    /**
     * Max lenght for the MaskedInput, will be defined in the cardinality of the javascript asset
     *
     * @var int
     */
    var $maxlength = 50;

    /**
     * Initializes the field.
     */
    public function init()
    {

        if (is_null($this->model) || is_null($this->attribute)) {
            throw new InvalidParamException('Widget must have a defined model and attribute.');
        }

        $view = $this->getView();

        UrlParserAsset::register($view);

        parent::init();
    }


    /**
     * Method called in case we have a source field from which we will get data for the UrlParser
     * 
     * @return string
     * @throws \Exception
     */
    protected function inputFieldReference()
    {
        $view = $this->getView();
        $button_id = 'uri_button_' . $this->field_id;
        $reference_id = Html::getInputId($this->source['model'], $this->source['attribute']);
        $maskedAlias = 'uri_' . $this->field_id;

        $r = '';
        $r .= Html::beginTag('div', ['class' => 'input-group form-group']);

        // Register the specific alias for the MaskedInput, each URLParser will have a different alias
        // because that way we will be able to define multiple regex definitions, url_separators and maxLengths
        // one for each UrlInput.
        $view->registerJs('defineUriMask("' . $maskedAlias . '", ' . $this->maxlength . ', "' . $this->url_separator . '", "' . $this->regex_definition . '");');

        $r .= MaskedInput::widget(['model' => $this->model, 'attribute' => $this->attribute,
            'options' => ['readonly' => TRUE, 'class' => 'form-control'], 'clientOptions' => ['alias' => $maskedAlias]]);
        $r .= Html::beginTag('div', ['class' => 'input-group-btn']);
        $r .= Html::button('', ['id' => $button_id, 'class' => 'glyphicon glyphicon-pencil btn']); // button
        $r .= Html::endTag('div');
        $r .= Html::endTag('div');

        // Register the button and reference field
        $view->registerJs('jQuery("#' . $button_id . '").uriParserButton($("#' . $this->field_id . '"), $("#' . $reference_id . '"));');

        return $r;
    }

    /**
     * Method called if we don't define a source field.
     * 
     * @return string
     * @throws \Exception
     */
    protected function inputFieldNoReference()
    {
        $view = $this->getView();

        $maskedAlias = 'uri_' . $this->field_id;
        // Register the specific alias for the MaskedInput, each URLParser will have a different alias
        // because that way we will be able to define multiple regex definitions, url_separators and maxLengths
        // one for each UrlInput.
        $view->registerJs('defineUriMask("' . $maskedAlias . '", ' . $this->maxlength . ', "' . $this->url_separator . '", "' . $this->regex_definition . '");');

        $r = '';
        $r .= Html::beginTag('div', ['class' => 'form-group']);
        $r .= MaskedInput::widget(['model' => $this->model, 'attribute' => $this->attribute, 'clientOptions' => ['alias' => 'uri']]);
        $r .= Html::endTag('div');

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
            $r = $this->inputFieldReference();
        }

        return $r;
    }
}