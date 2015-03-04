(function() {
  var Local, Machines, m, r,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  Local = (function() {
    function Local() {
      if (typeof localStorege !== "undefined" && localStorege !== null) {
        throw "No se cuenta con localStorege";
      }
    }

    Local.prototype.set = function() {};

    Local.prototype.get = function() {};

    Local.prototype.remove = function() {};

    return Local;

  })();

  Machines = (function() {
    function Machines() {
      this.updateModelData = __bind(this.updateModelData, this);
      this.justUpdateModel = __bind(this.justUpdateModel, this);
      this.askToUpdateTable = __bind(this.askToUpdateTable, this);
      this.getMachines = __bind(this.getMachines, this);
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
      this.grouped = {};
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
          _this.grouped = _.groupBy(_this.data, 'AREA');
          r.set('machines.loadingMachines', false);
          if (typeof r !== "undefined" && r !== null) {
            r.update('machines');
            return _this.setPopup();
          }
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
          console.log("'" + data + "'");
          r.set('machines.loadingMachines', true);
          _this.getMachines();
          r.set('lastUpdate', "Last Update: " + (moment(data.trim()).fromNow()));
          return setTimeout(function() {
            return _this.askToUpdateTable();
          }, 20000);
        };
      })(this));
    };

    Machines.prototype.setPopup = function() {
      return $(".label").popup({
        inline: false,
        hoverable: true,
        position: 'top center'
      });
    };

    Machines.prototype.justUpdateModel = function() {
      if (this.data != null) {
        this.data.map(this.updateModelData);
        this.queryCount++;
        return r.update();
      } else {
        return console.warn("Called before time");
      }
    };

    Machines.prototype.updateModelData = function(srvr) {
      var mmnt, target, _ref;
      target = _.find(this.data, function(el) {
        return el.ID === srvr.ID;
      });
      mmnt = moment(srvr.LASTTICK);
      target.LASTTICK = srvr.LASTTICK;
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
            return ['red', "" + (Math.round(target.diff / target.CICLETIME))];
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

    Machines.prototype.startFetching = function() {};

    return Machines;

  })();

  m = new Machines;

  r = new Ractive({
    el: 'container',
    template: '#template',
    data: {
      filter: '',
      machines: m,
      filtered: true
    }
  });

  r.observe('filter', function(query) {});

  window.r = r;

}).call(this);
