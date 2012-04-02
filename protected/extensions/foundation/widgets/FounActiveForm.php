<?php
/**
 * FounActiveForm class file.
 * @author Alex Urbano <asgaroth.belem@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package foundation.widgets
 */
Yii::import('foundation.widgets.input.FounInput');

/**
 * Foundation active form widget.
 */
class FounActiveForm extends CActiveForm {
	// Form types.
	const TYPE_NORMAL = '';
	const TYPE_NICE = 'nice';
	const TYPE_CUSTOM = 'custom';

	// Input classes.
	private $inpuTypes = array(
		self::TYPE_NORMAL => 'foundation.widgets.input.FounInputNormal',
		self::TYPE_NICE => 'foundation.widgets.input.FounInputNice',
		self::TYPE_CUSTOM => 'foundation.widgets.input.FounInputCustom',
	);

	/**
	 * @var string the form type. See class constants.
	 */
	public $type = self::TYPE_NORMAL;
	
	/**
	 * Initializes the widget.
	 * This renders the form open tag.
	 */
	public function init()
	{
		if (!isset($this->htmlOptions['class'])){
			$this->htmlOptions['class'] = $this->type;
		}else{
			$this->htmlOptions['class'] .= $this->type;
		}
		parent::init();
	}
	
	/**
	 * Creates an input row of a specific type.
	 * @param string $type the input type
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $data the data for list inputs
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function inputRow($type, $model, $attribute, $data = null, $htmlOptions = array()) {
		return Yii::app() -> controller -> widget($this->inpuTypes[$this->type], array(
			'type' => $type, 
			'form' => $this, 
			'model' => $model, 
			'attribute' => $attribute, 
			'data' => $data, 
			'htmlOptions' => $htmlOptions
		), true);
	}

	/**
	 * Renders a text field input row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function textFieldRow($model, $attribute, $htmlOptions = array()) {
		return $this -> inputRow(FounInput::TYPE_TEXT, $model, $attribute, null, $htmlOptions);
	}
	
	/**
	 * Renders a text area input row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function textAreaRow($model, $attribute, $htmlOptions = array()) {
		return $this -> inputRow(FounInput::TYPE_TEXTAREA, $model, $attribute, null, $htmlOptions);
	}
	
	/**
	 * Renders a radio button list input row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $data the list data
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function radioButtonListRow($model, $attribute, $data = array(), $htmlOptions = array()) {
		return $this -> inputRow(FounInput::TYPE_RADIOLIST, $model, $attribute, $data, $htmlOptions);
	}
	
	/**
	 * Renders a radio button list for a model attribute.
	 * This method is a wrapper of {@link CHtml::activeRadioButtonList}.
	 * Please check {@link CHtml::activeRadioButtonList} for detailed information
	 * about the parameters for this method.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $data value-label pairs used to generate the radio button list.
	 * @param array $htmlOptions additional HTML options.
	 * @return string the generated radio button list
	 * @since 0.9.5
	 */
	public function radioButtonList($model, $attribute, $data, $htmlOptions = array()) {
		return $this -> inputsList(false, $model, $attribute, $data, $htmlOptions);
	}
    
    /**
     * Renders a checkbox input row.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes
     * @return string the generated row
     */
    public function checkBoxRow($model, $attribute, $htmlOptions = array()) {
        return $this -> inputRow(FounInput::TYPE_CHECKBOX, $model, $attribute, null, $htmlOptions);
    }
    
    /**
     * Renders a radio button input row.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes
     * @return string the generated row
     */
    public function radioButtonRow($model, $attribute, $htmlOptions = array()) {
        return $this -> inputRow(FounInput::TYPE_RADIO, $model, $attribute, null, $htmlOptions);
    }
    
    /**
     * Renders a checkbox list input row.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $data the list data
     * @param array $htmlOptions additional HTML attributes
     * @return string the generated row
     */
    public function checkBoxListRow($model, $attribute, $data = array(), $htmlOptions = array()) {
        return $this -> inputRow(FounInput::TYPE_CHECKBOXLIST, $model, $attribute, $data, $htmlOptions);
    }
    
     /**
     * Renders a drop-down list input row.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $data the list data
     * @param array $htmlOptions additional HTML attributes
     * @return string the generated row
     */
    public function dropDownListRow($model, $attribute, $data = array(), $htmlOptions = array()) {
        return $this -> inputRow(FounInput::TYPE_DROPDOWN, $model, $attribute, $data, $htmlOptions);
    }
    
	/**
	 * Renders an input list.
	 * @param boolean $checkbox flag that indicates if the list is a checkbox-list.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $data value-label pairs used to generate the input list.
	 * @param array $htmlOptions additional HTML options.
	 * @return string the generated input list.
	 * @since 0.9.5
	 */
	protected function inputsList($checkbox, $model, $attribute, $data, $htmlOptions = array()) {
		CHtml::resolveNameID($model, $attribute, $htmlOptions);
		$select = CHtml::resolveValue($model, $attribute);

		if ($model -> hasErrors($attribute)) {
			if (isset($htmlOptions['class']))
				$htmlOptions['class'] .= ' ' . CHtml::$errorCss;
			else
				$htmlOptions['class'] = CHtml::$errorCss;
		}

		$name = $htmlOptions['name'];
		unset($htmlOptions['name']);

		if (array_key_exists('uncheckValue', $htmlOptions)) {
			$uncheck = $htmlOptions['uncheckValue'];
			unset($htmlOptions['uncheckValue']);
		} else
			$uncheck = '';

		$hiddenOptions = isset($htmlOptions['id']) ? array('id' => CHtml::ID_PREFIX . $htmlOptions['id']) : array('id' => false);
		$hidden = $uncheck !== null ? CHtml::hiddenField($name, $uncheck, $hiddenOptions) : '';

		if (isset($htmlOptions['template']))
			$template = $htmlOptions['template'];
		else
			$template = '<label for="{for}">{input} {label}</label>';

		unset($htmlOptions['template'], $htmlOptions['separator'], $htmlOptions['hint']);

		if ($checkbox && substr($name, -2) !== '[]')
			$name .= '[]';

		unset($htmlOptions['checkAll'], $htmlOptions['checkAllLast']);

		$labelOptions = isset($htmlOptions['labelOptions']) ? $htmlOptions['labelOptions'] : array();
		unset($htmlOptions['labelOptions']);

		$items = array();
		$baseID = CHtml::getIdByName($name);
		$id = 0;
		$method = $checkbox ? 'checkBox' : 'radioButton';


		foreach ($data as $value => $label) {
			$checked = !is_array($select) && !strcmp($value, $select) || is_array($select) && in_array($value, $select);
			$htmlOptions['value'] = $value;
			$htmlOptions['id'] = $baseID . '_' . $id++;
			$option = CHtml::$method($name, $checked, $htmlOptions);
			$labelOptions["for"] = $htmlOptions['id'];
			$items[] = CHtml::tag("label", $labelOptions, $option.$label);
		}
		return $hidden . implode('', $items);
	}

	/**
	 * Displays the first validation error for a model attribute.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute name
	 * @param array $htmlOptions additional HTML attributes to be rendered in the container div tag.
	 * @param boolean $enableAjaxValidation whether to enable AJAX validation for the specified attribute.
	 * @param boolean $enableClientValidation whether to enable client-side validation for the specified attribute.
	 * @return string the validation result (error display or success message).
	 */
	public function error($model, $attribute, $htmlOptions = array(), $enableAjaxValidation = true, $enableClientValidation = true) {
		if (!$this -> enableAjaxValidation)
			$enableAjaxValidation = false;

		if (!$this -> enableClientValidation)
			$enableClientValidation = false;

		if (!$enableAjaxValidation && !$enableClientValidation)
			return $this -> getErrorHtml($model, $attribute, $htmlOptions);

		$id = CHtml::activeId($model, $attribute);
		$inputID = isset($htmlOptions['inputID']) ? $htmlOptions['inputID'] : $id;
		unset($htmlOptions['inputID']);
		if (!isset($htmlOptions['id']))
			$htmlOptions['id'] = $inputID . '_em_';

		$option = array('id' => $id, 'inputID' => $inputID, 'errorID' => $htmlOptions['id'], 'model' => get_class($model), 'name' => CHtml::resolveName($model, $attribute), 'enableAjaxValidation' => $enableAjaxValidation, 'inputContainer' => 'div.control-group',  // Bootstrap requires this
		);

		$optionNames = array('validationDelay', 'validateOnChange', 'validateOnType', 'hideErrorMessage', 'inputContainer', 'errorCssClass', 'successCssClass', 'validatingCssClass', 'beforeValidateAttribute', 'afterValidateAttribute', );

		foreach ($optionNames as $name) {
			if (isset($htmlOptions[$name])) {
				$option[$name] = $htmlOptions[$name];
				unset($htmlOptions[$name]);
			}
		}

		if ($model instanceof CActiveRecord && !$model -> isNewRecord)
			$option['status'] = 1;

		if ($enableClientValidation) {
			$validators = isset($htmlOptions['clientValidation']) ? array($htmlOptions['clientValidation']) : array();
			foreach ($model->getValidators($attribute) as $validator) {
				if ($enableClientValidation && $validator -> enableClientValidation) {
					if (($js = $validator -> clientValidateAttribute($model, $attribute)) != '')
						$validators[] = $js;
				}
			}

			if ($validators !== array())
				$option['clientValidation'] = "js:function(value, messages, attribute) {\n" . implode("\n", $validators) . "\n}";
		}

		$html=$this -> getErrorHtml($model, $attribute, $htmlOptions);

		if ($html === '') {
			if (isset($htmlOptions['style']))
				$htmlOptions['style'] = rtrim($htmlOptions['style'], ';') . ';display: none';
			else
				$htmlOptions['style'] = 'display: none';

			$html = CHtml::tag('mall', $htmlOptions, '');
		}

		$this -> attributes[$inputID] = $option;
		return $html;
	}
	
	
	
	/**
	 * Displays the first validation error for a model attribute.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute name
	 * @param array $htmlOptions additional HTML attributes to be rendered in the container div tag.
	 * @param string $tag the tag to use for rendering the error.
	 * @return string the error display. Empty if no errors are found.
	 * @see CModel::getErrors
	 * @see errorMessageCss
	 */
	public static function getErrorHtml($model, $attribute, $htmlOptions = array())
	{
		CHtml::resolveName($model, $attribute);
		$error = $model->getError($attribute);

		if ($error !== null)
			return CHtml::tag('small', $htmlOptions, $error); // Bootstrap errors must be spans
		else
			return '';
	}
    
    /**
     * Displays a summary of validation errors for one or several models.
     * This method is very similar to {@link CHtml::errorSummary} except that it also works
     * when AJAX validation is performed.
     * @param mixed $models the models whose input errors are to be displayed. This can be either
     * a single model or an array of models.
     * @param string $header a piece of HTML code that appears in front of the errors
     * @param string $footer a piece of HTML code that appears at the end of the errors
     * @param array $htmlOptions additional HTML attributes to be rendered in the container div tag.
     * @return string the error summary. Empty if no errors are found.
     * @see CHtml::errorSummary
     */
    public function errorSummary($models, $header = null, $footer = null, $htmlOptions = array()) {
        if (!isset($htmlOptions['class']))
            $htmlOptions['class'] = 'alert-box error';
        // Bootstrap error class as default

        return parent::errorSummary($models, $header, $footer, $htmlOptions);
    }
	
	
	
	
	
	
	
	
	
	


	/**
	 * Renders a file field input row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function fileFieldRow($model, $attribute, $htmlOptions = array()) {
		return $this -> inputRow(BootInput::TYPE_FILE, $model, $attribute, null, $htmlOptions);
	}

	/**
	 * Renders a password field input row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions additional HTML attributes
	 * @return string the generated row
	 */
	public function passwordFieldRow($model, $attribute, $htmlOptions = array()) {
		return $this -> inputRow(BootInput::TYPE_PASSWORD, $model, $attribute, null, $htmlOptions);
	}

	/**
	 * Renders a captcha row.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions additional HTML attributes
	 * @param array $captchaOptions the captcha options
	 * @return string the generated row
	 * @since 0.9.3
	 */
	public function captchaRow($model, $attribute, $htmlOptions = array(), $captchaOptions = array()) {
		return $this -> inputRow(BootInput::TYPE_CAPTCHA, $model, $attribute, $captchaOptions, $htmlOptions);
	}

	/**
	 * Renders a checkbox list for a model attribute.
	 * This method is a wrapper of {@link CHtml::activeCheckBoxList}.
	 * Please check {@link CHtml::activeCheckBoxList} for detailed information
	 * about the parameters for this method.
	 * @param CModel $model the data model
	 * @param string $attribute the attribute
	 * @param array $data value-label pairs used to generate the check box list.
	 * @param array $htmlOptions additional HTML options.
	 * @return string the generated check box list
	 * @since 0.9.5
	 */
	public function checkBoxList($model, $attribute, $data, $htmlOptions = array()) {
		return $this -> inputsList(true, $model, $attribute, $data, $htmlOptions);
	}


}
