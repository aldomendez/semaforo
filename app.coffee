class Machines
  constructor: () ->
    @getMachines()
  getMachines: ()->
    coneccion = $.getJSON "toolbox.php",
      action: "getMachines"
    .done (data) =>
      if data.error then throw data.desc
      data.forEach  (d,i)->
        console.log d
    .fail (err) =>
        console.log 'todo ok'
    .always (data) =>
        @loadingIcon = false
        r.update()

  



r = new Ractive
  el: 'container'
  template:'#template'
  data:
    machines:new Machines()




window.r = r