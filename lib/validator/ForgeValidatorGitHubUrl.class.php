<?php

/**
 * undocumented class
 *
 * @package default
 * @author Guillermo Rauch
 **/
class ForgeValidatorGitHubUrl extends sfValidatorUrl
{
	
	const PATTERN = '#(https?://)?(www\.)?github\.com/([^/]+)/([^/]+)(/?|/tree(.*))?#i';
	
  /**
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorRegex
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->setOption('pattern', self::PATTERN);
  }

} // END class ForgeValidatorGitHubUrl extends 