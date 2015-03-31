(function() {
  var Machines, duration, p, r, util;

  Machines = (function() {
    function Machines() {
      this.loaded = false;
      this.load();
      this.placeHolder = {
        "DB_ID": "",
        "NAME": "",
        "DESCRIPTION": null,
        "AREA": "",
        "PROCESS": "",
        "SETUP_DATE": "",
        "DBCONNECTION": "",
        "DBTABLE": "",
        "DBMACHINE": "",
        "DBDEVICE": "",
        "LASTTICK": "",
        "LASTRUN": "",
        "CICLETIME": "",
        "BU": "undefined"
      };
    }

    Machines.prototype.load = function() {
      var promise;
      promise = $.getJSON('machines.php/all');
      return promise.done((function(_this) {
        return function(data) {
          _this.loaded = true;
          data.map(function(el) {
            return el.min = (el.CICLETIME / 60).toFixed(2);
          });
          data.map(function(el) {
            return el.seg = 0;
          });
          _this.data = data;
          r.set('managers', _.unique(_.pluck(_this.data, 'BU')));
          r.set('process', _.unique(_.pluck(_this.data, 'PROCESS')));
          _this.original = _.clone(_this.data);
          _this.filter = new Fuse(_this.data, {
            keys: ['NAME', 'BU', 'AREA', 'PROCESS']
          });
          r.update();
          return r.set('edited', false);
        };
      })(this));
    };

    Machines.prototype.search = function(searchString) {
      if (this.loaded) {
        if (searchString !== '') {
          this.data = this.filter.search(searchString);
          return r.update();
        } else {
          this.data = _.clone(this.original);
          return r.update();
        }
      }
    };

    Machines.prototype.clone = function(index) {
      var newMachine;
      newMachine = _.clone(r.get("machines.data." + index));
      newMachine.ID = void 0;
      delete newMachine.ID;
      this.data.push(newMachine);
      return r.set({
        editing: this.data.length - 1,
        edit: true,
        deleting: false
      });
    };

    Machines.prototype.save = function() {
      var datos, index, promise;
      if (this.data.length !== 0 && r.get('edited')) {
        index = r.get('editing');
        datos = r.get("machines.data." + index);
        datos.CICLETIME = duration(datos.min, datos.seg);
        if (datos.ID !== void 0) {
          promise = $.ajax({
            method: 'put',
            url: "machines.php/" + datos.ID,
            data: datos
          });
          return promise.done(function() {
            return r.set('saved', 'successfull');
          });
        } else {
          promise = $.ajax({
            method: 'post',
            url: "machines.php/",
            data: datos
          });
          return promise.done(function() {
            return r.set('saved', 'successfull');
          });
        }
      } else {

      }
    };

    Machines.prototype.add = function() {
      this.placeHolder.id = this.data.length + 1;
      this.data.push(_.clone(this.placeHolder));
      return r.set({
        editing: this.data.length - 1,
        edit: true,
        deleting: false
      });
    };

    return Machines;

  })();

  util = {};

  util.numReg = /num$/;

  util.indexMatch = /(\d*)\.num$/;

  duration = function(min, seg) {
    min = parseFloat(min);
    seg = parseFloat(seg);
    if (isNaN(min)) {
      min = 0;
    }
    if (isNaN(seg)) {
      seg = 0;
    }
    return "" + (((min * 60) + seg).toFixed(0));
  };

  p = new Machines();

  r = new Ractive({
    el: 'container',
    template: '#template',
    data: {
      machines: p,
      saved: '0',
      edit: false,
      editing: false,
      edited: false,
      sidebar: false,
      deleting: false,
      filter: '',
      duration: duration
    }
  });

  r.on('setMinutes', function(e, id) {
    var actual, branch, offset, valToSet;
    console.log(id);
    e.original.preventDefault();
    branch = "machines.data." + id + ".min";
    offset = e.dy < 0 ? -1 : 1;
    actual = parseInt(r.get(branch), 10);
    valToSet = actual + offset;
    if (valToSet >= 0) {
      return r.set(branch, valToSet);
    } else {
      return r.set(branch, 0);
    }
  });

  r.on('edit', function(e, param) {
    e.original.preventDefault();
    return r.set({
      edit: true,
      editing: param,
      deleting: false
    });
  });

  r.on('returnToList', function(e) {
    e.original.preventDefault();
    return r.set({
      edit: false,
      editing: 0,
      deleting: false
    });
  });

  r.on('save', function(e) {
    e.original.preventDefault();
    if (r.get('edited')) {
      p.save();
    }
    return r.set({
      edited: false
    });
  });

  r.on('clone', function(e, index) {
    e.original.preventDefault();
    return p.clone(index);
  });

  r.on('del', function(e, ind) {
    e.original.preventDefault();
    r.data.machines.data.splice(ind, 1);
    console.log('delete', ind);
    return r.fire('backward', e);
  });

  r.on('addNew', function(e) {
    e.original.preventDefault();
    r.data.machines.add();
    return r.set({
      'deleting': false
    });
  });

  r.on('backward', function(e) {
    var actual, offset, _ref;
    if ((e != null ? (_ref = e.original) != null ? _ref.preventDefault : void 0 : void 0) != null) {
      e.original.preventDefault();
    }
    actual = r.get('editing');
    offset = actual === 0 ? r.get('machines.data.length') - 1 : actual - 1;
    return r.set({
      editing: offset,
      deleting: false,
      edited: false
    });
  });

  r.on('forward', function(e) {
    var actual, offset, _ref;
    if ((e != null ? (_ref = e.original) != null ? _ref.preventDefault : void 0 : void 0) != null) {
      e.original.preventDefault();
    }
    actual = r.get('editing');
    offset = actual === r.get('machines.data.length') - 1 ? 0 : actual + 1;
    return r.set({
      editing: offset,
      deleting: false,
      edited: false
    });
  });

  r.on('sidebar', function(e) {
    e.original.preventDefault();
    return r.set('sidebar', !r.get('sidebar'));
  });

  r.on('askToDelete', function(e) {
    e.original.preventDefault();
    return r.set({
      deleting: true
    });
  });

  r.on('updateManager', function(e, val) {
    var editing;
    e.original.preventDefault();
    editing = r.get('editing');
    return r.set("machines.data." + editing + ".BU", val);
  });

  r.on('addNewManager', function(e) {
    return e.original.preventDefault();
  });

  r.observe('machines.data.*.*', function(nval, oval, keypath) {
    r.set('edited', true);
    r.set('deleting', false);
    if (r.get('edit') && util.numReg.test(keypath)) {
      if (nval.length === 7) {
        return r.data.machines.pushToQueue(keypath.match(/(\d*)\.num$/)[1]);
      }
    }
  });

  r.observe('machines.data.*.min machines.data.*.seg', function(nval, oval, keypath) {
    var id;
    id = keypath.match(/(\d+)..*$/)[1];
    return r.set("machines.data." + id + ".CICLETIME", duration(r.get("machines.data." + id + ".min"), r.get("machines.data." + id + ".seg")));
  });

  r.observe('filter', function(nval, oval, keypath) {
    return p.search(nval);
  });

  Mousetrap.bind("esc", function(e) {
    e.preventDefault();
    return r.set('filter', '');
  });

  window.r = r;

  window.p = p;

}).call(this);
