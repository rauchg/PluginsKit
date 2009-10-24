<div class="block">
	<h3><span>How to Add a Plugin</span></h3>
	
	<div class="block">
		<p>MooForge is tightly integrated with GitHub. All the plugins data is stored there, even the downloads. In order to add your plugin to the repository, please follow these steps.</p>
	</div>
		
	<div class="block">
		<h4>Steps</h4>
		<ol class="howto-steps">
			<li><p>Make sure your plugin is a top-level repository. We don't accept plugins that are directories within other repositories.</p></li>
			<li><p>Write the <code><a href="#readme-md">README.md</a></code> and <code><a href="#package-yml">package.yml</a></code> files (explanation below), and add them to the repository at the top directory.</p></li>
			<li><p>Make sure you respect the file structure (only the <strong>Source/</strong> subdirectory is enforced, but it's a good idea to have them all).</p>
			<div class="code block"><pre>/
	Source/
		YourPlugin.js				# main file
		YourPlugin.SomethingElse.js 		# extra component example
	Docs/
		YourPlugin.md				# plugin docs
	Demos/
	Specs/</pre></div>
				<p>Docs are encouraged. There's a <a href="http://www.nwhite.net/2009/03/05/moodocs-textmate-command/">bundle</a> for TextMate users to make this easier.</p>
			</li>				
			<li><p class="block">{explanation here for yaml header of files}</p></li>
			<li><p>At least one Git tag in your repository is necessary. To tag a 0.1 release, for example, run these commands:</p>
				<div class="code block"><pre>git tag -a 0.1
git push --tags</pre></div>
			</li>		
			<!-- <li>Test your <code>README.md</code> and <code>package.yml</code> files with the <?php echo link_to('Syntax Checker', '/plugin/syntaxChecker') ?>, to avoid pushing a non-working one to your repository.</li> -->
			<li><p>When everything looks good, click <?php echo link_to('Add a new plugin', '@add') ?> <?php if (!$sf_user->isAuthenticated()): ?> (you must be logged in) <?php endif ?></p></li>		
		</ol>

		<p>We also suggest taking a look at the <?php echo link_to('Plugin Writing Guidelines', '@pluginguidelines') ?>.</p>
	</div>
		
	<hr />
		
	<h4 id="readme-md">README.md template (<a href="http://github.com/Guille/TextboxList/blob/master/README.md">example</a>)</h4>
	
	<div class="code block"><pre>Plugin Name
===========

What goes here is the description. Please don't make it too long. It can contain basic *styling*, **styling**, etc. 

If an image is found within the description, that becomes the screenshot of the plugin. Screenshots are optional but encouraged, given the plugin has some visual interaction. The screenshot can be of any size, but try to keep it of about 200x100.

![Screenshot](http://url_to_project_screenshot)

How to use
----------

We expect this section for every plugin. It just explains how to use your plugin.
Never should a plugin rely on a 3rd party link to explain its behavior or functionality. We need this to ensure that if a website is removed or becomes inaccessible, people can still enjoy your plugins' functionality.

It often include code snippets, which are just indented pieces of text:

	#JS
	var script = new MyScript()
	script.doSomething();

If the first line is #JS or #CSS or #HTML, then the code is highlighted accordingly. 

Screenshots
-----------

This section is optional, but encouraged if the plugin affords it. Just a list of images, one per line. We do the resizing, so use actual size screenshots.

![Screenshot 1](http://url_to_project_screenshot)
![Screenshot 2](http://url_to_project_screenshot)
![Screenshot 3](http://url_to_project_screenshot)
![Screenshot 4](http://url_to_project_screenshot)

Arbitrary section
-----------------

This is an arbitrary section. You can have as many of these as you want.
Some arbitrary section examples:

* FAQ
* Notes
* Misc
* Known issues

The name is up to you, but remember to keep it meaningful and simple. Arbitrary sections are always optional.</pre></div>
	
	<hr />

	<h4 id="package-yml">package.yml template (<a href="http://github.com/Guille/TextboxList/blob/master/package.yml">example</a>)</h4>
	
	<div class="code block"><pre>name: Plugin Name
author: <?php echo $sf_user->isAuthenticated() ? $sf_user->getUsername() : '<em>your forge username here</em>' ?>

category: Interface
tags: [animation, canvas]
#docs: http://url.to.docs
#demo: http://url.to.demo
#current: 0.5</pre></div>
	
	<p>Notes:</p>
	<ul>
		<li>This is a <a href="http://yaml.org">YAML</a> file. Don't use tabs, use spaces, or the file won't parse.</li>
		<li>The <strong>category</strong> key has to be one valid, existing category. The list of categories is in the sidebar.</li>
		<li>Keep <strong>tags</strong> meaningful. Tags that repeat categories names, or stuff like "Javascript", "Cool" are not valuable, and bound to be removed by moderators. Tags that depict techniques or technologies involved are encouraged, such as "canvas", "accessibility".</li>
		<li>The <strong>current</strong> key points to an existing Git tag in your repository. It's optional, and the last Git tag is used if not present.</li>
		<li>We recommend using <a href="http://pages.github.com">GitHub pages</a> for <strong>demo</strong>. This ensures that the demo page never goes offline or becomes inaccessible.</li>
	</ul> 

</div>