(function() {
  var Local, Machines, m, parseDate, r,
    bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  $(document).ajaxStart(function() {
    return NProgress.start();
  });

  $(document).ajaxStop(function() {
    return NProgress.done();
  });

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
      this.updateModelData = bind(this.updateModelData, this);
      this.justUpdateModel = bind(this.justUpdateModel, this);
      this.askToUpdateTable = bind(this.askToUpdateTable, this);
      this.getMachines = bind(this.getMachines, this);
      this.queryCount = 0;
      this.loadingMachines = true;
      this.fuse = {
        options: {
          keys: ['AREA', 'PROCESS', 'NAME']
        }
      };
      this.getMachines();
      this.askToUpdateTable();
      this.startFetching();
      this.refreshModel();
      this.grouped = {};
      setTimeout((function(_this) {
        return function() {
          return location.href = location.href;
        };
      })(this), 450000);
      this.sizes = ['one', 'one', 'two', 'three', 'four', 'five', 'seven', 'eight', 'nine'];
    }

    Machines.prototype.getMachines = function() {
      var coneccion;
      this.now = moment();
      return coneccion = $.getJSON("machines.php").done((function(_this) {
        return function(data) {
          if (data.error) {
            throw data.desc;
          }
          if (_this.data == null) {
            _this.data = data;
          }
          data.map(_this.updateModelData);
          return _this.grouped = _.map(_.groupBy(_this.data, 'BU'), function(el, key1) {
            var group;
            group = _.groupBy(el, "AREA");
            group = _.map(group, function(el, key2) {
              var _g;
              _g = _.groupBy(el, 'PROCESS');
              return {
                key: key2,
                data: _g
              };
            });
            return {
              key: key1,
              data: group
            };
          });
        };
      })(this)).fail((function(_this) {
        return function(err) {
          return console.log('todo ok');
        };
      })(this)).always((function(_this) {
        return function(data) {
          _this.queryCount = 0;
          setTimeout(function() {
            return _this.getMachines();
          }, 20000);
          if (typeof r !== "undefined" && r !== null) {
            r.update();
            _this.setPopup();
            r.set('machines.loadingMachines', false);
            return r.set('size', _this.sizes[r.get('machines.grouped.length')]);
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
          r.set('lastUpdate', data);
          console.log("'" + data + "'");
          r.set('machines.loadingMachines', true);
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
      var mmnt, ref, target;
      target = _.find(this.data, function(el) {
        return el.ID === srvr.ID;
      });
      mmnt = moment(srvr.LASTTICK);
      target.LASTTICK = srvr.LASTTICK;
      target.humanized = mmnt.fromNow();
      target.CICLETIME = 1 * target.CICLETIME;
      target.diff = Math.round((this.now.diff(mmnt)) / 1000);
      return ref = (function() {
        switch (false) {
          case !(target.diff <= target.CICLETIME):
            return ['green', 'working correctly'];
          case !(target.diff > target.CICLETIME && target.diff < (target.CICLETIME * 2)):
            return ['yellow', 'some delay'];
          default:
            return ['red', "" + (Math.round(target.diff / target.CICLETIME))];
        }
      })(), target.status = ref[0], target.desc = ref[1], ref;
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

  parseDate = function(d) {
    return new Date(d.substring(0, 4), d.substring(4, 6) - 1, d.substring(6, 8), d.substring(8, 10), d.substring(10, 12), d.substring(12, 14));
  };

  m = new Machines;

  r = new Ractive({
    el: 'container',
    template: '#template',
    data: {
      filter: '',
      machines: m,
      filtered: true,
      humanizeDiff: function(date) {
        console.log(moment(date.trim()).fromNow());
        return moment(date.trim()).fromNow() || 'unknow';
      },
      round: function(num) {
        return Math.round(num);
      },
      color: {
        Alfredo_Tongo: 'yellow',
        Tomas_Lugo: 'blue',
        Gerardo_Martinez: 'green',
        Luis_Bejar: 'orange'
      }
    }
  });

  r.set('size', 'two');

  r.observe('filter', function(query) {
    return console.log(query);
  });

  window.r = r;

}).call(this);
