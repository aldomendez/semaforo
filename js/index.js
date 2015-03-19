(function(jQuery) {
  var $, Item, ItemView, List, ListView, listView;
  $ = jQuery;
  Backbone.sync = function(method, model, success, error) {
    return success();
  };
  Item = Backbone.Model.extend({
    defaults: {
      part1: 'hello',
      part2: 'world'
    }
  });
  List = Backbone.Collection.extend({
    model: Item
  });
  ItemView = Backbone.View.extend({
    tagName: 'li',
    events: {
      'click span.swap': 'swap',
      'click span.delete': 'remove'
    },
    initialize: function() {
      _.bindAll(this, 'render', 'unrender', 'swap', 'remove');
      this.model.bind('change', this.render);
      return this.model.bind('remove', this.unrender);
    },
    render: function() {
      $(this.el).html("<span>" + (this.model.get('part1')) + " " + (this.model.get('part2')) + "</span> <span class='swap'>[swap]</span><span class='delete'>[remove]</span>");
      return this;
    },
    unrender: function() {
      return $(this.el).remove();
    },
    swap: function() {
      var swapped;
      swapped = {
        part1: this.model.get('part2'),
        part2: this.model.get('part1')
      };
      return this.model.set(swapped);
    },
    remove: function() {
      return this.model.destroy();
    }
  });
  ListView = Backbone.View.extend({
    el: $('#container'),
    events: {
      'click button#add': 'addItem',
      'click button#multiAdd': 'addLotsOfItems',
      'click button#deleteAll': 'deleteAll'
    },
    initialize: function() {
      _.bindAll(this, 'render', 'addItem', 'appendItem', 'addLotsOfItems', 'deleteAll');
      this.collection = new List();
      this.collection.bind('add', this.appendItem);
      this.counter = 0;
      return this.render();
    },
    render: function() {
      var self;
      self = this;
      $(this.el).append("<button id='add'>Add list item</button> <button id='multiAdd'>Add 1000</button> <button id='deleteAll'>Delete All</button>");
      $(this.el).append("<ul></ul>");
      return _(this.collection.models).each(function(item) {
        return self.appendItem(item);
      }, this);
    },
    addLotsOfItems: function() {
      var self, _i, _results;
      self = this;
      return _((function() {
        _results = [];
        for (_i = 0; _i <= 1000; _i++){ _results.push(_i); }
        return _results;
      }).apply(this)).each(function(item) {
        return self.addItem();
      }, this);
    },
    deleteAll: function() {
      this.collection.reset();
      this.$el.empty();
      return this.render();
    },
    addItem: function() {
      var item;
      this.counter++;
      item = new Item();
      item.set({
        part2: item.get('part2') + " " + this.counter
      });
      return this.collection.add(item);
    },
    appendItem: function(item) {
      var itemView;
      itemView = new ItemView({
        model: item
      });
      return $('ul', this.el).append(itemView.render().el);
    }
  });
  return listView = new ListView();
})(jQuery);
//# sourceMappingURL=index.js.map