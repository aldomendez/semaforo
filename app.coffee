class Machines
  constructor: () ->
    @queryCount = 0
    @getMachines()
  getMachines: ()->
    coneccion = $.getJSON "toolbox.php",
      action: "getMachines"
    .done (data) =>
      if data.error then throw data.desc
      @data = data
      @data.map @updateModelData
      # console.log data
    .fail (err) =>
        console.log 'todo ok'
    .always (data) =>
        @queryCount++
        @loadingIcon = false
        r.update()
  updateModelData:(srvr)=>
    # console.log srvr
    target = _.find @data, (el)-> el.ID is srvr.ID
    console.log target



r = new Ractive
  el: 'container'
  template:'#template'
  data:
    machines:new Machines()




window.r = r