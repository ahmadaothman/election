import $ from '../../core/renderer';
import { getWindow } from '../../core/utils/window';
import eventsEngine from '../../events/core/events_engine';
import browser from '../../core/utils/browser';
import positionUtils from '../../animation/position';
import { each } from '../../core/utils/iterator';
import Class from '../../core/class';
import { Deferred } from '../../core/utils/deferred';
import Callbacks from '../../core/utils/callbacks';
import { VirtualDataLoader } from './ui.grid.core.virtual_data_loader';
import { isDefined } from '../../core/utils/type';
import gridCoreUtils from './ui.grid_core.utils';
var SCROLLING_MODE_INFINITE = 'infinite';
var SCROLLING_MODE_VIRTUAL = 'virtual';
var LEGACY_SCROLLING_MODE = 'scrolling.legacyMode';

var _isVirtualMode = that => that.option('scrolling.mode') === SCROLLING_MODE_VIRTUAL || that._isVirtual;

var _isAppendMode = that => that.option('scrolling.mode') === SCROLLING_MODE_INFINITE && !that._isVirtual;

export function subscribeToExternalScrollers($element, scrollChangedHandler, $targetElement) {
  var $scrollElement;
  var scrollableArray = [];
  var scrollToArray = [];
  var disposeArray = [];
  $targetElement = $targetElement || $element;

  function getElementOffset(scrollable) {
    var $scrollableElement = scrollable.element ? scrollable.$element() : scrollable;
    var scrollableOffset = positionUtils.offset($scrollableElement);

    if (!scrollableOffset) {
      return $element.offset().top;
    }

    return scrollable.scrollTop() - (scrollableOffset.top - $element.offset().top);
  }

  function createWindowScrollHandler(scrollable) {
    return function () {
      var scrollTop = scrollable.scrollTop() - getElementOffset(scrollable);
      scrollTop = scrollTop > 0 ? scrollTop : 0;
      scrollChangedHandler(scrollTop);
    };
  }

  var widgetScrollStrategy = {
    on: function on(scrollable, eventName, handler) {
      scrollable.on('scroll', handler);
    },
    off: function off(scrollable, eventName, handler) {
      scrollable.off('scroll', handler);
    }
  };

  function subscribeToScrollEvents($scrollElement) {
    var isDocument = $scrollElement.get(0).nodeName === '#document';
    var scrollable = $scrollElement.data('dxScrollable');
    var eventsStrategy = widgetScrollStrategy;

    if (!scrollable) {
      scrollable = isDocument && $(getWindow()) || $scrollElement.css('overflowY') === 'auto' && $scrollElement;
      eventsStrategy = eventsEngine;
      if (!scrollable) return;
    }

    var handler = createWindowScrollHandler(scrollable);
    eventsStrategy.on(scrollable, 'scroll', handler);
    scrollToArray.push(function (pos) {
      var topOffset = getElementOffset(scrollable);
      var scrollMethod = scrollable.scrollTo ? 'scrollTo' : 'scrollTop';

      if (pos - topOffset >= 0) {
        scrollable[scrollMethod](pos + topOffset);
      }
    });
    scrollableArray.push(scrollable);
    disposeArray.push(function () {
      eventsStrategy.off(scrollable, 'scroll', handler);
    });
  }

  for ($scrollElement = $targetElement.parent(); $scrollElement.length; $scrollElement = $scrollElement.parent()) {
    subscribeToScrollEvents($scrollElement);
  }

  return {
    scrollTo: function scrollTo(pos) {
      each(scrollToArray, function (_, scrollTo) {
        scrollTo(pos);
      });
    },
    dispose: function dispose() {
      each(disposeArray, function (_, dispose) {
        dispose();
      });
    }
  };
}
export var VirtualScrollController = Class.inherit(function () {
  var members = {
    ctor: function ctor(component, dataOptions, isVirtual) {
      this._dataOptions = dataOptions;
      this.component = component;
      this._viewportSize = component.option(LEGACY_SCROLLING_MODE) === false ? 15 : 0;
      this._viewportItemSize = 20;
      this._viewportItemIndex = 0;
      this._position = 0;
      this._isScrollingBack = false;
      this._contentSize = 0;
      this._itemSizes = {};
      this._sizeRatio = 1;
      this._isVirtual = isVirtual;
      this.positionChanged = Callbacks();
      this._dataLoader = new VirtualDataLoader(this, this._dataOptions);
    },
    getItemSizes: function getItemSizes() {
      return this._itemSizes;
    },
    option: function option() {
      return this.component.option.apply(this.component, arguments);
    },
    isVirtual: function isVirtual() {
      return this._isVirtual;
    },
    virtualItemsCount: function virtualItemsCount() {
      if (_isVirtualMode(this)) {
        var dataOptions = this._dataOptions;
        var totalItemsCount = dataOptions.totalItemsCount();

        if (this.option(LEGACY_SCROLLING_MODE) === false && totalItemsCount !== -1) {
          var viewportParams = this.getViewportParams();
          var loadedOffset = dataOptions.loadedOffset();
          var loadedItemCount = dataOptions.loadedItemCount();
          var skip = Math.max(viewportParams.skip, loadedOffset);
          var take = Math.min(viewportParams.take, loadedItemCount);
          var endItemsCount = Math.max(totalItemsCount - (skip + take), 0);
          return {
            begin: skip,
            end: endItemsCount
          };
        }

        return this._dataLoader.virtualItemsCount.apply(this._dataLoader, arguments);
      }
    },
    getScrollingTimeout: function getScrollingTimeout() {
      var renderAsync = this.option('scrolling.renderAsync');
      var scrollingTimeout = 0;

      if (!isDefined(renderAsync)) {
        scrollingTimeout = Math.min(this.option('scrolling.timeout') || 0, this._dataOptions.changingDuration());

        if (scrollingTimeout < this.option('scrolling.renderingThreshold')) {
          scrollingTimeout = this.option('scrolling.minTimeout') || 0;
        }
      } else if (renderAsync) {
        var _this$option;

        scrollingTimeout = (_this$option = this.option('scrolling.timeout')) !== null && _this$option !== void 0 ? _this$option : 0;
      }

      return scrollingTimeout;
    },
    setViewportPosition: function setViewportPosition(position) {
      var result = new Deferred();
      var scrollingTimeout = this.getScrollingTimeout();
      clearTimeout(this._scrollTimeoutID);

      if (scrollingTimeout > 0) {
        this._scrollTimeoutID = setTimeout(() => {
          this._setViewportPositionCore(position);

          result.resolve();
        }, scrollingTimeout);
      } else {
        this._setViewportPositionCore(position);

        result.resolve();
      }

      return result.promise();
    },
    getViewportPosition: function getViewportPosition() {
      return this._position;
    },
    getItemIndexByPosition: function getItemIndexByPosition(position) {
      var _position;

      position = (_position = position) !== null && _position !== void 0 ? _position : this._position;
      var defaultItemSize = this.getItemSize();
      var offset = 0;
      var itemOffset = 0;
      var itemOffsetsWithSize = Object.keys(this._itemSizes).concat(-1);

      for (var i = 0; i < itemOffsetsWithSize.length && offset < position; i++) {
        var itemOffsetWithSize = parseInt(itemOffsetsWithSize[i]);
        var itemOffsetDiff = (position - offset) / defaultItemSize;

        if (itemOffsetWithSize < 0 || itemOffset + itemOffsetDiff < itemOffsetWithSize) {
          itemOffset += itemOffsetDiff;
          break;
        } else {
          itemOffsetDiff = itemOffsetWithSize - itemOffset;
          offset += itemOffsetDiff * defaultItemSize;
          itemOffset += itemOffsetDiff;
        }

        var itemSize = this._itemSizes[itemOffsetWithSize];
        offset += itemSize;
        itemOffset += offset < position ? 1 : (position - offset + itemSize) / itemSize;
      }

      return Math.round(itemOffset * 50) / 50;
    },
    isScrollingBack: function isScrollingBack() {
      return this._isScrollingBack;
    },
    _setViewportPositionCore: function _setViewportPositionCore(position) {
      var prevPosition = this._position || 0;
      this._position = position;

      if (prevPosition !== this._position) {
        this._isScrollingBack = this._position < prevPosition;
      }

      var itemIndex = this.getItemIndexByPosition();
      var result = this.setViewportItemIndex(itemIndex);
      this.positionChanged.fire();
      return result;
    },
    setContentItemSizes: function setContentItemSizes(sizes) {
      var virtualItemsCount = this.virtualItemsCount();
      this._contentSize = sizes.reduce((a, b) => a + b, 0);

      if (virtualItemsCount) {
        sizes.forEach((size, index) => {
          this._itemSizes[virtualItemsCount.begin + index] = size;
        });

        var virtualContentSize = (virtualItemsCount.begin + virtualItemsCount.end + this.itemsCount()) * this._viewportItemSize;

        var contentHeightLimit = gridCoreUtils.getContentHeightLimit(browser);

        if (virtualContentSize > contentHeightLimit) {
          this._sizeRatio = contentHeightLimit / virtualContentSize;
        } else {
          this._sizeRatio = 1;
        }
      }
    },
    getItemSize: function getItemSize() {
      return this._viewportItemSize * this._sizeRatio;
    },
    getItemOffset: function getItemOffset(itemIndex, isEnd) {
      var virtualItemsCount = this.virtualItemsCount();
      var itemCount = itemIndex;
      if (!virtualItemsCount) return 0;
      var offset = 0;

      var totalItemsCount = this._dataOptions.totalItemsCount();

      Object.keys(this._itemSizes).forEach(currentItemIndex => {
        if (!itemCount) return;

        if (isEnd ? currentItemIndex >= totalItemsCount - itemIndex : currentItemIndex < itemIndex) {
          offset += this._itemSizes[currentItemIndex];
          itemCount--;
        }
      });
      return Math.floor(offset + itemCount * this._viewportItemSize * this._sizeRatio);
    },
    getContentOffset: function getContentOffset(type) {
      var isEnd = type === 'end';
      var virtualItemsCount = this.virtualItemsCount();
      if (!virtualItemsCount) return 0;
      return this.getItemOffset(isEnd ? virtualItemsCount.end : virtualItemsCount.begin, isEnd);
    },
    getVirtualContentSize: function getVirtualContentSize() {
      var virtualItemsCount = this.virtualItemsCount();
      return virtualItemsCount ? this.getContentOffset('begin') + this.getContentOffset('end') + this._contentSize : 0;
    },
    getViewportItemIndex: function getViewportItemIndex() {
      return this._viewportItemIndex;
    },
    setViewportItemIndex: function setViewportItemIndex(itemIndex) {
      this._viewportItemIndex = itemIndex;

      if (this.option(LEGACY_SCROLLING_MODE) === false) {
        return;
      }

      return this._dataLoader.viewportItemIndexChanged.apply(this._dataLoader, arguments);
    },
    viewportItemSize: function viewportItemSize(size) {
      if (size !== undefined) {
        this._viewportItemSize = size;
      }

      return this._viewportItemSize;
    },
    viewportSize: function viewportSize(size) {
      if (size !== undefined) {
        this._viewportSize = size;
      }

      return this._viewportSize;
    },
    viewportHeight: function viewportHeight(height) {
      var begin = this.getItemIndexByPosition();
      var end = this.getItemIndexByPosition(this._position + height);
      this.viewportSize(Math.ceil(end - begin));

      if (this._viewportItemIndex !== begin) {
        this._setViewportPositionCore(this._position);
      }
    },
    reset: function reset(isRefresh) {
      this._dataLoader.reset();

      if (!isRefresh) {
        this._itemSizes = {};
      }
    },
    subscribeToWindowScrollEvents: function subscribeToWindowScrollEvents($element) {
      this._windowScroll = this._windowScroll || subscribeToExternalScrollers($element, scrollTop => {
        if (this.viewportItemSize()) {
          this.setViewportPosition(scrollTop);
        }
      });
    },
    dispose: function dispose() {
      clearTimeout(this._scrollTimeoutID);
      this._windowScroll && this._windowScroll.dispose();
      this._windowScroll = null;
    },
    scrollTo: function scrollTo(pos) {
      this._windowScroll && this._windowScroll.scrollTo(pos);
    },
    isVirtualMode: function isVirtualMode() {
      return _isVirtualMode(this);
    },
    isAppendMode: function isAppendMode() {
      return _isAppendMode(this);
    },
    // new mode
    getViewportParams: function getViewportParams() {
      var _this$option2;

      var virtualMode = this.option('scrolling.mode') === SCROLLING_MODE_VIRTUAL;

      var totalItemsCount = this._dataOptions.totalItemsCount();

      var hasKnownLastPage = this._dataOptions.hasKnownLastPage();

      var topIndex = hasKnownLastPage && this._viewportItemIndex > totalItemsCount ? totalItemsCount : this._viewportItemIndex;
      var bottomIndex = this._viewportSize + topIndex;
      var maxGap = this.option('scrolling.prerenderedRowChunkSize') || 1;
      var isScrollingBack = this.isScrollingBack();
      var minGap = (_this$option2 = this.option('scrolling.prerenderedRowCount')) !== null && _this$option2 !== void 0 ? _this$option2 : 1;
      var topMinGap = isScrollingBack ? minGap : 0;
      var bottomMinGap = isScrollingBack ? 0 : minGap;
      var skip = Math.floor(Math.max(0, topIndex - topMinGap) / maxGap) * maxGap;
      var take = Math.ceil((bottomIndex + bottomMinGap - skip) / maxGap) * maxGap;

      if (virtualMode) {
        var remainedItems = Math.max(0, totalItemsCount - skip);
        take = Math.min(take, remainedItems);
      }

      return {
        skip,
        take
      };
    },
    itemsCount: function itemsCount() {
      var result = 0;

      if (this.option(LEGACY_SCROLLING_MODE)) {
        result = this._dataLoader.itemsCount.apply(this._dataLoader, arguments);
      } else {
        result = this._dataOptions.itemsCount();
      }

      return result;
    }
  };
  ['pageIndex', 'beginPageIndex', 'endPageIndex', 'pageSize', 'load', 'loadIfNeed', 'handleDataChanged', 'getDelayDeferred'].forEach(function (name) {
    members[name] = function () {
      return this._dataLoader[name].apply(this._dataLoader, arguments);
    };
  });
  return members;
}());