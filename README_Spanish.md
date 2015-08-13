# Yii2 Url Input Widget
Url input widget para generar strings de tipo URI en formularios de Yii2.

## ¿Qué es Url Input Widget?

Este módulo añade un nuevo campo input que puede ser usado en solitario o junto con otro de tipo text para generar strings de tipo URI válidas. Además viene incluido en el paquete un UriValidator que valida este tipo de datos tanto en cliente como en servidor. 

Desarrollado by Joseba Juániz ([@Patroklo](http://twitter.com/Patroklo))

[Versión en inglés](https://github.com/Patroklo/yii2-widget-input-url/blob/master/README.md)

## Requisitos mínimos

* Yii2
* Php 5.4 o superior
* JQuery

## Planes futuros

* Ninguno.

## Licencia

Esto es software libre. Está liberado bajo los términos de la siguiente licencia BSD

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

## Instalación

* Instalar [Yii 2](http://www.yiiframework.com/download)
* Instalar el paquete vía [composer](http://getcomposer.org/download/) 
		
		"cyneek/yii2-widget-urlparser": "dev-master"
		
* Profit!


## Casos de uso del Widget

En todas las opciones, por defecto, el campo de tipo texto que almacena el string de tipo URI filtra automáticamente los datos que se le introducen usando javascript para formar una cadena de caracteres válida. Esto no está relacionado con la validación de UriValidator, sino que es un filtro adicional añadido para añadir un nivel mayor de robustez al funcionamiento del widget.

El widget puede ser usado de varias formas, dependiendo de los datos que se incluyan en su definición:

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
		

No tendrá validación en navegador, tampoco heredará ningún cambio en los formularios o campos input hechos por el desarrollador.

#### Opciones

* model (ActiveRecord o Model) (obligatorio)
> Define el modelo de datos que se usará para crear el campo del formulario.


* attribute (string) (obligatorio)
> Define el atributo del modelo de datos que se usará para crear el campo del formulario.


* url_separator (char) (opcional)
> Es el caracter que se utiliza para sustituir el espacio en la construcción de un string de tipo URI, por defecto es *"-"* 
>**Atención:** Si se modifica, el nuevo valor debe ser a su vez un caracter URI válido.


* regex_definition (char) (opcional)
> Un regex que se usará para definir la validez de los datos introducidos en el campo de URI. En caso de que no sean válidos, no se introducirán.


* maxlength (integer) (opcional)
> Máximo número de caracteres que aceptará el campo.


* source (array) (opcional)
    * model (ActiveRecord o Model) (obligatorio)
    * attribute (string) (obligatorio)
> Define si el widget tendrá una fuente de datos para el string de tipo uri externo.
> Los valores que contiene deben ser siempre un modelo y un atributo de ese modelo de un campo de tipo texto distinto al definido en el widget que contenga ese formulario.

Si se define el campo source, el widget creará un campo de tipo input text que estará declarado como readonly, eso es porque se usará el campo input definido en source como fuente de la cadena de caracteres URI. No obstante, el widget también incluirá un botón que permitirá al usuario desactivar la opción readonly del campo y también el enlace entre los dos campos, permitiendole introducir manualmente la cadena de caracteres en el campo input del widget.

### Junto con un widget ActiveForm

		
		echo $form->field($model, 'fieldName')->widget('\cyneek\yii2\widget\urlparser\UrlParser', ['source' => ['model' => $model, 'attribute' => 'sourceFieldName']]);
		

Tendrá validación en cliente si está activado así en el formulario.


#### Opciones

* url_separator (char) (opcional)
> Es el caracter que se utiliza para sustituir el espacio en la construcción de un string de tipo URI, por defecto es *"-"* 
>**Atención:** Si se modifica, el nuevo valor debe ser a su vez un caracter URI válido.


* source (array) (opcional)
    * model (ActiveRecord o Model) (obligatorio)
    * attribute (string) (obligatorio)
> Define si el widget tendrá una fuente de datos para el string de tipo uri externo.
> Los valores que contiene deben ser siempre un modelo y un atributo de ese modelo de un campo de tipo texto distinto al definido en el widget que contenga ese formulario.


Si se define el campo source, el widget creará un campo de tipo input text que estará declarado como readonly, eso es porque se usará el campo input definido en source como fuente de la cadena de caracteres URI. No obstante, el widget también incluirá un botón que permitirá al usuario desactivar la opción readonly del campo y también el enlace entre los dos campos, permitiendole introducir manualmente la cadena de caracteres en el campo input del widget.

## Uso de UriValidation

Adicionalmente se ha incluído una librería de validación para datos de tipo URI en el paquete. Se encarga de recoger los datos enviados por este Widget y comprobarlos ya sea desde el servidor web o el navegador usando javaScript.

Su uso es idéntico al que podría hacerse de UrlValidator, la única diferencia es que, dado que se trata de un validador adicional no incluído por defecto en el sistema, hay que añadirlo manualmente en el componente Validator, que es el que se encarga de almacenar todos los validadores que hay activos en el sistema. Usualmente se hará desde el método *rules* del modelo que contenga el campo de tipo URI que se quiera validar.

	public function rules()
	{
		// añade UriValidator a la lista de validadores activos del componente Validator
		Validator::$builtInValidators['uri'] = 'cyneek\yii2\widget\urlparser\validators\UriValidator';
		
		return [
				[['name', 'uriField'], 'required'],
				[['name'], 'string'],
				[['name', 'uriField'], 'safe'],
				[['uriField'], 'uri']
		];
	}

A partir de ese momento el validador ya estará activo y funcional para el sistema y podrá ser usado de igual manera que cualquier otro validador de Yii2.