import { setWidth, setHeight } from '../../core/utils/size';
import $ from '../../core/renderer';
import { isDefined } from '../../core/utils/type';
import { inArray } from '../../core/utils/array';
import { each } from '../../core/utils/iterator';
import { AreaItem } from './ui.pivot_grid.area_item';
var PIVOTGRID_AREA_CLASS = 'dx-pivotgrid-area';
var PIVOTGRID_AREA_COLUMN_CLASS = 'dx-pivotgrid-horizontal-headers';
var PIVOTGRID_AREA_ROW_CLASS = 'dx-pivotgrid-vertical-headers';
var PIVOTGRID_TOTAL_CLASS = 'dx-total';
var PIVOTGRID_GRAND_TOTAL_CLASS = 'dx-grandtotal';
var PIVOTGRID_ROW_TOTAL_CLASS = 'dx-row-total';
var PIVOTGRID_EXPANDED_CLASS = 'dx-pivotgrid-expanded';
var PIVOTGRID_COLLAPSED_CLASS = 'dx-pivotgrid-collapsed';
var PIVOTGRID_LAST_CELL_CLASS = 'dx-last-cell';
var PIVOTGRID_VERTICAL_SCROLL_CLASS = 'dx-vertical-scroll';
var PIVOTGRID_EXPAND_BORDER = 'dx-expand-border';

function getCellPath(tableElement, cell) {
  if (cell) {
    var data = tableElement.data().data;
    var rowIndex = cell.parentNode.rowIndex;
    var cellIndex = cell.cellIndex;
    return data[rowIndex] && data[rowIndex][cellIndex] && data[rowIndex][cellIndex].path;
  }
}

export var HorizontalHeadersArea = AreaItem.inherit({
  ctor: function ctor(component) {
    this.callBase(component);
    this._scrollBarWidth = 0;
  },
  _getAreaName: function _getAreaName() {
    return 'column';
  },
  _getAreaClassName: function _getAreaClassName() {
    return PIVOTGRID_AREA_COLUMN_CLASS;
  },
  _createGroupElement: function _createGroupElement() {
    return $('<div>').addClass(this._getAreaClassName()).addClass(PIVOTGRID_AREA_CLASS);
  },
  _applyCustomStyles: function _applyCustomStyles(options) {
    var cssArray = options.cssArray;
    var cell = options.cell;
    var rowsCount = options.rowsCount;
    var classArray = options.classArray;

    if (options.cellIndex === options.cellsCount - 1) {
      cssArray.push((options.rtlEnabled ? 'border-left:' : 'border-right:') + '0px');
    }

    if (cell.rowspan === rowsCount - options.rowIndex || options.rowIndex + 1 === rowsCount) {
      cssArray.push('border-bottom-width:0px');
    }

    if (cell.type === 'T' || cell.type === 'GT') {
      classArray.push(PIVOTGRID_ROW_TOTAL_CLASS);
    }

    if (options.cell.type === 'T') {
      classArray.push(PIVOTGRID_TOTAL_CLASS);
    }

    if (options.cell.type === 'GT') {
      classArray.push(PIVOTGRID_GRAND_TOTAL_CLASS);
    }

    if (isDefined(cell.expanded)) {
      classArray.push(cell.expanded ? PIVOTGRID_EXPANDED_CLASS : PIVOTGRID_COLLAPSED_CLASS);
    }

    this.callBase(options);
  },
  _getMainElementMarkup: function _getMainElementMarkup() {
    return '<thead class=\'' + this._getAreaClassName() + '\'>';
  },
  _getCloseMainElementMarkup: function _getCloseMainElementMarkup() {
    return '</thead>';
  },
  setVirtualContentParams: function setVirtualContentParams(params) {
    this.callBase(params);

    this._setTableCss({
      left: params.left,
      top: 0
    });

    this._virtualContentWidth = params.width;
  },
  hasScroll: function hasScroll() {
    var tableWidth = this._virtualContent ? this._virtualContentWidth : this._tableWidth;
    var groupWidth = this.getGroupWidth();

    if (groupWidth && tableWidth) {
      return tableWidth - groupWidth >= 1;
    }

    return false;
  },
  renderScrollable: function renderScrollable() {
    this._groupElement.dxScrollable({
      useNative: false,
      useSimulatedScrollbar: false,
      showScrollbar: 'never',
      bounceEnabled: false,
      direction: 'horizontal',
      updateManually: true
    });
  },
  processScrollBarSpacing: function processScrollBarSpacing(scrollBarWidth) {
    var groupAlignment = this.option('rtlEnabled') ? 'right' : 'left';
    var groupWidth = this.getGroupWidth();

    if (groupWidth) {
      this.setGroupWidth(groupWidth - scrollBarWidth);
    }

    if (this._scrollBarWidth) {
      this._groupElement.next().remove();
    }

    this._groupElement.toggleClass(PIVOTGRID_VERTICAL_SCROLL_CLASS, scrollBarWidth > 0);

    setWidth(this._groupElement.css('float', groupAlignment), this.getGroupHeight());
    this._scrollBarWidth = scrollBarWidth;
  },
  getScrollPath: function getScrollPath(offset) {
    var tableElement = this.tableElement();
    var cell;
    offset -= parseInt(tableElement[0].style.left, 10) || 0;
    each(tableElement.find('td'), function (_, td) {
      if (td.colSpan === 1 && td.offsetLeft <= offset && td.offsetWidth + td.offsetLeft > offset) {
        cell = td;
        return false;
      }
    });
    return getCellPath(tableElement, cell);
  },
  _moveFakeTable: function _moveFakeTable(scrollPos) {
    this._moveFakeTableHorizontally(scrollPos);

    this.callBase();
  }
});
export var VerticalHeadersArea = HorizontalHeadersArea.inherit({
  _getAreaClassName: function _getAreaClassName() {
    return PIVOTGRID_AREA_ROW_CLASS;
  },
  _applyCustomStyles: function _applyCustomStyles(options) {
    this.callBase(options);

    if (options.cellIndex === options.cellsCount - 1) {
      options.classArray.push(PIVOTGRID_LAST_CELL_CLASS);
    }

    if (options.rowIndex === options.rowsCount - 1) {
      options.cssArray.push('border-bottom: 0px');
    }

    if (options.cell.isWhiteSpace) {
      options.classArray.push('dx-white-space-column');
    }
  },
  _getAreaName: function _getAreaName() {
    return 'row';
  },
  setVirtualContentParams: function setVirtualContentParams(params) {
    this.callBase(params);

    this._setTableCss({
      top: params.top,
      left: 0
    });

    this._virtualContentHeight = params.height;
  },
  hasScroll: function hasScroll() {
    var tableHeight = this._virtualContent ? this._virtualContentHeight : this._tableHeight;
    var groupHeight = this.getGroupHeight();

    if (groupHeight && tableHeight) {
      return tableHeight - groupHeight >= 1;
    }

    return false;
  },
  renderScrollable: function renderScrollable() {
    this._groupElement.dxScrollable({
      useNative: false,
      useSimulatedScrollbar: false,
      showScrollbar: 'never',
      bounceEnabled: false,
      direction: 'vertical',
      updateManually: true
    });
  },
  processScrollBarSpacing: function processScrollBarSpacing(scrollBarWidth) {
    var groupHeight = this.getGroupHeight();

    if (groupHeight) {
      this.setGroupHeight(groupHeight - scrollBarWidth);
    }

    if (this._scrollBarWidth) {
      this._groupElement.next().remove();
    }

    if (scrollBarWidth) {
      var $div = $('<div>');
      setWidth($div, '100%');
      setHeight($div, scrollBarWidth - 1);

      this._groupElement.after($div);
    }

    this._scrollBarWidth = scrollBarWidth;
  },
  getScrollPath: function getScrollPath(offset) {
    var tableElement = this.tableElement();
    var cell;
    offset -= parseInt(tableElement[0].style.top, 10) || 0;
    each(tableElement.find('tr'), function (_, tr) {
      var td = tr.childNodes[tr.childNodes.length - 1];

      if (td && td.rowSpan === 1 && td.offsetTop <= offset && td.offsetHeight + td.offsetTop > offset) {
        cell = td;
        return false;
      }
    });
    return getCellPath(tableElement, cell);
  },
  _moveFakeTable: function _moveFakeTable(scrollPos) {
    this._moveFakeTableTop(scrollPos);

    this.callBase();
  },
  _getRowClassNames: function _getRowClassNames(rowIndex, cell, rowClassNames) {
    if (rowIndex !== 0 & cell.expanded && inArray(PIVOTGRID_EXPAND_BORDER, rowClassNames) === -1) {
      rowClassNames.push(PIVOTGRID_EXPAND_BORDER);
    }
  },
  _getMainElementMarkup: function _getMainElementMarkup() {
    return '<tbody class=\'' + this._getAreaClassName() + '\'>';
  },
  _getCloseMainElementMarkup: function _getCloseMainElementMarkup() {
    return '</tbody>';
  },
  updateColspans: function updateColspans(columnCount) {
    var rows = this.tableElement()[0].rows;
    var columnOffset = 0;
    var columnOffsetResetIndexes = [];

    if (this.getColumnsCount() - columnCount > 0) {
      return;
    }

    for (var i = 0; i < rows.length; i++) {
      for (var j = 0; j < rows[i].cells.length; j++) {
        var cell = rows[i].cells[j];
        var rowSpan = cell.rowSpan;

        if (columnOffsetResetIndexes[i]) {
          columnOffset -= columnOffsetResetIndexes[i];
          columnOffsetResetIndexes[i] = 0;
        }

        var diff = columnCount - (columnOffset + cell.colSpan);

        if (j === rows[i].cells.length - 1 && diff > 0) {
          cell.colSpan = cell.colSpan + diff;
        }

        columnOffsetResetIndexes[i + rowSpan] = (columnOffsetResetIndexes[i + rowSpan] || 0) + cell.colSpan;
        columnOffset += cell.colSpan;
      }
    }
  }
});