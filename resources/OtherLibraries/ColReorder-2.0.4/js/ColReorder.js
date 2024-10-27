import { init, getOrder, setOrder, validateMove } from './functions';
/**
 * This is one possible UI for column reordering in DataTables. In this case
 * columns are reordered by clicking and dragging a column header. It calculates
 * where columns can be dropped based on the column header used to start the drag
 * and then `colReorder.move()` method to alter the DataTable.
 */
var ColReorder = /** @class */ (function () {
    function ColReorder(dt, opts) {
        this.dom = {
            drag: null
        };
        this.c = {
            columns: null,
            enable: null,
            order: null
        };
        this.s = {
            dropZones: [],
            mouse: {
                absLeft: -1,
                offset: {
                    x: -1,
                    y: -1
                },
                start: {
                    x: -1,
                    y: -1
                },
                target: null,
                targets: []
            },
            scrollInterval: null
        };
        var that = this;
        var ctx = dt.settings()[0];
        // Check if ColReorder already has been initialised on this DataTable - only
        // one can exist.
        if (ctx._colReorder) {
            return;
        }
        dt.settings()[0]._colReorder = this;
        this.dt = dt;
        $.extend(this.c, ColReorder.defaults, opts);
        init(dt);
        dt.on('stateSaveParams', function (e, s, d) {
            d.colReorder = getOrder(dt);
        });
        dt.on('destroy', function () {
            dt.off('.colReorder');
            dt.colReorder.reset();
        });
        // Initial ordering / state restoring
        var loaded = dt.state.loaded();
        var order = this.c.order;
        if (loaded && loaded.colReorder) {
            order = loaded.colReorder;
        }
        if (order) {
            dt.ready(function () {
                setOrder(dt, order, true);
            });
        }
        dt.table()
            .header.structure()
            .forEach(function (row) {
            for (var i = 0; i < row.length; i++) {
                if (row[i] && row[i].cell) {
                    that._addListener(row[i].cell);
                }
            }
        });
    }
    ColReorder.prototype.disable = function () {
        this.c.enable = false;
        return this;
    };
    ColReorder.prototype.enable = function (flag) {
        if (flag === void 0) { flag = true; }
        if (flag === false) {
            return this.disable();
        }
        this.c.enable = true;
        return this;
    };
    /**
     * Attach the mouse down listener to an element to start a column reorder action
     *
     * @param el
     */
    ColReorder.prototype._addListener = function (el) {
        var that = this;
        $(el)
            .on('selectstart.colReorder', function () {
            return false;
        })
            .on('mousedown.colReorder touchstart.colReorder', function (e) {
            // Ignore middle and right click
            if (e.type === 'mousedown' && e.which !== 1) {
                return;
            }
            // Ignore if disabled
            if (!that.c.enable) {
                return;
            }
            that._mouseDown(e, this);
        });
    };
    /**
     * Create the element that is dragged around the page
     */
    ColReorder.prototype._createDragNode = function () {
        var origCell = this.s.mouse.target;
        var origTr = origCell.parent();
        var origThead = origTr.parent();
        var origTable = origThead.parent();
        var cloneCell = origCell.clone();
        // This is a slightly odd combination of jQuery and DOM, but it is the
        // fastest and least resource intensive way I could think of cloning
        // the table with just a single header cell in it.
        this.dom.drag = $(origTable[0].cloneNode(false))
            .addClass('dtcr-cloned')
            .append($(origThead[0].cloneNode(false)).append($(origTr[0].cloneNode(false)).append(cloneCell[0])) // Not sure why  it doesn't want to append a jQuery node
        )
            .css({
            position: 'absolute',
            top: 0,
            left: 0,
            width: $(origCell).outerWidth(),
            height: $(origCell).outerHeight()
        })
            .appendTo('body');
    };
    /**
     * Get cursor position regardless of mouse or touch input
     *
     * @param e Event
     * @param prop Property name to get
     * @returns Value - assuming a number here
     */
    ColReorder.prototype._cursorPosition = function (e, prop) {
        return e.type.indexOf('touch') !== -1 ? e.originalEvent.touches[0][prop] : e[prop];
    };
    /**
     * Cache values at start
     *
     * @param e Triggering event
     * @param cell Cell that the action started on
     * @returns
     */
    ColReorder.prototype._mouseDown = function (e, cell) {
        var _this = this;
        var target = $(e.target).closest('th, td');
        var offset = target.offset();
        var moveableColumns = this.dt.columns(this.c.columns).indexes().toArray();
        var moveColumnIndexes = $(cell)
            .attr('data-dt-column')
            .split(',')
            .map(function (val) {
            return parseInt(val, 10);
        });
        // Don't do anything for columns which are not selected as moveable
        for (var j = 0; j < moveColumnIndexes.length; j++) {
            if (!moveableColumns.includes(moveColumnIndexes[j])) {
                return false;
            }
        }
        this.s.mouse.start.x = this._cursorPosition(e, 'pageX');
        this.s.mouse.start.y = this._cursorPosition(e, 'pageY');
        this.s.mouse.offset.x = this._cursorPosition(e, 'pageX') - offset.left;
        this.s.mouse.offset.y = this._cursorPosition(e, 'pageY') - offset.top;
        this.s.mouse.target = target;
        this.s.mouse.targets = moveColumnIndexes;
        // Classes to highlight the columns being moved
        for (var i = 0; i < moveColumnIndexes.length; i++) {
            var cells = this.dt
                .cells(null, moveColumnIndexes[i], { page: 'current' })
                .nodes()
                .to$();
            var klass = 'dtcr-moving';
            if (i === 0) {
                klass += ' dtcr-moving-first';
            }
            if (i === moveColumnIndexes.length - 1) {
                klass += ' dtcr-moving-last';
            }
            cells.addClass(klass);
        }
        this._regions(moveColumnIndexes);
        this._scrollRegions();
        /* Add event handlers to the document */
        $(document)
            .on('mousemove.colReorder touchmove.colReorder', function (e) {
            _this._mouseMove(e);
        })
            .on('mouseup.colReorder touchend.colReorder', function (e) {
            _this._mouseUp(e);
        });
    };
    ColReorder.prototype._mouseMove = function (e) {
        if (this.dom.drag === null) {
            // Only create the drag element if the mouse has moved a specific distance from the start
            // point - this allows the user to make small mouse movements when sorting and not have a
            // possibly confusing drag element showing up
            if (Math.pow(Math.pow(this._cursorPosition(e, 'pageX') - this.s.mouse.start.x, 2) +
                Math.pow(this._cursorPosition(e, 'pageY') - this.s.mouse.start.y, 2), 0.5) < 5) {
                return;
            }
            $(document.body).addClass('dtcr-dragging');
            this._createDragNode();
        }
        // Position the element - we respect where in the element the click occurred
        this.dom.drag.css({
            left: this._cursorPosition(e, 'pageX') - this.s.mouse.offset.x,
            top: this._cursorPosition(e, 'pageY') - this.s.mouse.offset.y
        });
        // Find cursor's left position relative to the table
        var tableOffset = $(this.dt.table().node()).offset().left;
        var cursorMouseLeft = this._cursorPosition(e, 'pageX') - tableOffset;
        var dropZone = this.s.dropZones.find(function (zone) {
            if (zone.left <= cursorMouseLeft && cursorMouseLeft <= zone.left + zone.width) {
                return true;
            }
            return false;
        });
        this.s.mouse.absLeft = this._cursorPosition(e, 'pageX');
        if (!dropZone) {
            return;
        }
        if (!dropZone.self) {
            this._move(dropZone, cursorMouseLeft);
        }
    };
    ColReorder.prototype._mouseUp = function (e) {
        $(document).off('.colReorder');
        $(document.body).removeClass('dtcr-dragging');
        if (this.dom.drag) {
            this.dom.drag.remove();
            this.dom.drag = null;
        }
        if (this.s.scrollInterval) {
            clearInterval(this.s.scrollInterval);
        }
        this.dt.cells('.dtcr-moving').nodes().to$().removeClass('dtcr-moving dtcr-moving-first dtcr-moving-last');
    };
    /**
     * Shift columns around
     *
     * @param dropZone Where to move to
     * @param cursorMouseLeft Cursor position, relative to the left of the table
     */
    ColReorder.prototype._move = function (dropZone, cursorMouseLeft) {
        var that = this;
        this.dt.colReorder.move(this.s.mouse.targets, dropZone.colIdx);
        // Update the targets
        this.s.mouse.targets = $(this.s.mouse.target)
            .attr('data-dt-column')
            .split(',')
            .map(function (val) {
            return parseInt(val, 10);
        });
        this._regions(this.s.mouse.targets);
        var visibleTargets = this.s.mouse.targets.filter(function (val) {
            return that.dt.column(val).visible();
        });
        // If the column being moved is smaller than the column it is replacing,
        // the drop zones might need a correction to allow for this since, otherwise
        // we might immediately be changing the column order as soon as it was placed.
        // Find the drop zone for the first in the list of targets - is its
        // left greater than the mouse position. If so, it needs correcting
        var dz = this.s.dropZones.find(function (zone) {
            return zone.colIdx === visibleTargets[0];
        });
        var dzIdx = this.s.dropZones.indexOf(dz);
        if (dz.left > cursorMouseLeft) {
            var previousDiff = dz.left - cursorMouseLeft;
            var previousDz = this.s.dropZones[dzIdx - 1];
            dz.left -= previousDiff;
            dz.width += previousDiff;
            if (previousDz) {
                previousDz.width -= previousDiff;
            }
        }
        // And for the last in the list
        dz = this.s.dropZones.find(function (zone) {
            return zone.colIdx === visibleTargets[visibleTargets.length - 1];
        });
        if (dz.left + dz.width < cursorMouseLeft) {
            var nextDiff = cursorMouseLeft - (dz.left + dz.width);
            var nextDz = this.s.dropZones[dzIdx + 1];
            dz.width += nextDiff;
            if (nextDz) {
                nextDz.left += nextDiff;
                nextDz.width -= nextDiff;
            }
        }
    };
    /**
     * Determine the boundaries for where drops can happen and where they would
     * insert into.
     */
    ColReorder.prototype._regions = function (moveColumns) {
        var that = this;
        var dropZones = [];
        var totalWidth = 0;
        var negativeCorrect = 0;
        var allowedColumns = this.dt.columns(this.c.columns).indexes().toArray();
        var widths = this.dt.columns().widths();
        // Each column is a drop zone
        this.dt.columns().every(function (colIdx, tabIdx, i) {
            if (!this.visible()) {
                return;
            }
            var columnWidth = widths[colIdx];
            // Check that we are allowed to move into this column - if not, need
            // to offset the widths
            if (!allowedColumns.includes(colIdx)) {
                totalWidth += columnWidth;
                return;
            }
            var valid = validateMove(that.dt, moveColumns, colIdx);
            if (valid) {
                // New drop zone. Note that it might have it's offset moved
                // by the final condition in this logic set
                dropZones.push({
                    colIdx: colIdx,
                    left: totalWidth - negativeCorrect,
                    self: moveColumns[0] <= colIdx && colIdx <= moveColumns[moveColumns.length - 1],
                    width: columnWidth + negativeCorrect
                });
            }
            else if (colIdx < moveColumns[0]) {
                // Not valid and before the column(s) to be moved - the drop
                // zone for the previous valid drop point is extended
                if (dropZones.length) {
                    dropZones[dropZones.length - 1].width += columnWidth;
                }
            }
            else if (colIdx > moveColumns[moveColumns.length - 1]) {
                // Not valid and after the column(s) to be moved - the next
                // drop zone to be created will be extended
                negativeCorrect += columnWidth;
            }
            totalWidth += columnWidth;
        });
        this.s.dropZones = dropZones;
        // this._drawDropZones();
    };
    /**
     * Check if the table is scrolling or not. It is it the `table` isn't the same for
     * the header and body parents.
     *
     * @returns
     */
    ColReorder.prototype._isScrolling = function () {
        return this.dt.table().body().parentNode !== this.dt.table().header().parentNode;
    };
    /**
     * Set an interval clock that will check to see if the scrolling of the table body should be moved
     * as the mouse moves on the scroll (allowing a drag and drop to columns which aren't yet visible)
     */
    ColReorder.prototype._scrollRegions = function () {
        if (!this._isScrolling()) {
            // Not scrolling - nothing to do
            return;
        }
        var that = this;
        var tableLeft = $(this.dt.table().container()).position().left;
        var tableWidth = $(this.dt.table().container()).outerWidth();
        var mouseBuffer = 75;
        var scrollContainer = this.dt.table().body().parentElement.parentElement;
        this.s.scrollInterval = setInterval(function () {
            var mouseLeft = that.s.mouse.absLeft;
            if (mouseLeft < tableLeft + mouseBuffer && scrollContainer.scrollLeft) {
                scrollContainer.scrollLeft -= 5;
            }
            else if (mouseLeft > tableLeft + tableWidth - mouseBuffer &&
                scrollContainer.scrollLeft < scrollContainer.scrollWidth) {
                scrollContainer.scrollLeft += 5;
            }
        }, 25);
    };
    // This is handy for debugging where the drop zones actually are!
    // private _drawDropZones () {
    // 	let dropZones = this.s.dropZones;
    // 	$('div.allan').remove();
    // 	for (let i=0 ; i<dropZones.length ; i++) {
    // 		let zone = dropZones[i];
    // 		$(this.dt.table().container()).append(
    // 			$('<div>')
    // 				.addClass('allan')
    // 				.css({
    // 					position: 'absolute',
    // 					top: 0,
    // 					width: zone.width - 4,
    // 					height: 20,
    // 					left: zone.left + 2,
    // 					border: '1px solid red',
    // 				})
    // 		);
    // 	}
    // }
    ColReorder.defaults = {
        columns: '',
        enable: true,
        order: null
    };
    ColReorder.version = '2.0.4';
    return ColReorder;
}());
export default ColReorder;
