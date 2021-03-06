"use strict";

exports.GET_LABEL_WIDTH_BY_TEXT_CLASS = exports.FIELD_ITEM_REQUIRED_MARK_CLASS = exports.FIELD_ITEM_OPTIONAL_MARK_CLASS = exports.FIELD_ITEM_LABEL_TEXT_CLASS = exports.FIELD_ITEM_LABEL_LOCATION_CLASS = void 0;
exports.renderLabel = renderLabel;
exports.setLabelWidthByMaxLabelWidth = setLabelWidthByMaxLabelWidth;

var _renderer = _interopRequireDefault(require("../../../core/renderer"));

var _type = require("../../../core/utils/type");

var _string = require("../../../core/utils/string");

var _uiFormLayout_manager = require("../ui.form.layout_manager.utils");

var _constants = require("../constants");

var _excluded = ["$FIELD_ITEM_LABEL_CONTENT_CLASS"];

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _objectWithoutProperties(source, excluded) { if (source == null) return {}; var target = _objectWithoutPropertiesLoose(source, excluded); var key, i; if (Object.getOwnPropertySymbols) { var sourceSymbolKeys = Object.getOwnPropertySymbols(source); for (i = 0; i < sourceSymbolKeys.length; i++) { key = sourceSymbolKeys[i]; if (excluded.indexOf(key) >= 0) continue; if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue; target[key] = source[key]; } } return target; }

function _objectWithoutPropertiesLoose(source, excluded) { if (source == null) return {}; var target = {}; var sourceKeys = Object.keys(source); var key, i; for (i = 0; i < sourceKeys.length; i++) { key = sourceKeys[i]; if (excluded.indexOf(key) >= 0) continue; target[key] = source[key]; } return target; }

// TODO: exported for tests only
var GET_LABEL_WIDTH_BY_TEXT_CLASS = 'dx-layout-manager-hidden-label';
exports.GET_LABEL_WIDTH_BY_TEXT_CLASS = GET_LABEL_WIDTH_BY_TEXT_CLASS;
var FIELD_ITEM_REQUIRED_MARK_CLASS = 'dx-field-item-required-mark';
exports.FIELD_ITEM_REQUIRED_MARK_CLASS = FIELD_ITEM_REQUIRED_MARK_CLASS;
var FIELD_ITEM_LABEL_LOCATION_CLASS = 'dx-field-item-label-location-';
exports.FIELD_ITEM_LABEL_LOCATION_CLASS = FIELD_ITEM_LABEL_LOCATION_CLASS;
var FIELD_ITEM_OPTIONAL_MARK_CLASS = 'dx-field-item-optional-mark';
exports.FIELD_ITEM_OPTIONAL_MARK_CLASS = FIELD_ITEM_OPTIONAL_MARK_CLASS;
var FIELD_ITEM_LABEL_TEXT_CLASS = 'dx-field-item-label-text';
exports.FIELD_ITEM_LABEL_TEXT_CLASS = FIELD_ITEM_LABEL_TEXT_CLASS;

function renderLabel(_ref) {
  var text = _ref.text,
      id = _ref.id,
      location = _ref.location,
      alignment = _ref.alignment,
      _ref$labelID = _ref.labelID,
      labelID = _ref$labelID === void 0 ? null : _ref$labelID,
      _ref$markOptions = _ref.markOptions,
      markOptions = _ref$markOptions === void 0 ? {} : _ref$markOptions;

  if (!(0, _type.isDefined)(text) || text.length <= 0) {
    return null;
  }

  return (0, _renderer.default)('<label>').addClass(_constants.FIELD_ITEM_LABEL_CLASS + ' ' + FIELD_ITEM_LABEL_LOCATION_CLASS + location).attr('for', id).attr('id', labelID).css('textAlign', alignment).append((0, _renderer.default)('<span>').addClass(_constants.FIELD_ITEM_LABEL_CONTENT_CLASS).append((0, _renderer.default)('<span>').addClass(FIELD_ITEM_LABEL_TEXT_CLASS).text(text), _renderLabelMark(markOptions)));
}

function _renderLabelMark(markOptions) {
  var markText = (0, _uiFormLayout_manager.getLabelMarkText)(markOptions);

  if (markText === '') {
    return null;
  }

  return (0, _renderer.default)('<span>').addClass(markOptions.showRequiredMark ? FIELD_ITEM_REQUIRED_MARK_CLASS : FIELD_ITEM_OPTIONAL_MARK_CLASS).text(markText);
}

function setLabelWidthByMaxLabelWidth($targetContainer, labelsSelector, labelMarkOptions) {
  var FIELD_ITEM_LABEL_CONTENT_CLASS_Selector = "".concat(labelsSelector, " > .").concat(_constants.FIELD_ITEM_LABEL_CLASS, ":not(.").concat(FIELD_ITEM_LABEL_LOCATION_CLASS, "top) > .").concat(_constants.FIELD_ITEM_LABEL_CONTENT_CLASS);
  var $FIELD_ITEM_LABEL_CONTENT_CLASS_Items = $targetContainer.find(FIELD_ITEM_LABEL_CONTENT_CLASS_Selector);
  var FIELD_ITEM_LABEL_CONTENT_CLASS_Length = $FIELD_ITEM_LABEL_CONTENT_CLASS_Items.length;
  var labelWidth;
  var i;
  var maxWidth = 0;

  for (i = 0; i < FIELD_ITEM_LABEL_CONTENT_CLASS_Length; i++) {
    labelWidth = getLabelWidthByInnerHTML({
      // _hiddenLabelText was introduced in https://hg/mobile/rev/27b4f57f10bb , "dxForm: add alignItemLabelsInAllGroups and fix type script"
      // It's not clear why $labelTexts.offsetWidth doesn't meet the needs
      $FIELD_ITEM_LABEL_CONTENT_CLASS: $FIELD_ITEM_LABEL_CONTENT_CLASS_Items[i],
      location: 'left',
      markOptions: labelMarkOptions
    });

    if (labelWidth > maxWidth) {
      maxWidth = labelWidth;
    }
  }

  for (i = 0; i < FIELD_ITEM_LABEL_CONTENT_CLASS_Length; i++) {
    $FIELD_ITEM_LABEL_CONTENT_CLASS_Items[i].style.width = maxWidth + 'px';
  }
}

function getLabelWidthByInnerHTML(options) {
  var $FIELD_ITEM_LABEL_CONTENT_CLASS = options.$FIELD_ITEM_LABEL_CONTENT_CLASS,
      renderLabelOptions = _objectWithoutProperties(options, _excluded);

  var $hiddenContainer = (0, _renderer.default)('<div>').addClass(_constants.WIDGET_CLASS).addClass(GET_LABEL_WIDTH_BY_TEXT_CLASS).appendTo('body');
  renderLabelOptions.text = ' '; // space was in initial PR https://hg/mobile/rev/27b4f57f10bb

  var $label = renderLabel(renderLabelOptions).appendTo($hiddenContainer);
  var labelTextElement = $label.find('.' + FIELD_ITEM_LABEL_TEXT_CLASS)[0]; // this code has slow performance
  // innerHTML was added in https://hg/mobile/rev/3ed89cf230a4 for T350537
  // innerHTML is read from a DOMElement.innerHTML

  labelTextElement.innerHTML = getLabelInnerHTML($FIELD_ITEM_LABEL_CONTENT_CLASS);
  var result = labelTextElement.offsetWidth;
  $hiddenContainer.remove();
  return result;
}

function getLabelInnerHTML($FIELD_ITEM_LABEL_CONTENT_CLASS) {
  var length = $FIELD_ITEM_LABEL_CONTENT_CLASS.children.length;
  var child;
  var result = '';
  var i;

  for (i = 0; i < length; i++) {
    child = $FIELD_ITEM_LABEL_CONTENT_CLASS.children[i]; // Was introduced in https://hg/mobile/rev/1f81a5afaab3 , "dxForm: fix test cafe tests":
    // It's not clear why "$labelTexts[i].children[0].innerHTML" doesn't meet the needs.

    result = result + (!(0, _string.isEmpty)(child.innerText) ? child.innerText : child.innerHTML);
  }

  return result;
}