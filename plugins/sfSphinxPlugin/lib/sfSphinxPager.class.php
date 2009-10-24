<?php
/**
 * sfSphinx pager class
 * @deprecated    this class has been deprecated in favor of sfSphinxPropelPager
 * @package sfSphinxPlugin
 * @author  Hung Dao <hungdao@mahshelf.com>
 */

class sfSphinxPager extends sfSphinxPropelPager
{
  /**
   * Constructor
   * @param object         $class
   * @param integer        $maxPerPage
   * @param sfSphinxClient $sphinx
   */
  public function __construct($class, $maxPerPage = 10, sfSphinxClient $sphinx)
  {
    if (sfConfig::get('sf_logging_enabled'))
    {
      sfContext::getInstance()->getEventDispatcher()->notify(new sfEvent(null, 'application.log', array('Class ' . __CLASS__ . ' is deprecated in favor of sfSphinxPropelPager.', 'priority' => sfLogger::ERR)));
    }
    parent::__construct($class, $maxPerPage, $sphinx);
  }
}
