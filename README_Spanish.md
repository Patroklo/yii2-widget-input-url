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

## Opciones del método Widget

* model (ActiveRecord o Model) (obligatorio)
> Define el modelo de datos que se usará para crear el campo del formulario.


* attribute (string) (obligatorio)
> Define el atributo del modelo de datos que se usará para crear el campo del formulario.


* form (ActiveForm) (opcional)
> Es el objeto Form que define el formulario en el que está incluído el widget. Se usará para heredar las configuraciones del formulario a la hora de crear el campo.


* enableClientValidation (boolean) (opcional)
> Usado cuando no se pasa el objeto formulario. Define si está activado o no en el widget la validación en navegador mediante javascript.


* url_separator (char) (opcional)
> Es el caracter que se utiliza para sustituir el espacio en la construcción de un string de tipo URI, por defecto es *"-"* 
>**Atención:** Si se modifica, el nuevo valor debe ser a su vez un caracter URI válido.


* source (array) (opcional)
    * model (ActiveRecord o Model) (obligatorio)
    * attribute (string) (obligatorio)
> Define si el widget tendrá una fuente de datos para el string de tipo uri externo.
> Los valores que contiene deben ser siempre un modelo y un atributo de ese modelo de un campo de tipo texto distinto al definido en el widget que contenga ese formulario.


## Opciones del método estático addManualValidation

El método addManualValidation tan solo debe ser llamado en caso de que no hayamos asignado en el método Widget la opción *form* y queramos activar al campo validación en cliente. Esta validación en el navegador será para todas las reglas que se le hayan asignado al campo en el modelo, no solo la validación de UriValidator.  

* model (string) (obligatorio)
> Define el modelo de datos que se usará para crear el campo del formulario.


* attribute (string) (obligatorio)
> Define el atributo del modelo de datos que se usará para crear el campo del formulario.


## Casos de uso del Widget

Dado que esta librería es un campo widget separado del sistema de formularios en sí mismo existe el problema de que no se puede crear por defecto el campo de forma normal incluyendo todas las opciones del formulario en el que se encuentra al igual que los campos input normales de Yii2, esto afecta a validaciones desde cliente, modificaciones css o en php que se hayan realizado, etc.. Para solucionar esto se ha añadido una opción adicional en el widget que se encarga de recoger el formulario en el que se encuentra incluído el widget y lo usa para crear los campos internos de tipo input text que tiene y así adecuarlo al resto del diseño.

En todas las opciones, por defecto, el campo de tipo texto que almacena el string de tipo URI filtra automáticamente los datos que se le introducen usando javascript para formar una cadena de caracteres válida. Esto no está relacionado con la validación de UriValidator, sino que es un filtro adicional añadido para añadir un nivel mayor de robustez al funcionamiento del widget.

El widget puede ser usado de varias formas, dependiendo de los datos que se incluyan en su definición:

### 1. Sin campo fuente ni usando referencia del formulario

Esto generará un campo de tipo texto en el que el usuario tendrá que rellenar manualmente la cadena de caracteres de tipo URI que quiere enviar al sistema. 

El caso de uso más sencillo de este widget es el de definir el campo de URI en solitario sin incluir una referencia al formulario. Si tan solo incluimos esta línea de código no tendríamos validación automática en el navegador y existe la probabilidad de que los cambios adicionales que se hayan realizado por parte del desarrollador en algún campo no aparezcan en este widget:

		
		echo \cyneek\yii2\widget\urlparser\UrlParser::widget(['model' => $model, 'attribute' => 'fieldName']);
		

Si lo que queremos es añadir a este caso **validación en el navegador** para este widget habrá que incluir una línea más **DESPUES** de cerrar el formulario en el que está incluído el widget:

		
		echo \cyneek\yii2\widget\urlparser\UrlParser::addManualValidation(['model' => $model, 'attribute' => 'fieldName']);
		

Esta llamada a un método estático incluirá un código JavaScript que añadirá validación en el navegador para este campo de tipo text en el widget.

**Atención:** Tan sólo debe incluirse esta línea si el resto del formulario también tiene la validación en el navegador activada.

Adicionalmente hay una configuración opcional que puede añadirse a la definición del widget que desactivaría la validación en navegador para este caso, aunque no está recomendado su uso, para ello habría que utilizar esta definición en lugar de la incluida anteriormente:

		
		echo \cyneek\yii2\widget\urlparser\UrlParser::widget(['model' => $model, 'attribute' => 'fieldName', 'enableClientValidation' => TRUE|FALSE]);
		

Dependiendo del valor que tenga enableClientValidation en este caso, el campo del widget tendrá activada o no la validación en cliente.

### 2. Sin campo fuente pero usando referencia del formulario

Este caso generará un campo de tipo texto que el usuario tendrá que rellenar manualmente con una cadena de caracteres de tipo URI que quiera enviar al sistema pero automáticamente tendrá por defecto incluídas las mismas opciones que el resto de campos que se utilice en el formulario. Este caso es más directo que el anterior, ya que no es necesario incluir ninguna llamada a ningún método adicional del widget para que funcione apropiadamente.

		
		echo \cyneek\yii2\widget\urlparser\UrlParser::widget(['form' => $form, 'model' => $model, 'attribute' => 'fieldName']);
		

En este caso, si el formulario tiene declarada validación en cliente, el campo que añada el widget también la tendrá por defecto, si la tiene desactivada, el campo widget también la tendrá. No sería necesario incluir nada más.

### 3. Con campo fuente pero sin referencia del formulario

Este caso generará un campo de tipo texto que estará bloqueado a la escritura por defecto, ya que usará como origen del string de tipo URI un input externo del formulario que nosotros declararemos en la creación del widget. No obstante también se incluye un botón en el widget que permitirá al usuario desactivar este enlace e introducir manualmente los caracteres en el widget de forma independiente a la fuente de datos que le hayamos definido.

		
		echo \cyneek\yii2\widget\urlparser\UrlParser::widget(['model' => $model, 'attribute' => 'fieldName', 'source' => ['model' => $model, 'attribute' => 'sourceFieldName']]);
		

Al igual que el caso número 1, el widget, al no tener incluído el formulario en su definición, no podrá incluir validaciones en el input que introduce. Para ello también habrá que incluir una llamada adicional **DESPUES** de cerrar el formulario en el que está incluído el widget:

		
		echo \cyneek\yii2\widget\urlparser\UrlParser::addManualValidation(['model' => $model, 'attribute' => 'fieldName']);
		

Esta llamada a un método estático incluirá un código JavaScript que añadirá validación en el navegador para este campo de tipo text en el widget.

**Atención:** Tan sólo debe incluirse esta línea si el resto del formulario también tiene la validación en el navegador activada.

También es posible añadir la opción enableClientValidation que se ha explicado en el punto 1 de esta sección para activar / desactivar manualmente la validación en cliente de este widget.

### 4. Con campo fuente y con referencia al formulario

Este caso es el más completo de todos. Incluye el uso de un campo externo al widget como origen del string de tipo URI que se introducirá automáticamente en este, la opción de desactivar este enlace manualmente por parte del usuario, y se le aplicarán las configuraciones del formulario en el que está incluído el widget de forma automática. Para crearlo tan solo habría que incluir el código:

		
		echo \cyneek\yii2\widget\urlparser\UrlParser::widget(['form' => $form, 'model' => $model, 'attribute' => 'fieldName', 'source' => ['model' => $model, 'attribute' => 'sourceFieldName']]);
		

En este caso, si el formulario tiene declarada validación en cliente, el campo que añada el widget también la tendrá por defecto, si la tiene desactivada, el campo widget también la tendrá. No sería necesario incluir nada más.


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