<?php
namespace Mouf\MVC\BCE\Classes\Descriptors;

/**
 * This class is the simpliest FieldDescriptor:
 * it handles a field that has no "connections" to other objects (
 * as user name or login for example)
 * @Component
 */
class BaseFieldDescriptor extends FieldDescriptor {

	/**
	 * The name of the function that retruns the value of the field from the bean.
	 * For example, with $user->getLogin(), the $getter property should be "getLogin"
	 * @Property
	 * @var string
	 */
	public $getter;
	
	/**
	 * The name of the function that sets the value of the field into the bean.
	 * For example, with $user->setLogin($login), the $setter property should be "setLogin"
	 * @Property
	 * @var string
	 */
	public $setter;

	/**
	 * Loads the values of the bean into the descriptors, calling main bean's getter
	 * Eventually formats the value before displaying it 	
	 * @param mixed $mainBean
	 */
	public function load($mainBean, $id = null, &$form = null, $isIdFieldDescriptor = false){
		$fieldValue = $this->getValue($mainBean);
		if ($isIdFieldDescriptor && !$id) {
			//In this case, field's value is the ID
			$descriptorInstance = new FieldDescriptorInstance($this, $form, $fieldValue);
		}else{
			$descriptorInstance = new FieldDescriptorInstance($this, $form, $id);
		}
		$descriptorInstance->value = $fieldValue;
		return $descriptorInstance;
	}
	
	public function getValue($mainBean){
		if ($mainBean == null){
			$fieldValue = $this->getDefaultValue();
		}else{
			$fieldValue = call_user_func(array($mainBean, $this->getter));
			if ($this->formatter){
				$fieldValue = $this->formatter->format($fieldValue);
			}
		}
		return $fieldValue;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see BCEFieldDescriptorInterface::postSave()
	 */
	public function postSave($bean, $beanId, $postValues = null){
		return;
	} 
	
	/**
	 * Simply calls the setter of the descriptor's related field into the bean
	 * @param mixed $mainBean
	 * @param mixed $value
	 */
	public function setValue($mainBean, $value){
		call_user_func(array($mainBean, $this->setter), $value);
	}

	/**
	 * Returns the bean's value after loading the descriptor
	 */
	public function getFieldValue(){
		return $this->value;
	}

}