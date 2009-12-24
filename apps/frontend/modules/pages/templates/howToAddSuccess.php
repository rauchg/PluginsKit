<div class="block">
	<h3><span>How to Add a Plugin</span></h3>
	
	<div class="block">
		<p>MooForge is tightly integrated with GitHub. All the plugins data is stored there, even the downloads. In order to add your plugin to the repository, please follow these steps.</p>
	</div>
		
	<div class="block">
		<h4>Prepare Your Plugin</h4>
		<ol class="howto-steps">
			<li>
				<h5>Set Up Your Git Repository</h5>
				<p>Make sure your plugin is a top-level repository. We don't accept plugins that are directories within other repositories.</p>
			</li>
			<li>
				<h5>Add a Manifest and a Readme</h5>
				<p>Write the <code><a href="#readme-md">README.md</a></code> and <code><a href="#package-yml">package.yml</a></code> files (explanation below), and add them to the repository at the top directory.</p>
			</li>
			<li>
				<h5>Organize Your Files</h5>
				
				<p>Make sure you respect the file structure (only the <strong>Source/</strong> subdirectory is enforced, but it's a good idea to have them all).</p>
			<div class="code block"><pre>/
	Source/						#This can have subdirectories if you like
		YourPlugin.js				# main file
		YourPlugin.SomethingElse.js 		# extra component example
	Docs/
		YourPlugin.md				# plugin docs
		</pre></div>
				<p>Docs are encouraged. There's a <a href="http://www.nwhite.net/2009/03/05/moodocs-textmate-command/">bundle</a> for TextMate users to make this easier.</p>
			</li>
			<li>
				<h5>Script Headers</h5>
				<p>
					Each script in your <em>/Source</em> directory must have a specific header that includes metadata used by the forge. This header information is a YAML fragment with key/value sets of information that the will use for various features.
				</p>
				<p>
					First, an example (the order of the key/values does not matter):
				</p>
<div class="code block"><pre>/*
---
description: Element class, Elements class, and basic dom methods.

license: MIT-style

authors:
- Jimmy Dean
- Buck Kingsley

requires:
- localComponent1
- [localComponent2, localComponent3]
- externalPackage1/tag: component4
- externalPackage2/tag: [component1, component2]

provides: [Element, Elements, $, $$]

...
*/</pre></div>
				<ul>
					<li>description: a very brief, one line description of the contents of this file; try to keep this under 100 characters if you can.
					</li>
					<li>license: the license for your plugin
					</li>
					<li>authors: a list of authors for credit
					</li>
					<li>requires: a list of the required components for your plugin to work
					</li>
					<li>provides: a list of components that your plugin provides
					</li>
				</ul>
				<p>
					<a href="#yamlnotes">See the YAML Header footnotes below for additional tips and details.</a>
				</p>
			</li>
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
	
	<hr />
	
	<h3>Notes about YAML</h3>
	
	<div class="block">	 
  	<p><a href="http://yaml.org">YAML</a> validness is required. Some general tips and rules:</p>
  </div>
  
	<div class="block">
  	
  	<ul class="howto-steps">
  	  <li>Don't use tabs, use spaces, or the file won't parse.</li>  
  	  <li>You can easily test your YAML before committing by pasting it into <a href="http://yaml-online-parser.appspot.com/">this online YAML tester</a>.</li>
      <li>For lists, two syntaxes are available:
      <div class="code block"><pre>authors: [Jimmy Dean, Buck Kingsley]
# OR
authors:
 - Jimmey Dean
 - Buck Kingsley</pre></div>
      </li>
  	</ul>
	</div>
	
	<hr />
	
	<h4>package.yml:</h4>
	<ul>
		<li>The <strong>category</strong> key has to be one valid, existing category. The list of categories is in the sidebar.</li>
		<li>Keep <strong>tags</strong> meaningful. Tags that repeat categories names, or stuff like "Javascript", "Cool" are not valuable, and bound to be removed by moderators. Tags that depict techniques or technologies involved are encouraged, such as "canvas", "accessibility".</li>
		<li>The <strong>current</strong> key points to an existing Git tag in your repository. It's optional, and the last Git tag is used if not present.</li>
		<li>We recommend using <a href="http://pages.github.com">GitHub pages</a> for <strong>demo</strong>. This ensures that the demo page never goes offline or becomes inaccessible.</li>
	</ul>
	
	<hr />
	
	<h4 id="yamlnotes">
		JavaScript files headers:
	</h4>
	<ul class="howto-steps">
		<li>You can include other values if you like, but they won't be used by the forge. For example, if you wanted to include data used by your own script builders that's fine so long as these are present.
		</li>
		<li>The provides list is a list of components in the current file. This allows you to have a file that might have several utilities and later allow you to split the file up. For example, if you had a file called "CustomSelectors.js" with custom selectors defined for "checked", "empty", and "selected" you consider making your "provides" values "[CustomSelectors.checked, CustomSelectors.empty, CustomSelectors.selected]" which would allow you to, at some future date, split the file up, or consolidate its contents with another file. This allows other plugins to require these components even if you reorganize them in the future.
		</li>
		<li>The requires list is a list of components the current file needs in order to run. Each requirement list is preceded by the name of that component (as found in the Forge), the version (tag) your plugin works with, and then a list of components.
		</li>
		<li>The names of MooTools Core and MooTools More are simply "core" and "more". All other plugin names are the short name at the end of their url (for instance, the <a href="http://mootools.net/plugins/p/floom">Floom</a> plugin's url is "http://mootools.net/plugins/p/<strong>floom</strong>" - so "<strong>floom</strong>" is it's name).
		</li>
		<li>To refer to files within the current plugin simply name them with no repo:tag prefix.</li>
		<li>Plugins can be included wholesale with an asterisk in single quotes, like so:
<div class="code block"><pre>
requires: 
  core/1.2.4: '*'
</pre></div>

<p>It is not really recommended that you do this, as it will require users to perhaps load content they don't need in order to run your plugin. It is far better to include only the components that you need.</p>
		</li>
		<li>Dependency trees are implied, so if you require core/1.2.4:Class you are also requiring core/1.2.4:Core. You don't need to list implied dependencies, but you can if you like.
		</li>
		<li>This dependency system will allow the forge to build a library for users, allowing them to select a group of plugins and then save the built library. Further, it will allow the forge and other tools to at some point in the future run automated tests and present working demos by including the required components. Having a valid dependency map is very important. The forge does not validate that your dependency map is correct, only that it is present (for now).
		</li>
	</ul>

</div>