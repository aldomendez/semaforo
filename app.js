(function() {
  var Machines, r,
    __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  Machines = (function() {
    function Machines() {
      this.updateModelData = __bind(this.updateModelData, this);
      this.queryCount = 0;
      this.getMachines();
    }

    Machines.prototype.getMachines = function() {
      var coneccion;
      return coneccion = $.getJSON("toolbox.php", {
        action: "getMachines"
      }).done((function(_this) {
        return function(data) {
          if (data.error) {
            throw data.desc;
          }
          _this.data = data;
          return _this.data.map(_this.updateModelData);
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
      var target;
      target = _.find(this.data, function(el) {
        return el.ID === srvr.ID;
      });
      return console.log(target);
    };

    return Machines;

  })();

  r = new Ractive({
    el: 'container',
    template: '#template',
    data: {
      machines: new Machines()
    }
  });

  window.r = r;

}).call(this);
//# sourceMappingURL=app.js.map