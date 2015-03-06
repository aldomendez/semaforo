class Local
  constructor: () ->
    if localStorege? then throw "No se cuenta con localStorege"
  set:()->

  get:()->

  remove:()->
  


class Machines
  constructor: () ->
    @queryCount = 0
    @loadingMachines = true
    @fuse =
      options:
        keys:['AREA','PROCESS','NAME']
    @getMachines()
    @askToUpdateTable()
    @startFetching()
    @refreshModel()
    @grouped = {}
    setTimeout ()=>
      location.href = "http://cymautocert/osaapp/semaforo/"
    ,450000 # 7.5 min

  getMachines: ()=>
    @now = moment()
    coneccion = $.getJSON "toolbox.php",
      action: "getMachines"
    .done (data) =>
      if data.error then throw data.desc
      if !@data? then @data = data
      data.map @updateModelData
      @fuse.search = new Fuse @data, @fuse.options
      # console.table data
    .fail (err) =>
      console.log 'todo ok'
    .always (data) =>
      @queryCount = 0
      # Utilizo underscore para agrupar los datos por area
      @grouped = _.groupBy @data, 'AREA'
      @grouped = _.map @grouped,(el,key)->
        _g = _.groupBy el, 'PROCESS'
        return {key:key, data:_g}
      r.set 'machines.loadingMachines', false
      setTimeout ()=>
        @getMachines()
      ,20000
      if r?
        r.update()
        @setPopup()
        


  askToUpdateTable:()=>
    @now = moment()
    coneccion = $.get "toolbox.php",
      action: "updateTables"
    .done (data) =>
      if data.error then throw data.desc
    .fail (err) =>
      console.log err
    .always (data) =>
      r.set 'lastUpdate', data
      console.log "'#{data}'"
      r.set 'machines.loadingMachines', true
      @getMachines()
      # r.set 'lastUpdate',"Last Update: #{moment(data.trim()).fromNow()}"
      setTimeout ()=>
        @askToUpdateTable()
      ,20000

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
    

m = new Machines
r = new Ractive
  el: 'container'
  template:'#template'
  data:
    filter:''
    machines:m
    filtered:true
    humanizeDiff: (date)->
      moment(date.trim()).fromNow()


r.observe 'filter', (query)->
  console.log query



window.r = r