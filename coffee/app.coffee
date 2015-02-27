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
    @fuse.search = new Fuse @data||[], @fuse.options
    @getMachines()
    @askToUpdateTable()
    @startFetching()
    @refreshModel()
    @grouped = {}
    setTimeout ()=>
      @setPopup()
    ,1600

  getMachines: ()=>
    @now = moment()
    coneccion = $.getJSON "toolbox.php",
      action: "getMachines"
    .done (data) =>
      if data.error then throw data.desc
      if !@data? then @data = data
      data.map @updateModelData
      # console.table data
    .fail (err) =>
      console.log 'todo ok'
    .always (data) =>
      @queryCount = 0
      @grouped = _.groupBy @data, 'AREA'
      r.set 'machines.loadingMachines', false
      if !r? then r.update('machines')

  askToUpdateTable:()=>
    @now = moment()
    coneccion = $.get "toolbox.php",
      action: "updateTables"
    .done (data) =>
      if data.error then throw data.desc
    .fail (err) =>
      console.log err
    .always (data) =>
      console.log "'#{data}'"
      r.set 'machines.loadingMachines', true
      @getMachines()
      @setPopup()
      r.set 'lastUpdate',"Last Update: #{moment(data.trim()).fromNow()}"

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
    mmnt = moment(srvr.LASTTICK)
    target.LASTTICK = srvr.LASTTICK
    target.humanized = mmnt.fromNow()
    target.CICLETIME = 1*target.CICLETIME
    target.diff = Math.round((@now.diff mmnt)/1000)
    [target.status,target.desc] = switch
      when target.diff <= target.CICLETIME then ['green','working correctly']
      when target.diff > target.CICLETIME && target.diff < (target.CICLETIME * 2) then ['yellow','some delay']
      else ['red',"#{Math.round( target.diff/target.CICLETIME)}"]

  refreshModel:()->
    setInterval ()=>
      @justUpdateModel()
    ,1000

  startFetching:()->
    setInterval ()=>
      @askToUpdateTable()
    ,20000

m = new Machines
r = new Ractive
  el: 'container'
  template:'#template'
  data:
    filter:''
    machines:m
    filtered:true


r.observe 'filter', (query)->
  # console.log machines.fuse.search query



window.r = r