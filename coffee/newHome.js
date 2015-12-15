import Vue from 'vue'
import fuse from 'fuse.js'
import _ from 'underscore'
import moment from 'moment'
import App from './app.vue'

Vue.use(require('vue-resource'))

new Vue({
  el: 'body',
  components: {
    app: App
  }
})






/*
class Machines
  constructor: () ->
    @queryCount = 0
    @loadingMachines = true
    @fuse =
      options:
        keys:['AREA','PROCESS','NAME']
    @getMachines()
    # Now another service is updating the database
    # @askToUpdateTable()
    @startFetching()
    @refreshModel()
    @grouped={}
    ###setTimeout ()=>
      location.href = location.href
    ,450000 # 7.5 min###
    @sizes=['one','one','two','three','four','five','seven','eight','nine']
  getMachines: ()=>
    @now = moment()
    coneccion = $.getJSON "machines.php"
    .done (data) =>
      # Envia un error si hay problemas con los datos obtenidos
      if data.error then throw data.desc
      # En caso de que no tengamos datos previos los asignamos
      # antes de continuar
      if !@data? then @data = data
      # Agregamos datos que se muestran al usuario pero que no vienen desde la base de datos
      data.map @updateModelData
      


      @data.map (target,i)=>
        if !@grouped[target.BU]? then @grouped[target.BU] = {}
        if !@grouped[target.BU][target.AREA]? then @grouped[target.BU][target.AREA] = {}
        if !@grouped[target.BU][target.AREA][target.PROCESS]? then @grouped[target.BU][target.AREA][target.PROCESS] = []
        element = _.find @grouped[target.BU][target.AREA][target.PROCESS], (el)-> return el.ID is target.ID
        if !element?
          @grouped[target.BU][target.AREA][target.PROCESS].push target
        else
          element = target
      # console.log @grouped


    .fail (err) =>
      console.log 'todo ok'
    .always (data) =>
      @queryCount = 0
      # Utilizo underscore para agrupar los datos por area
      setTimeout ()=>
        @getMachines()
      ,20000
      if r?
        r.update()
        @setPopup()
        r.set 'machines.loadingMachines', false
        r.set 'size', @sizes[_.size(r.get('machines.grouped'))]
        
  setPopup:()->
    $(".label").popup({
      inline:false
      hoverable:true
      position:'top center'
      })

  justUpdateModel:()=>
    if @data?
      @data.map @updateModelData
      @queryCount++
      r.update()
    else
      console.warn "Called before time"

  updateModelData:(srvr)=>
    # console.log srvr
    target = _.find @data, (el)-> el.ID is srvr.ID
    # Genera le fecha actual directamente con el framework de manejo de fechas
    mmnt = moment(srvr.LASTTICK)
    # Se guarda localmente la fecha de la ultima pieza que vio en la base de datos.
    target.LASTTICK = srvr.LASTTICK
    # Obtiene el numero de minutos 
    target.humanized = mmnt.fromNow()
    # Convierte a numero el tiempo de ciclo
    target.CICLETIME = 1*target.CICLETIME
    # Calculo de segundos sin que termine una pieza
    target.diff = Math.round((@now.diff mmnt)/1000)
    # Calculo de estatus y de las piezas que lleva sin hacer
    [target.status,target.desc] = switch
      when target.diff <= target.CICLETIME then ['green','working correctly']
      when target.diff > target.CICLETIME && target.diff < (target.CICLETIME * 2) then ['yellow','some delay']
      else ['red',"#{Math.round( target.diff/target.CICLETIME)}"]

  refreshModel:()->
    setInterval ()=>
      @justUpdateModel()
    ,1000

  startFetching:()->

parseDate = (d) ->
  new Date(d.substring(0,4),
    d.substring(4, 6) - 1,
    d.substring(6, 8),
    d.substring(8, 10),
    d.substring(10, 12),
    d.substring(12,14))

# window.oc = $.get 'dateoffset.php'
# oc.done (data)->
#   serverDate = parseDate data
#   actualDate = new Date()
#   window.oc = Math.floor(((serverDate - actualDate)/1000)/60)
# oc.fail (data)->
#   console.warn data



m = new Machines
r = new Ractive
  el: 'container'
  template:'#template'
  data:
    filter:''
    machines:m
    filtered:true
    humanizeDiff: (date)->
      console.log moment(date.trim()).fromNow()
      moment(date.trim()).fromNow() || 'unknow'
    round:(num)->
      Math.round num
    color:{
      Alfredo_Tongo:'yellow'
      Tomas_Lugo:'blue'
      Gerardo_Martinez:'green'
      Luis_Bejar:'orange'
    }

r.set 'size', 'two'

r.observe 'filter', (query)->
  console.log query



window.r = r
*/