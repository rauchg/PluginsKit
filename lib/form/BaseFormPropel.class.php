<?php

/**
 * Project form base class.
 *
 * @package    mooforge
 * @subpackage form
 * @author     Guillermo Rauch
 * @version    SVN: $Id: BaseFormPropel.class.php 1 2009-09-06 18:35:08Z rauchg@gmail.com $
 */
abstract class BaseFormPropel extends sfFormPropel
{
  public function setup()
  {
  }

	public function toJson($successMsg = '', $forceField = null){
		if (!$this->isValid()){
			$errors = array();
			$widgetSchema = $this->getWidgetSchema();
			$fields = $widgetSchema->getFields();
			foreach ($this->getErrorSchema()->getErrors() as $name => $error){				
				if ($name || $forceField){
					if ($forceField) $name = $forceField;
					$id = $fields[$name]->generateId($widgetSchema->generateName($name));
				} else {
					$id = '__global__';
				}
				
				if (!isset($errors[$id])) $errors[$id] = array();
				$errors[$id][] = $error->getMessage();
			}
			return json_encode(array('errors' => $errors));
		} else {
			return json_encode(array('success' => $successMsg));
		}
	}

}
