(function() {
  var SearchView, search_view;

  SearchView = Backbone.View.extend({
    initialize: function() {
      return this.render();
    },
    render: function() {
      var template;
      template = _.template($("#search_template").html(), {});
      return this.$el.html(template);
    },
    events: {
      'click input[type=button]': 'doSearch'
    },
    doSearch: function(event) {
      return console.log("Search for " + ($('#search_input').val()));
    }
  });

  search_view = new SearchView({
    el: $("#container")
  });

}).call(this);
