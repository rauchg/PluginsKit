<form action="<?php echo url_for('search') ?>" id="search-form" class="block">
	<div id="search_field">
		<?php echo $search['q']->render(array('class' => 'text', 'placeholder' => 'Search')) ?>
	</div>
	<div id="search_submit" class="input_submit">
		<input type="submit" value="Go" />
	</div>
	<?php echo $search->renderHiddenFields() ?>
</form>

<?php if($sf_user->isAuthenticated()): ?>
<h4 class="login"><span>Developer Menu</span></h4>
<ul id="loggedin-area">
	<li><?php echo link_to('My profile', 'user', array('username' => $sf_user->getUsername())) ?></li>
	<li><?php echo link_to('Add a new plugin', '@add') ?></li>
	<li><?php echo link_to('Settings', '@settings') ?></li>
	<li><?php echo link_to('Logout', '@logout') ?></li>
</ul>
<?php elseif($sf_context->getActionName() == 'twitterSignup'): ?>
<h4 class="login"><span>Developer Menu</span></h4>	
<ul id="loggedin-area">
	<li><?php echo link_to('Logout', '@logout') ?></li>
</ul>	
<?php else: ?>
<h4 class="login"><span>Developer Login</span></h4>

<form action="<?php echo url_for('@login') ?>" method="post" accept-charset="utf-8">
	<ul id="login-form" class="required">
		<li><?php echo $login['email']->renderLabel('Email') ?> <?php echo $login['email']->render(array('class' => 'text')) ?></li>
		<li><?php echo $login['password']->renderLabel('Password') ?> <?php echo $login['password']->render(array('class' => 'text')) ?>		  
		  <div class="input_submit"><input type="submit" name="login_submit" value="Login" id="login_submit" /></div>			
		</li>					
	</ul>
	<?php echo $login->renderHiddenFields() ?>				
</form>

<ul id="login-other">
	<li id="login-other-twitter"><a href="<?php echo url_for('@twitterlogin') ?>" class="twitter_button">Sign in with twitter</a></li>
	<li><?php echo link_to('Normal Signup', '@signup') ?></li>	
	<li><?php echo link_to('I forgot my password', '@forgot') ?></li>		
</ul>
<?php endif ?>

<h4 class="development"><span>Browse</span> (<?php echo link_to('all', '@browse') ?>)</h4>
<ul>
	<?php foreach($categories as $category): ?>
	<li><?php echo link_to($category->getTitle(), 'browse', array('category' => $category->getSlug())) ?></li>
	<?php endforeach ?>
</ul>

<h4 class="resources"><span>Resources</span></h4>
<ul>
	<li><?php echo link_to('How to Add a Plugin', '@howtoadd') ?></li>
	<li><?php echo link_to('Plugin Writing Guidelines', '@pluginguidelines') ?></li>	
	<!-- <li><?php echo link_to('Syntax Checker', 'plugin/syntaxChecker') ?></li> -->
</ul>

<h4 class="people"><span>People</span></h4>
<ul>
	<li><?php echo link_to('Plugin Authors', 'developers') ?></li>
</ul>