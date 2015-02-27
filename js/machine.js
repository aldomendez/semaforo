(function() {
  var Machines,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  Machines = (function() {
    function Machines() {
      this.updateModelData = __bind(this.updateModelData, this);
      this.data = [
        {
          "ID": "10",
          "DB_ID": "CYBOND47",
          "NAME": "CyBond47",
          "DESCRIPTION": null,
          "AREA": "LR4-Shim",
          "PROCESS": "[\"GlassLensAttach\",\"Tosa-Osa\"]",
          "SETUP_DATE": "26-feb-2015 15:39",
          "DBCONNECTION": "mxoptix",
          "DBTABLE": null,
          "LASTTICK": "26-feb-2015 15:39",
          "LASTRUN": "26-feb-2015 15:39",
          "CICLETIME": "40"
        }, {
          "ID": "3",
          "DB_ID": "CYBOND13",
          "NAME": "CyBond13",
          "DESCRIPTION": null,
          "AREA": "LR4-Shim",
          "PROCESS": "[\"Rosa-Shim\"]",
          "SETUP_DATE": "26-feb-2015 15:39",
          "DBCONNECTION": "mxoptix",
          "DBTABLE": null,
          "LASTTICK": "26-feb-2015 15:39",
          "LASTRUN": "26-feb-2015 15:39",
          "CICLETIME": "80"
        }, {
          "ID": "7",
          "DB_ID": "CYBOND38",
          "NAME": "CyBond38",
          "DESCRIPTION": null,
          "AREA": "LR4-Shim",
          "PROCESS": "[\"Tosa-SiLens\",\"Standard\",\"Lens-Remeasure\"]",
          "SETUP_DATE": "26-feb-2015 15:39",
          "DBCONNECTION": "mxoptix",
          "DBTABLE": null,
          "LASTTICK": "26-feb-2015 15:39",
          "LASTRUN": "26-feb-2015 15:39",
          "CICLETIME": "100"
        }, {
          "ID": "8",
          "DB_ID": "CYBOND42",
          "NAME": "CyBond42",
          "DESCRIPTION": null,
          "AREA": "LR4-Shim",
          "PROCESS": "[\"Tosa-Osa\"]",
          "SETUP_DATE": "26-feb-2015 15:39",
          "DBCONNECTION": "mxoptix",
          "DBTABLE": null,
          "LASTTICK": "26-feb-2015 15:39",
          "LASTRUN": "26-feb-2015 15:39",
          "CICLETIME": "50"
        }
      ];
      this.data.forEach(this.updateModelData);
      this.now = moment();
    }

    Machines.prototype.updateModelData = function(srvr) {
      var mmnt, target, _ref;
      target = _.find(this.data, function(el) {
        return el.ID === srvr.ID;
      });
      mmnt = moment(target.LASTTICK);
      target.humanized = mmnt.fromNow();
      target.CICLETIME = 1 * target.CICLETIME;
      target.diff = Math.round((this.now.diff(mmnt)) / 1000);
      return _ref = (function() {
        switch (false) {
          case !(target.diff <= target.CICLETIME):
            return ['green', 'working correctly'];
          case !(target.diff > target.CICLETIME && target.diff < (target.CICLETIME * 2)):
            return ['yellow', 'some delay'];
          default:
            return ['red', "" + (Math.round(target.diff / target.CICLETIME)) + " devices missing"];
        }
      })(), target.status = _ref[0], target.desc = _ref[1], _ref;
    };

    return Machines;

  })();

}).call(this);
