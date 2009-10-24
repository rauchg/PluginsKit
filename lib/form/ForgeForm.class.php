<?php

/**
 * Forge base form
 *
 * @package forge
 * @subpackage form
 * @author Guillermo Rauch
 **/
class ForgeForm extends sfForm
{
	
	/**
	 * Override regular validation schema class
	 *
	 * @return void
	 * @author Guillermo Rauch
	 */
	public function setValidators(array $validators){
    $this->setValidatorSchema(new ForgeValidatorSchema($validators));
	}

	/**
	 * JSON expression of the success of errors of the form
	 *
	 * @param string $successMsg Optionally, a success message to return
	 * @param string $forceField If supplied, all errors are assigned this key (which normally is a field of the form)
	 * @return string JSON response
	 * @author Guillermo Rauch
	 */
	public function toJson($successMsg = '', $forceField = null)
	{
		if ($this->isValid()) {
			return json_encode(array('success' => $successMsg));
		}

		$errors = array();
		$widgetSchema = $this->getWidgetSchema();
		$fields = $widgetSchema->getFields();

		foreach ($this->getErrorSchema()->getErrors() as $name => $error) {
			$id = '__global__';

			if ($name != null || $forceField) {
				if ($forceField !== null) {
					$name = $forceField;
				}

				if (isset($fields[$name])) {
					$id = $fields[$name]->generateId($widgetSchema->generateName($name));
				}
			}

			if (!in_array($id, $errors)) {
				$errors[$id] = array();
			}
			$errors[$id][] = $error->getMessage();
		}

		return json_encode(array('errors' => $errors));
	}
	
} // END class ForgeForm extends sfForm