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
      # Envia un error si hay problemas con los datos obtenidos
      if data.error then throw data.desc
      # En caso de que no tengamos datos previos los asignamos
      # antes de continuar
      if !@data? then @data = data
      # Agregamos datos que se muestran al usuario pero que no vienen desde la base de datos
      data.map @updateModelData
      
      # Aunque no funciona, generamos el filtro para las busquedas
      # Lo voy a dejar de lado hasta que sepa como es que lo voy a usar
      # @fuse.search = new Fuse @data, @fuse.options

      # Este bloque genera 3 niveles de agrupamiento por BU, area y por proceso
      # @grouped = 
      @grouped = _.map _.groupBy(@data, 'BU'), (el, key1)->
        group = _.groupBy el, "AREA"
        group = _.map group,(el,key2)->
          _g = _.groupBy el, 'PROCESS'
          return {key:key2, data:_g}
        return {key:key1, data:group}
      # console.table data
    .fail (err) =>
      console.log 'todo ok'
    .always (data) =>
      @queryCount = 0
      # Utilizo underscore para agrupar los datos por area
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