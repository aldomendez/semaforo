(function() {
  var Machines, m, r,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  Machines = (function() {
    function Machines() {
      this.updateModelData = __bind(this.updateModelData, this);
      this.askToUpdateTable = __bind(this.askToUpdateTable, this);
      this.queryCount = 0;
      this.loadingMachines = true;
      this.fuse = {
        options: {
          keys: ['AREA', 'PROCESS', 'NAME']
        }
      };
      this.fuse.search = new Fuse(this.data || [], this.fuse.options);
      this.getMachines();
      this.askToUpdateTable();
      this.startFetching();
      this.refreshModel();
    }

    Machines.prototype.getMachines = function() {
      var coneccion;
      this.now = moment();
      return coneccion = $.getJSON("toolbox.php", {
        action: "getMachines"
      }).done((function(_this) {
        return function(data) {
          if (data.error) {
            throw data.desc;
          }
          if (_this.data == null) {
            _this.data = data;
          }
          data.map(_this.updateModelData);
          return console.table(data);
        };
      })(this)).fail((function(_this) {
        return function(err) {
          return console.log('todo ok');
        };
      })(this)).always((function(_this) {
        return function(data) {
          _this.queryCount = 0;
          _this.loadingMachines = false;
          return r.update();
        };
      })(this));
    };

    Machines.prototype.askToUpdateTable = function() {
      var coneccion;
      this.now = moment();
      return coneccion = $.get("toolbox.php", {
        action: "updateTables"
      }).done((function(_this) {
        return function(data) {
          if (data.error) {
            throw data.desc;
          }
        };
      })(this)).fail((function(_this) {
        return function(err) {
          return console.log(err);
        };
      })(this)).always((function(_this) {
        return function(data) {
          _this.getMachines;
          return r.set('lastUpdate', "Last Updated: " + (moment(data.trim()).fromNow()));
        };
      })(this));
    };

    Machines.prototype.justUpdateModel = function() {
      this.data.map(this.updateModelData);
      this.queryCount++;
      return r.update();
    };

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

    Machines.prototype.refreshModel = function() {
      return setInterval((function(_this) {
        return function() {
          return _this.justUpdateModel();
        };
      })(this), 1000);
    };

    Machines.prototype.startFetching = function() {
      return setInterval((function(_this) {
        return function() {
          return _this.askToUpdateTable();
        };
      })(this), 6000);
    };

    return Machines;

  })();

  m = new Machines;

  r = new Ractive({
    el: 'container',
    template: '#template',
    data: {
      filter: '',
      machines: m
    }
  });

  r.observe('filter', function(query) {});

  window.r = r;

}).call(this);
