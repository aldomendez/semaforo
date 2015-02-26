(function() {
  var Machines, r;

  Machines = (function() {
    function Machines() {
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
          return data.forEach(function(d, i) {
            return console.log(d);
          });
        };
      })(this)).fail((function(_this) {
        return function(err) {
          return console.log('todo ok');
        };
      })(this)).always((function(_this) {
        return function(data) {
          _this.loadingIcon = false;
          return r.update();
        };
      })(this));
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