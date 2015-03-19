# Taken from http://arturadib.com/hello-backbonejs/docs/2.html
do (jQuery)->
	$ = jQuery;
	Item = Backbone.Model.extend {
		defaults:{
			part1: 'hello'
			part2: 'world'
		}
	}
	List = Backbone.Collection.extend {
		model: Item
	}
	ListView = Backbone.View.extend {
		el:$ '#container'
		events:{
			'click button#add':'addItem'
		}
		initialize:()->
			_.bindAll this, 'render', 'addItem', 'appendItem'
			this.collection = new List()
			this.collection.bind 'add', this.appendItem

			this.counter = 0
			this.render()
		render:()->
			self = this
			$(this.el).append "<button id='add'>Add list item</button>"
			$(this.el).append "<ul></ul>"
			_(this.collection.models).each (item)->
				self.appendItem(item)
			,this
		addItem:()->
			this.counter++
			item = new Item()
			item.set {
				part2: item.get('part2') + this.counter
			}
			this.collection.add(item)
		appendItem:(item)->
			$('ul', this.el).append "<li>#{item.get('part1')} #{item.get('part2')}</li>"
	}

	listView = new ListView()