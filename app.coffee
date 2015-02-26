class Machines
  constructor: () ->
    @queryCount = 0
    @fuse =
      options:
        keys:['AREA','PROCESS','NAME']
    @fuse.search = new Fuse @data||[], @fuse.options
    @getMachines()
    @startFetching()

  getMachines: ()->
    @now = moment()
    coneccion = $.getJSON "toolbox.php",
      action: "getMachines"
    .done (data) =>
      if data.error then throw data.desc
      @data = data
      @data.map @updateModelData
      console.table data
    .fail (err) =>
        console.log 'todo ok'
    .always (data) =>
        @queryCount++
        @loadingIcon = false
        r.update()

  updateModelData:(srvr)=>
    # console.log srvr
    target = _.find @data, (el)-> el.ID is srvr.ID
    mmnt = moment(target.LASTTICK)
    target.humanized = mmnt.fromNow()
    target.diff = Math.round((@now.diff mmnt)/1000)
    target.status = switch
      when target.diff <= target.CICLETIME then 'green'
      when target.diff > target.CICLETIME > target.diff *2 then 'yellow'
      else 'red'
    target.desc = switch
      when target.diff <= target.CICLETIME then 'working correctly'
      when target.diff > target.CICLETIME > target.diff *2 then 'some delay'
      else "#{Math.round( target.diff/target.CICLETIME)} devices missing"

  startFetching:()->
    setInterval ()=>
      @getMachines()
    ,60000

m = new Machines
r = new Ractive
  el: 'container'
  template:'#template'
  data:
    filter:''
    machines:m


r.observe 'filter', (query)->
  # console.log machines.fuse.search query



window.r = r