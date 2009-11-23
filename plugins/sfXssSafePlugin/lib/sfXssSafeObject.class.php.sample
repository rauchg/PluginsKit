<?php

/**
 * Sample to clean an element (here <object> and <embed>) overloading HTML Purifier 
 *
 */

class HTMLPurifier_AttrTransform_ParamValidator extends HTMLPurifier_AttrTransform 
{
  var $name = "ParamValidator";
  var $uri;
  
  function HTMLPurifier_AttrTransform_ParamValidator()
  {
    $this->uri = new HTMLPurifier_AttrDef_URI();
  }
  
  function transform($attr, $config, $context)
  {
    switch ($attr['name'])
    {
      case 'allowScriptAccess':
      case 'allowscriptaccess':
        $attr['value'] = 'sameDomain';
        break;
      case 'wmode':
          $attr['value'] = 'transparent';
          break;
      case 'enablejsurls':
        $attr['value'] = 'false';
        break;
      case 'movie':
        $attr['value'] = $this->uri->validate($attr['value'], $config, $context);
        break;
      case 'allowFullScreen':
      case 'allowfullscreen':
        $attr['value'] = 'false';
        break;
      default:
        $attr['name'] = $attr['value'] = null;
    }
    return $attr;
  }
}

class HTMLPurifier_AttrTransform_ObjectValidator extends HTMLPurifier_AttrTransform
{
  var $name = "ObjectValidator";

  function transform($attr, $config, $context)
  {
    if (!isset($attr['type']))
    {
      $attr['type'] = 'application/x-shockwave-flash';
    }
    return $attr;
  }
}

class HTMLPurifier_AttrTransform_EmbedValidator extends HTMLPurifier_AttrTransform 
{
  var $name = "EmbedValidator";

  function transform($attr, $config, $context)
  {
    $attr['allowscriptaccess'] = 'never';
    $attr['enablejsurls'] = 'false';
    $attr['enablehref'] = 'false';
    return $attr;
  }
}

/**
 * Injects tokens for reformatting
 */
class HTMLPurifier_Injector_AddParam extends HTMLPurifier_Injector
{
  var $name = 'AddParam';
  var $needed  = array('object', 'param');
  function handleElement(&$token)
  {
    if ($token->name == 'object')
    {
      $token = array(
        $token,
        new HTMLPurifier_Token_Start('param', array(
          'name' => 'enablejsurls',
          'value' => 'false'))
      );
    }
  }
}

?>