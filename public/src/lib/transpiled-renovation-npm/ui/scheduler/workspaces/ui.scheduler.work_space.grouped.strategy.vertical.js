"use strict";

exports.default = void 0;

var _position = require("../../../core/utils/position");

var _cache = require("./cache");

var _classes = require("../classes");

var _base = require("../../../renovation/ui/scheduler/view_model/to_test/views/utils/base");

var DATE_HEADER_OFFSET = 10;
var WORK_SPACE_BORDER = 1;

var VerticalGroupedStrategy = /*#__PURE__*/function () {
  function VerticalGroupedStrategy(workSpace) {
    this._workSpace = workSpace;
    this.cache = new _cache.Cache();
  }

  var _proto = VerticalGroupedStrategy.prototype;

  _proto.prepareCellIndexes = function prepareCellIndexes(cellCoordinates, groupIndex, inAllDayRow) {
    var rowIndex = cellCoordinates.rowIndex + groupIndex * this._workSpace._getRowCount();

    if (this._workSpace.supportAllDayRow() && this._workSpace.option('showAllDayPanel')) {
      rowIndex += groupIndex;

      if (!inAllDayRow) {
        rowIndex += 1;
      }
    }

    return {
      rowIndex: rowIndex,
      columnIndex: cellCoordinates.columnIndex
    };
  };

  _proto.getGroupIndex = function getGroupIndex(rowIndex) {
    return Math.floor(rowIndex / this._workSpace._getRowCount());
  };

  _proto.calculateHeaderCellRepeatCount = function calculateHeaderCellRepeatCount() {
    return 1;
  };

  _proto.insertAllDayRowsIntoDateTable = function insertAllDayRowsIntoDateTable() {
    return this._workSpace.option('showAllDayPanel');
  };

  _proto.getTotalCellCount = function getTotalCellCount() {
    return this._workSpace._getCellCount();
  };

  _proto.getTotalRowCount = function getTotalRowCount() {
    return this._workSpace._getRowCount() * this._workSpace._getGroupCount();
  };

  _proto.calculateTimeCellRepeatCount = function calculateTimeCellRepeatCount() {
    return this._workSpace._getGroupCount() || 1;
  };

  _proto.getWorkSpaceMinWidth = function getWorkSpaceMinWidth() {
    var minWidth = this._workSpace._getWorkSpaceWidth();

    var workspaceContainerWidth = (0, _position.getBoundingRect)(this._workSpace.$element().get(0)).width - this._workSpace.getTimePanelWidth() - this._workSpace.getGroupTableWidth() - 2 * WORK_SPACE_BORDER;

    if (minWidth < workspaceContainerWidth) {
      minWidth = workspaceContainerWidth;
    }

    return minWidth;
  };

  _proto.getAllDayOffset = function getAllDayOffset() {
    return 0;
  };

  _proto.getGroupCountClass = function getGroupCountClass(groups) {
    return (0, _base.getVerticalGroupCountClass)(groups);
  };

  _proto.getLeftOffset = function getLeftOffset() {
    return this._workSpace.getTimePanelWidth() + this._workSpace.getGroupTableWidth();
  };

  _proto.getGroupBoundsOffset = function getGroupBoundsOffset(cellCount, $cells, cellWidth, coordinates) {
    var _this = this;

    var groupIndex = coordinates.groupIndex;
    return this.cache.get("groupBoundsOffset".concat(groupIndex), function () {
      var startOffset = $cells.eq(0).offset().left;
      var endOffset = $cells.eq(cellCount - 1).offset().left + cellWidth;

      var startDayHour = _this._workSpace.option('startDayHour');

      var endDayHour = _this._workSpace.option('endDayHour');

      var hoursInterval = _this._workSpace.option('hoursInterval');

      var dayHeight = (0, _base.calculateDayDuration)(startDayHour, endDayHour) / hoursInterval * _this._workSpace.getCellHeight();

      var scrollTop = _this.getScrollableScrollTop();

      var topOffset = groupIndex * dayHeight + (0, _position.getBoundingRect)(_this._workSpace._$thead.get(0)).height + _this._workSpace.option('getHeaderHeight')() + DATE_HEADER_OFFSET - scrollTop;

      if (_this._workSpace.option('showAllDayPanel') && _this._workSpace.supportAllDayRow()) {
        topOffset += _this._workSpace.getCellHeight() * (groupIndex + 1);
      }

      var bottomOffset = topOffset + dayHeight;
      return _this._groupBoundsOffset = {
        left: startOffset,
        right: endOffset,
        top: topOffset,
        bottom: bottomOffset
      };
    });
  };

  _proto.shiftIndicator = function shiftIndicator($indicator, height, rtlOffset, i) {
    var offset = this._workSpace.getIndicatorOffset(0);

    var tableOffset = this._workSpace.option('crossScrollingEnabled') ? 0 : this._workSpace.getGroupTableWidth();
    var horizontalOffset = rtlOffset ? rtlOffset - offset : offset;
    var verticalOffset = this._workSpace._getRowCount() * this._workSpace.getCellHeight() * i;

    if (this._workSpace.supportAllDayRow() && this._workSpace.option('showAllDayPanel')) {
      verticalOffset += this._workSpace.getAllDayHeight() * (i + 1);
    }

    $indicator.css('left', horizontalOffset + tableOffset);
    $indicator.css('top', height + verticalOffset);
  };

  _proto.getShaderOffset = function getShaderOffset(i, width) {
    var offset = this._workSpace.option('crossScrollingEnabled') ? 0 : this._workSpace.getGroupTableWidth();
    return this._workSpace.option('rtlEnabled') ? (0, _position.getBoundingRect)(this._$container.get(0)).width - offset - this._workSpace.getWorkSpaceLeftOffset() - width : offset;
  };

  _proto.getShaderTopOffset = function getShaderTopOffset(i) {
    return 0;
  };

  _proto.getShaderHeight = function getShaderHeight() {
    var height = this._workSpace.getIndicationHeight();

    if (this._workSpace.supportAllDayRow() && this._workSpace.option('showAllDayPanel')) {
      height += this._workSpace.getCellHeight();
    }

    return height;
  };

  _proto.getShaderMaxHeight = function getShaderMaxHeight() {
    var height = this._workSpace._getRowCount() * this._workSpace.getCellHeight();

    if (this._workSpace.supportAllDayRow() && this._workSpace.option('showAllDayPanel')) {
      height += this._workSpace.getCellHeight();
    }

    return height;
  };

  _proto.getShaderWidth = function getShaderWidth() {
    return this._workSpace.getIndicationWidth(0);
  };

  _proto.getScrollableScrollTop = function getScrollableScrollTop() {
    return this._workSpace.getScrollable().scrollTop();
  } // ------------
  // We do not need these methods in renovation
  // ------------
  ;

  _proto.addAdditionalGroupCellClasses = function addAdditionalGroupCellClasses(cellClass, index, i, j) {
    cellClass = this._addLastGroupCellClass(cellClass, i + 1);
    return this._addFirstGroupCellClass(cellClass, i + 1);
  };

  _proto._addLastGroupCellClass = function _addLastGroupCellClass(cellClass, index) {
    if (index % this._workSpace._getRowCount() === 0) {
      return "".concat(cellClass, " ").concat(_classes.LAST_GROUP_CELL_CLASS);
    }

    return cellClass;
  };

  _proto._addFirstGroupCellClass = function _addFirstGroupCellClass(cellClass, index) {
    if ((index - 1) % this._workSpace._getRowCount() === 0) {
      return "".concat(cellClass, " ").concat(_classes.FIRST_GROUP_CELL_CLASS);
    }

    return cellClass;
  };

  return VerticalGroupedStrategy;
}();

var _default = VerticalGroupedStrategy;
exports.default = _default;
module.exports = exports.default;
module.exports.default = exports.default;