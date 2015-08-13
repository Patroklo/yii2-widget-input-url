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

## Widget use cases

In all this cases, by default, the input text field that holds the URI string will filter automatically all data that it's added with javaScript by making a valid string of characters. This has nothing to do with UriValidator, it's an additional filter added to increase the widget toughness.

This widget can be used in many ways, depending of it's definition options:

### Standalone

		
		echo \cyneek\yii2\widget\urlparser\UrlParser::widget([
																'model' => $model, 
																'attribute' => 'fieldName', 
																'source' => 
																	[
																		'model' => $model, 
																		'attribute' => 'sourceFieldName'
																	]
																]);
		

Won't have client validation, also won't inherit any form or input changes made by the developer.

#### Options

* model (ActiveRecord or Model) (obligatory)
> Defines the model that will be used to make the form input field.


* attribute (string) (obligatory)
> Defines the model attribute that will be used to make de form input field.


* url_separator (char) (optional)
> It's the character used to replace the space character while making a URI string. By default it's defined as *"-"*
> **Warning** If it's defined, the new value must also be a URI valid character.


* regex_definition (char) (optional)
> Regex that will be used to validate the inserted data in the URI field. In case it's not valid, it won't be added in it.


* maxlength (integer) (optional)
> Max number of characters that the field will allow.


* source (array) (optional)
    * model (ActiveRecord or Model) (obligatory)
    * attribute (string) (obligatory)
> Defines if the widget will have an external URI string source.
> The values that hold this array must be a model object and an attribute from this model that makes an input text field in the same form where the widget it's located. Also, it must be a different attribute than the defined for the widget itself. 

If source is defined, the widget will make an input text field that will be set as readonly, that's becase it will use the source field as root of its URI string data. Nevertheless, the widget will also include a button that will let the user to deactivate the readonly option of the field and the link between the two fields, letting him to introduce manually the data directly in the widget input field.

### Alongside ActiveForm widget

		
		echo $form->field($model, 'fieldName')->widget('\cyneek\yii2\widget\urlparser\UrlParser', ['source' => ['model' => $model, 'attribute' => 'sourceFieldName']]);
		

It will have client validation if enabled at the form.

#### Options

* url_separator (char) (optional)
> It's the character used to replace the space character while making a URI string. By default it's defined as *"-"*
> **Warning** If it's defined, the new value must also be a URI valid character.


* source (array) (optional)
    * model (ActiveRecord or Model) (obligatory)
    * attribute (string) (obligatory)
> Defines if the widget will have an external URI string source.
> The values that hold this array must be a model object and an attribute from this model that makes an input text field in the same form where the widget it's located. Also, it must be a different attribute than the defined for the widget itself. 

If source is defined, the widget will make an input text field that will be set as readonly, that's becase it will use the source field as root of its URI string data. Nevertheless, the widget will also include a button that will let the user to deactivate the readonly option of the field and the link between the two fields, letting him to introduce manually the data directly in the widget input field.

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