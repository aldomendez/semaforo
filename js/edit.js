var PartNum, i, mapping, p, r, util, _fn, _i, _len;

PartNum = (function() {
  function PartNum() {
    this.load();
    this.placeHolder = {
      "id": 0,
      "used": "",
      "desc": "",
      "num": "-",
      "SAP": false,
      "status": "Done",
      "location": "-",
      "udm": "unidad",
      "rev": "-"
    };
  }

  PartNum.prototype.load = function() {
    var promise;
    promise = $.getJSON('toolbox.php', {
      'action': 'getMachines'
    });
    return promise.done((function(_this) {
      return function(data) {
        _this.data = data;
        r.update();
        return r.set('edited', false);
      };
    })(this));
  };

  PartNum.prototype.save = function() {
    var promise;
    if (this.data.length !== 0 && r.get('edited')) {
      return promise = $.post('utilities.php', {
        datos: this.data
      });
    }
  };

  PartNum.prototype.add = function() {
    this.placeHolder.id = this.data.length + 1;
    this.data.push(_.clone(this.placeHolder));
    return r.set({
      editing: this.data.length - 1,
      edit: true,
      deleting: false
    });
  };

  PartNum.prototype.getOsfmInfo = function(item) {
    var promise;
    promise = $.getJSON('toolbox.php', {
      'action': 'getMachines',
      'item': item.num
    });
    promise.done((function(_this) {
      return function(data) {
        if (item != null) {
          item.osfm = data;
        }
        return r.update();
      };
    })(this));
    promise.fail(function(a, b, c) {
      console.log('Falle al recojer datos de la base de datos');
      return console.log(a, b, c);
    });
    return promise;
  };

  return PartNum;

})();

util = {};

util.numReg = /num$/;

util.indexMatch = /(\d*)\.num$/;

p = new PartNum();

r = new Ractive({
  el: 'container',
  template: '#template',
  data: {
    part_num: p,
    edit: false,
    editing: 0,
    edited: false,
    sidebar: false,
    deleting: false,
    duration: function(min, seg) {
      min = parseFloat(min);
      seg = parseFloat(seg);
      if (isNaN(min)) {
        min = 0;
      }
      if (isNaN(seg)) {
        seg = 0;
      }
      return "" + (((min * 60) + seg).toFixed(0));
    }
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
  r.data.part_num.save();
  return r.set({
    edited: false
  });
});

r.on('del', function(e, ind) {
  e.original.preventDefault();
  r.data.part_num.data.splice(ind, 1);
  console.log('delete', ind);
  return r.fire('backward', e);
});

r.on('addNew', function(e) {
  e.original.preventDefault();
  r.data.part_num.add();
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
  offset = actual === 0 ? r.get('part_num.data.length') - 1 : actual - 1;
  return r.set({
    editing: offset,
    deleting: false
  });
});

r.on('forward', function(e) {
  var actual, offset, _ref;
  if ((e != null ? (_ref = e.original) != null ? _ref.preventDefault : void 0 : void 0) != null) {
    e.original.preventDefault();
  }
  actual = r.get('editing');
  offset = actual === r.get('part_num.data.length') - 1 ? 0 : actual + 1;
  return r.set({
    editing: offset,
    deleting: false
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

r.on('fetchOSFMData', function(e, index) {
  e.original.preventDefault();
  return r.data.part_num.pushToQueue(index);
});

r.observe('part_num.data.*.*', function(nval, oval, keypath) {
  r.set('edited', true);
  r.set('deleting', false);
  if (r.get('edit') && util.numReg.test(keypath)) {
    if (nval.length === 7) {
      return r.data.part_num.pushToQueue(keypath.match(/(\d*)\.num$/)[1]);
    }
  }
});

mapping = [['d', 'description'], ['u', 'part_num'], ['l', 'location'], ['a', 'area'], ['s', 'status'], ['r', 'revision']];

_fn = function(i) {
  return Mousetrap.bind("alt+" + i[0], (function(_this) {
    return function(e) {
      e.preventDefault();
      return document.getElementById("" + i[1]).focus();
    };
  })(this));
};
for (_i = 0, _len = mapping.length; _i < _len; _i++) {
  i = mapping[_i];
  _fn(i);
}

Mousetrap.bind("alt+left", (function(_this) {
  return function(e) {
    e.preventDefault();
    return r.fire('backward', null, e);
  };
})(this));

Mousetrap.bind("alt+right", (function(_this) {
  return function(e) {
    e.preventDefault();
    return r.fire('forward', null, e);
  };
})(this));

window.r = r;
