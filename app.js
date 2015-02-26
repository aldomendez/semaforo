(function() {
  var Machines, m, r,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  Machines = (function() {
    function Machines() {
      this.updateModelData = __bind(this.updateModelData, this);
      this.queryCount = 0;
      this.fuse = {
        options: {
          keys: ['AREA', 'PROCESS', 'NAME']
        }
      };
      this.fuse.search = new Fuse(this.data || [], this.fuse.options);
      this.getMachines();
      this.startFetching();
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
          _this.data = data;
          _this.data.map(_this.updateModelData);
          return console.table(data);
        };
      })(this)).fail((function(_this) {
        return function(err) {
          return console.log('todo ok');
        };
      })(this)).always((function(_this) {
        return function(data) {
          _this.queryCount++;
          _this.loadingIcon = false;
          return r.update();
        };
      })(this));
    };

    Machines.prototype.updateModelData = function(srvr) {
      var mmnt, target;
      target = _.find(this.data, function(el) {
        return el.ID === srvr.ID;
      });
      mmnt = moment(target.LASTTICK);
      target.humanized = mmnt.fromNow();
      target.diff = Math.round((this.now.diff(mmnt)) / 1000);
      target.status = (function() {
        var _ref;
        switch (false) {
          case !(target.diff <= target.CICLETIME):
            return 'working correctly';
          case !((target.diff > (_ref = target.CICLETIME) && _ref > target.diff * 2)):
            return 'some delay';
          default:
            return 'red';
        }
      })();
      return target.desc = (function() {
        var _ref;
        switch (false) {
          case !(target.diff <= target.CICLETIME):
            return 'green';
          case !((target.diff > (_ref = target.CICLETIME) && _ref > target.diff * 2)):
            return 'yellow';
          default:
            return "" + (Math.round(target.diff / target.CICLETIME)) + " devices missing";
        }
      })();
    };

    Machines.prototype.startFetching = function() {
      return setInterval((function(_this) {
        return function() {
          return _this.getMachines();
        };
      })(this), 60000);
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

  r.observe('filter', function(query) {
    return console.log(machines.fuse.search(query));
  });

  window.r = r;

}).call(this);
//# sourceMappingURL=app.js.map