$(document).ajaxStart ()->
  NProgress.start()
$(document).ajaxStop ()->
  NProgress.done()

class Machines
  constructor: () ->
    @loaded = false
    @load()
    @placeHolder =
      "DB_ID": "",
      "NAME": "",
      "DESCRIPTION": null,
      "AREA": "",
      "PROCESS": "",
      "SETUP_DATE": "",
      "DBCONNECTION": "",
      "DBTABLE": "",
      "DBMACHINE": "",
      "DBDEVICE": "",
      "LASTTICK": "",
      "LASTRUN": "",
      "CICLETIME": "",
      "BU": "undefined"
  load:()->
    promise = $.getJSON 'machines.php/all'
    promise.done (data)=>
      @loaded = true
      data.map (el)-> el.min = (el.CICLETIME/60).toFixed(2)
      data.map (el)-> el.seg = 0
      @data = data
      r.set 'managers', _.unique(_.pluck(@data,'BU'))
      r.set 'process', _.unique(_.pluck(@data,'PROCESS'))
      @original = _.clone @data
      @filter = new Fuse(@data,{keys: ['NAME', 'BU','AREA','PROCESS']})
      r.update()
      r.set 'edited', false

      gapi.signin.render('googleLoginButton')

      
  search:(searchString)->
    if @loaded
      if searchString isnt ''
        @data = @filter.search searchString
        r.update()
      else
        @data = _.clone @original
        r.update()
      
  clone:(index)->

    newMachine = _.clone r.get "machines.data.#{index}"
    newMachine.ID = undefined
    delete newMachine.ID
    @data.push newMachine
    r.set
      editing:@data.length - 1
      edit:true
      deleting:false

  save:()->
    if @data.length isnt 0 and r.get 'edited'
      index = r.get 'editing'
      datos = r.get "machines.data.#{index}"
      datos.CICLETIME = duration datos.min, datos.seg
      if datos.ID isnt undefined
        promise = $.ajax
          method:'put'
          url:"machines.php/#{datos.ID}"
          data: datos
        promise.done ()->
  #        TODO: Buscar la manera de decir que todo se guardo correctamente
          r.set 'saved','successfull'
      else
        promise = $.ajax
          method:'post'
          url:"machines.php/"
          data: datos
        promise.done ()->
  #        TODO: Buscar la manera de decir que todo se guardo correctamente
          r.set 'saved','successfull'
    else
#      r.set {
#
#      }
  add: ()->
    @placeHolder.id = @data.length + 1
    @data.push _.clone @placeHolder
    r.set
      editing:@.data.length - 1
      edit: true
      deleting: false


# Datos que pueden servir mas adelante
util = {}
util.numReg = /num$/
util.indexMatch = /(\d*)\.num$/


duration = (min,seg)->
  min = parseFloat(min)
  seg = parseFloat(seg)
  if isNaN min then min = 0
  if isNaN seg then seg = 0
  return "#{((min*60) + seg).toFixed(0)}"

p = new Machines()
r = new Ractive {
  el: 'container'
  template: '#template'
  data:{
    machines : p
    saved:'0'
    edit: false
    editing: false
    edited: false
    sidebar: false
    deleting: false
    showSigninButton:true
    filter:''
    duration:duration
  }
}

r.on 'setMinutes',(e,id)->
  console.log id
  e.original.preventDefault()
  branch = "machines.data.#{id}.min"
  offset = if e.dy < 0 then -1 else 1
  actual = parseInt(r.get(branch),10)
  valToSet = actual + offset
  if valToSet >=0
    r.set branch, valToSet
  else
    r.set branch, 0


r.on 'edit', (e, param)->
  e.original.preventDefault()
  # console.log param
  r.set
    edit:true
    editing:param
    deleting: false

r.on 'returnToList', (e)->
  e.original.preventDefault()
  r.set
    edit:false
    editing:0
    deleting:false

r.on 'save', (e)->
  e.original.preventDefault()
  if r.get 'edited'
    p.save()
  r.set
    edited:false

r.on 'clone', (e, index)->
  e.original.preventDefault()
  p.clone(index)

r.on 'del', (e, ind)->
  e.original.preventDefault()
  r.data.machines.data.splice(ind,1)
  console.log 'delete', ind
  r.fire 'backward', e

r.on 'addNew', (e)->
  e.original.preventDefault()
  r.data.machines.add()
  r.set 
    'deleting': false

r.on 'backward', (e)->
  if e?.original?.preventDefault? then e.original.preventDefault()
  actual = r.get('editing')
  offset = if actual is 0  then r.get('machines.data.length')-1 else actual-1
  r.set
    editing: offset
    deleting: false
    edited:false

r.on 'forward', (e)->
  if e?.original?.preventDefault? then e.original.preventDefault()
  actual = r.get('editing')
  offset = if actual is r.get('machines.data.length')-1 then 0 else actual+1
  r.set
    editing: offset
    deleting: false
    edited:false

# TODO: Si no voy a hacer funcionar la sidebar esto deberia de irse
r.on 'sidebar', (e)->
  e.original.preventDefault()
  r.set 'sidebar', !r.get 'sidebar'

r.on 'askToDelete', (e)->
  e.original.preventDefault()
  r.set
    deleting:true

r.on 'updateManager', (e,val)->
  e.original.preventDefault()
  editing = r.get 'editing'
  r.set "machines.data.#{editing}.BU", val

r.on 'addNewManager', (e)->
  e.original.preventDefault()


r.observe 'machines.data.*.*', (nval, oval, keypath)->
  r.set 'edited', true
  r.set 'deleting', false
  if r.get('edit') and util.numReg.test keypath
    if nval.length is 7 
      r.data.machines.pushToQueue(keypath.match(/(\d*)\.num$/)[1])

r.observe 'machines.data.*.min machines.data.*.seg', (nval, oval, keypath)->
  id = keypath.match(/(\d+)..*$/)[1]
  r.set "machines.data.#{id}.CICLETIME", duration(r.get("machines.data.#{id}.min"),r.get("machines.data.#{id}.seg"))

#  Esta es la parte que informa para que se filtren los datos y editar sea mas facil
r.observe 'filter', (nval, oval, keypath)->
  p.search nval

Mousetrap.bind "esc", (e)->
  e.preventDefault()
  r.set 'filter', ''

window.r = r
window.p = p

window.signinCallback = (authResult)->
  if authResult.status.signed_in
    r.set 'showSigninButton', false
  else
    if authResult.error is 'user_signed_out'
      r.set 
        'showSigninButton':false
        message:'user_signed_out'
    else if authResult.error is 'access_denied'
      r.set 
        'showSigninButton':false
        message:'access_denied'
    else if authResult.error is 'immediate_failed'
      r.set 
        'showSigninButton':false
        message:'immediate_failed'


