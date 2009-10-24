<?php

/**
 * retrieve results corresponding to Sphinx found ids, getting them from table
 * @param  array  $results     results from sfSphinxClient::Query()
 * @param  string $peer_class  peer class to get data, e.g. "ItemPeer"
 * @param  string $id_field    id field used in Sphinx, e.g. "id" (for ItemPeer::ID)
 * @param  string $peer_method method name of $peer_class, e.g. "doSelect"
 * @return array               array of objects returned by peer class
 * @deprecated  Please use sfSphinxPager insted
 */
function get_search_results($results, $peer_class, $id_field, $peer_method)
{
  if (empty($results['matches']))
  {
    return array();
  }
  $ids = array();
  foreach ($results['matches'] as $match)
  {
    $ids[] = $match['id'];
  }
  $c = new Criteria();
  $c->add($id_field, $ids, Criteria::IN);
  $c->addAscendingOrderByColumn('FIELD(' . $id_field . ',' . implode(',', $ids) . ')');
  return call_user_func($peer_class . '::' . $peer_method, $c);
}

/**
 * search $needle in $haystack and highlight it
 * @param  string text to search in
 * @param  mixed  word(s) to highlight (string or array)
 * @param  string HTML tag used for highlighting (defaut: bold)
 * @return string text with highlighted words
 */
function highlight_search_result($haystack, $needle, $tag = 'b')
{
  $stopwords = array(); // TODO
  $needle = preg_split('/[^a-zàèìòùéA-ZÀÈÌÒÙÉ0-9]+/', $needle);
  if (empty($needle))
  {
    return $haystack;
  }
  if (strlen($haystack) == 0)
  {
    return false;
  }
  $hl = '<' . $tag . '>\1</' . $tag . '>';  // highlight
  $pattern = '#(%s)#i';
  foreach ($needle as $v)
  {
    $v = strtolower($v);
    // limit (3) should be equal to mysql variable 'ft_min_word_len'
    if (strlen(trim($v)) == 0 || in_array($v, $stopwords) || strlen($v) < 3){
      continue; //  no empty words or stopwords
    }
    $qv = preg_quote($v); // regex quote
    $qv1 = preg_quote(htmlentities($v));  // regex quote
    $regex = sprintf($pattern, $qv);
    $haystack = preg_replace($regex, $hl, $haystack);
    if ($qv != $qv1)
    {
      $regex1 = sprintf($pattern, $qv1);
      $haystack = preg_replace($regex1, $hl, $haystack);
    }
  }
  return $haystack;
}

/**
 * display a page navigator
 * @param  string  $uri   internal uri (e.g. "module/action")
 * @param  string  $query query string
 * @param  integer $found number of found results
 * @param  integer $page  current page (default is first page)
 * @param  integer $delta number of page links to display in pager
 * @return string
 * @deprecated  Please use sfSphinxPager insted
 */
function search_pager($uri, $query, $found, $page = 1, $delta = 10)
{
  if ($found == 0)
  {
    return '';
  }
  $last_page = floor($found / $delta);
  if ($last_page == 0)
  {
    return '';
  }
  $p = '';
  if ($page > 1)
  {
    $p .= '<a href="' . url_for($uri) . '/q/' . $query . '">&laquo;</a> ';
    $p .= '<a href="' . url_for($uri) . '/q/' . $query . '/p/' . ($page - 1) . '">&lt;</a> ';
  }
  for ($i = 1; $i <= $delta; $i ++)
  {
    if ($i == $page)
    {
      $p .= '<b>' . $i . '</b> ';
    }
    else
    {
      $p .= '<a href="' . url_for($uri) . '/q/' . $query . '/p/' . $i . '">' . $i . '</a> ';
    }
    if ($i == $last_page)
    {
      break;
    }
  }
  if ($page < $last_page)
  {
    $p .= '<a href="' . url_for($uri) . '/q/' . $query . '/p/' . ($page + 1) . '">&gt;</a> ';
    $p .= '<a href="' . url_for($uri) . '/q/' . $query . '/p/' . $last_page . '">&raquo;</a>';
  }
  return $p;
}

?>
