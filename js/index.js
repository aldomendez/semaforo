(function(jQuery) {
  var $, ListView, listView;
  $ = jQuery;
  ListView = Backbone.View.extend({
    el: $('#container'),
    events: {
      'click button#add': 'addItem'
    },
    initialize: function() {
      _.bindAll(this, 'render', 'addItem');
      this.counter = 0;
      return this.render();
    },
    render: function() {
      $(this.el).append("<button id='add'>Add list item</button>");
      $(this.el).append("<ul></ul>");
      return this.addItem();
    },
    addItem: function() {
      this.counter++;
      return $('ul', this.el).append(" <li>hello world " + this.counter + "</li> ");
    }
  });
  return listView = new ListView();
})(jQuery);
//# sourceMappingURL=index.js.map