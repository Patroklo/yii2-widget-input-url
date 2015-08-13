<?php

namespace cyneek\yii2\widget\urlparser\validators;

use Yii;
use yii\helpers\Json;
use yii\validators\ValidationAsset;
use yii\validators\Validator;
use yii\web\JsExpression;


class UriValidator extends Validator
{
    /**
     * @var string the regular expression used to validate the attribute value.
     */
    public $pattern = '/[^-_\w+]/';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->message === NULL) {
            $this->message = Yii::t('yii', '{attribute} is not a valid URI.');
        }
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        $result = $this->validateValue($value);
        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
        }
    }

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        // make sure the length is limited to avoid DOS attacks
        if (!is_string($value) || strlen($value) > 2000) {
            return [$this->message, []];
        }

        $pattern = $this->pattern;

        if (preg_match($pattern, $value)) {
            return [$this->message, []];
        }

        return NULL;
    }

    /**
     * @inheritdoc
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {

        $pattern = $this->pattern;

        $options = [
            'pattern' => new JsExpression($pattern),
            'message' => Yii::$app->getI18n()->format($this->message, [
                'attribute' => $model->getAttributeLabel($attribute),
            ], Yii::$app->language)
        ];
        if ($this->skipOnEmpty) {
            $options['skipOnEmpty'] = 1;
        }

        ValidationAsset::register($view);


        $jsonOptions = Json::encode($options);

        // launches the uri method for calling client side validation and
        // the call itself with the data.
        return <<<JS
		
		if (yii.validation.uri == undefined)
		{
    yii.validation.uri = function (value, messages, options) {
        if (options.skipOnEmpty && yii.validation.isEmpty(value)) {
            return;
        }

        if (value.match(options.pattern)) {
            yii.validation.addMessage(messages, options.message, value);
        }
    };
		}

	yii.validation.uri(value, messages,  $jsonOptions  );
JS;

    }
}
