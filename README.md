# Yii2 Url Input Widget
Url input widget for making uri strings in yii2 forms

## What's File Uploader Manager?

This module adds a new input field that can be used algonside another input text field to make valid uri strings. Also there is bundled in the package a UriValidator for both client side and server side validation.

Developed by Joseba Juániz ([@Patroklo](http://twitter.com/Patroklo))

[Spanish Readme version](https://github.com/Patroklo/yii2-widget-input-url/blob/master/README_Spanish.md)

## Minimum requirements

* Yii2
* Php 5.4 or above

## Future plans

* None right now.

## License

This is free software. It is released under the terms of the following BSD License.

Copyright (c) 2014, by Cyneek
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions
are met:
1. Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.
3. Neither the name of Cyneek nor the names of its contributors
   may be used to endorse or promote products derived from this software
   without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER "AS IS" AND ANY
EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

## Instalation

* Install [Yii 2](http://www.yiiframework.com/download)
* Install package via [composer](http://getcomposer.org/download/) 
		
		"cyneek/yii2-widget-urlparser": "dev-master"
		
* Profit!

## Widget method options

* model (ActiveRecord or Model) (obligatory)
> Defines the model that will be used to make the form input field.

* attribute (string) (obligatory)
> Defines the model attribute that will be used to make de form input field.

* form (ActiveForm) (optional)
> Its the ActiveForm object that defines the form in which the widget it's included. It will be used to inherit the form config when making the input field.

* enableClientValidation (boolean) (optional)
> Used when the "form" optiones it's not defined in the widget. It establishes if the Yii2 javaScript client validation it's or isn't activated.


* url_separator (char) (optional)
> It's the character used to replace the space character while making a URI string. By default it's defined as *"-"*
> **Warning** If it's defined, the new value must also be a URI valid character.



* source (array) (optional)
    * model (ActiveRecord or Model) (obligatory)
    * attribute (string) (obligatory)
> Defines if the widget will have an external URI string source.
> The values that hold this array must be a model object and an attribute from this model that makes an input text field in the same form where the widget it's located. Also, it must be a different attribute than the defined for the widget itself. 


## addManualValidation static method options

The addManualValidation method must only be called in case we haven't defined in the widget the *form* option and we want to activate the client validation for its field. This client validation will include all the rules that are defined in the field's model, not only the UriValidator validation.

* model (string) (obligatory)
> Defines the model that will be used to make the form input field.


* attribute (string) (obligatory)
> Defines the model attribute that will be used to make de form input field.


## Widget use cases

Given that this library it's a widget input field separated from the form system there is the problem that it's impossible to make the input text field normally using the ActiveForm object without adding it as an option. This will affect the client validations and css or php changes ever made.  

In all this cases, by default, the input text field that holds the URI string will filter automatically all data that it's added with javaScript by making a valid string of characters. This has nothing to do with UriValidator, it's an additional filter added to increase the widget toughness.

This widget can be used in many ways, depending of it's definition options:

### 1. No source input field neither using form reference

This will make an input text field that user can fill manually with a URI string of characters 

This is the most easy use case, only defining the model and attribute options. But if we only add this line of code, the input field won't have any client validation and if the ActiveForm object that holds the widget has any special feature, it also won't be shown.
		
		echo \cyneek\yii2\widget\urlparser\UrlParser::widget(['model' => $model, 'attribute' => 'fieldName']);
		

If we want to add in this case **client validation** for this widget, there must be included an additional call **AFTER** closing the form that holds the widget:
		
		echo \cyneek\yii2\widget\urlparser\UrlParser::addManualValidation(['model' => $model, 'attribute' => 'fieldName']);
		

This static method call will add a JavaScript code that will add client validation in the browser for this field.

**Warning** This code should only be included if the rest of the form itself has the client validation activaded.

Aditionally, there is an optional configuration that can be added to the widget definition that would unset the client validation in this case, though it's not advised its use, for that it must be used the enableClientValidation option:
		
		echo \cyneek\yii2\widget\urlparser\UrlParser::widget(['model' => $model, 'attribute' => 'fieldName', 'enableClientValidation' => TRUE|FALSE]);
		

Depending of the value of enableClientValidation in this case, the widget input field will have switched on or off its client validation.

### 2. No source input but using a form reference

This case wil make an input text field that the user will have to manually fill with a valid URI string of characters. But the input fields will be generated with the ActiveForm object that holds the widget, so it will come with the same options and changes that the rest of the fields of the form. This case requires less configurations than the previous one, becase it's not neccesary to add any extra call or method to make it work appropriately.

		
		echo \cyneek\yii2\widget\urlparser\UrlParser::widget(['form' => $form, 'model' => $model, 'attribute' => 'fieldName']);
		

In this case, if the form has client validation activated, the widget input field also will have it. There is not necessary to include anything more.

### 3. With source input but without form reference

This case will make an input text field that will be set as readonly, that's becase it will use another field as source of its URI string data that we will have identified while declaring the widget. Nevertheless, the widget will also include a button that will let the user to deactivate the readonly option of the field and the link between the two fields, letting him to introduce manually the data directly in the widget input field.

		
		echo \cyneek\yii2\widget\urlparser\UrlParser::widget(['model' => $model, 'attribute' => 'fieldName', 'source' => ['model' => $model, 'attribute' => 'sourceFieldName']]);
		

As in the case number 1, the widget, because it doesn't have the ActiveForm object included as an option in it's definition, won't be able to include client validation even if the form has it switched on. In order to prevent that, you must add a static method calling **AFTER** closing the form that holds the widget.
		
		echo \cyneek\yii2\widget\urlparser\UrlParser::addManualValidation(['model' => $model, 'attribute' => 'fieldName']);
		

This static method call will add a JavaScript code that will add client validation in the browser for this field.

**Warning** This code should only be included if the rest of the form itself has the client validation activaded.

It's also possible to include the enableClientValidation option that has been explained in case 1 of this section to activate / deactivate manually the client validation of this widget.

### 4. With source input and form reference

This is the most complete case of all. It includes the use of an external input text field as source of the URI string data that will add automatically the data into the widget, the option of deactivating this behaviour and letting the user to add manually the data into the widget and the making of the widget input fields with the ActiveForm object that holds the field, inheriting all the configurations and changes made for it:

		
		echo \cyneek\yii2\widget\urlparser\UrlParser::widget(['form' => $form, 'model' => $model, 'attribute' => 'fieldName', 'source' => ['model' => $model, 'attribute' => 'sourceFieldName']]);
		

In this case, if the form has client validation activated, the widget input field also will have it. There is not necessary to include anything more.


## Using UriValidation

Additionally it has been included in the package a validation library for URI string data. Its main utility it's to verify the data sent via form and to ratify it as a valid / invalid URI string either from the Yii 2 php code or client javaScript.

Its use is identical as the UrlValidator, the only difference it's that, given the library it's not included by default in the system, you must include it manually in the Validator component, which is the responsible of storing all the active validators in the system. Usually this will be done in the *rules* method that holds the URI attribute that we want validated.

	public function rules()
	{
		// adds UriValidator to the list of active validatos in the Validator component.
		Validator::$builtInValidators['uri'] = 'cyneek\yii2\widget\urlparser\validators\UriValidator';
		
		return [
				[['name', 'uriField'], 'required'],
				[['name'], 'string'],
				[['name', 'uriField'], 'safe'],
				[['uriField'], 'uri']
		];
	}

From this moment, the validator will be active and functional for the system and can be used as any other default system validator.