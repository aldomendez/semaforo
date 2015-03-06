Eq = Backbone.Model.extend
  urlRoot:'equipments.php/equipments'
  defaults:{
    AREA: ""
    BU: "1"
    CICLETIME: "1"
    DBCONNECTION: ""
    DBDEVICE: ""
    DBMACHINE: ""
    DBTABLE: ""
    DB_ID: ""
    DESCRIPTION: ''
    ID: "20"
    NAME: ""
    PROCESS: ""
  }
    
  validate:(attrs, options)->
    
  initialize:()->
    console.log "Welcome to this world"
    @bind 'error', (model, error)->
      console.warn error

Equipments = Backbone.Collection.extend
  url:'equipments.php/equipments'
  model: Eq

eqm = new Equipments()
eqm.fetch()


SearchView = Backbone.View.extend
  initialize: ()->
    _.bindAll(this, 'render')
    this.render()
  render:()->
    template = _.template $("#search_template").html(), {}
    @.$el.html template
  events:
    'click input[type=button]':'doSearch'
  doSearch:(event)->
    console.log "Search for #{$('#search_input').val()}"

search_view = new SearchView
  el: $ "#container"

