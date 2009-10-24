<?php
/**
 * sfSphinx Propel pager class
 * @package sfSphinxPlugin
 * @author  Hung Dao <hungdao@mahshelf.com>
 */

class sfSphinxPager extends sfPropelPager
{
  protected
    $peer_method_name       = 'retrieveByPKs',
    $keyword                = null,
    $sphinx                 = null;

  /**
   * Constructor
   * @param object         $class
   * @param integer        $maxPerPage
   * @param sfSphinxClient $sphinx
   */
  public function __construct($class, $maxPerPage = 10, sfSphinxClient $sphinx)
  {
    parent::__construct($class, $maxPerPage);
    $this->sphinx = $sphinx;
  }

  /**
   * A function to be called after parameters have been set
   */
  public function init()
  {
    $hasMaxRecordLimit = ($this->getMaxRecordLimit() !== false);
    $maxRecordLimit = $this->getMaxRecordLimit();

    $res = $this->sphinx->getRes();
    if ($res === false)
    {
      return;
    }

    $count = $res['total_found'];

    $this->setNbResults($hasMaxRecordLimit ? min($count, $maxRecordLimit) : $count);

    if (($this->getPage() == 0 || $this->getMaxPerPage() == 0))
    {
      $this->setLastPage(0);
    }
    else
    {
      $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));

      $offset = ($this->getPage() - 1) * $this->getMaxPerPage();

      if ($hasMaxRecordLimit)
      {
        $maxRecordLimit = $maxRecordLimit - $offset;
        if ($maxRecordLimit > $this->getMaxPerPage())
        {
          $limit = $this->getMaxPerPage();
        }
        else
        {
          $limit = $maxRecordLimit;
        }
      }
      else
      {
        $limit= $this->getMaxPerPage();
      }
      $this->sphinx->SetLimits($offset, $limit);
    }
  }

  /**
   * Retrieve an object of a certain model with offset
   * used internally by getCurrent()
   * @param  integer $offset
   * @return object
   */
  protected function retrieveObject($offset)
  {
    $this->sphinx->SetLimits($offset - 1, 1); // We only need one object

    $res = $this->sphinx->getRes();
    if ($res['total_found'])
    {
      $ids = array();
      foreach ($res['matches'] as $match)
      {
        $ids[] = $match['id'];
      }

      // be smart and try to use best peer method
      $peer_method = $this->getPeerMethod();
      if ($peer_method == 'retrieveByPks')
      {
        $results = call_user_func(array($this->getClassPeer(), $peer_method), $ids);
      }
      else
      {
        $results = call_user_func(array($this->getClassPeer(), $peer_method), $this->getCriteria());
      }
      return is_array($results) && isset($results[0]) ? $results[0] : null;
    }
    else
    {
      return null;
    }
  }

  /**
   * Return an array of result on the given page
   * @return array
   */
  public function getResults()
  {
    $res = $this->sphinx->getRes();
    if ($res['total_found'])
    {
      // First we need to get the Ids
      $ids = array();
      foreach ($res['matches'] as $match)
      {
        $ids[] = $match['id'];
      }
      // Then we retrieve the objects correspoding to the found Ids
      return call_user_func(array($this->getClassPeer(), $this->getPeerMethod()), $ids);
    }
    else
    {
      return array();
    }

  }

}
