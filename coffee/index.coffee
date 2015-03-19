# Taken from http://arturadib.com/hello-backbonejs/docs/2.html
do (jQuery)->
	$ = jQuery;

	Backbone.sync = (method, model, success, error)->
		success()

	Item = Backbone.Model.extend {
		defaults:{
			part1: 'hello'
			part2: 'world'
		}
	}
	List = Backbone.Collection.extend {
		model: Item
	}

	ItemView = Backbone.View.extend {
		tagName:'li'
		events: {
			'click span.swap': 'swap'
			'click span.delete': 'remove'
		}
		initialize:()->
			_.bindAll this, 'render', 'unrender', 'swap', 'remove'

			this.model.bind 'change', this.render
			this.model.bind 'remove', this.unrender
		render:()->
			$(this.el).html "<span>#{this.model.get 'part1'} #{this.model.get 'part2'}</span> <span class='swap'>[swap]</span><span class='delete'>[remove]</span>"
			return this # for chainable calls, like `.render().el`
		unrender:()->
			$(this.el).remove()
		swap:()->
			swapped = {
				part1: this.model.get 'part2'
				part2: this.model.get 'part1'
			}
			this.model.set swapped
		remove:()->
			this.model.destroy()
	}

	ListView = Backbone.View.extend {
		el: $ '#container'
		events:{
			'click button#add':'addItem'
			'click button#multiAdd':'addLotsOfItems'
			'click button#deleteAll':'deleteAll'
		}
		initialize:()->
			_.bindAll this, 'render', 'addItem', 'appendItem','addLotsOfItems', 'deleteAll'
			this.collection = new List()
			this.collection.bind 'add', this.appendItem

			this.counter = 0
			this.render()
		render:()->
			self = this
			$(this.el).append "<button id='add'>Add list item</button> <button id='multiAdd'>Add 1000</button> <button id='deleteAll'>Delete All</button>"
			$(this.el).append "<ul></ul>"
			_(this.collection.models).each (item)-> # in case collection is not empty
				self.appendItem(item)
			,this
		addLotsOfItems:()->
			self = this
			_([0..1000]).each (item)->
				self.addItem()
			,this
		deleteAll:()->
			# console.log this.collection
			this.collection.reset()
			this.$el.empty()
			this.render()
		addItem:()->
			this.counter++
			item = new Item()
			item.set {
				part2: item.get('part2') + " " + this.counter
			}
			this.collection.add(item)
		appendItem:(item)->
			itemView = new ItemView {
				model: item
			}
			$('ul', this.el).append itemView.render().el
	}

	listView = new ListView()