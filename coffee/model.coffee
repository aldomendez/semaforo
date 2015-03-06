UserModel = Backbone.Model.extend
  urlRoot:'slim.php/user'
  defaults:
    name:''
    email:''
    age:1
  validate:(attributes)->
    if attributes.age < 0
      return "You can't be negatives years old"
    if attributes.name isnt 'Dr manhatten'
      return "You have to have a pretty name"
  initialize:()->
    console.log "Welcome to this world"
    @bind 'error', (model, error)->
      console.warn error
    

user = new UserModel
user.set {name:'Aldo'}
userDetails = {
  name:'Aldo'
  email:'aldo@gmail.com'
  age:-1
}

user.save userDetails,{
  success:(user)->
    console.log user.attributes
}

user = new UserModel {id:12}

user.fetch {
  success: (user)->
    console.log  user.attributes
}

user.save { name: 'David'}, {
  success: (model)->
    console.log model.attributes
}
user.destroy {
  success:()->
    console.log 'Destroyed'
}