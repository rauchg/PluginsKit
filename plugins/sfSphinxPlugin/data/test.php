<?php
/**
 * test script for sfSphinxClient.class.php
 * Usage: php test.php <query> [index]
 * Query parameter is mandatory. Enclose in quotes if query contains more words.
 * E.g.; php test.php "foo bar"
 * Index parameter is optional. If not passed, query is executed on all index.
 * Example: php test.php "foo bar" myIndex
 */

// check for params
if ($_SERVER['argc'] < 1)
{
  exit('usage: php ' . $_SERVER['argv'][0] . ' <query> [index]' . PHP_EOL);
}

// include lib
require_once dirname(__FILE__) . '/../lib/sfSphinxClient.class.php';

// options
$options = array(
  'weights' => array(100, 1),
);

// query
$q = $_SERVER['argv'][1];
// index
$index = empty($_SERVER['argv'][2]) ? '*' : $_SERVER['argv'][2];

// do query
$sphinx = new sfSphinxClient($options);
$res = $sphinx->Query($q);

// query failed
if ($res === false)
{
  exit('Query failed: ' . $sphinx->GetLastError() . PHP_EOL);
}

// some warning?
if ($sphinx->GetLastWarning())
{
  echo 'WARNING: ' . $sphinx->GetLastWarning() . PHP_EOL . PHP_EOL;
}

// display results
echo "Query '$q' retrieved $res[total] of $res[total_found] matches in $res[time] sec." . PHP_EOL;
echo 'Query stats:' . PHP_EOL;
if (is_array($res['words']))
{
  foreach ($res['words'] as $word => $info)
  {
    echo "    '$word' found $info[hits] times in $info[docs] documents" . PHP_EOL;
  }
}
echo PHP_EOL;

if (is_array($res['matches']))
{
  $n = 1;
  echo 'Matches:' . PHP_EOL;
  foreach ($res['matches'] as $docinfo)
  {
    printf('%2u', $n);
    echo '. doc_id = ' . $docinfo['id'] . ', weight = ' . $docinfo['weight'];
    foreach ($res['attrs'] as $attrname => $attrtype)
    {
      $value = $docinfo['attrs'][$attrname];
      if ($attrtype & sfSphinxClient::SPH_ATTR_MULTI)
      {
        $value = '(' . implode(',', $value) . ')';
      }
      else
      {
        if ($attrtype == sfSphinxClient::SPH_ATTR_TIMESTAMP)
        {
          $value = date('Y-m-d H:i:s', $value);
        }
      }
      echo ", $attrname = $value";
    }
    echo PHP_EOL;
    $n ++;
  }
}

?>
