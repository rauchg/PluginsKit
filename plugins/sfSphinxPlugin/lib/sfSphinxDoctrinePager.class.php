<?php
/**
 * sfSphinx Doctrine pager class
 * @author  Kamil Rojewski <krojew@o2.pl>
 */

class sfSphinxDoctrinePager extends sfPager
{
  protected
    $keyword                = null,
    $sphinx                 = null,
    $pk_column              = 'id',
    $query                  = null;

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
    $this->query = Doctrine::getTable($this->getClass())->createQuery();
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
   * Get the query for the pager
   *
   * @return Doctrine_Query $query
   */
  public function getQuery()
  {
    return $this->query;
  }

  /**
   * Set query object for the pager
   *
   * @param Doctrine_Query $query
   * @return void
   */
  public function setQuery($query)
  {
    $this->query = $query;
  }

  /**
   * Get Pk column name
   *
   * @return string
   */
  public function getPkColumn()
  {
    return $this->pk_column;
  }

  /**
   * Set Pk column name
   *
   * @param string $column
   * @return void
   */
  public function setPkColumn($column)
  {
    $this->pk_column = $column;
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
      $id = $match['id'][0];

      $query = clone $this->getQuery();
      $result = $query->where($this->pk_column.' = ?', $id)->execute();
      return $result;
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
      $query = clone $this->getQuery();
      $result = $query->whereIn($this->pk_column, $ids)->execute();
      return $result ? $result : array();
    }
    else
    {
      return array();
    }

  }

}

