<?php
/*
 * This file is part of the sfPropelActAsSluggableBehavior package.
 * 
 * (c) 2006-2007 Guillermo Rauch (http://devthought.com)
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
/**
 * This behavior automates the generation of 'slugs' based on the return value of a model method.
 * Its code is inspired in sfPropelActAsNestedSetBehavior, from which some functions were taken.
 *
 * @author  Guillermo Rauch (http://devthought.com)
 */
 
class sfPropelActAsSluggableBehavior
{

  private $default_columns = array('from' => 'title', 'to' => 'slug');
  private $default_separator = '-';
  private $default_permanent = false;

  /**
   * Called before node is saved
   *
   * @param   BaseObject  $node
   */
  public function preSave(BaseObject $node)
  {  
    $conf_permanent = sprintf('propel_behavior_sfPropelActAsSluggableBehavior_%s_permanent', get_class($node));
    $permanent = sfConfig::has($conf_permanent) ? sfConfig::get($conf_permanent) : $this->default_permanent;
    
    $getter = self::forgeMethodName($node, 'get', 'to');
    
    if (!$permanent || $node->isNew() || !$node->$getter())
    {
      $slug = $this->createSlug($node);    
    
      $to_setter = self::forgeMethodName($node, 'set', 'to');    
      $node->$to_setter($slug);
    }
  }
  
  /**
   * Returns the appropiate slug to save
   *
   * @param   BaseObject  $node
   * @param   string      $from     Column from which slug is generated
   */
  public function createSlug(BaseObject $node)
  {
    $peer_name = get_class($node->getPeer());
    $node_class = get_class($node);
    
    $getter = self::forgeMethodName($node, 'get', 'from');
    $column = self::getColumnConstant($node_class, 'to');
    
    $conf_separator = sprintf('propel_behavior_sfPropelActAsSluggableBehavior_%s_separator', $node_class);
    $separator = sfConfig::has($conf_separator) ? sfConfig::get($conf_separator) : $this->default_separator;
    
    $slug = sfPropelActAsSluggableBehaviorUtils::stripText($node->$getter(), $separator);
    $ret  = $slug;
    $i = 0;
    
    while(1)
    {   
      $c = new Criteria();
      $c->add($column, $ret);
      $entry = call_user_func(array($peer_name, 'doSelectOne'), $c);
    
      if($entry && !$entry->equals($node)) {
        $i++;
        $ret = $slug . $separator . $i;
      } else {
        return $ret;
      }
    }
  }
  
  /**
   * Returns the appropriate column name.
   * 
   * @author  Tristan Rivoallan 
   * @param   string   $node_class               Propel model class
   * @param   string   $column                   "generic" column name (either parent, left, right, scope)
   * @param   bool     $skip_table_name_prefix   Removes table name from column name if true (defaults to false)
   * 
   * @return  string   Column's name
   */
  private static function getColumnConstant($node_class, $column, $skip_table_name_prefix = false)
  {
    $conf_directive = sprintf('propel_behavior_sfPropelActAsSluggableBehavior_%s_columns', $node_class);
    $columns = sfConfig::get($conf_directive);

    return $skip_table_name_prefix ? substr($columns[$column], strpos($columns[$column], '.') + 1) : $columns[$column];    
  }

  /**
   * Returns getter / setter name for requested column.
   * 
   * @author  Tristan Rivoallan 
   * @param   BaseObject  $node
   * @param   string      $prefix     get|set|...
   * @param   string      $column     from|to
   */
  private static function forgeMethodName($node, $prefix, $column)
  {
    $method_name = sprintf('%s%s', $prefix, $node->getPeer()->translateFieldName(self::getColumnConstant(get_class($node), $column), 
                                                                        BasePeer::TYPE_COLNAME, 
                                                                        BasePeer::TYPE_PHPNAME));
    return $method_name;
  }
}


?>