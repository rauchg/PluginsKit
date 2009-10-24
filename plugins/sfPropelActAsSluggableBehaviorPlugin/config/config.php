<?php
/*
 * This file is part of the sfPropelActAsTaggableBehavior package.
 * 
 * (c) 2007 Guillermo Rauch <rauchg@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

sfPropelBehavior::registerHooks('sfPropelActAsSluggableBehavior', array (
  ':save:pre' => array ('sfPropelActAsSluggableBehavior', 'preSave'),
));