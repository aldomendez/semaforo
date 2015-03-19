do (jQuery)->
	$ = jQuery;
	ListView = Backbone.View.extend {
		el: $ '#container'
		events:{
			'click button#add':'addItem'
		}
		initialize:()->
			_.bindAll this, 'render', 'addItem'
			this.counter = 0
			this.render();
		render:()->
			$(this.el).append "<button id='add'>Add list item</button>"
			$(this.el).append "<ul></ul>"
			this.addItem()
		addItem:()->
			this.counter++
			$('ul', this.el).append " <li>hello world #{this.counter}</li> "
	}
	listView = new ListView()