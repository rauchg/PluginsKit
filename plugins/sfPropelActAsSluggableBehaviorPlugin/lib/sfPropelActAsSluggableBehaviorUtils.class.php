<?php
/*
 * This file is part of the sfPropelActAsSluggableBehavior package.
 * 
 * (c) 2006-2007 Guillermo Rauch (http://devthought.com)
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class sfPropelActAsSluggableBehaviorUtils
{
  public static function stripText($text, $separator = '-')
  {
    // convert special characters
    $text = utf8_decode($text);
    $text = htmlentities($text);
    $text = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde);/', '$1', $text);
    $text = html_entity_decode($text);
    
    $text = strtolower($text);

    // strip all non word chars
    $text = preg_replace('/\W/', ' ', $text);

    // replace all white space sections with a separator
    $text = preg_replace('/\ +/', $separator, $text);

    // trim separators
    $text = trim($text, $separator);
    //$text = preg_replace('/\-$/', '', $text);
    //$text = preg_replace('/^\-/', '', $text);
        
    return $text;
  }

  
}

?>