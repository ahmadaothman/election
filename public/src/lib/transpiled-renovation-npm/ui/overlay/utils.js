"use strict";

exports.getElementMaxHeightByWindow = void 0;

var _size = require("../../core/utils/size");

var _renderer = _interopRequireDefault(require("../../core/renderer"));

var _window = require("../../core/utils/window");

var _type = require("../../core/utils/type");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var WINDOW_HEIGHT_PERCENT = 0.9;

var getElementMaxHeightByWindow = function getElementMaxHeightByWindow($element, startLocation) {
  var $window = (0, _renderer.default)((0, _window.getWindow)());

  var _$element$offset = $element.offset(),
      elementOffset = _$element$offset.top;

  var actualOffset;

  if ((0, _type.isNumeric)(startLocation)) {
    if (startLocation < elementOffset) {
      return elementOffset - startLocation;
    } else {
      actualOffset = (0, _size.getInnerHeight)($window) - startLocation + $window.scrollTop();
    }
  } else {
    var offsetTop = elementOffset - $window.scrollTop();
    var offsetBottom = (0, _size.getInnerHeight)($window) - offsetTop - (0, _size.getOuterHeight)($element);
    actualOffset = Math.max(offsetTop, offsetBottom);
  }

  return actualOffset * WINDOW_HEIGHT_PERCENT;
};

exports.getElementMaxHeightByWindow = getElementMaxHeightByWindow;