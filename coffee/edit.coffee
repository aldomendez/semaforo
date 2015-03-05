class PartNum
  constructor: () ->
    @load()
    @placeHolder =
      "id": 0
      "used": ""
      "desc": ""
      "num": "-"
      "SAP": false
      "status": "Done"
      "location":"-"
      "udm":"unidad"
      "rev":"-"
  load:()->
    promise = $.getJSON 'toolbox.php',
      'action':'getMachines'
    promise.done (data)=>
      @data = data
      # console.table data
      r.update()
      r.set 'edited', false
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
  getOsfmInfo:(item)->
    promise = $.getJSON 'toolbox.php',
      'action':'getMachines'
      # 'action':'justWait'
      'item':item.num
    promise.done (data)=>
      # console.log data
      if item?
        item.osfm = data
      r.update()
    promise.fail (a,b,c)->
      console.log 'Falle al recojer datos de la base de datos'
      console.log a,b,c
    return promise


# Datos que pueden servir mas adelante
util = {}
util.numReg = /num$/
util.indexMatch = /(\d*)\.num$/

p = new PartNum()

r = new Ractive {
  el: 'container'
  template: '#template'
  data:{
    part_num : p
    edit: false
    editing: 0
    edited: false
    sidebar: false
    deleting: false
    duration:(min,seg)->
      min = parseFloat(min)
      seg = parseFloat(seg)
      if isNaN min then min = 0
      if isNaN seg then seg = 0
      return "#{((min*60) + seg).toFixed(0)}"
  }
}

r.on 'edit', (e, param)->
  e.original.preventDefault()
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
  r.data.part_num.save()
  r.set
    edited:false

r.on 'del', (e, ind)->
  e.original.preventDefault()
  r.data.part_num.data.splice(ind,1)
  console.log 'delete', ind
  r.fire 'backward', e

r.on 'addNew', (e)->
  e.original.preventDefault()
  r.data.part_num.add()
  r.set 
    'deleting': false

r.on 'backward', (e)->
  if e?.original?.preventDefault? then e.original.preventDefault()
  actual = r.get('editing')
  offset = if actual is 0  then r.get('part_num.data.length')-1 else actual-1
  r.set
    editing: offset
    deleting: false

r.on 'forward', (e)->
  if e?.original?.preventDefault? then e.original.preventDefault()
  actual = r.get('editing')
  offset = if actual is r.get('part_num.data.length')-1 then 0 else actual+1
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
  r.data.part_num.pushToQueue(index)

r.observe 'part_num.data.*.*', (nval, oval, keypath)->
  r.set 'edited', true
  r.set 'deleting', false
  if r.get('edit') and util.numReg.test keypath
    if nval.length is 7 
      r.data.part_num.pushToQueue(keypath.match(/(\d*)\.num$/)[1])



mapping = [
    ['d','description']
    ['u','part_num']
    ['l','location']
    ['a','area']
    ['s','status']
    ['r','revision']
  ]
# Toma el mapa de Shorcuts y los enlaza con su controlador
# Sirve para que enfoque las cajas de texto dependiendo de
# que combinacion de teclas presione.
for i in mapping
  do (i)->
    Mousetrap.bind "alt+#{i[0]}",(e)=>
      e.preventDefault()
      document.getElementById("#{i[1]}").focus()
# Crea el controlador de ALT + Flecha izquierda
# para que me muestre el elemento anterior
Mousetrap.bind "alt+left", (e)=>
  e.preventDefault()
  r.fire 'backward', null, e
# Crea el controlador de ALT + Flecha derecha
# para que me muestre el elemento siguiente
Mousetrap.bind "alt+right", (e)=>
  e.preventDefault()
  r.fire 'forward', null, e


window.r = r