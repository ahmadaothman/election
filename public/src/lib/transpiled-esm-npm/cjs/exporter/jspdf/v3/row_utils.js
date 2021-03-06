"use strict";

exports.applyBordersConfig = applyBordersConfig;
exports.applyColSpans = applyColSpans;
exports.applyRowSpans = applyRowSpans;
exports.calculateCoordinates = calculateCoordinates;
exports.calculateHeights = calculateHeights;
exports.calculateTableSize = calculateTableSize;
exports.initializeCellsWidth = initializeCellsWidth;
exports.resizeFirstColumnByIndentLevel = resizeFirstColumnByIndentLevel;

var _type = require("../../../core/utils/type");

var _pdf_utils_v = require("./pdf_utils_v3");

var _normalizeOptions = require("./normalizeOptions");

function calculateColumnsWidths(doc, dataProvider, topLeft, margin) {
  var _topLeft$x;

  var columnsWidths = dataProvider.getColumnsWidths();

  if (!columnsWidths.length) {
    return [];
  }

  var summaryGridWidth = columnsWidths.reduce(function (accumulator, width) {
    return accumulator + width;
  });
  var normalizedMargin = (0, _normalizeOptions.normalizeBoundaryValue)(margin); // TODO: check measure units there

  var availablePageWidth = doc.internal.pageSize.getWidth() - ((_topLeft$x = topLeft === null || topLeft === void 0 ? void 0 : topLeft.x) !== null && _topLeft$x !== void 0 ? _topLeft$x : 0) - normalizedMargin.left - normalizedMargin.right;
  var ratio = availablePageWidth >= summaryGridWidth ? 1 : availablePageWidth / summaryGridWidth;
  return columnsWidths.map(function (width) {
    return width * ratio;
  });
}

function initializeCellsWidth(doc, dataProvider, rows, options) {
  var _options$columnWidths;

  var columnWidths = (_options$columnWidths = options === null || options === void 0 ? void 0 : options.columnWidths) !== null && _options$columnWidths !== void 0 ? _options$columnWidths : calculateColumnsWidths(doc, dataProvider, options === null || options === void 0 ? void 0 : options.topLeft, options === null || options === void 0 ? void 0 : options.margin);
  rows.forEach(function (row) {
    row.cells.forEach(function (_ref, index) {
      var gridCell = _ref.gridCell,
          pdfCell = _ref.pdfCell;
      pdfCell._rect.w = columnWidths[index];
    });
  });
}

function calculateHeights(doc, rows, options) {
  rows.forEach(function (row) {
    var pdfCells = row.cells.map(function (c) {
      return c.pdfCell;
    });
    var customerHeight;

    if (options.onRowExporting) {
      var args = {
        rowCells: pdfCells
      };
      options.onRowExporting(args);

      if ((0, _type.isDefined)(args.rowHeight)) {
        customerHeight = args.rowHeight;
      }
    }

    row.height = (0, _type.isDefined)(customerHeight) ? customerHeight : (0, _pdf_utils_v.calculateRowHeight)(doc, row.cells, pdfCells.map(function (c) {
      return c._rect.w;
    }));
    pdfCells.forEach(function (cell) {
      cell._rect.h = row.height;
    });
  });
}

function applyColSpans(rows) {
  for (var rowIndex = 0; rowIndex < rows.length; rowIndex++) {
    var row = rows[rowIndex];

    for (var cellIndex = 0; cellIndex < row.cells.length; cellIndex++) {
      var cell = row.cells[cellIndex];

      if ((0, _type.isDefined)(cell.colSpan) && !(0, _type.isDefined)(cell.pdfCell.isMerged)) {
        for (var spanIndex = 1; spanIndex <= cell.colSpan; spanIndex++) {
          var mergedCell = rows[rowIndex].cells[cellIndex + spanIndex];
          cell.pdfCell._rect.w += mergedCell.pdfCell._rect.w;
          mergedCell.pdfCell._rect.w = 0;
          mergedCell.pdfCell.isMerged = true;
        }
      }
    }
  }
}

function applyRowSpans(rows) {
  for (var rowIndex = 0; rowIndex < rows.length; rowIndex++) {
    var row = rows[rowIndex];

    for (var cellIndex = 0; cellIndex < row.cells.length; cellIndex++) {
      var cell = row.cells[cellIndex];

      if ((0, _type.isDefined)(cell.rowSpan) && !(0, _type.isDefined)(cell.pdfCell.isMerged)) {
        for (var spanIndex = 1; spanIndex <= cell.rowSpan; spanIndex++) {
          var mergedCell = rows[rowIndex + spanIndex].cells[cellIndex];
          cell.pdfCell._rect.h += mergedCell.pdfCell._rect.h;
          mergedCell.pdfCell._rect.h = 0;
          mergedCell.pdfCell.isMerged = true;
        }
      }
    }
  }
}

function resizeFirstColumnByIndentLevel(rows, options) {
  rows.forEach(function (row) {
    row.cells[0].pdfCell._rect.w -= row.indentLevel * options.indent;
  });
}

function applyBordersConfig(rows) {
  for (var rowIndex = 0; rowIndex < rows.length; rowIndex++) {
    var cells = rows[rowIndex].cells;

    for (var columnIndex = 0; columnIndex < cells.length; columnIndex++) {
      var pdfCell = cells[columnIndex].pdfCell;
      var leftPdfCell = columnIndex >= 1 ? cells[columnIndex - 1].pdfCell : null;
      var topPdfCell = rowIndex >= 1 ? rows[rowIndex - 1].cells[columnIndex].pdfCell : null;

      if (pdfCell.drawLeftBorder === false && !(0, _type.isDefined)(cells[columnIndex].colSpan)) {
        // TODO: Check this logic after implementing splitting to pages
        if ((0, _type.isDefined)(leftPdfCell)) {
          leftPdfCell.drawRightBorder = false;
        }
      } else if (!(0, _type.isDefined)(pdfCell.drawLeftBorder)) {
        if ((0, _type.isDefined)(leftPdfCell) && leftPdfCell.drawRightBorder === false) {
          pdfCell.drawLeftBorder = false;
        }
      }

      if (pdfCell.drawTopBorder === false) {
        if ((0, _type.isDefined)(topPdfCell)) {
          topPdfCell.drawBottomBorder = false;
        }
      } else if (!(0, _type.isDefined)(pdfCell.drawTopBorder)) {
        if ((0, _type.isDefined)(topPdfCell) && topPdfCell.drawBottomBorder === false) {
          pdfCell.drawTopBorder = false;
        }
      }
    }
  }
}

function calculateCoordinates(doc, rows, options) {
  var _topLeft$y;

  var topLeft = options === null || options === void 0 ? void 0 : options.topLeft;
  var margin = (0, _normalizeOptions.normalizeBoundaryValue)(options === null || options === void 0 ? void 0 : options.margin);
  var y = ((_topLeft$y = topLeft === null || topLeft === void 0 ? void 0 : topLeft.y) !== null && _topLeft$y !== void 0 ? _topLeft$y : 0) + margin.top;
  rows.forEach(function (row) {
    var _topLeft$x2;

    var x = ((_topLeft$x2 = topLeft === null || topLeft === void 0 ? void 0 : topLeft.x) !== null && _topLeft$x2 !== void 0 ? _topLeft$x2 : 0) + margin.left;
    var intend = row.indentLevel * options.indent;
    row.cells.forEach(function (cell) {
      cell.pdfCell._rect.x = x + intend;
      cell.pdfCell._rect.y = y;
      x += cell.pdfCell._rect.w;
    });
    y += row.height;
  });
}

function calculateTableSize(doc, rows, options) {
  var _topLeft$x3, _topLeft$y2, _columnWidths$reduce, _rowHeights$reduce;

  var topLeft = options === null || options === void 0 ? void 0 : options.topLeft;
  var columnWidths = options === null || options === void 0 ? void 0 : options.columnWidths;
  var rowHeights = rows.map(function (row) {
    return row.height;
  });
  return {
    x: (_topLeft$x3 = topLeft === null || topLeft === void 0 ? void 0 : topLeft.x) !== null && _topLeft$x3 !== void 0 ? _topLeft$x3 : 0,
    y: (_topLeft$y2 = topLeft === null || topLeft === void 0 ? void 0 : topLeft.y) !== null && _topLeft$y2 !== void 0 ? _topLeft$y2 : 0,
    w: (_columnWidths$reduce = columnWidths === null || columnWidths === void 0 ? void 0 : columnWidths.reduce(function (a, b) {
      return a + b;
    }, 0)) !== null && _columnWidths$reduce !== void 0 ? _columnWidths$reduce : 0,
    h: (_rowHeights$reduce = rowHeights === null || rowHeights === void 0 ? void 0 : rowHeights.reduce(function (a, b) {
      return a + b;
    }, 0)) !== null && _rowHeights$reduce !== void 0 ? _rowHeights$reduce : 0
  };
}