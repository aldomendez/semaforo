class Machines
  constructor: () ->
    @data = [{"ID":"10","DB_ID":"CYBOND47","NAME":"CyBond47","DESCRIPTION":null,"AREA":"LR4-Shim","PROCESS":"[\"GlassLensAttach\",\"Tosa-Osa\"]","SETUP_DATE":"26-feb-2015 15:39","DBCONNECTION":"mxoptix","DBTABLE":null,"LASTTICK":"26-feb-2015 15:39","LASTRUN":"26-feb-2015 15:39","CICLETIME":"40"},{"ID":"3","DB_ID":"CYBOND13","NAME":"CyBond13","DESCRIPTION":null,"AREA":"LR4-Shim","PROCESS":"[\"Rosa-Shim\"]","SETUP_DATE":"26-feb-2015 15:39","DBCONNECTION":"mxoptix","DBTABLE":null,"LASTTICK":"26-feb-2015 15:39","LASTRUN":"26-feb-2015 15:39","CICLETIME":"80"},{"ID":"7","DB_ID":"CYBOND38","NAME":"CyBond38","DESCRIPTION":null,"AREA":"LR4-Shim","PROCESS":"[\"Tosa-SiLens\",\"Standard\",\"Lens-Remeasure\"]","SETUP_DATE":"26-feb-2015 15:39","DBCONNECTION":"mxoptix","DBTABLE":null,"LASTTICK":"26-feb-2015 15:39","LASTRUN":"26-feb-2015 15:39","CICLETIME":"100"},{"ID":"8","DB_ID":"CYBOND42","NAME":"CyBond42","DESCRIPTION":null,"AREA":"LR4-Shim","PROCESS":"[\"Tosa-Osa\"]","SETUP_DATE":"26-feb-2015 15:39","DBCONNECTION":"mxoptix","DBTABLE":null,"LASTTICK":"26-feb-2015 15:39","LASTRUN":"26-feb-2015 15:39","CICLETIME":"50"}]
    @data.forEach @updateModelData
    @now = moment()
  updateModelData:(srvr)=>
    # console.log srvr
    target = _.find @data, (el)-> el.ID is srvr.ID
    mmnt = moment(target.LASTTICK)
    target.humanized = mmnt.fromNow()
    target.CICLETIME = 1*target.CICLETIME
    target.diff = Math.round((@now.diff mmnt)/1000)
    [target.status,target.desc] = switch
      when target.diff <= target.CICLETIME then ['green','working correctly']
      when target.diff > target.CICLETIME && target.diff < (target.CICLETIME * 2) then ['yellow','some delay']
      else ['red',"#{Math.round( target.diff/target.CICLETIME)} devices missing"]
