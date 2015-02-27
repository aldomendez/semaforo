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

  getMachines: ()=>
    @now = moment()
    coneccion = $.getJSON "toolbox.php",
      action: "getMachines"
    .done (data) =>
      if data.error then throw data.desc
      if !@data? then @data = data
      data.map @updateModelData
      console.table data
    .fail (err) =>
      console.log 'todo ok'
    .always (data) =>
      @queryCount = 0
      @grouped = _.groupBy @data, 'AREA'
      r.set 'machines.loadingMachines', false
      if !r? then r.update()

  askToUpdateTable:()=>
    @now = moment()
    coneccion = $.get "toolbox.php",
      action: "updateTables"
    .done (data) =>
      if data.error then throw data.desc
    .fail (err) =>
      console.log err
    .always (data) =>
      console.log 'updating table'
      r.set 'machines.loadingMachines', true
      @getMachines()
      r.set 'lastUpdate',"Last Updated: #{moment(data.trim()).fromNow()}"


  justUpdateModel:()=>
    @data.map @updateModelData
    @queryCount++
    r.update()

  updateModelData:(srvr)=>
    # console.log srvr
    target = _.find @data, (el)-> el.ID is srvr.ID
    mmnt = moment(srvr.LASTTICK)
    target.humanized = mmnt.fromNow()
    target.CICLETIME = 1*target.CICLETIME
    target.diff = Math.round((@now.diff mmnt)/1000)
    [target.status,target.desc] = switch
      when target.diff <= target.CICLETIME then ['green','working correctly']
      when target.diff > target.CICLETIME && target.diff < (target.CICLETIME * 2) then ['yellow','some delay']
      else ['red',"#{Math.round( target.diff/target.CICLETIME)} devices missing"]

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