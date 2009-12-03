/*
---
name: PluginsKit JavaScript
authors:
  - Guillermo Rauch
requires:
  core/1.2.1:     '*'
  more:           [Request.Queue, Class.Occlude]
  fancyzoom:      0.1
provides:
  - pluginskit
...
*/

var ProgressBar = new Class({
	
	Implements: [Events, Options],
	
	options: {
		start: 0,
		fx: {
			duration: 400,
			transiton: 'back:ease:in'
		}
	},
	
	toElement: function(){
		return this.element;
	},
	
	initialize: function(element, options){
		this.setOptions(options);
		if (element){
			this.element = $(element);
			this.bar = $(element).getFirst();
		} else {
			this.element = new Element('div', {'class': 'progress-bar'});
			this.bar = new Element('div', {'class': 'progress-bar-bit', 'html': '<span></span>'}).inject(this.element);
		}
		this.dummy = new Element('div').inject(this.element);
		this.element.store('progressbar', this);
		if (this.options.fx) this.bar.set('tween', this.options.fx);
	},
	
	set: function(factor, callback){
		this.bar[this.options.fx ? 'tween' : 'setStyle']('width', Math.min(factor, 1) * this.dummy.offsetWidth);		
		if (callback) this.options.fx ? this.bar.get('tween').chain(callback) : callback();
		if (factor == 1) this.fireEvent('complete');
		return this;
	}
	
});

var ForgePluginRequest = new Class({
	
	Extends: Request.JSON,
	
	options: {
		link: 'ignore',
		reportInject: [document.body, 'top'],
		totalSteps: 6,
		startStatusText: 'Starting plugin addition'
	},
	
	initialize: function(options){
		this.parent(options);
		this.step = 1;
	},
	
	send: function(options, internal){
		if (this.running) return this;		
		if (this.responded && !internal) this.cleanup();
		this.parent(options);
		if (this.step == 1){
			this.report = new Element('div', {'class': 'github-request-report'}).fade('hide');
			this.report.inject.run(this.options.reportInject, this.report);
			this.errors = new Element('ul', {'class': 'errors'}).inject(this.report);
			this.message = new Element('div', {'class': 'status', 'html': this.options.startStatusText}).inject(this.report);
			this.progress = new ProgressBar(null, {
				onComplete: function(){
					this.report.addClass('github-request-report-complete')
				}.bind(this)
			});
			this.progress.toElement().inject(this.report);
			this.report.fade('in');
			this.fireEvent('firstRequest');
		}
	},
	
	onSuccess: function(obj, text){
		this.parent(obj, text);
		this.responded = true;
		if (obj.errors){
			this.progress.toElement().addClass('progress-bar_error');
			for (var i in obj.errors){
				obj.errors[i].each(function(text){
					new Element('li', {'html': text}).inject(this.errors);
				}, this);
				this.message.destroy();
			}
			this.fireEvent('stepErrors', [obj.errors, obj]);
		} else if (obj.success){
			this.progress.set(this.step / this.options.totalSteps, function(){
				this.step++;			

				if (obj.status) this.message.set('html', obj.status);

				if (this.step <= this.options.totalSteps){
					if (obj.addid) this.addid = obj.addid;					
					this.send({
						data: $merge({addid: this.addid}),
						url: this.options.url + '-' + this.step
					}, true);
					this.fireEvent('stepSuccess');
				} else {
					this.fireEvent('stepsSuccess');
				}
			}.bind(this));
		}
	},
	
	onFailure: function(){
		this.parent();
		// @todo
		// this.cleanup();
	},
	
	cancel: function(){
		this.parent();
		this.cleanup();
	},
	
	cleanup: function(){	
		this.report.destroy();
		this.step = 1;
		this.responded = null;
	}
		
});

var Forge = {
	
	init: function(){		
	  $$('input[type=password]').each(function(el){
	    new PassShark(el, {duration: 800});
	  });
	  
		// Submit form
		if ($('add-plugin-form')){
			var request = new ForgePluginRequest({
				url: $('add-plugin-form').get('action'),
				method: 'post',
				onFirstRequest: function(){
					$('plugin_add_submit').set('disabled', 'disabled').addClass('input_submit_disabled');
				},
				onStepErrors: function(){
					$('plugin_add_submit').erase('disabled').removeClass('input_submit_disabled');					
				},
				onStepsSuccess: function(){
					$('plugin_add_submit').erase('disabled').removeClass('input_submit_disabled');					
				},
				onFailure: function(){
					// $('plugin_add_submit').removeClass('input_submit_disabled');					
				},
				reportInject: ['plugin_add_submit', 'after']
			});
			$('add-plugin-form').addEvent('submit', function(evt){
				evt.preventDefault();
				if (!$('plugin_add_submit').hasClass('input_submit_disabled')) request.send({
					data: { 
						'url': $('url').get('value')
					}
				});
			});
		}
		
		// Update form
		if ($('plugin-update')){
			var request = new ForgePluginRequest({
				url: $('update-form').get('action'),
				method: 'post',
				onFirstRequest: function(){
					$('plugin-update').set('disabled', 'disabled').addClass('button_disabled');
				},
				onStepErrors: function(){
					$('plugin-update').erase('disabled').removeClass('button_disabled');					
				},
				onStepsSuccess: function(){
					$('plugin-update').erase('disabled').removeClass('button_disabled');					
				},
				startStatusText: 'Starting plugin update',
				reportInject: ['update-form', 'bottom']
			});
			
			$('plugin-update').addEvent('click', function(ev){
				ev.preventDefault();
				$('update-form').setStyle('display', 'block');
				request.send({
					data: {
						'id': $('update-form').getElement('input[name=id]').get('value')
					}
				});
			});
		}
		
		if ($('plugin-delete')){
		  $('plugin-delete').addEvent('click', function(e){
		    if (!confirm('Are you sure you want to delete this?')) e.preventDefault();
		  });
		}
		
		// Object style syntax with files in same level as html document. 
		$$('#project pre').light({altLines: 'hover', mode: 'ol'});
		
		setupZoom();
	}
	
};

window.addEvent('domready', function(){ Forge.init(); });