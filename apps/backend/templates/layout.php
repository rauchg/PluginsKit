<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
  </head>
  <body>
    <?php if ($sf_user->isAuthenticated() && $sf_user->hasCredential('admin')): ?>
    <ul id="nav">
      <li><?php echo link_to('Dashboard', 'dashboard/index') ?></li>
      <li><?php echo link_to('Authors', 'author/index') ?></li>
      <li><?php echo link_to('Plugins', 'plugin/index') ?></li>      
    </ul>
    <?php endif ?>
    
    <?php echo $sf_content ?>
  </body>
</html>
