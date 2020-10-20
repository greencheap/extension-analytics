"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;
var _default = {
  computed: {
    getTagFor: function getTagFor() {
      var expName = this.widget.explorer.explorer;
      var data = [];

      _.find(this.explorer, function (exp) {
        if (exp.name === expName) {
          data = exp.tagsFor;
        }
      });

      return data;
    },
    getNames: function getNames() {
      var _this = this;

      var expName = this.widget.explorer.explorer;
      var data = [];

      _.find(this.explorer, function (exp) {
        if (exp.name === expName) {
          data = exp.tag[_this.widget.explorer.tag];
        }
      });

      return data;
    },
    getHeaderTitle: function getHeaderTitle() {
      var _this2 = this;

      var expName = this.widget.explorer.explorer;
      var title = expName;

      _.find(this.explorer, function (exp) {
        if (exp.name === expName) {
          var tags = exp.tag[_this2.widget.explorer.tag];

          _.find(tags, function (tag) {
            if (tag.name == _this2.widget.explorer.name) {
              title = tag.alias;
            }
          });
        }
      });

      return title;
    }
  }
};
exports["default"] = _default;