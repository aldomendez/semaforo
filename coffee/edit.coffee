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
      "BU": "1"
  load:()->
    promise = $.getJSON 'toolbox.php',
      'action':'getMachines'
    promise.done (data)=>
      @loaded = true
      data.map (el)-> el.min = (el.CICLETIME/60).toFixed(2)
      @data = data
      @original = _.clone @data
      @filter = new Fuse(@data,{keys: ['NAME', 'BU']})
      r.update()
      r.set 'edited', false
  search:(searchString)->
    if @loaded
      if searchString isnt ''
        @data = @filter.search searchString
        r.update()
      else
        @data = _.clone @original
        r.update()
      
    
  save:()->
    if @data.length isnt 0 and r.get 'edited'
      promise = $.post 'utilities.php',
        datos: @data
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

p = new Machines()

r = new Ractive {
  el: 'container'
  template: '#template'
  data:{
    machines : p
    edit: false
    editing: 0
    edited: false
    sidebar: false
    deleting: false
    filter:''
    duration:(min,seg)->
      min = parseFloat(min)
      seg = parseFloat(seg)
      if isNaN min then min = 0
      if isNaN seg then seg = 0
      return "#{((min*60) + seg).toFixed(0)}"
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
  r.data.machines.save()
  r.set
    edited:false

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

r.on 'forward', (e)->
  if e?.original?.preventDefault? then e.original.preventDefault()
  actual = r.get('editing')
  offset = if actual is r.get('machines.data.length')-1 then 0 else actual+1
  r.set
    editing: offset
    deleting: false

r.on 'sidebar', (e)->
  e.original.preventDefault()
  r.set 'sidebar', !r.get 'sidebar'

r.on 'askToDelete', (e)->
  e.original.preventDefault()
  r.set
    deleting:true

r.on 'fetchOSFMData', (e, index)->
  e.original.preventDefault()
  r.data.machines.pushToQueue(index)

r.on 'toogleSidebar', (e)->
  e.original.preventDefault()
  r.set 'sidebar', !r.get 'sidebar'
  console.log r.get('sidebar')

r.observe 'machines.data.*.*', (nval, oval, keypath)->
  r.set 'edited', true
  r.set 'deleting', false
  if r.get('edit') and util.numReg.test keypath
    if nval.length is 7 
      r.data.machines.pushToQueue(keypath.match(/(\d*)\.num$/)[1])

r.observe 'filter', (nval, oval, keypath)->
  console.log nval
  p.search nval


# mapping = [
#     ['d','description']
#     ['u','part_num']
#     ['l','location']
#     ['a','area']
#     ['s','status']
#     ['r','revision']
#   ]
# # Toma el mapa de Shorcuts y los enlaza con su controlador
# # Sirve para que enfoque las cajas de texto dependiendo de
# # que combinacion de teclas presione.
# for i in mapping
#   do (i)->
#     Mousetrap.bind "alt+#{i[0]}",(e)=>
#       e.preventDefault()
#       document.getElementById("#{i[1]}").focus()
# # Crea el controlador de ALT + Flecha izquierda
# # para que me muestre el elemento anterior
# Mousetrap.bind "alt+left", (e)=>
#   e.preventDefault()
#   r.fire 'backward', null, e
# # Crea el controlador de ALT + Flecha derecha
# # para que me muestre el elemento siguiente
# Mousetrap.bind "alt+right", (e)=>
#   e.preventDefault()
#   r.fire 'forward', null, e
Mousetrap.bind "esc", (e)=>
  console.log 'esc'
  e.preventDefault()
  r.set 'filter', ''


window.r = r
window.p = p