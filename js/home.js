var Eq, Equipments, SearchView, eqm, search_view;

Eq = Backbone.Model.extend({
  urlRoot: 'equipments.php/equipments',
  defaults: {
    AREA: "",
    BU: "1",
    CICLETIME: "1",
    DBCONNECTION: "",
    DBDEVICE: "",
    DBMACHINE: "",
    DBTABLE: "",
    DB_ID: "",
    DESCRIPTION: '',
    ID: "20",
    NAME: "",
    PROCESS: ""
  },
  validate: function(attrs, options) {},
  initialize: function() {
    console.log("Welcome to this world");
    return this.bind('error', function(model, error) {
      return console.warn(error);
    });
  }
});

Equipments = Backbone.Collection.extend({
  url: 'equipments.php/equipments',
  model: Eq
});

eqm = new Equipments();

eqm.fetch();

SearchView = Backbone.View.extend({
  initialize: function() {
    _.bindAll(this, 'render');
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
