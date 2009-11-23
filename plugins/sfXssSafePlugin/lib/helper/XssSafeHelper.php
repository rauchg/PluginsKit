<?php

/**
 * XssSafe Helper - Clean cross site scripting exploits from string 
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Alexandre Mogère
 *
 * @uses <a href="http://htmlpurifier.org/">HTML Purifier</a>
 */

define('HTMLPURIFIER_PREFIX', realpath(dirname(__FILE__) . '/../vendor/htmlpurifier'));

if (!class_exists('HTMLPurifier_PropertyList'))
{
  require_once(HTMLPURIFIER_PREFIX . '/HTMLPurifier.auto.php');
}

/**
 * The function runs HTML Purifier as an alternative between
 * escaping raw and escaping entities.
 *
 * @param string $dirty_html the value to clean
 * @return string the escaped value
 */
function esc_xsssafe($dirty_html)
{
  if (false === $dirty_html || null === $dirty_html || 0 === $dirty_html)
  {
    return '';
  }
  
  //set_error_handler('XssSafeErrorHandler');
  
  static $purifier = false;
  
  if (!$purifier)
  {
    $hasCustom     = false;
    $aElements     = array();
    $aAttributes   = array();
    
    // sets configuration
    $config        = HTMLPurifier_Config::createDefault();

    $definitions   = sfConfig::get('app_sfXssSafePlugin_definition');
    if (!empty($definitions))
    {
      foreach ($definitions as $def => $conf)
      {
        if (!empty($conf))
        {
          foreach ($conf as $directive => $values)
          {
            if ($def == 'AutoFormat' && $directive != 'Custom')
            {
              // customizable elements
              if ($directive == 'Element')
              {
                $aElements = $values;
              }
              // customizable attributes
              else if($directive == 'Attribute')
              {
                $aAttributes = $values;
              }
              $hasCustom = true;
            }
            else
            {
              if (($def == 'AutoFormat' && $directive == 'Custom')
                  &&
                !class_exists("HTMLPurifier_Injector_$values"))
              {
                continue;
              }
              $config->set(sprintf("%s.%s", $def, $directive), $values);
              // $values can be a string or an ArrayList
            }
          }
        }
      }
    }

    if (sfConfig::get('sf_environment') == 'dev' || sfConfig::get('sf_environment') == 'test')
    {
      // turns off cache
      $config->set(sprintf("%s.%s", 'Cache', 'DefinitionImpl'), null);
    }
    else
    {
      // sets the cache directory into Symfony cache directory
      $config->set(sprintf("%s.%s", 'Cache', 'DefinitionImpl'), sfConfig::get('sf_cache_dir'));
    }

    if ($hasCustom)
    {
      $def = $config->getHTMLDefinition(true);

      // adds custom elements
      if (!empty($aElements))
      {
        foreach ($aElements as $name => $element)
        {
          $name = strtolower($name);
          ${$name} = $def->addElement(
            $name,
            $element['type'],
            $element['contents'],
            $element['attr_includes'],
            $element['attr']
          );
          $factory = 'HTMLPurifier_AttrTransform_'.ucfirst($name).'Validator';
          if (class_exists($factory))
          {
            ${$name}->attr_transform_post[] = new $factory();
          }
        }
      }
      
      // adds custom attributs
      if (!empty($aAttributes))
      {
        foreach ($aAttributes as $name => $attr)
        {
          $name = strtolower($name);
          ${$name} = $def->addAttribute(
            $name,
            $attr['attr_name'],
            $attr['def']
          );
        }
      }
    }

    $purifier = new HTMLPurifier($config);
  }
  
  $clean_html = $purifier->purify($dirty_html);
  
  restore_error_handler();
  return $clean_html;
}

define('ESC_XSSSAFE', 'esc_xsssafe');

/**
 * Error handler.
 *
 * @param mixed Error number
 * @param string Error message
 * @param string Error file
 * @param mixed Error line
 */
function XssSafeErrorHandler($errno, $errstr, $errfile, $errline)
{
  if (($errno & error_reporting()) == 0)
  {
    return;
  }

  throw new sfException(sprintf('{XssSafeHelper} Error at %s line %s (%s)',
    $errfile,
    $errline,
    $errstr)
  );
}

?>