/*!
 * Font Awesome Icon Picker
 * https://farbelous.github.io/fontawesome-iconpicker/
 *
 * @author Javi Aguilar, itsjavi.com
 * @license MIT License
 * @see https://github.com/farbelous/fontawesome-iconpicker/blob/master/LICENSE
 */


!function (e) {
    "function" == typeof define && define.amd ? define(["jquery"], e) : e(jQuery)
}(function (j) {
    j.ui = j.ui || {};
    j.ui.version = "1.12.1";
    !function () {
        var r, y = Math.max, x = Math.abs, s = /left|center|right/, i = /top|center|bottom/,
            c = /[\+\-]\d+(\.[\d]+)?%?/, f = /^\w+/, l = /%$/, o = j.fn.pos;

        function q(e, a, t) {
            return [parseFloat(e[0]) * (l.test(e[0]) ? a / 100 : 1), parseFloat(e[1]) * (l.test(e[1]) ? t / 100 : 1)]
        }

        function C(e, a) {
            return parseInt(j.css(e, a), 10) || 0
        }

        j.pos = {
            scrollbarWidth: function () {
                if (void 0 !== r) return r;
                var e, a,
                    t = j("<div style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),
                    s = t.children()[0];
                return j("body").append(t), e = s.offsetWidth, t.css("overflow", "scroll"), e === (a = s.offsetWidth) && (a = t[0].clientWidth), t.remove(), r = e - a
            }, getScrollInfo: function (e) {
                var a = e.isWindow || e.isDocument ? "" : e.element.css("overflow-x"),
                    t = e.isWindow || e.isDocument ? "" : e.element.css("overflow-y"),
                    s = "scroll" === a || "auto" === a && e.width < e.element[0].scrollWidth;
                return {
                    width: "scroll" === t || "auto" === t && e.height < e.element[0].scrollHeight ? j.pos.scrollbarWidth() : 0,
                    height: s ? j.pos.scrollbarWidth() : 0
                }
            }, getWithinInfo: function (e) {
                var a = j(e || window), t = j.isWindow(a[0]), s = !!a[0] && 9 === a[0].nodeType;
                return {
                    element: a,
                    isWindow: t,
                    isDocument: s,
                    offset: !t && !s ? j(e).offset() : {left: 0, top: 0},
                    scrollLeft: a.scrollLeft(),
                    scrollTop: a.scrollTop(),
                    width: a.outerWidth(),
                    height: a.outerHeight()
                }
            }
        }, j.fn.pos = function (h) {
            if (!h || !h.of) return o.apply(this, arguments);
            h = j.extend({}, h);
            var m, p, d, T, u, e, a, t, g = j(h.of), b = j.pos.getWithinInfo(h.within), k = j.pos.getScrollInfo(b),
                w = (h.collision || "flip").split(" "), v = {};
            return e = 9 === (t = (a = g)[0]).nodeType ? {
                width: a.width(),
                height: a.height(),
                offset: {top: 0, left: 0}
            } : j.isWindow(t) ? {
                width: a.width(),
                height: a.height(),
                offset: {top: a.scrollTop(), left: a.scrollLeft()}
            } : t.preventDefault ? {
                width: 0,
                height: 0,
                offset: {top: t.pageY, left: t.pageX}
            } : {
                width: a.outerWidth(),
                height: a.outerHeight(),
                offset: a.offset()
            }, g[0].preventDefault && (h.at = "left top"), p = e.width, d = e.height, T = e.offset, u = j.extend({}, T), j.each(["my", "at"], function () {
                var e, a, t = (h[this] || "").split(" ");
                1 === t.length && (t = s.test(t[0]) ? t.concat(["center"]) : i.test(t[0]) ? ["center"].concat(t) : ["center", "center"]), t[0] = s.test(t[0]) ? t[0] : "center", t[1] = i.test(t[1]) ? t[1] : "center", e = c.exec(t[0]), a = c.exec(t[1]), v[this] = [e ? e[0] : 0, a ? a[0] : 0], h[this] = [f.exec(t[0])[0], f.exec(t[1])[0]]
            }), 1 === w.length && (w[1] = w[0]), "right" === h.at[0] ? u.left += p : "center" === h.at[0] && (u.left += p / 2), "bottom" === h.at[1] ? u.top += d : "center" === h.at[1] && (u.top += d / 2), m = q(v.at, p, d), u.left += m[0], u.top += m[1], this.each(function () {
                var t, e, c = j(this), f = c.outerWidth(), l = c.outerHeight(), a = C(this, "marginLeft"),
                    s = C(this, "marginTop"), r = f + a + C(this, "marginRight") + k.width,
                    i = l + s + C(this, "marginBottom") + k.height, o = j.extend({}, u),
                    n = q(v.my, c.outerWidth(), c.outerHeight());
                "right" === h.my[0] ? o.left -= f : "center" === h.my[0] && (o.left -= f / 2), "bottom" === h.my[1] ? o.top -= l : "center" === h.my[1] && (o.top -= l / 2), o.left += n[0], o.top += n[1], t = {
                    marginLeft: a,
                    marginTop: s
                }, j.each(["left", "top"], function (e, a) {
                    j.ui.pos[w[e]] && j.ui.pos[w[e]][a](o, {
                        targetWidth: p,
                        targetHeight: d,
                        elemWidth: f,
                        elemHeight: l,
                        collisionPosition: t,
                        collisionWidth: r,
                        collisionHeight: i,
                        offset: [m[0] + n[0], m[1] + n[1]],
                        my: h.my,
                        at: h.at,
                        within: b,
                        elem: c
                    })
                }), h.using && (e = function (e) {
                    var a = T.left - o.left, t = a + p - f, s = T.top - o.top, r = s + d - l, i = {
                        target: {element: g, left: T.left, top: T.top, width: p, height: d},
                        element: {element: c, left: o.left, top: o.top, width: f, height: l},
                        horizontal: t < 0 ? "left" : 0 < a ? "right" : "center",
                        vertical: r < 0 ? "top" : 0 < s ? "bottom" : "middle"
                    };
                    p < f && x(a + t) < p && (i.horizontal = "center"), d < l && x(s + r) < d && (i.vertical = "middle"), y(x(a), x(t)) > y(x(s), x(r)) ? i.important = "horizontal" : i.important = "vertical", h.using.call(this, e, i)
                }), c.offset(j.extend(o, {using: e}))
            })
        }, j.ui.pos = {
            _trigger: function (e, a, t, s) {
                a.elem && a.elem.trigger({type: t, position: e, positionData: a, triggered: s})
            }, fit: {
                left: function (e, a) {
                    j.ui.pos._trigger(e, a, "posCollide", "fitLeft");
                    var t, s = a.within, r = s.isWindow ? s.scrollLeft : s.offset.left, i = s.width,
                        c = e.left - a.collisionPosition.marginLeft, f = r - c, l = c + a.collisionWidth - i - r;
                    a.collisionWidth > i ? 0 < f && l <= 0 ? (t = e.left + f + a.collisionWidth - i - r, e.left += f - t) : e.left = 0 < l && f <= 0 ? r : l < f ? r + i - a.collisionWidth : r : 0 < f ? e.left += f : 0 < l ? e.left -= l : e.left = y(e.left - c, e.left), j.ui.pos._trigger(e, a, "posCollided", "fitLeft")
                }, top: function (e, a) {
                    j.ui.pos._trigger(e, a, "posCollide", "fitTop");
                    var t, s = a.within, r = s.isWindow ? s.scrollTop : s.offset.top, i = a.within.height,
                        c = e.top - a.collisionPosition.marginTop, f = r - c, l = c + a.collisionHeight - i - r;
                    a.collisionHeight > i ? 0 < f && l <= 0 ? (t = e.top + f + a.collisionHeight - i - r, e.top += f - t) : e.top = 0 < l && f <= 0 ? r : l < f ? r + i - a.collisionHeight : r : 0 < f ? e.top += f : 0 < l ? e.top -= l : e.top = y(e.top - c, e.top), j.ui.pos._trigger(e, a, "posCollided", "fitTop")
                }
            }, flip: {
                left: function (e, a) {
                    j.ui.pos._trigger(e, a, "posCollide", "flipLeft");
                    var t, s, r = a.within, i = r.offset.left + r.scrollLeft, c = r.width,
                        f = r.isWindow ? r.scrollLeft : r.offset.left, l = e.left - a.collisionPosition.marginLeft,
                        o = l - f, n = l + a.collisionWidth - c - f,
                        h = "left" === a.my[0] ? -a.elemWidth : "right" === a.my[0] ? a.elemWidth : 0,
                        m = "left" === a.at[0] ? a.targetWidth : "right" === a.at[0] ? -a.targetWidth : 0,
                        p = -2 * a.offset[0];
                    o < 0 ? ((t = e.left + h + m + p + a.collisionWidth - c - i) < 0 || t < x(o)) && (e.left += h + m + p) : 0 < n && (0 < (s = e.left - a.collisionPosition.marginLeft + h + m + p - f) || x(s) < n) && (e.left += h + m + p), j.ui.pos._trigger(e, a, "posCollided", "flipLeft")
                }, top: function (e, a) {
                    j.ui.pos._trigger(e, a, "posCollide", "flipTop");
                    var t, s, r = a.within, i = r.offset.top + r.scrollTop, c = r.height,
                        f = r.isWindow ? r.scrollTop : r.offset.top, l = e.top - a.collisionPosition.marginTop,
                        o = l - f, n = l + a.collisionHeight - c - f,
                        h = "top" === a.my[1] ? -a.elemHeight : "bottom" === a.my[1] ? a.elemHeight : 0,
                        m = "top" === a.at[1] ? a.targetHeight : "bottom" === a.at[1] ? -a.targetHeight : 0,
                        p = -2 * a.offset[1];
                    o < 0 ? ((s = e.top + h + m + p + a.collisionHeight - c - i) < 0 || s < x(o)) && (e.top += h + m + p) : 0 < n && (0 < (t = e.top - a.collisionPosition.marginTop + h + m + p - f) || x(t) < n) && (e.top += h + m + p), j.ui.pos._trigger(e, a, "posCollided", "flipTop")
                }
            }, flipfit: {
                left: function () {
                    j.ui.pos.flip.left.apply(this, arguments), j.ui.pos.fit.left.apply(this, arguments)
                }, top: function () {
                    j.ui.pos.flip.top.apply(this, arguments), j.ui.pos.fit.top.apply(this, arguments)
                }
            }
        }, function () {
            var e, a, t, s, r, i = document.getElementsByTagName("body")[0], c = document.createElement("div");
            for (r in e = document.createElement(i ? "div" : "body"), t = {
                visibility: "hidden",
                width: 0,
                height: 0,
                border: 0,
                margin: 0,
                background: "none"
            }, i && j.extend(t, {position: "absolute", left: "-1000px", top: "-1000px"}), t) e.style[r] = t[r];
            e.appendChild(c), (a = i || document.documentElement).insertBefore(e, a.firstChild), c.style.cssText = "position: absolute; left: 10.7432222px;", s = j(c).offset().left, j.support.offsetFractions = 10 < s && s < 11, e.innerHTML = "", a.removeChild(e)
        }()
    }();
    j.ui.position
}), function (e) {
    "use strict";
    "function" == typeof define && define.amd ? define(["jquery"], e) : window.jQuery && !window.jQuery.fn.iconpicker && e(window.jQuery)
}(function (l) {
    "use strict";
    var t = function (e) {
        return !1 === e || "" === e || null == e
    }, s = function (e) {
        return 0 < l(e).length
    }, r = function (e) {
        return "string" == typeof e || e instanceof String
    }, i = function (e, a) {
        return -1 !== l.inArray(e, a)
    }, c = function (e, a) {
        this._id = c._idCounter++, this.element = l(e).addClass("iconpicker-element"), this._trigger("iconpickerCreate", {iconpickerValue: this.iconpickerValue}), this.options = l.extend({}, c.defaultOptions, this.element.data(), a), this.options.templates = l.extend({}, c.defaultOptions.templates, this.options.templates), this.options.originalPlacement = this.options.placement, this.container = !!s(this.options.container) && l(this.options.container), !1 === this.container && (this.element.is(".dropdown-toggle") ? this.container = l("~ .dropdown-menu:first", this.element) : this.container = this.element.is("input,textarea,button,.btn") ? this.element.parent() : this.element), this.container.addClass("iconpicker-container"), this.isDropdownMenu() && (this.options.placement = "inline"), this.input = !!this.element.is("input,textarea") && this.element.addClass("iconpicker-input"), !1 === this.input && (this.input = this.container.find(this.options.input), this.input.is("input,textarea") || (this.input = !1)), this.component = this.isDropdownMenu() ? this.container.parent().find(this.options.component) : this.container.find(this.options.component), 0 === this.component.length ? this.component = !1 : this.component.find("i").addClass("iconpicker-component"), this._createPopover(), this._createIconpicker(), 0 === this.getAcceptButton().length && (this.options.mustAccept = !1), this.isInputGroup() ? this.container.parent().append(this.popover) : this.container.append(this.popover), this._bindElementEvents(), this._bindWindowEvents(), this.update(this.options.selected), this.isInline() && this.show(), this._trigger("iconpickerCreated", {iconpickerValue: this.iconpickerValue})
    };
    c._idCounter = 0, c.defaultOptions = {
        title: !1,
        selected: !1,
        defaultValue: !1,
        placement: "bottom",
        collision: "none",
        animation: !0,
        hideOnSelect: !1,
        showFooter: !1,
        searchInFooter: !1,
        mustAccept: !1,
        selectedCustomClass: "bg-primary",
        icons: [],
        fullClassFormatter: function (e) {
            return e
        },
        input: "input,.iconpicker-input",
        inputSearch: !1,
        container: !1,
        component: ".input-group-addon,.iconpicker-component",
        templates: {
            popover: '<div class="iconpicker-popover popover"><div class="arrow"></div><div class="popover-title"></div><div class="popover-content"></div></div>',
            footer: '<div class="popover-footer"></div>',
            buttons: '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</button> <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</button>',
            search: '<input type="search" class="form-control iconpicker-search" placeholder="Type to filter" />',
            iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
            iconpickerItem: '<a role="button" href="javascript:;" class="iconpicker-item"><i></i></a>'
        }
    }, c.batch = function (e, a) {
        var t = Array.prototype.slice.call(arguments, 2);
        return l(e).each(function () {
            var e = l(this).data("iconpicker");
            e && e[a].apply(e, t)
        })
    }, c.prototype = {
        constructor: c, options: {}, _id: 0, _trigger: function (e, a) {
            a = a || {}, this.element.trigger(l.extend({type: e, iconpickerInstance: this}, a))
        }, _createPopover: function () {
            this.popover = l(this.options.templates.popover);
            var e = this.popover.find(".popover-title");
            if (this.options.title && e.append(l('<div class="popover-title-text">' + this.options.title + "</div>")), this.hasSeparatedSearchInput() && !this.options.searchInFooter ? e.append(this.options.templates.search) : this.options.title || e.remove(), this.options.showFooter && !t(this.options.templates.footer)) {
                var a = l(this.options.templates.footer);
                this.hasSeparatedSearchInput() && this.options.searchInFooter && a.append(l(this.options.templates.search)), t(this.options.templates.buttons) || a.append(l(this.options.templates.buttons)), this.popover.append(a)
            }
            return !0 === this.options.animation && this.popover.addClass("fade"), this.popover
        }, _createIconpicker: function () {
            var t = this;
            this.iconpicker = l(this.options.templates.iconpicker);
            var e = function (e) {
                var a = l(this);
                a.is("i") && (a = a.parent()), t._trigger("iconpickerSelect", {
                    iconpickerItem: a,
                    iconpickerValue: t.iconpickerValue
                }), !1 === t.options.mustAccept ? (t.update(a.data("iconpickerValue")), t._trigger("iconpickerSelected", {
                    iconpickerItem: this,
                    iconpickerValue: t.iconpickerValue
                })) : t.update(a.data("iconpickerValue"), !0), t.options.hideOnSelect && !1 === t.options.mustAccept && t.hide()
            }, a = l(this.options.templates.iconpickerItem), s = [];
            for (var r in this.options.icons) if ("string" == typeof this.options.icons[r].title) {
                var i = a.clone();
                if (i.find("i").addClass(this.options.fullClassFormatter(this.options.icons[r].title)), i.data("iconpickerValue", this.options.icons[r].title).on("click.iconpicker", e), i.attr("title", "." + this.options.icons[r].title), 0 < this.options.icons[r].searchTerms.length) {
                    for (var c = "", f = 0; f < this.options.icons[r].searchTerms.length; f++) c = c + this.options.icons[r].searchTerms[f] + " ";
                    i.attr("data-search-terms", c)
                }
                s.push(i)
            }
            return this.iconpicker.find(".iconpicker-items").append(s), this.popover.find(".popover-content").append(this.iconpicker), this.iconpicker
        }, _isEventInsideIconpicker: function (e) {
            var a = l(e.target);
            return !((!a.hasClass("iconpicker-element") || a.hasClass("iconpicker-element") && !a.is(this.element)) && 0 === a.parents(".iconpicker-popover").length)
        }, _bindElementEvents: function () {
            var a = this;
            this.getSearchInput().on("keyup.iconpicker", function () {
                a.filter(l(this).val().toLowerCase())
            }), this.getAcceptButton().on("click.iconpicker", function () {
                var e = a.iconpicker.find(".iconpicker-selected").get(0);
                a.update(a.iconpickerValue), a._trigger("iconpickerSelected", {
                    iconpickerItem: e,
                    iconpickerValue: a.iconpickerValue
                }), a.isInline() || a.hide()
            }), this.getCancelButton().on("click.iconpicker", function () {
                a.isInline() || a.hide()
            }), this.element.on("focus.iconpicker", function (e) {
                a.show(), e.stopPropagation()
            }), this.hasComponent() && this.component.on("click.iconpicker", function () {
                a.toggle()
            }), this.hasInput() && this.input.on("keyup.iconpicker", function (e) {
                i(e.keyCode, [38, 40, 37, 39, 16, 17, 18, 9, 8, 91, 93, 20, 46, 186, 190, 46, 78, 188, 44, 86]) ? a._updateFormGroupStatus(!1 !== a.getValid(this.value)) : a.update(), !0 === a.options.inputSearch && a.filter(l(this).val().toLowerCase())
            })
        }, _bindWindowEvents: function () {
            var e = l(window.document), a = this, t = ".iconpicker.inst" + this._id;
            l(window).on("resize.iconpicker" + t + " orientationchange.iconpicker" + t, function (e) {
                a.popover.hasClass("in") && a.updatePlacement()
            }), a.isInline() || e.on("mouseup" + t, function (e) {
                a._isEventInsideIconpicker(e) || a.isInline() || a.hide()
            })
        }, _unbindElementEvents: function () {
            this.popover.off(".iconpicker"), this.element.off(".iconpicker"), this.hasInput() && this.input.off(".iconpicker"), this.hasComponent() && this.component.off(".iconpicker"), this.hasContainer() && this.container.off(".iconpicker")
        }, _unbindWindowEvents: function () {
            l(window).off(".iconpicker.inst" + this._id), l(window.document).off(".iconpicker.inst" + this._id)
        }, updatePlacement: function (e, a) {
            e = e || this.options.placement, this.options.placement = e, a = !0 === (a = a || this.options.collision) ? "flip" : a;
            var t = {
                at: "right bottom",
                my: "right top",
                of: this.hasInput() && !this.isInputGroup() ? this.input : this.container,
                collision: !0 === a ? "flip" : a,
                within: window
            };
            if (this.popover.removeClass("inline topLeftCorner topLeft top topRight topRightCorner rightTop right rightBottom bottomRight bottomRightCorner bottom bottomLeft bottomLeftCorner leftBottom left leftTop"), "object" == typeof e) return this.popover.pos(l.extend({}, t, e));
            switch (e) {
                case"inline":
                    t = !1;
                    break;
                case"topLeftCorner":
                    t.my = "right bottom", t.at = "left top";
                    break;
                case"topLeft":
                    t.my = "left bottom", t.at = "left top";
                    break;
                case"top":
                    t.my = "center bottom", t.at = "center top";
                    break;
                case"topRight":
                    t.my = "right bottom", t.at = "right top";
                    break;
                case"topRightCorner":
                    t.my = "left bottom", t.at = "right top";
                    break;
                case"rightTop":
                    t.my = "left bottom", t.at = "right center";
                    break;
                case"right":
                    t.my = "left center", t.at = "right center";
                    break;
                case"rightBottom":
                    t.my = "left top", t.at = "right center";
                    break;
                case"bottomRightCorner":
                    t.my = "left top", t.at = "right bottom";
                    break;
                case"bottomRight":
                    t.my = "right top", t.at = "right bottom";
                    break;
                case"bottom":
                    t.my = "center top", t.at = "center bottom";
                    break;
                case"bottomLeft":
                    t.my = "left top", t.at = "left bottom";
                    break;
                case"bottomLeftCorner":
                    t.my = "right top", t.at = "left bottom";
                    break;
                case"leftBottom":
                    t.my = "right top", t.at = "left center";
                    break;
                case"left":
                    t.my = "right center", t.at = "left center";
                    break;
                case"leftTop":
                    t.my = "right bottom", t.at = "left center";
                    break;
                default:
                    return !1
            }
            return this.popover.css({display: "inline" === this.options.placement ? "" : "block"}), !1 !== t ? this.popover.pos(t).css("maxWidth", l(window).width() - this.container.offset().left - 5) : this.popover.css({
                top: "auto",
                right: "auto",
                bottom: "auto",
                left: "auto",
                maxWidth: "none"
            }), this.popover.addClass(this.options.placement), !0
        }, _updateComponents: function () {
            if (this.iconpicker.find(".iconpicker-item.iconpicker-selected").removeClass("iconpicker-selected " + this.options.selectedCustomClass), this.iconpickerValue && this.iconpicker.find("." + this.options.fullClassFormatter(this.iconpickerValue).replace(/ /g, ".")).parent().addClass("iconpicker-selected " + this.options.selectedCustomClass), this.hasComponent()) {
                var e = this.component.find("i");
                0 < e.length ? e.attr("class", this.options.fullClassFormatter(this.iconpickerValue)) : this.component.html(this.getHtml())
            }
        }, _updateFormGroupStatus: function (e) {
            return !!this.hasInput() && (!1 !== e ? this.input.parents(".form-group:first").removeClass("has-error") : this.input.parents(".form-group:first").addClass("has-error"), !0)
        }, getValid: function (e) {
            r(e) || (e = "");
            var a = "" === e;
            e = l.trim(e);
            for (var t = !1, s = 0; s < this.options.icons.length; s++) if (this.options.icons[s].title === e) {
                t = !0;
                break
            }
            return !(!t && !a) && e
        }, setValue: function (e) {
            var a = this.getValid(e);
            return !1 !== a ? (this.iconpickerValue = a, this._trigger("iconpickerSetValue", {iconpickerValue: a}), this.iconpickerValue) : (this._trigger("iconpickerInvalid", {iconpickerValue: e}), !1)
        }, getHtml: function () {
            return '<i class="' + this.options.fullClassFormatter(this.iconpickerValue) + '"></i>'
        }, setSourceValue: function (e) {
            return !1 !== (e = this.setValue(e)) && "" !== e && (this.hasInput() ? this.input.val(this.iconpickerValue) : this.element.data("iconpickerValue", this.iconpickerValue), this._trigger("iconpickerSetSourceValue", {iconpickerValue: e})), e
        }, getSourceValue: function (e) {
            var a = e = e || this.options.defaultValue;
            return void 0 !== (a = this.hasInput() ? this.input.val() : this.element.data("iconpickerValue")) && "" !== a && null !== a && !1 !== a || (a = e), a
        }, hasInput: function () {
            return !1 !== this.input
        }, isInputSearch: function () {
            return this.hasInput() && !0 === this.options.inputSearch
        }, isInputGroup: function () {
            return this.container.is(".input-group")
        }, isDropdownMenu: function () {
            return this.container.is(".dropdown-menu")
        }, hasSeparatedSearchInput: function () {
            return !1 !== this.options.templates.search && !this.isInputSearch()
        }, hasComponent: function () {
            return !1 !== this.component
        }, hasContainer: function () {
            return !1 !== this.container
        }, getAcceptButton: function () {
            return this.popover.find(".iconpicker-btn-accept")
        }, getCancelButton: function () {
            return this.popover.find(".iconpicker-btn-cancel")
        }, getSearchInput: function () {
            return this.popover.find(".iconpicker-search")
        }, filter: function (s) {
            if (t(s)) return this.iconpicker.find(".iconpicker-item").show(), l(!1);
            var r = [];
            return this.iconpicker.find(".iconpicker-item").each(function () {
                var e = l(this), a = e.attr("title").toLowerCase();
                a = a + " " + (e.attr("data-search-terms") ? e.attr("data-search-terms").toLowerCase() : "");
                var t = !1;
                try {
                    t = new RegExp("(^|\\W)" + s, "g")
                } catch (e) {
                    t = !1
                }
                !1 !== t && a.match(t) ? (r.push(e), e.show()) : e.hide()
            }), r
        }, show: function () {
            if (this.popover.hasClass("in")) return !1;
            l.iconpicker.batch(l(".iconpicker-popover.in:not(.inline)").not(this.popover), "hide"), this._trigger("iconpickerShow", {iconpickerValue: this.iconpickerValue}), this.updatePlacement(), this.popover.addClass("in"), setTimeout(l.proxy(function () {
                this.popover.css("display", this.isInline() ? "" : "block"), this._trigger("iconpickerShown", {iconpickerValue: this.iconpickerValue})
            }, this), this.options.animation ? 300 : 1)
        }, hide: function () {
            if (!this.popover.hasClass("in")) return !1;
            this._trigger("iconpickerHide", {iconpickerValue: this.iconpickerValue}), this.popover.removeClass("in"), setTimeout(l.proxy(function () {
                this.popover.css("display", "none"), this.getSearchInput().val(""), this.filter(""), this._trigger("iconpickerHidden", {iconpickerValue: this.iconpickerValue})
            }, this), this.options.animation ? 300 : 1)
        }, toggle: function () {
            this.popover.is(":visible") ? this.hide() : this.show(!0)
        }, update: function (e, a) {
            return e = e || this.getSourceValue(this.iconpickerValue), this._trigger("iconpickerUpdate", {iconpickerValue: this.iconpickerValue}), !0 === a ? e = this.setValue(e) : (e = this.setSourceValue(e), this._updateFormGroupStatus(!1 !== e)), !1 !== e && this._updateComponents(), this._trigger("iconpickerUpdated", {iconpickerValue: this.iconpickerValue}), e
        }, destroy: function () {
            this._trigger("iconpickerDestroy", {iconpickerValue: this.iconpickerValue}), this.element.removeData("iconpicker").removeData("iconpickerValue").removeClass("iconpicker-element"), this._unbindElementEvents(), this._unbindWindowEvents(), l(this.popover).remove(), this._trigger("iconpickerDestroyed", {iconpickerValue: this.iconpickerValue})
        }, disable: function () {
            return !!this.hasInput() && (this.input.prop("disabled", !0), !0)
        }, enable: function () {
            return !!this.hasInput() && (this.input.prop("disabled", !1), !0)
        }, isDisabled: function () {
            return !!this.hasInput() && !0 === this.input.prop("disabled")
        }, isInline: function () {
            return "inline" === this.options.placement || this.popover.hasClass("inline")
        }
    }, l.iconpicker = c, l.fn.iconpicker = function (a) {
        return this.each(function () {
            var e = l(this);
            e.data("iconpicker") || e.data("iconpicker", new c(this, "object" == typeof a ? a : {}))
        })
    }, c.defaultOptions = l.extend(c.defaultOptions, {
        icons: [{title: "fab fa-500px", searchTerms: []}, {
            title: "fab fa-accessible-icon",
            searchTerms: ["accessibility", "handicap", "person", "wheelchair", "wheelchair-alt"]
        }, {title: "fab fa-accusoft", searchTerms: []}, {
            title: "fab fa-acquisitions-incorporated",
            searchTerms: ["Dungeons & Dragons", "d&d", "dnd", "fantasy", "game", "gaming", "tabletop"]
        }, {title: "fas fa-ad", searchTerms: []}, {
            title: "fas fa-address-book",
            searchTerms: []
        }, {title: "far fa-address-book", searchTerms: []}, {
            title: "fas fa-address-card",
            searchTerms: []
        }, {title: "far fa-address-card", searchTerms: []}, {
            title: "fas fa-adjust",
            searchTerms: ["contrast"]
        }, {title: "fab fa-adn", searchTerms: []}, {
            title: "fab fa-adversal",
            searchTerms: []
        }, {title: "fab fa-affiliatetheme", searchTerms: []}, {
            title: "fas fa-air-freshener",
            searchTerms: []
        }, {title: "fab fa-algolia", searchTerms: []}, {
            title: "fas fa-align-center",
            searchTerms: ["middle", "text"]
        }, {title: "fas fa-align-justify", searchTerms: ["text"]}, {
            title: "fas fa-align-left",
            searchTerms: ["text"]
        }, {title: "fas fa-align-right", searchTerms: ["text"]}, {
            title: "fab fa-alipay",
            searchTerms: []
        }, {
            title: "fas fa-allergies",
            searchTerms: ["freckles", "hand", "intolerances", "pox", "spots"]
        }, {title: "fab fa-amazon", searchTerms: []}, {
            title: "fab fa-amazon-pay",
            searchTerms: []
        }, {
            title: "fas fa-ambulance",
            searchTerms: ["help", "machine", "support", "vehicle"]
        }, {title: "fas fa-american-sign-language-interpreting", searchTerms: []}, {
            title: "fab fa-amilia",
            searchTerms: []
        }, {title: "fas fa-anchor", searchTerms: ["link"]}, {
            title: "fab fa-android",
            searchTerms: ["robot"]
        }, {title: "fab fa-angellist", searchTerms: []}, {
            title: "fas fa-angle-double-down",
            searchTerms: ["arrows"]
        }, {
            title: "fas fa-angle-double-left",
            searchTerms: ["arrows", "back", "laquo", "previous", "quote"]
        }, {
            title: "fas fa-angle-double-right",
            searchTerms: ["arrows", "forward", "next", "quote", "raquo"]
        }, {title: "fas fa-angle-double-up", searchTerms: ["arrows"]}, {
            title: "fas fa-angle-down",
            searchTerms: ["arrow"]
        }, {title: "fas fa-angle-left", searchTerms: ["arrow", "back", "previous"]}, {
            title: "fas fa-angle-right",
            searchTerms: ["arrow", "forward", "next"]
        }, {title: "fas fa-angle-up", searchTerms: ["arrow"]}, {
            title: "fas fa-angry",
            searchTerms: ["disapprove", "emoticon", "face", "mad", "upset"]
        }, {
            title: "far fa-angry",
            searchTerms: ["disapprove", "emoticon", "face", "mad", "upset"]
        }, {title: "fab fa-angrycreative", searchTerms: []}, {
            title: "fab fa-angular",
            searchTerms: []
        }, {
            title: "fas fa-ankh",
            searchTerms: ["amulet", "copper", "coptic christianity", "copts", "crux ansata", "egyptian", "venus"]
        }, {title: "fab fa-app-store", searchTerms: []}, {
            title: "fab fa-app-store-ios",
            searchTerms: []
        }, {title: "fab fa-apper", searchTerms: []}, {
            title: "fab fa-apple",
            searchTerms: ["food", "fruit", "mac", "osx"]
        }, {
            title: "fas fa-apple-alt",
            searchTerms: ["fall", "food", "fruit", "fuji", "macintosh", "seasonal"]
        }, {title: "fab fa-apple-pay", searchTerms: []}, {
            title: "fas fa-archive",
            searchTerms: ["box", "package", "storage"]
        }, {
            title: "fas fa-archway",
            searchTerms: ["arc", "monument", "road", "street"]
        }, {
            title: "fas fa-arrow-alt-circle-down",
            searchTerms: ["arrow-circle-o-down", "download"]
        }, {
            title: "far fa-arrow-alt-circle-down",
            searchTerms: ["arrow-circle-o-down", "download"]
        }, {
            title: "fas fa-arrow-alt-circle-left",
            searchTerms: ["arrow-circle-o-left", "back", "previous"]
        }, {
            title: "far fa-arrow-alt-circle-left",
            searchTerms: ["arrow-circle-o-left", "back", "previous"]
        }, {
            title: "fas fa-arrow-alt-circle-right",
            searchTerms: ["arrow-circle-o-right", "forward", "next"]
        }, {
            title: "far fa-arrow-alt-circle-right",
            searchTerms: ["arrow-circle-o-right", "forward", "next"]
        }, {
            title: "fas fa-arrow-alt-circle-up",
            searchTerms: ["arrow-circle-o-up"]
        }, {
            title: "far fa-arrow-alt-circle-up",
            searchTerms: ["arrow-circle-o-up"]
        }, {title: "fas fa-arrow-circle-down", searchTerms: ["download"]}, {
            title: "fas fa-arrow-circle-left",
            searchTerms: ["back", "previous"]
        }, {title: "fas fa-arrow-circle-right", searchTerms: ["forward", "next"]}, {
            title: "fas fa-arrow-circle-up",
            searchTerms: []
        }, {title: "fas fa-arrow-down", searchTerms: ["download"]}, {
            title: "fas fa-arrow-left",
            searchTerms: ["back", "previous"]
        }, {title: "fas fa-arrow-right", searchTerms: ["forward", "next"]}, {
            title: "fas fa-arrow-up",
            searchTerms: []
        }, {
            title: "fas fa-arrows-alt",
            searchTerms: ["arrow", "arrows", "bigger", "enlarge", "expand", "fullscreen", "move", "position", "reorder", "resize"]
        }, {title: "fas fa-arrows-alt-h", searchTerms: ["arrows-h", "resize"]}, {
            title: "fas fa-arrows-alt-v",
            searchTerms: ["arrows-v", "resize"]
        }, {title: "fas fa-assistive-listening-systems", searchTerms: []}, {
            title: "fas fa-asterisk",
            searchTerms: ["details"]
        }, {title: "fab fa-asymmetrik", searchTerms: []}, {
            title: "fas fa-at",
            searchTerms: ["e-mail", "email"]
        }, {
            title: "fas fa-atlas",
            searchTerms: ["book", "directions", "geography", "map", "wayfinding"]
        }, {title: "fas fa-atom", searchTerms: ["atheism", "chemistry", "science"]}, {
            title: "fab fa-audible",
            searchTerms: []
        }, {title: "fas fa-audio-description", searchTerms: []}, {
            title: "fab fa-autoprefixer",
            searchTerms: []
        }, {title: "fab fa-avianex", searchTerms: []}, {
            title: "fab fa-aviato",
            searchTerms: []
        }, {
            title: "fas fa-award",
            searchTerms: ["honor", "praise", "prize", "recognition", "ribbon"]
        }, {title: "fab fa-aws", searchTerms: []}, {
            title: "fas fa-backspace",
            searchTerms: ["command", "delete", "keyboard", "undo"]
        }, {title: "fas fa-backward", searchTerms: ["previous", "rewind"]}, {
            title: "fas fa-balance-scale",
            searchTerms: ["balanced", "justice", "legal", "measure", "weight"]
        }, {
            title: "fas fa-ban",
            searchTerms: ["abort", "ban", "block", "cancel", "delete", "hide", "prohibit", "remove", "stop", "trash"]
        }, {title: "fas fa-band-aid", searchTerms: ["bandage", "boo boo", "ouch"]}, {
            title: "fab fa-bandcamp",
            searchTerms: []
        }, {title: "fas fa-barcode", searchTerms: ["scan"]}, {
            title: "fas fa-bars",
            searchTerms: ["checklist", "drag", "hamburger", "list", "menu", "nav", "navigation", "ol", "reorder", "settings", "todo", "ul"]
        }, {title: "fas fa-baseball-ball", searchTerms: []}, {
            title: "fas fa-basketball-ball",
            searchTerms: []
        }, {title: "fas fa-bath", searchTerms: []}, {
            title: "fas fa-battery-empty",
            searchTerms: ["power", "status"]
        }, {title: "fas fa-battery-full", searchTerms: ["power", "status"]}, {
            title: "fas fa-battery-half",
            searchTerms: ["power", "status"]
        }, {title: "fas fa-battery-quarter", searchTerms: ["power", "status"]}, {
            title: "fas fa-battery-three-quarters",
            searchTerms: ["power", "status"]
        }, {title: "fas fa-bed", searchTerms: ["lodging", "sleep", "travel"]}, {
            title: "fas fa-beer",
            searchTerms: ["alcohol", "bar", "beverage", "drink", "liquor", "mug", "stein"]
        }, {title: "fab fa-behance", searchTerms: []}, {
            title: "fab fa-behance-square",
            searchTerms: []
        }, {title: "fas fa-bell", searchTerms: ["alert", "notification", "reminder"]}, {
            title: "far fa-bell",
            searchTerms: ["alert", "notification", "reminder"]
        }, {title: "fas fa-bell-slash", searchTerms: []}, {
            title: "far fa-bell-slash",
            searchTerms: []
        }, {
            title: "fas fa-bezier-curve",
            searchTerms: ["curves", "illustrator", "lines", "path", "vector"]
        }, {title: "fas fa-bible", searchTerms: ["book", "catholicism", "christianity"]}, {
            title: "fas fa-bicycle",
            searchTerms: ["bike", "gears", "transportation", "vehicle"]
        }, {title: "fab fa-bimobject", searchTerms: []}, {
            title: "fas fa-binoculars",
            searchTerms: []
        }, {title: "fas fa-birthday-cake", searchTerms: []}, {
            title: "fab fa-bitbucket",
            searchTerms: ["bitbucket-square", "git"]
        }, {title: "fab fa-bitcoin", searchTerms: []}, {
            title: "fab fa-bity",
            searchTerms: []
        }, {title: "fab fa-black-tie", searchTerms: []}, {
            title: "fab fa-blackberry",
            searchTerms: []
        }, {title: "fas fa-blender", searchTerms: []}, {
            title: "fas fa-blender-phone",
            searchTerms: ["appliance", "fantasy", "silly"]
        }, {title: "fas fa-blind", searchTerms: []}, {
            title: "fab fa-blogger",
            searchTerms: []
        }, {title: "fab fa-blogger-b", searchTerms: []}, {
            title: "fab fa-bluetooth",
            searchTerms: []
        }, {title: "fab fa-bluetooth-b", searchTerms: []}, {
            title: "fas fa-bold",
            searchTerms: []
        }, {title: "fas fa-bolt", searchTerms: ["electricity", "lightning", "weather", "zap"]}, {
            title: "fas fa-bomb",
            searchTerms: []
        }, {title: "fas fa-bone", searchTerms: []}, {
            title: "fas fa-bong",
            searchTerms: ["aparatus", "cannabis", "marijuana", "pipe", "smoke", "smoking"]
        }, {title: "fas fa-book", searchTerms: ["documentation", "read"]}, {
            title: "fas fa-book-dead",
            searchTerms: ["Dungeons & Dragons", "crossbones", "d&d", "dark arts", "death", "dnd", "documentation", "evil", "fantasy", "halloween", "holiday", "read", "skull", "spell"]
        }, {
            title: "fas fa-book-open",
            searchTerms: ["flyer", "notebook", "open book", "pamphlet", "reading"]
        }, {title: "fas fa-book-reader", searchTerms: ["library"]}, {
            title: "fas fa-bookmark",
            searchTerms: ["save"]
        }, {title: "far fa-bookmark", searchTerms: ["save"]}, {
            title: "fas fa-bowling-ball",
            searchTerms: []
        }, {title: "fas fa-box", searchTerms: ["package"]}, {
            title: "fas fa-box-open",
            searchTerms: []
        }, {title: "fas fa-boxes", searchTerms: []}, {title: "fas fa-braille", searchTerms: []}, {
            title: "fas fa-brain",
            searchTerms: ["cerebellum", "gray matter", "intellect", "medulla oblongata", "mind", "noodle", "wit"]
        }, {
            title: "fas fa-briefcase",
            searchTerms: ["bag", "business", "luggage", "office", "work"]
        }, {title: "fas fa-briefcase-medical", searchTerms: ["health briefcase"]}, {
            title: "fas fa-broadcast-tower",
            searchTerms: ["airwaves", "radio", "waves"]
        }, {
            title: "fas fa-broom",
            searchTerms: ["clean", "firebolt", "fly", "halloween", "holiday", "nimbus 2000", "quidditch", "sweep", "witch"]
        }, {title: "fas fa-brush", searchTerms: ["bristles", "color", "handle", "painting"]}, {
            title: "fab fa-btc",
            searchTerms: []
        }, {title: "fas fa-bug", searchTerms: ["insect", "report"]}, {
            title: "fas fa-building",
            searchTerms: ["apartment", "business", "company", "office", "work"]
        }, {
            title: "far fa-building",
            searchTerms: ["apartment", "business", "company", "office", "work"]
        }, {
            title: "fas fa-bullhorn",
            searchTerms: ["announcement", "broadcast", "louder", "megaphone", "share"]
        }, {title: "fas fa-bullseye", searchTerms: ["target"]}, {
            title: "fas fa-burn",
            searchTerms: ["energy"]
        }, {title: "fab fa-buromobelexperte", searchTerms: []}, {
            title: "fas fa-bus",
            searchTerms: ["machine", "public transportation", "transportation", "vehicle"]
        }, {
            title: "fas fa-bus-alt",
            searchTerms: ["machine", "public transportation", "transportation", "vehicle"]
        }, {
            title: "fas fa-business-time",
            searchTerms: ["briefcase", "business socks", "clock", "flight of the conchords", "wednesday"]
        }, {title: "fab fa-buysellads", searchTerms: []}, {
            title: "fas fa-calculator",
            searchTerms: []
        }, {
            title: "fas fa-calendar",
            searchTerms: ["calendar-o", "date", "event", "schedule", "time", "when"]
        }, {
            title: "far fa-calendar",
            searchTerms: ["calendar-o", "date", "event", "schedule", "time", "when"]
        }, {
            title: "fas fa-calendar-alt",
            searchTerms: ["calendar", "date", "event", "schedule", "time", "when"]
        }, {
            title: "far fa-calendar-alt",
            searchTerms: ["calendar", "date", "event", "schedule", "time", "when"]
        }, {
            title: "fas fa-calendar-check",
            searchTerms: ["accept", "agree", "appointment", "confirm", "correct", "done", "ok", "select", "success", "todo"]
        }, {
            title: "far fa-calendar-check",
            searchTerms: ["accept", "agree", "appointment", "confirm", "correct", "done", "ok", "select", "success", "todo"]
        }, {
            title: "fas fa-calendar-minus",
            searchTerms: ["delete", "negative", "remove"]
        }, {
            title: "far fa-calendar-minus",
            searchTerms: ["delete", "negative", "remove"]
        }, {
            title: "fas fa-calendar-plus",
            searchTerms: ["add", "create", "new", "positive"]
        }, {
            title: "far fa-calendar-plus",
            searchTerms: ["add", "create", "new", "positive"]
        }, {
            title: "fas fa-calendar-times",
            searchTerms: ["archive", "delete", "remove", "x"]
        }, {title: "far fa-calendar-times", searchTerms: ["archive", "delete", "remove", "x"]}, {
            title: "fas fa-camera",
            searchTerms: ["photo", "picture", "record"]
        }, {title: "fas fa-camera-retro", searchTerms: ["photo", "picture", "record"]}, {
            title: "fas fa-campground",
            searchTerms: ["camping", "fall", "outdoors", "seasonal", "tent"]
        }, {
            title: "fas fa-cannabis",
            searchTerms: ["bud", "chronic", "drugs", "endica", "endo", "ganja", "marijuana", "mary jane", "pot", "reefer", "sativa", "spliff", "weed", "whacky-tabacky"]
        }, {title: "fas fa-capsules", searchTerms: ["drugs", "medicine"]}, {
            title: "fas fa-car",
            searchTerms: ["machine", "transportation", "vehicle"]
        }, {title: "fas fa-car-alt", searchTerms: []}, {
            title: "fas fa-car-battery",
            searchTerms: []
        }, {title: "fas fa-car-crash", searchTerms: []}, {
            title: "fas fa-car-side",
            searchTerms: []
        }, {
            title: "fas fa-caret-down",
            searchTerms: ["arrow", "dropdown", "menu", "more", "triangle down"]
        }, {
            title: "fas fa-caret-left",
            searchTerms: ["arrow", "back", "previous", "triangle left"]
        }, {
            title: "fas fa-caret-right",
            searchTerms: ["arrow", "forward", "next", "triangle right"]
        }, {
            title: "fas fa-caret-square-down",
            searchTerms: ["caret-square-o-down", "dropdown", "menu", "more"]
        }, {
            title: "far fa-caret-square-down",
            searchTerms: ["caret-square-o-down", "dropdown", "menu", "more"]
        }, {
            title: "fas fa-caret-square-left",
            searchTerms: ["back", "caret-square-o-left", "previous"]
        }, {
            title: "far fa-caret-square-left",
            searchTerms: ["back", "caret-square-o-left", "previous"]
        }, {
            title: "fas fa-caret-square-right",
            searchTerms: ["caret-square-o-right", "forward", "next"]
        }, {
            title: "far fa-caret-square-right",
            searchTerms: ["caret-square-o-right", "forward", "next"]
        }, {title: "fas fa-caret-square-up", searchTerms: ["caret-square-o-up"]}, {
            title: "far fa-caret-square-up",
            searchTerms: ["caret-square-o-up"]
        }, {title: "fas fa-caret-up", searchTerms: ["arrow", "triangle up"]}, {
            title: "fas fa-cart-arrow-down",
            searchTerms: ["shopping"]
        }, {
            title: "fas fa-cart-plus",
            searchTerms: ["add", "create", "new", "positive", "shopping"]
        }, {
            title: "fas fa-cat",
            searchTerms: ["feline", "halloween", "holiday", "kitten", "kitty", "meow", "pet"]
        }, {title: "fab fa-cc-amazon-pay", searchTerms: []}, {
            title: "fab fa-cc-amex",
            searchTerms: ["amex"]
        }, {title: "fab fa-cc-apple-pay", searchTerms: []}, {
            title: "fab fa-cc-diners-club",
            searchTerms: []
        }, {title: "fab fa-cc-discover", searchTerms: []}, {
            title: "fab fa-cc-jcb",
            searchTerms: []
        }, {title: "fab fa-cc-mastercard", searchTerms: []}, {
            title: "fab fa-cc-paypal",
            searchTerms: []
        }, {title: "fab fa-cc-stripe", searchTerms: []}, {
            title: "fab fa-cc-visa",
            searchTerms: []
        }, {title: "fab fa-centercode", searchTerms: []}, {
            title: "fas fa-certificate",
            searchTerms: ["badge", "star"]
        }, {title: "fas fa-chair", searchTerms: ["furniture", "seat"]}, {
            title: "fas fa-chalkboard",
            searchTerms: ["blackboard", "learning", "school", "teaching", "whiteboard", "writing"]
        }, {
            title: "fas fa-chalkboard-teacher",
            searchTerms: ["blackboard", "instructor", "learning", "professor", "school", "whiteboard", "writing"]
        }, {title: "fas fa-charging-station", searchTerms: []}, {
            title: "fas fa-chart-area",
            searchTerms: ["analytics", "area-chart", "graph"]
        }, {title: "fas fa-chart-bar", searchTerms: ["analytics", "bar-chart", "graph"]}, {
            title: "far fa-chart-bar",
            searchTerms: ["analytics", "bar-chart", "graph"]
        }, {
            title: "fas fa-chart-line",
            searchTerms: ["activity", "analytics", "dashboard", "graph", "line-chart"]
        }, {title: "fas fa-chart-pie", searchTerms: ["analytics", "graph", "pie-chart"]}, {
            title: "fas fa-check",
            searchTerms: ["accept", "agree", "checkmark", "confirm", "correct", "done", "notice", "notification", "notify", "ok", "select", "success", "tick", "todo", "yes"]
        }, {
            title: "fas fa-check-circle",
            searchTerms: ["accept", "agree", "confirm", "correct", "done", "ok", "select", "success", "todo", "yes"]
        }, {
            title: "far fa-check-circle",
            searchTerms: ["accept", "agree", "confirm", "correct", "done", "ok", "select", "success", "todo", "yes"]
        }, {
            title: "fas fa-check-double",
            searchTerms: ["accept", "agree", "checkmark", "confirm", "correct", "done", "notice", "notification", "notify", "ok", "select", "success", "tick", "todo"]
        }, {
            title: "fas fa-check-square",
            searchTerms: ["accept", "agree", "checkmark", "confirm", "correct", "done", "ok", "select", "success", "todo", "yes"]
        }, {
            title: "far fa-check-square",
            searchTerms: ["accept", "agree", "checkmark", "confirm", "correct", "done", "ok", "select", "success", "todo", "yes"]
        }, {title: "fas fa-chess", searchTerms: []}, {
            title: "fas fa-chess-bishop",
            searchTerms: []
        }, {title: "fas fa-chess-board", searchTerms: []}, {
            title: "fas fa-chess-king",
            searchTerms: []
        }, {title: "fas fa-chess-knight", searchTerms: []}, {
            title: "fas fa-chess-pawn",
            searchTerms: []
        }, {title: "fas fa-chess-queen", searchTerms: []}, {
            title: "fas fa-chess-rook",
            searchTerms: []
        }, {
            title: "fas fa-chevron-circle-down",
            searchTerms: ["arrow", "dropdown", "menu", "more"]
        }, {
            title: "fas fa-chevron-circle-left",
            searchTerms: ["arrow", "back", "previous"]
        }, {
            title: "fas fa-chevron-circle-right",
            searchTerms: ["arrow", "forward", "next"]
        }, {title: "fas fa-chevron-circle-up", searchTerms: ["arrow"]}, {
            title: "fas fa-chevron-down",
            searchTerms: []
        }, {title: "fas fa-chevron-left", searchTerms: ["back", "bracket", "previous"]}, {
            title: "fas fa-chevron-right",
            searchTerms: ["bracket", "forward", "next"]
        }, {title: "fas fa-chevron-up", searchTerms: []}, {
            title: "fas fa-child",
            searchTerms: []
        }, {title: "fab fa-chrome", searchTerms: ["browser"]}, {
            title: "fas fa-church",
            searchTerms: ["building", "community", "religion"]
        }, {title: "fas fa-circle", searchTerms: ["circle-thin", "dot", "notification"]}, {
            title: "far fa-circle",
            searchTerms: ["circle-thin", "dot", "notification"]
        }, {title: "fas fa-circle-notch", searchTerms: ["circle-o-notch"]}, {
            title: "fas fa-city",
            searchTerms: ["buildings", "busy", "skyscrapers", "urban", "windows"]
        }, {title: "fas fa-clipboard", searchTerms: ["paste"]}, {
            title: "far fa-clipboard",
            searchTerms: ["paste"]
        }, {
            title: "fas fa-clipboard-check",
            searchTerms: ["accept", "agree", "confirm", "done", "ok", "select", "success", "todo", "yes"]
        }, {
            title: "fas fa-clipboard-list",
            searchTerms: ["checklist", "completed", "done", "finished", "intinerary", "ol", "schedule", "todo", "ul"]
        }, {
            title: "fas fa-clock",
            searchTerms: ["date", "late", "schedule", "timer", "timestamp", "watch"]
        }, {
            title: "far fa-clock",
            searchTerms: ["date", "late", "schedule", "timer", "timestamp", "watch"]
        }, {title: "fas fa-clone", searchTerms: ["copy", "duplicate"]}, {
            title: "far fa-clone",
            searchTerms: ["copy", "duplicate"]
        }, {title: "fas fa-closed-captioning", searchTerms: ["cc"]}, {
            title: "far fa-closed-captioning",
            searchTerms: ["cc"]
        }, {title: "fas fa-cloud", searchTerms: ["save"]}, {
            title: "fas fa-cloud-download-alt",
            searchTerms: ["import"]
        }, {title: "fas fa-cloud-meatball", searchTerms: []}, {
            title: "fas fa-cloud-moon",
            searchTerms: ["crescent", "evening", "halloween", "holiday", "lunar", "night", "sky"]
        }, {title: "fas fa-cloud-moon-rain", searchTerms: []}, {
            title: "fas fa-cloud-rain",
            searchTerms: ["precipitation"]
        }, {
            title: "fas fa-cloud-showers-heavy",
            searchTerms: ["precipitation", "rain", "storm"]
        }, {
            title: "fas fa-cloud-sun",
            searchTerms: ["day", "daytime", "fall", "outdoors", "seasonal"]
        }, {title: "fas fa-cloud-sun-rain", searchTerms: []}, {
            title: "fas fa-cloud-upload-alt",
            searchTerms: ["cloud-upload"]
        }, {title: "fab fa-cloudscale", searchTerms: []}, {
            title: "fab fa-cloudsmith",
            searchTerms: []
        }, {title: "fab fa-cloudversify", searchTerms: []}, {
            title: "fas fa-cocktail",
            searchTerms: ["alcohol", "beverage", "drink"]
        }, {title: "fas fa-code", searchTerms: ["brackets", "html"]}, {
            title: "fas fa-code-branch",
            searchTerms: ["branch", "code-fork", "fork", "git", "github", "rebase", "svn", "vcs", "version"]
        }, {title: "fab fa-codepen", searchTerms: []}, {
            title: "fab fa-codiepie",
            searchTerms: []
        }, {
            title: "fas fa-coffee",
            searchTerms: ["beverage", "breakfast", "cafe", "drink", "fall", "morning", "mug", "seasonal", "tea"]
        }, {title: "fas fa-cog", searchTerms: ["settings"]}, {
            title: "fas fa-cogs",
            searchTerms: ["gears", "settings"]
        }, {title: "fas fa-coins", searchTerms: []}, {
            title: "fas fa-columns",
            searchTerms: ["dashboard", "panes", "split"]
        }, {
            title: "fas fa-comment",
            searchTerms: ["bubble", "chat", "conversation", "feedback", "message", "note", "notification", "sms", "speech", "texting"]
        }, {
            title: "far fa-comment",
            searchTerms: ["bubble", "chat", "conversation", "feedback", "message", "note", "notification", "sms", "speech", "texting"]
        }, {
            title: "fas fa-comment-alt",
            searchTerms: ["bubble", "chat", "commenting", "conversation", "feedback", "message", "note", "notification", "sms", "speech", "texting"]
        }, {
            title: "far fa-comment-alt",
            searchTerms: ["bubble", "chat", "commenting", "conversation", "feedback", "message", "note", "notification", "sms", "speech", "texting"]
        }, {title: "fas fa-comment-dollar", searchTerms: []}, {
            title: "fas fa-comment-dots",
            searchTerms: []
        }, {title: "far fa-comment-dots", searchTerms: []}, {
            title: "fas fa-comment-slash",
            searchTerms: []
        }, {
            title: "fas fa-comments",
            searchTerms: ["bubble", "chat", "conversation", "feedback", "message", "note", "notification", "sms", "speech", "texting"]
        }, {
            title: "far fa-comments",
            searchTerms: ["bubble", "chat", "conversation", "feedback", "message", "note", "notification", "sms", "speech", "texting"]
        }, {title: "fas fa-comments-dollar", searchTerms: []}, {
            title: "fas fa-compact-disc",
            searchTerms: ["bluray", "cd", "disc", "media"]
        }, {
            title: "fas fa-compass",
            searchTerms: ["directory", "location", "menu", "safari"]
        }, {
            title: "far fa-compass",
            searchTerms: ["directory", "location", "menu", "safari"]
        }, {
            title: "fas fa-compress",
            searchTerms: ["collapse", "combine", "contract", "merge", "smaller"]
        }, {
            title: "fas fa-concierge-bell",
            searchTerms: ["attention", "hotel", "service", "support"]
        }, {title: "fab fa-connectdevelop", searchTerms: []}, {
            title: "fab fa-contao",
            searchTerms: []
        }, {
            title: "fas fa-cookie",
            searchTerms: ["baked good", "chips", "food", "snack", "sweet", "treat"]
        }, {
            title: "fas fa-cookie-bite",
            searchTerms: ["baked good", "bitten", "chips", "eating", "food", "snack", "sweet", "treat"]
        }, {title: "fas fa-copy", searchTerms: ["clone", "duplicate", "file", "files-o"]}, {
            title: "far fa-copy",
            searchTerms: ["clone", "duplicate", "file", "files-o"]
        }, {title: "fas fa-copyright", searchTerms: []}, {
            title: "far fa-copyright",
            searchTerms: []
        }, {title: "fas fa-couch", searchTerms: ["furniture", "sofa"]}, {
            title: "fab fa-cpanel",
            searchTerms: []
        }, {title: "fab fa-creative-commons", searchTerms: []}, {
            title: "fab fa-creative-commons-by",
            searchTerms: []
        }, {title: "fab fa-creative-commons-nc", searchTerms: []}, {
            title: "fab fa-creative-commons-nc-eu",
            searchTerms: []
        }, {title: "fab fa-creative-commons-nc-jp", searchTerms: []}, {
            title: "fab fa-creative-commons-nd",
            searchTerms: []
        }, {title: "fab fa-creative-commons-pd", searchTerms: []}, {
            title: "fab fa-creative-commons-pd-alt",
            searchTerms: []
        }, {title: "fab fa-creative-commons-remix", searchTerms: []}, {
            title: "fab fa-creative-commons-sa",
            searchTerms: []
        }, {
            title: "fab fa-creative-commons-sampling",
            searchTerms: []
        }, {title: "fab fa-creative-commons-sampling-plus", searchTerms: []}, {
            title: "fab fa-creative-commons-share",
            searchTerms: []
        }, {title: "fab fa-creative-commons-zero", searchTerms: []}, {
            title: "fas fa-credit-card",
            searchTerms: ["buy", "checkout", "credit-card-alt", "debit", "money", "payment", "purchase"]
        }, {
            title: "far fa-credit-card",
            searchTerms: ["buy", "checkout", "credit-card-alt", "debit", "money", "payment", "purchase"]
        }, {
            title: "fab fa-critical-role",
            searchTerms: ["Dungeons & Dragons", "d&d", "dnd", "fantasy", "game", "gaming", "tabletop"]
        }, {title: "fas fa-crop", searchTerms: ["design"]}, {
            title: "fas fa-crop-alt",
            searchTerms: []
        }, {title: "fas fa-cross", searchTerms: ["catholicism", "christianity"]}, {
            title: "fas fa-crosshairs",
            searchTerms: ["gpd", "picker", "position"]
        }, {
            title: "fas fa-crow",
            searchTerms: ["bird", "bullfrog", "fauna", "halloween", "holiday", "toad"]
        }, {title: "fas fa-crown", searchTerms: []}, {
            title: "fab fa-css3",
            searchTerms: ["code"]
        }, {title: "fab fa-css3-alt", searchTerms: []}, {
            title: "fas fa-cube",
            searchTerms: ["package"]
        }, {title: "fas fa-cubes", searchTerms: ["packages"]}, {
            title: "fas fa-cut",
            searchTerms: ["scissors"]
        }, {title: "fab fa-cuttlefish", searchTerms: []}, {
            title: "fab fa-d-and-d",
            searchTerms: []
        }, {
            title: "fab fa-d-and-d-beyond",
            searchTerms: ["Dungeons & Dragons", "d&d", "dnd", "fantasy", "gaming", "tabletop"]
        }, {title: "fab fa-dashcube", searchTerms: []}, {
            title: "fas fa-database",
            searchTerms: []
        }, {title: "fas fa-deaf", searchTerms: []}, {
            title: "fab fa-delicious",
            searchTerms: []
        }, {
            title: "fas fa-democrat",
            searchTerms: ["american", "democratic party", "donkey", "election", "left", "left-wing", "liberal", "politics", "usa"]
        }, {title: "fab fa-deploydog", searchTerms: []}, {
            title: "fab fa-deskpro",
            searchTerms: []
        }, {
            title: "fas fa-desktop",
            searchTerms: ["computer", "cpu", "demo", "desktop", "device", "machine", "monitor", "pc", "screen"]
        }, {title: "fab fa-dev", searchTerms: []}, {
            title: "fab fa-deviantart",
            searchTerms: []
        }, {
            title: "fas fa-dharmachakra",
            searchTerms: ["buddhism", "buddhist", "wheel of dharma"]
        }, {title: "fas fa-diagnoses", searchTerms: []}, {
            title: "fas fa-dice",
            searchTerms: ["chance", "gambling", "game", "roll"]
        }, {
            title: "fas fa-dice-d20",
            searchTerms: ["Dungeons & Dragons", "chance", "d&d", "dnd", "fantasy", "gambling", "game", "roll"]
        }, {
            title: "fas fa-dice-d6",
            searchTerms: ["Dungeons & Dragons", "chance", "d&d", "dnd", "fantasy", "gambling", "game", "roll"]
        }, {title: "fas fa-dice-five", searchTerms: ["chance", "gambling", "game", "roll"]}, {
            title: "fas fa-dice-four",
            searchTerms: ["chance", "gambling", "game", "roll"]
        }, {title: "fas fa-dice-one", searchTerms: ["chance", "gambling", "game", "roll"]}, {
            title: "fas fa-dice-six",
            searchTerms: ["chance", "gambling", "game", "roll"]
        }, {title: "fas fa-dice-three", searchTerms: ["chance", "gambling", "game", "roll"]}, {
            title: "fas fa-dice-two",
            searchTerms: ["chance", "gambling", "game", "roll"]
        }, {title: "fab fa-digg", searchTerms: []}, {
            title: "fab fa-digital-ocean",
            searchTerms: []
        }, {title: "fas fa-digital-tachograph", searchTerms: []}, {
            title: "fas fa-directions",
            searchTerms: []
        }, {title: "fab fa-discord", searchTerms: []}, {
            title: "fab fa-discourse",
            searchTerms: []
        }, {title: "fas fa-divide", searchTerms: []}, {
            title: "fas fa-dizzy",
            searchTerms: ["dazed", "disapprove", "emoticon", "face"]
        }, {title: "far fa-dizzy", searchTerms: ["dazed", "disapprove", "emoticon", "face"]}, {
            title: "fas fa-dna",
            searchTerms: ["double helix", "helix"]
        }, {title: "fab fa-dochub", searchTerms: []}, {title: "fab fa-docker", searchTerms: []}, {
            title: "fas fa-dog",
            searchTerms: ["canine", "fauna", "mammmal", "pet", "pooch", "puppy", "woof"]
        }, {
            title: "fas fa-dollar-sign",
            searchTerms: ["$", "dollar-sign", "money", "price", "usd"]
        }, {title: "fas fa-dolly", searchTerms: []}, {
            title: "fas fa-dolly-flatbed",
            searchTerms: []
        }, {title: "fas fa-donate", searchTerms: ["generosity", "give"]}, {
            title: "fas fa-door-closed",
            searchTerms: []
        }, {title: "fas fa-door-open", searchTerms: []}, {
            title: "fas fa-dot-circle",
            searchTerms: ["bullseye", "notification", "target"]
        }, {title: "far fa-dot-circle", searchTerms: ["bullseye", "notification", "target"]}, {
            title: "fas fa-dove",
            searchTerms: ["bird", "fauna", "flying", "peace"]
        }, {title: "fas fa-download", searchTerms: ["import"]}, {
            title: "fab fa-draft2digital",
            searchTerms: []
        }, {
            title: "fas fa-drafting-compass",
            searchTerms: ["mechanical drawing", "plot", "plotting"]
        }, {
            title: "fas fa-dragon",
            searchTerms: ["Dungeons & Dragons", "d&d", "dnd", "fantasy"]
        }, {title: "fas fa-draw-polygon", searchTerms: []}, {
            title: "fab fa-dribbble",
            searchTerms: []
        }, {title: "fab fa-dribbble-square", searchTerms: []}, {
            title: "fab fa-dropbox",
            searchTerms: []
        }, {
            title: "fas fa-drum",
            searchTerms: ["instrument", "music", "percussion", "snare", "sound"]
        }, {
            title: "fas fa-drum-steelpan",
            searchTerms: ["calypso", "instrument", "music", "percussion", "reggae", "snare", "sound", "steel", "tropical"]
        }, {title: "fas fa-drumstick-bite", searchTerms: []}, {
            title: "fab fa-drupal",
            searchTerms: []
        }, {
            title: "fas fa-dumbbell",
            searchTerms: ["exercise", "gym", "strength", "weight", "weight-lifting"]
        }, {
            title: "fas fa-dungeon",
            searchTerms: ["Dungeons & Dragons", "d&d", "dnd", "door", "entrance", "fantasy", "gate"]
        }, {title: "fab fa-dyalog", searchTerms: []}, {
            title: "fab fa-earlybirds",
            searchTerms: []
        }, {title: "fab fa-ebay", searchTerms: []}, {
            title: "fab fa-edge",
            searchTerms: ["browser", "ie"]
        }, {title: "fas fa-edit", searchTerms: ["edit", "pen", "pencil", "update", "write"]}, {
            title: "far fa-edit",
            searchTerms: ["edit", "pen", "pencil", "update", "write"]
        }, {title: "fas fa-eject", searchTerms: []}, {
            title: "fab fa-elementor",
            searchTerms: []
        }, {
            title: "fas fa-ellipsis-h",
            searchTerms: ["dots", "drag", "kebab", "list", "menu", "nav", "navigation", "ol", "reorder", "settings", "ul"]
        }, {
            title: "fas fa-ellipsis-v",
            searchTerms: ["dots", "drag", "kebab", "list", "menu", "nav", "navigation", "ol", "reorder", "settings", "ul"]
        }, {title: "fab fa-ello", searchTerms: []}, {title: "fab fa-ember", searchTerms: []}, {
            title: "fab fa-empire",
            searchTerms: []
        }, {
            title: "fas fa-envelope",
            searchTerms: ["e-mail", "email", "letter", "mail", "message", "notification", "support"]
        }, {
            title: "far fa-envelope",
            searchTerms: ["e-mail", "email", "letter", "mail", "message", "notification", "support"]
        }, {
            title: "fas fa-envelope-open",
            searchTerms: ["e-mail", "email", "letter", "mail", "message", "notification", "support"]
        }, {
            title: "far fa-envelope-open",
            searchTerms: ["e-mail", "email", "letter", "mail", "message", "notification", "support"]
        }, {title: "fas fa-envelope-open-text", searchTerms: []}, {
            title: "fas fa-envelope-square",
            searchTerms: ["e-mail", "email", "letter", "mail", "message", "notification", "support"]
        }, {title: "fab fa-envira", searchTerms: ["leaf"]}, {
            title: "fas fa-equals",
            searchTerms: []
        }, {title: "fas fa-eraser", searchTerms: ["delete", "remove"]}, {
            title: "fab fa-erlang",
            searchTerms: []
        }, {title: "fab fa-ethereum", searchTerms: []}, {
            title: "fab fa-etsy",
            searchTerms: []
        }, {title: "fas fa-euro-sign", searchTerms: ["eur"]}, {
            title: "fas fa-exchange-alt",
            searchTerms: ["arrow", "arrows", "exchange", "reciprocate", "return", "swap", "transfer"]
        }, {
            title: "fas fa-exclamation",
            searchTerms: ["alert", "danger", "error", "important", "notice", "notification", "notify", "problem", "warning"]
        }, {
            title: "fas fa-exclamation-circle",
            searchTerms: ["alert", "danger", "error", "important", "notice", "notification", "notify", "problem", "warning"]
        }, {
            title: "fas fa-exclamation-triangle",
            searchTerms: ["alert", "danger", "error", "important", "notice", "notification", "notify", "problem", "warning"]
        }, {title: "fas fa-expand", searchTerms: ["bigger", "enlarge", "resize"]}, {
            title: "fas fa-expand-arrows-alt",
            searchTerms: ["arrows-alt", "bigger", "enlarge", "move", "resize"]
        }, {title: "fab fa-expeditedssl", searchTerms: []}, {
            title: "fas fa-external-link-alt",
            searchTerms: ["external-link", "new", "open"]
        }, {
            title: "fas fa-external-link-square-alt",
            searchTerms: ["external-link-square", "new", "open"]
        }, {
            title: "fas fa-eye",
            searchTerms: ["optic", "see", "seen", "show", "sight", "views", "visible"]
        }, {
            title: "far fa-eye",
            searchTerms: ["optic", "see", "seen", "show", "sight", "views", "visible"]
        }, {title: "fas fa-eye-dropper", searchTerms: ["eyedropper"]}, {
            title: "fas fa-eye-slash",
            searchTerms: ["blind", "hide", "show", "toggle", "unseen", "views", "visible", "visiblity"]
        }, {
            title: "far fa-eye-slash",
            searchTerms: ["blind", "hide", "show", "toggle", "unseen", "views", "visible", "visiblity"]
        }, {
            title: "fab fa-facebook",
            searchTerms: ["facebook-official", "social network"]
        }, {title: "fab fa-facebook-f", searchTerms: ["facebook"]}, {
            title: "fab fa-facebook-messenger",
            searchTerms: []
        }, {title: "fab fa-facebook-square", searchTerms: ["social network"]}, {
            title: "fab fa-fantasy-flight-games",
            searchTerms: ["Dungeons & Dragons", "d&d", "dnd", "fantasy", "game", "gaming", "tabletop"]
        }, {
            title: "fas fa-fast-backward",
            searchTerms: ["beginning", "first", "previous", "rewind", "start"]
        }, {title: "fas fa-fast-forward", searchTerms: ["end", "last", "next"]}, {
            title: "fas fa-fax",
            searchTerms: []
        }, {title: "fas fa-feather", searchTerms: ["bird", "light", "plucked", "quill"]}, {
            title: "fas fa-feather-alt",
            searchTerms: ["bird", "light", "plucked", "quill"]
        }, {
            title: "fas fa-female",
            searchTerms: ["human", "person", "profile", "user", "woman"]
        }, {
            title: "fas fa-fighter-jet",
            searchTerms: ["airplane", "fast", "fly", "goose", "maverick", "plane", "quick", "top gun", "transportation", "travel"]
        }, {title: "fas fa-file", searchTerms: ["document", "new", "page", "pdf", "resume"]}, {
            title: "far fa-file",
            searchTerms: ["document", "new", "page", "pdf", "resume"]
        }, {
            title: "fas fa-file-alt",
            searchTerms: ["document", "file-text", "invoice", "new", "page", "pdf"]
        }, {
            title: "far fa-file-alt",
            searchTerms: ["document", "file-text", "invoice", "new", "page", "pdf"]
        }, {
            title: "fas fa-file-archive",
            searchTerms: [".zip", "bundle", "compress", "compression", "download", "zip"]
        }, {
            title: "far fa-file-archive",
            searchTerms: [".zip", "bundle", "compress", "compression", "download", "zip"]
        }, {title: "fas fa-file-audio", searchTerms: []}, {
            title: "far fa-file-audio",
            searchTerms: []
        }, {title: "fas fa-file-code", searchTerms: []}, {
            title: "far fa-file-code",
            searchTerms: []
        }, {
            title: "fas fa-file-contract",
            searchTerms: ["agreement", "binding", "document", "legal", "signature"]
        }, {title: "fas fa-file-csv", searchTerms: ["spreadsheets"]}, {
            title: "fas fa-file-download",
            searchTerms: []
        }, {title: "fas fa-file-excel", searchTerms: []}, {
            title: "far fa-file-excel",
            searchTerms: []
        }, {title: "fas fa-file-export", searchTerms: []}, {
            title: "fas fa-file-image",
            searchTerms: []
        }, {title: "far fa-file-image", searchTerms: []}, {
            title: "fas fa-file-import",
            searchTerms: []
        }, {
            title: "fas fa-file-invoice",
            searchTerms: ["bill", "document", "receipt"]
        }, {
            title: "fas fa-file-invoice-dollar",
            searchTerms: ["$", "bill", "document", "dollar-sign", "money", "receipt", "usd"]
        }, {title: "fas fa-file-medical", searchTerms: []}, {
            title: "fas fa-file-medical-alt",
            searchTerms: []
        }, {title: "fas fa-file-pdf", searchTerms: []}, {
            title: "far fa-file-pdf",
            searchTerms: []
        }, {title: "fas fa-file-powerpoint", searchTerms: []}, {
            title: "far fa-file-powerpoint",
            searchTerms: []
        }, {
            title: "fas fa-file-prescription",
            searchTerms: ["drugs", "medical", "medicine", "rx"]
        }, {
            title: "fas fa-file-signature",
            searchTerms: ["John Hancock", "contract", "document", "name"]
        }, {title: "fas fa-file-upload", searchTerms: []}, {
            title: "fas fa-file-video",
            searchTerms: []
        }, {title: "far fa-file-video", searchTerms: []}, {
            title: "fas fa-file-word",
            searchTerms: []
        }, {title: "far fa-file-word", searchTerms: []}, {
            title: "fas fa-fill",
            searchTerms: ["bucket", "color", "paint", "paint bucket"]
        }, {
            title: "fas fa-fill-drip",
            searchTerms: ["bucket", "color", "drop", "paint", "paint bucket", "spill"]
        }, {title: "fas fa-film", searchTerms: ["movie"]}, {
            title: "fas fa-filter",
            searchTerms: ["funnel", "options"]
        }, {
            title: "fas fa-fingerprint",
            searchTerms: ["human", "id", "identification", "lock", "smudge", "touch", "unique", "unlock"]
        }, {
            title: "fas fa-fire",
            searchTerms: ["caliente", "flame", "heat", "hot", "popular"]
        }, {title: "fas fa-fire-extinguisher", searchTerms: []}, {
            title: "fab fa-firefox",
            searchTerms: ["browser"]
        }, {title: "fas fa-first-aid", searchTerms: []}, {
            title: "fab fa-first-order",
            searchTerms: []
        }, {title: "fab fa-first-order-alt", searchTerms: []}, {
            title: "fab fa-firstdraft",
            searchTerms: []
        }, {title: "fas fa-fish", searchTerms: ["fauna", "gold", "swimming"]}, {
            title: "fas fa-fist-raised",
            searchTerms: ["Dungeons & Dragons", "d&d", "dnd", "fantasy", "hand", "ki", "monk", "resist", "strength", "unarmed combat"]
        }, {
            title: "fas fa-flag",
            searchTerms: ["country", "notice", "notification", "notify", "pole", "report", "symbol"]
        }, {
            title: "far fa-flag",
            searchTerms: ["country", "notice", "notification", "notify", "pole", "report", "symbol"]
        }, {
            title: "fas fa-flag-checkered",
            searchTerms: ["notice", "notification", "notify", "pole", "racing", "report", "symbol"]
        }, {
            title: "fas fa-flag-usa",
            searchTerms: ["betsy ross", "country", "old glory", "stars", "stripes", "symbol"]
        }, {title: "fas fa-flask", searchTerms: ["beaker", "experimental", "labs", "science"]}, {
            title: "fab fa-flickr",
            searchTerms: []
        }, {title: "fab fa-flipboard", searchTerms: []}, {
            title: "fas fa-flushed",
            searchTerms: ["embarrassed", "emoticon", "face"]
        }, {title: "far fa-flushed", searchTerms: ["embarrassed", "emoticon", "face"]}, {
            title: "fab fa-fly",
            searchTerms: []
        }, {title: "fas fa-folder", searchTerms: []}, {
            title: "far fa-folder",
            searchTerms: []
        }, {
            title: "fas fa-folder-minus",
            searchTerms: ["archive", "delete", "negative", "remove"]
        }, {title: "fas fa-folder-open", searchTerms: []}, {
            title: "far fa-folder-open",
            searchTerms: []
        }, {title: "fas fa-folder-plus", searchTerms: ["add", "create", "new", "positive"]}, {
            title: "fas fa-font",
            searchTerms: ["text"]
        }, {title: "fab fa-font-awesome", searchTerms: ["meanpath"]}, {
            title: "fab fa-font-awesome-alt",
            searchTerms: []
        }, {title: "fab fa-font-awesome-flag", searchTerms: []}, {
            title: "far fa-font-awesome-logo-full",
            searchTerms: []
        }, {title: "fas fa-font-awesome-logo-full", searchTerms: []}, {
            title: "fab fa-font-awesome-logo-full",
            searchTerms: []
        }, {title: "fab fa-fonticons", searchTerms: []}, {
            title: "fab fa-fonticons-fi",
            searchTerms: []
        }, {title: "fas fa-football-ball", searchTerms: ["fall", "pigskin", "seasonal"]}, {
            title: "fab fa-fort-awesome",
            searchTerms: ["castle"]
        }, {title: "fab fa-fort-awesome-alt", searchTerms: ["castle"]}, {
            title: "fab fa-forumbee",
            searchTerms: []
        }, {title: "fas fa-forward", searchTerms: ["forward", "next"]}, {
            title: "fab fa-foursquare",
            searchTerms: []
        }, {title: "fab fa-free-code-camp", searchTerms: []}, {
            title: "fab fa-freebsd",
            searchTerms: []
        }, {
            title: "fas fa-frog",
            searchTerms: ["amphibian", "bullfrog", "fauna", "hop", "kermit", "kiss", "prince", "ribbit", "toad", "wart"]
        }, {
            title: "fas fa-frown",
            searchTerms: ["disapprove", "emoticon", "face", "rating", "sad"]
        }, {
            title: "far fa-frown",
            searchTerms: ["disapprove", "emoticon", "face", "rating", "sad"]
        }, {
            title: "fas fa-frown-open",
            searchTerms: ["disapprove", "emoticon", "face", "rating", "sad"]
        }, {
            title: "far fa-frown-open",
            searchTerms: ["disapprove", "emoticon", "face", "rating", "sad"]
        }, {title: "fab fa-fulcrum", searchTerms: []}, {
            title: "fas fa-funnel-dollar",
            searchTerms: []
        }, {title: "fas fa-futbol", searchTerms: ["ball", "football", "soccer"]}, {
            title: "far fa-futbol",
            searchTerms: ["ball", "football", "soccer"]
        }, {
            title: "fab fa-galactic-republic",
            searchTerms: ["politics", "star wars"]
        }, {title: "fab fa-galactic-senate", searchTerms: ["star wars"]}, {
            title: "fas fa-gamepad",
            searchTerms: ["controller"]
        }, {title: "fas fa-gas-pump", searchTerms: []}, {
            title: "fas fa-gavel",
            searchTerms: ["hammer", "judge", "lawyer", "opinion"]
        }, {title: "fas fa-gem", searchTerms: ["diamond"]}, {
            title: "far fa-gem",
            searchTerms: ["diamond"]
        }, {title: "fas fa-genderless", searchTerms: []}, {
            title: "fab fa-get-pocket",
            searchTerms: []
        }, {title: "fab fa-gg", searchTerms: []}, {title: "fab fa-gg-circle", searchTerms: []}, {
            title: "fas fa-ghost",
            searchTerms: ["apparition", "blinky", "clyde", "floating", "halloween", "holiday", "inky", "pinky", "spirit"]
        }, {
            title: "fas fa-gift",
            searchTerms: ["generosity", "giving", "party", "present", "wrapped"]
        }, {title: "fab fa-git", searchTerms: []}, {
            title: "fab fa-git-square",
            searchTerms: []
        }, {title: "fab fa-github", searchTerms: ["octocat"]}, {
            title: "fab fa-github-alt",
            searchTerms: ["octocat"]
        }, {title: "fab fa-github-square", searchTerms: ["octocat"]}, {
            title: "fab fa-gitkraken",
            searchTerms: []
        }, {title: "fab fa-gitlab", searchTerms: ["Axosoft"]}, {
            title: "fab fa-gitter",
            searchTerms: []
        }, {
            title: "fas fa-glass-martini",
            searchTerms: ["alcohol", "bar", "beverage", "drink", "glass", "liquor", "martini"]
        }, {title: "fas fa-glass-martini-alt", searchTerms: []}, {
            title: "fas fa-glasses",
            searchTerms: ["foureyes", "hipster", "nerd", "reading", "sight", "spectacles"]
        }, {title: "fab fa-glide", searchTerms: []}, {title: "fab fa-glide-g", searchTerms: []}, {
            title: "fas fa-globe",
            searchTerms: ["all", "coordinates", "country", "earth", "global", "gps", "language", "localize", "location", "map", "online", "place", "planet", "translate", "travel", "world"]
        }, {
            title: "fas fa-globe-africa",
            searchTerms: ["all", "country", "earth", "global", "gps", "language", "localize", "location", "map", "online", "place", "planet", "translate", "travel", "world"]
        }, {
            title: "fas fa-globe-americas",
            searchTerms: ["all", "country", "earth", "global", "gps", "language", "localize", "location", "map", "online", "place", "planet", "translate", "travel", "world"]
        }, {
            title: "fas fa-globe-asia",
            searchTerms: ["all", "country", "earth", "global", "gps", "language", "localize", "location", "map", "online", "place", "planet", "translate", "travel", "world"]
        }, {title: "fab fa-gofore", searchTerms: []}, {
            title: "fas fa-golf-ball",
            searchTerms: []
        }, {title: "fab fa-goodreads", searchTerms: []}, {
            title: "fab fa-goodreads-g",
            searchTerms: []
        }, {title: "fab fa-google", searchTerms: []}, {
            title: "fab fa-google-drive",
            searchTerms: []
        }, {title: "fab fa-google-play", searchTerms: []}, {
            title: "fab fa-google-plus",
            searchTerms: ["google-plus-circle", "google-plus-official"]
        }, {
            title: "fab fa-google-plus-g",
            searchTerms: ["google-plus", "social network"]
        }, {title: "fab fa-google-plus-square", searchTerms: ["social network"]}, {
            title: "fab fa-google-wallet",
            searchTerms: []
        }, {
            title: "fas fa-gopuram",
            searchTerms: ["building", "entrance", "hinduism", "temple", "tower"]
        }, {title: "fas fa-graduation-cap", searchTerms: ["learning", "school", "student"]}, {
            title: "fab fa-gratipay",
            searchTerms: ["favorite", "heart", "like", "love"]
        }, {title: "fab fa-grav", searchTerms: []}, {
            title: "fas fa-greater-than",
            searchTerms: []
        }, {title: "fas fa-greater-than-equal", searchTerms: []}, {
            title: "fas fa-grimace",
            searchTerms: ["cringe", "emoticon", "face"]
        }, {title: "far fa-grimace", searchTerms: ["cringe", "emoticon", "face"]}, {
            title: "fas fa-grin",
            searchTerms: ["emoticon", "face", "laugh", "smile"]
        }, {title: "far fa-grin", searchTerms: ["emoticon", "face", "laugh", "smile"]}, {
            title: "fas fa-grin-alt",
            searchTerms: ["emoticon", "face", "laugh", "smile"]
        }, {title: "far fa-grin-alt", searchTerms: ["emoticon", "face", "laugh", "smile"]}, {
            title: "fas fa-grin-beam",
            searchTerms: ["emoticon", "face", "laugh", "smile"]
        }, {
            title: "far fa-grin-beam",
            searchTerms: ["emoticon", "face", "laugh", "smile"]
        }, {
            title: "fas fa-grin-beam-sweat",
            searchTerms: ["emoticon", "face", "smile"]
        }, {title: "far fa-grin-beam-sweat", searchTerms: ["emoticon", "face", "smile"]}, {
            title: "fas fa-grin-hearts",
            searchTerms: ["emoticon", "face", "love", "smile"]
        }, {
            title: "far fa-grin-hearts",
            searchTerms: ["emoticon", "face", "love", "smile"]
        }, {
            title: "fas fa-grin-squint",
            searchTerms: ["emoticon", "face", "laugh", "smile"]
        }, {
            title: "far fa-grin-squint",
            searchTerms: ["emoticon", "face", "laugh", "smile"]
        }, {
            title: "fas fa-grin-squint-tears",
            searchTerms: ["emoticon", "face", "happy", "smile"]
        }, {
            title: "far fa-grin-squint-tears",
            searchTerms: ["emoticon", "face", "happy", "smile"]
        }, {title: "fas fa-grin-stars", searchTerms: ["emoticon", "face", "star-struck"]}, {
            title: "far fa-grin-stars",
            searchTerms: ["emoticon", "face", "star-struck"]
        }, {title: "fas fa-grin-tears", searchTerms: ["LOL", "emoticon", "face"]}, {
            title: "far fa-grin-tears",
            searchTerms: ["LOL", "emoticon", "face"]
        }, {title: "fas fa-grin-tongue", searchTerms: ["LOL", "emoticon", "face"]}, {
            title: "far fa-grin-tongue",
            searchTerms: ["LOL", "emoticon", "face"]
        }, {
            title: "fas fa-grin-tongue-squint",
            searchTerms: ["LOL", "emoticon", "face"]
        }, {
            title: "far fa-grin-tongue-squint",
            searchTerms: ["LOL", "emoticon", "face"]
        }, {
            title: "fas fa-grin-tongue-wink",
            searchTerms: ["LOL", "emoticon", "face"]
        }, {title: "far fa-grin-tongue-wink", searchTerms: ["LOL", "emoticon", "face"]}, {
            title: "fas fa-grin-wink",
            searchTerms: ["emoticon", "face", "flirt", "laugh", "smile"]
        }, {
            title: "far fa-grin-wink",
            searchTerms: ["emoticon", "face", "flirt", "laugh", "smile"]
        }, {
            title: "fas fa-grip-horizontal",
            searchTerms: ["affordance", "drag", "drop", "grab", "handle"]
        }, {
            title: "fas fa-grip-vertical",
            searchTerms: ["affordance", "drag", "drop", "grab", "handle"]
        }, {title: "fab fa-gripfire", searchTerms: []}, {title: "fab fa-grunt", searchTerms: []}, {
            title: "fab fa-gulp",
            searchTerms: []
        }, {title: "fas fa-h-square", searchTerms: ["hospital", "hotel"]}, {
            title: "fab fa-hacker-news",
            searchTerms: []
        }, {title: "fab fa-hacker-news-square", searchTerms: []}, {
            title: "fab fa-hackerrank",
            searchTerms: []
        }, {
            title: "fas fa-hammer",
            searchTerms: ["admin", "fix", "repair", "settings", "tool"]
        }, {
            title: "fas fa-hamsa",
            searchTerms: ["amulet", "christianity", "islam", "jewish", "judaism", "muslim", "protection"]
        }, {title: "fas fa-hand-holding", searchTerms: []}, {
            title: "fas fa-hand-holding-heart",
            searchTerms: []
        }, {
            title: "fas fa-hand-holding-usd",
            searchTerms: ["$", "dollar sign", "donation", "giving", "money", "price"]
        }, {title: "fas fa-hand-lizard", searchTerms: []}, {
            title: "far fa-hand-lizard",
            searchTerms: []
        }, {title: "fas fa-hand-paper", searchTerms: ["stop"]}, {
            title: "far fa-hand-paper",
            searchTerms: ["stop"]
        }, {title: "fas fa-hand-peace", searchTerms: []}, {
            title: "far fa-hand-peace",
            searchTerms: []
        }, {
            title: "fas fa-hand-point-down",
            searchTerms: ["finger", "hand-o-down", "point"]
        }, {
            title: "far fa-hand-point-down",
            searchTerms: ["finger", "hand-o-down", "point"]
        }, {
            title: "fas fa-hand-point-left",
            searchTerms: ["back", "finger", "hand-o-left", "left", "point", "previous"]
        }, {
            title: "far fa-hand-point-left",
            searchTerms: ["back", "finger", "hand-o-left", "left", "point", "previous"]
        }, {
            title: "fas fa-hand-point-right",
            searchTerms: ["finger", "forward", "hand-o-right", "next", "point", "right"]
        }, {
            title: "far fa-hand-point-right",
            searchTerms: ["finger", "forward", "hand-o-right", "next", "point", "right"]
        }, {
            title: "fas fa-hand-point-up",
            searchTerms: ["finger", "hand-o-up", "point"]
        }, {
            title: "far fa-hand-point-up",
            searchTerms: ["finger", "hand-o-up", "point"]
        }, {title: "fas fa-hand-pointer", searchTerms: ["select"]}, {
            title: "far fa-hand-pointer",
            searchTerms: ["select"]
        }, {title: "fas fa-hand-rock", searchTerms: []}, {
            title: "far fa-hand-rock",
            searchTerms: []
        }, {title: "fas fa-hand-scissors", searchTerms: []}, {
            title: "far fa-hand-scissors",
            searchTerms: []
        }, {title: "fas fa-hand-spock", searchTerms: []}, {
            title: "far fa-hand-spock",
            searchTerms: []
        }, {title: "fas fa-hands", searchTerms: []}, {
            title: "fas fa-hands-helping",
            searchTerms: ["aid", "assistance", "partnership", "volunteering"]
        }, {title: "fas fa-handshake", searchTerms: ["greeting", "partnership"]}, {
            title: "far fa-handshake",
            searchTerms: ["greeting", "partnership"]
        }, {
            title: "fas fa-hanukiah",
            searchTerms: ["candle", "hanukkah", "jewish", "judaism", "light"]
        }, {title: "fas fa-hashtag", searchTerms: []}, {
            title: "fas fa-hat-wizard",
            searchTerms: ["Dungeons & Dragons", "buckle", "cloth", "clothing", "d&d", "dnd", "fantasy", "halloween", "holiday", "mage", "magic", "pointy", "witch"]
        }, {title: "fas fa-haykal", searchTerms: ["bahai", "bahá'í", "star"]}, {
            title: "fas fa-hdd",
            searchTerms: ["cpu", "hard drive", "harddrive", "machine", "save", "storage"]
        }, {
            title: "far fa-hdd",
            searchTerms: ["cpu", "hard drive", "harddrive", "machine", "save", "storage"]
        }, {title: "fas fa-heading", searchTerms: ["header"]}, {
            title: "fas fa-headphones",
            searchTerms: ["audio", "listen", "music", "sound", "speaker"]
        }, {
            title: "fas fa-headphones-alt",
            searchTerms: ["audio", "listen", "music", "sound", "speaker"]
        }, {
            title: "fas fa-headset",
            searchTerms: ["audio", "gamer", "gaming", "listen", "live chat", "microphone", "shot caller", "sound", "support", "telemarketer"]
        }, {title: "fas fa-heart", searchTerms: ["favorite", "like", "love"]}, {
            title: "far fa-heart",
            searchTerms: ["favorite", "like", "love"]
        }, {title: "fas fa-heartbeat", searchTerms: ["ekg", "lifeline", "vital signs"]}, {
            title: "fas fa-helicopter",
            searchTerms: ["airwolf", "apache", "chopper", "flight", "fly"]
        }, {
            title: "fas fa-highlighter",
            searchTerms: ["edit", "marker", "sharpie", "update", "write"]
        }, {
            title: "fas fa-hiking",
            searchTerms: ["activity", "backpack", "fall", "fitness", "outdoors", "seasonal", "walking"]
        }, {title: "fas fa-hippo", searchTerms: ["fauna", "hungry", "mammmal"]}, {
            title: "fab fa-hips",
            searchTerms: []
        }, {title: "fab fa-hire-a-helper", searchTerms: []}, {
            title: "fas fa-history",
            searchTerms: []
        }, {title: "fas fa-hockey-puck", searchTerms: []}, {
            title: "fas fa-home",
            searchTerms: ["house", "main"]
        }, {title: "fab fa-hooli", searchTerms: []}, {
            title: "fab fa-hornbill",
            searchTerms: []
        }, {title: "fas fa-horse", searchTerms: ["equus", "fauna", "mammmal", "neigh"]}, {
            title: "fas fa-hospital",
            searchTerms: ["building", "emergency room", "medical center"]
        }, {
            title: "far fa-hospital",
            searchTerms: ["building", "emergency room", "medical center"]
        }, {
            title: "fas fa-hospital-alt",
            searchTerms: ["building", "emergency room", "medical center"]
        }, {title: "fas fa-hospital-symbol", searchTerms: []}, {
            title: "fas fa-hot-tub",
            searchTerms: []
        }, {title: "fas fa-hotel", searchTerms: ["building", "lodging"]}, {
            title: "fab fa-hotjar",
            searchTerms: []
        }, {title: "fas fa-hourglass", searchTerms: []}, {
            title: "far fa-hourglass",
            searchTerms: []
        }, {title: "fas fa-hourglass-end", searchTerms: []}, {
            title: "fas fa-hourglass-half",
            searchTerms: []
        }, {title: "fas fa-hourglass-start", searchTerms: []}, {
            title: "fas fa-house-damage",
            searchTerms: ["devastation", "home"]
        }, {title: "fab fa-houzz", searchTerms: []}, {
            title: "fas fa-hryvnia",
            searchTerms: ["money"]
        }, {title: "fab fa-html5", searchTerms: []}, {
            title: "fab fa-hubspot",
            searchTerms: []
        }, {title: "fas fa-i-cursor", searchTerms: []}, {
            title: "fas fa-id-badge",
            searchTerms: []
        }, {title: "far fa-id-badge", searchTerms: []}, {
            title: "fas fa-id-card",
            searchTerms: ["document", "identification", "issued"]
        }, {
            title: "far fa-id-card",
            searchTerms: ["document", "identification", "issued"]
        }, {title: "fas fa-id-card-alt", searchTerms: ["demographics"]}, {
            title: "fas fa-image",
            searchTerms: ["album", "photo", "picture"]
        }, {title: "far fa-image", searchTerms: ["album", "photo", "picture"]}, {
            title: "fas fa-images",
            searchTerms: ["album", "photo", "picture"]
        }, {title: "far fa-images", searchTerms: ["album", "photo", "picture"]}, {
            title: "fab fa-imdb",
            searchTerms: []
        }, {title: "fas fa-inbox", searchTerms: []}, {
            title: "fas fa-indent",
            searchTerms: []
        }, {title: "fas fa-industry", searchTerms: ["factory", "manufacturing"]}, {
            title: "fas fa-infinity",
            searchTerms: []
        }, {
            title: "fas fa-info",
            searchTerms: ["details", "help", "information", "more"]
        }, {
            title: "fas fa-info-circle",
            searchTerms: ["details", "help", "information", "more"]
        }, {title: "fab fa-instagram", searchTerms: []}, {
            title: "fab fa-internet-explorer",
            searchTerms: ["browser", "ie"]
        }, {title: "fab fa-ioxhost", searchTerms: []}, {
            title: "fas fa-italic",
            searchTerms: ["italics"]
        }, {title: "fab fa-itunes", searchTerms: []}, {
            title: "fab fa-itunes-note",
            searchTerms: []
        }, {title: "fab fa-java", searchTerms: []}, {
            title: "fas fa-jedi",
            searchTerms: ["star wars"]
        }, {title: "fab fa-jedi-order", searchTerms: ["star wars"]}, {
            title: "fab fa-jenkins",
            searchTerms: []
        }, {title: "fab fa-joget", searchTerms: []}, {
            title: "fas fa-joint",
            searchTerms: ["blunt", "cannabis", "doobie", "drugs", "marijuana", "roach", "smoke", "smoking", "spliff"]
        }, {title: "fab fa-joomla", searchTerms: []}, {
            title: "fas fa-journal-whills",
            searchTerms: ["book", "jedi", "star wars", "the force"]
        }, {title: "fab fa-js", searchTerms: []}, {
            title: "fab fa-js-square",
            searchTerms: []
        }, {title: "fab fa-jsfiddle", searchTerms: []}, {
            title: "fas fa-kaaba",
            searchTerms: ["building", "cube", "islam", "muslim"]
        }, {title: "fab fa-kaggle", searchTerms: []}, {
            title: "fas fa-key",
            searchTerms: ["password", "unlock"]
        }, {title: "fab fa-keybase", searchTerms: []}, {
            title: "fas fa-keyboard",
            searchTerms: ["input", "type"]
        }, {title: "far fa-keyboard", searchTerms: ["input", "type"]}, {
            title: "fab fa-keycdn",
            searchTerms: []
        }, {title: "fas fa-khanda", searchTerms: ["chakkar", "sikh", "sikhism", "sword"]}, {
            title: "fab fa-kickstarter",
            searchTerms: []
        }, {title: "fab fa-kickstarter-k", searchTerms: []}, {
            title: "fas fa-kiss",
            searchTerms: ["beso", "emoticon", "face", "love", "smooch"]
        }, {
            title: "far fa-kiss",
            searchTerms: ["beso", "emoticon", "face", "love", "smooch"]
        }, {
            title: "fas fa-kiss-beam",
            searchTerms: ["beso", "emoticon", "face", "love", "smooch"]
        }, {
            title: "far fa-kiss-beam",
            searchTerms: ["beso", "emoticon", "face", "love", "smooch"]
        }, {
            title: "fas fa-kiss-wink-heart",
            searchTerms: ["beso", "emoticon", "face", "love", "smooch"]
        }, {
            title: "far fa-kiss-wink-heart",
            searchTerms: ["beso", "emoticon", "face", "love", "smooch"]
        }, {title: "fas fa-kiwi-bird", searchTerms: ["bird", "fauna"]}, {
            title: "fab fa-korvue",
            searchTerms: []
        }, {
            title: "fas fa-landmark",
            searchTerms: ["building", "historic", "memoroable", "politics"]
        }, {
            title: "fas fa-language",
            searchTerms: ["dialect", "idiom", "localize", "speech", "translate", "vernacular"]
        }, {
            title: "fas fa-laptop",
            searchTerms: ["computer", "cpu", "dell", "demo", "device", "dude you're getting", "mac", "macbook", "machine", "pc"]
        }, {title: "fas fa-laptop-code", searchTerms: []}, {
            title: "fab fa-laravel",
            searchTerms: []
        }, {title: "fab fa-lastfm", searchTerms: []}, {
            title: "fab fa-lastfm-square",
            searchTerms: []
        }, {title: "fas fa-laugh", searchTerms: ["LOL", "emoticon", "face", "laugh"]}, {
            title: "far fa-laugh",
            searchTerms: ["LOL", "emoticon", "face", "laugh"]
        }, {title: "fas fa-laugh-beam", searchTerms: ["LOL", "emoticon", "face"]}, {
            title: "far fa-laugh-beam",
            searchTerms: ["LOL", "emoticon", "face"]
        }, {title: "fas fa-laugh-squint", searchTerms: ["LOL", "emoticon", "face"]}, {
            title: "far fa-laugh-squint",
            searchTerms: ["LOL", "emoticon", "face"]
        }, {title: "fas fa-laugh-wink", searchTerms: ["LOL", "emoticon", "face"]}, {
            title: "far fa-laugh-wink",
            searchTerms: ["LOL", "emoticon", "face"]
        }, {title: "fas fa-layer-group", searchTerms: ["layers"]}, {
            title: "fas fa-leaf",
            searchTerms: ["eco", "flora", "nature", "plant"]
        }, {title: "fab fa-leanpub", searchTerms: []}, {
            title: "fas fa-lemon",
            searchTerms: ["food"]
        }, {title: "far fa-lemon", searchTerms: ["food"]}, {
            title: "fab fa-less",
            searchTerms: []
        }, {title: "fas fa-less-than", searchTerms: []}, {
            title: "fas fa-less-than-equal",
            searchTerms: []
        }, {title: "fas fa-level-down-alt", searchTerms: ["level-down"]}, {
            title: "fas fa-level-up-alt",
            searchTerms: ["level-up"]
        }, {title: "fas fa-life-ring", searchTerms: ["support"]}, {
            title: "far fa-life-ring",
            searchTerms: ["support"]
        }, {title: "fas fa-lightbulb", searchTerms: ["idea", "inspiration"]}, {
            title: "far fa-lightbulb",
            searchTerms: ["idea", "inspiration"]
        }, {title: "fab fa-line", searchTerms: []}, {
            title: "fas fa-link",
            searchTerms: ["chain"]
        }, {title: "fab fa-linkedin", searchTerms: ["linkedin-square"]}, {
            title: "fab fa-linkedin-in",
            searchTerms: ["linkedin"]
        }, {title: "fab fa-linode", searchTerms: []}, {
            title: "fab fa-linux",
            searchTerms: ["tux"]
        }, {title: "fas fa-lira-sign", searchTerms: ["try", "turkish"]}, {
            title: "fas fa-list",
            searchTerms: ["checklist", "completed", "done", "finished", "ol", "todo", "ul"]
        }, {
            title: "fas fa-list-alt",
            searchTerms: ["checklist", "completed", "done", "finished", "ol", "todo", "ul"]
        }, {
            title: "far fa-list-alt",
            searchTerms: ["checklist", "completed", "done", "finished", "ol", "todo", "ul"]
        }, {
            title: "fas fa-list-ol",
            searchTerms: ["checklist", "list", "numbers", "ol", "todo", "ul"]
        }, {
            title: "fas fa-list-ul",
            searchTerms: ["checklist", "list", "ol", "todo", "ul"]
        }, {
            title: "fas fa-location-arrow",
            searchTerms: ["address", "coordinates", "gps", "location", "map", "place", "where"]
        }, {title: "fas fa-lock", searchTerms: ["admin", "protect", "security"]}, {
            title: "fas fa-lock-open",
            searchTerms: ["admin", "lock", "open", "password", "protect"]
        }, {
            title: "fas fa-long-arrow-alt-down",
            searchTerms: ["long-arrow-down"]
        }, {
            title: "fas fa-long-arrow-alt-left",
            searchTerms: ["back", "long-arrow-left", "previous"]
        }, {
            title: "fas fa-long-arrow-alt-right",
            searchTerms: ["long-arrow-right"]
        }, {title: "fas fa-long-arrow-alt-up", searchTerms: ["long-arrow-up"]}, {
            title: "fas fa-low-vision",
            searchTerms: []
        }, {title: "fas fa-luggage-cart", searchTerms: []}, {
            title: "fab fa-lyft",
            searchTerms: []
        }, {title: "fab fa-magento", searchTerms: []}, {
            title: "fas fa-magic",
            searchTerms: ["autocomplete", "automatic", "mage", "magic", "spell", "witch", "wizard"]
        }, {title: "fas fa-magnet", searchTerms: []}, {
            title: "fas fa-mail-bulk",
            searchTerms: []
        }, {title: "fab fa-mailchimp", searchTerms: []}, {
            title: "fas fa-male",
            searchTerms: ["human", "man", "person", "profile", "user"]
        }, {title: "fab fa-mandalorian", searchTerms: []}, {
            title: "fas fa-map",
            searchTerms: ["coordinates", "location", "paper", "place", "travel"]
        }, {
            title: "far fa-map",
            searchTerms: ["coordinates", "location", "paper", "place", "travel"]
        }, {
            title: "fas fa-map-marked",
            searchTerms: ["address", "coordinates", "destination", "gps", "localize", "location", "map", "paper", "pin", "place", "point of interest", "position", "route", "travel", "where"]
        }, {
            title: "fas fa-map-marked-alt",
            searchTerms: ["address", "coordinates", "destination", "gps", "localize", "location", "map", "paper", "pin", "place", "point of interest", "position", "route", "travel", "where"]
        }, {
            title: "fas fa-map-marker",
            searchTerms: ["address", "coordinates", "gps", "localize", "location", "map", "pin", "place", "position", "travel", "where"]
        }, {
            title: "fas fa-map-marker-alt",
            searchTerms: ["address", "coordinates", "gps", "localize", "location", "map", "pin", "place", "position", "travel", "where"]
        }, {
            title: "fas fa-map-pin",
            searchTerms: ["address", "coordinates", "gps", "localize", "location", "map", "marker", "place", "position", "travel", "where"]
        }, {title: "fas fa-map-signs", searchTerms: []}, {
            title: "fab fa-markdown",
            searchTerms: []
        }, {title: "fas fa-marker", searchTerms: ["edit", "sharpie", "update", "write"]}, {
            title: "fas fa-mars",
            searchTerms: ["male"]
        }, {title: "fas fa-mars-double", searchTerms: []}, {
            title: "fas fa-mars-stroke",
            searchTerms: []
        }, {title: "fas fa-mars-stroke-h", searchTerms: []}, {
            title: "fas fa-mars-stroke-v",
            searchTerms: []
        }, {
            title: "fas fa-mask",
            searchTerms: ["costume", "disguise", "halloween", "holiday", "secret", "super hero"]
        }, {title: "fab fa-mastodon", searchTerms: []}, {
            title: "fab fa-maxcdn",
            searchTerms: []
        }, {title: "fas fa-medal", searchTerms: []}, {
            title: "fab fa-medapps",
            searchTerms: []
        }, {title: "fab fa-medium", searchTerms: []}, {
            title: "fab fa-medium-m",
            searchTerms: []
        }, {
            title: "fas fa-medkit",
            searchTerms: ["first aid", "firstaid", "health", "help", "support"]
        }, {title: "fab fa-medrt", searchTerms: []}, {
            title: "fab fa-meetup",
            searchTerms: []
        }, {title: "fab fa-megaport", searchTerms: []}, {
            title: "fas fa-meh",
            searchTerms: ["emoticon", "face", "neutral", "rating"]
        }, {title: "far fa-meh", searchTerms: ["emoticon", "face", "neutral", "rating"]}, {
            title: "fas fa-meh-blank",
            searchTerms: ["emoticon", "face", "neutral", "rating"]
        }, {
            title: "far fa-meh-blank",
            searchTerms: ["emoticon", "face", "neutral", "rating"]
        }, {
            title: "fas fa-meh-rolling-eyes",
            searchTerms: ["emoticon", "face", "neutral", "rating"]
        }, {
            title: "far fa-meh-rolling-eyes",
            searchTerms: ["emoticon", "face", "neutral", "rating"]
        }, {title: "fas fa-memory", searchTerms: ["DIMM", "RAM"]}, {
            title: "fas fa-menorah",
            searchTerms: ["candle", "hanukkah", "jewish", "judaism", "light"]
        }, {title: "fas fa-mercury", searchTerms: ["transgender"]}, {
            title: "fas fa-meteor",
            searchTerms: []
        }, {title: "fas fa-microchip", searchTerms: ["cpu", "processor"]}, {
            title: "fas fa-microphone",
            searchTerms: ["record", "sound", "voice"]
        }, {
            title: "fas fa-microphone-alt",
            searchTerms: ["record", "sound", "voice"]
        }, {
            title: "fas fa-microphone-alt-slash",
            searchTerms: ["disable", "mute", "record", "sound", "voice"]
        }, {
            title: "fas fa-microphone-slash",
            searchTerms: ["disable", "mute", "record", "sound", "voice"]
        }, {title: "fas fa-microscope", searchTerms: []}, {
            title: "fab fa-microsoft",
            searchTerms: []
        }, {
            title: "fas fa-minus",
            searchTerms: ["collapse", "delete", "hide", "minify", "negative", "remove", "trash"]
        }, {
            title: "fas fa-minus-circle",
            searchTerms: ["delete", "hide", "negative", "remove", "trash"]
        }, {
            title: "fas fa-minus-square",
            searchTerms: ["collapse", "delete", "hide", "minify", "negative", "remove", "trash"]
        }, {
            title: "far fa-minus-square",
            searchTerms: ["collapse", "delete", "hide", "minify", "negative", "remove", "trash"]
        }, {title: "fab fa-mix", searchTerms: []}, {title: "fab fa-mixcloud", searchTerms: []}, {
            title: "fab fa-mizuni",
            searchTerms: []
        }, {
            title: "fas fa-mobile",
            searchTerms: ["apple", "call", "cell phone", "cellphone", "device", "iphone", "number", "screen", "telephone", "text"]
        }, {
            title: "fas fa-mobile-alt",
            searchTerms: ["apple", "call", "cell phone", "cellphone", "device", "iphone", "number", "screen", "telephone", "text"]
        }, {title: "fab fa-modx", searchTerms: []}, {
            title: "fab fa-monero",
            searchTerms: []
        }, {
            title: "fas fa-money-bill",
            searchTerms: ["buy", "cash", "checkout", "money", "payment", "price", "purchase"]
        }, {
            title: "fas fa-money-bill-alt",
            searchTerms: ["buy", "cash", "checkout", "money", "payment", "price", "purchase"]
        }, {
            title: "far fa-money-bill-alt",
            searchTerms: ["buy", "cash", "checkout", "money", "payment", "price", "purchase"]
        }, {title: "fas fa-money-bill-wave", searchTerms: []}, {
            title: "fas fa-money-bill-wave-alt",
            searchTerms: []
        }, {title: "fas fa-money-check", searchTerms: ["bank check", "cheque"]}, {
            title: "fas fa-money-check-alt",
            searchTerms: ["bank check", "cheque"]
        }, {title: "fas fa-monument", searchTerms: ["building", "historic", "memoroable"]}, {
            title: "fas fa-moon",
            searchTerms: ["contrast", "crescent", "darker", "lunar", "night"]
        }, {
            title: "far fa-moon",
            searchTerms: ["contrast", "crescent", "darker", "lunar", "night"]
        }, {
            title: "fas fa-mortar-pestle",
            searchTerms: ["crush", "culinary", "grind", "medical", "mix", "spices"]
        }, {title: "fas fa-mosque", searchTerms: ["building", "islam", "muslim"]}, {
            title: "fas fa-motorcycle",
            searchTerms: ["bike", "machine", "transportation", "vehicle"]
        }, {title: "fas fa-mountain", searchTerms: []}, {
            title: "fas fa-mouse-pointer",
            searchTerms: ["select"]
        }, {title: "fas fa-music", searchTerms: ["note", "sound"]}, {
            title: "fab fa-napster",
            searchTerms: []
        }, {title: "fab fa-neos", searchTerms: []}, {
            title: "fas fa-network-wired",
            searchTerms: []
        }, {title: "fas fa-neuter", searchTerms: []}, {
            title: "fas fa-newspaper",
            searchTerms: ["article", "press"]
        }, {title: "far fa-newspaper", searchTerms: ["article", "press"]}, {
            title: "fab fa-nimblr",
            searchTerms: []
        }, {title: "fab fa-nintendo-switch", searchTerms: []}, {
            title: "fab fa-node",
            searchTerms: []
        }, {title: "fab fa-node-js", searchTerms: []}, {
            title: "fas fa-not-equal",
            searchTerms: []
        }, {title: "fas fa-notes-medical", searchTerms: []}, {
            title: "fab fa-npm",
            searchTerms: []
        }, {title: "fab fa-ns8", searchTerms: []}, {
            title: "fab fa-nutritionix",
            searchTerms: []
        }, {title: "fas fa-object-group", searchTerms: ["design"]}, {
            title: "far fa-object-group",
            searchTerms: ["design"]
        }, {title: "fas fa-object-ungroup", searchTerms: ["design"]}, {
            title: "far fa-object-ungroup",
            searchTerms: ["design"]
        }, {title: "fab fa-odnoklassniki", searchTerms: []}, {
            title: "fab fa-odnoklassniki-square",
            searchTerms: []
        }, {title: "fas fa-oil-can", searchTerms: []}, {
            title: "fab fa-old-republic",
            searchTerms: ["politics", "star wars"]
        }, {title: "fas fa-om", searchTerms: ["buddhism", "hinduism", "jainism", "mantra"]}, {
            title: "fab fa-opencart",
            searchTerms: []
        }, {title: "fab fa-openid", searchTerms: []}, {
            title: "fab fa-opera",
            searchTerms: []
        }, {title: "fab fa-optin-monster", searchTerms: []}, {
            title: "fab fa-osi",
            searchTerms: []
        }, {title: "fas fa-otter", searchTerms: ["fauna", "mammmal"]}, {
            title: "fas fa-outdent",
            searchTerms: []
        }, {title: "fab fa-page4", searchTerms: []}, {
            title: "fab fa-pagelines",
            searchTerms: ["eco", "flora", "leaf", "leaves", "nature", "plant", "tree"]
        }, {title: "fas fa-paint-brush", searchTerms: []}, {
            title: "fas fa-paint-roller",
            searchTerms: ["brush", "painting", "tool"]
        }, {title: "fas fa-palette", searchTerms: ["colors", "painting"]}, {
            title: "fab fa-palfed",
            searchTerms: []
        }, {title: "fas fa-pallet", searchTerms: []}, {
            title: "fas fa-paper-plane",
            searchTerms: []
        }, {title: "far fa-paper-plane", searchTerms: []}, {
            title: "fas fa-paperclip",
            searchTerms: ["attachment"]
        }, {
            title: "fas fa-parachute-box",
            searchTerms: ["aid", "assistance", "rescue", "supplies"]
        }, {title: "fas fa-paragraph", searchTerms: []}, {
            title: "fas fa-parking",
            searchTerms: []
        }, {
            title: "fas fa-passport",
            searchTerms: ["document", "identification", "issued"]
        }, {
            title: "fas fa-pastafarianism",
            searchTerms: ["agnosticism", "atheism", "flying spaghetti monster", "fsm"]
        }, {title: "fas fa-paste", searchTerms: ["clipboard", "copy"]}, {
            title: "fab fa-patreon",
            searchTerms: []
        }, {title: "fas fa-pause", searchTerms: ["wait"]}, {
            title: "fas fa-pause-circle",
            searchTerms: []
        }, {title: "far fa-pause-circle", searchTerms: []}, {
            title: "fas fa-paw",
            searchTerms: ["animal", "pet"]
        }, {title: "fab fa-paypal", searchTerms: []}, {title: "fas fa-peace", searchTerms: []}, {
            title: "fas fa-pen",
            searchTerms: ["design", "edit", "update", "write"]
        }, {title: "fas fa-pen-alt", searchTerms: ["design", "edit", "update", "write"]}, {
            title: "fas fa-pen-fancy",
            searchTerms: ["design", "edit", "fountain pen", "update", "write"]
        }, {
            title: "fas fa-pen-nib",
            searchTerms: ["design", "edit", "fountain pen", "update", "write"]
        }, {
            title: "fas fa-pen-square",
            searchTerms: ["edit", "pencil-square", "update", "write"]
        }, {
            title: "fas fa-pencil-alt",
            searchTerms: ["design", "edit", "pencil", "update", "write"]
        }, {title: "fas fa-pencil-ruler", searchTerms: []}, {
            title: "fab fa-penny-arcade",
            searchTerms: ["Dungeons & Dragons", "d&d", "dnd", "fantasy", "game", "gaming", "pax", "tabletop"]
        }, {title: "fas fa-people-carry", searchTerms: ["movers"]}, {
            title: "fas fa-percent",
            searchTerms: []
        }, {title: "fas fa-percentage", searchTerms: []}, {
            title: "fab fa-periscope",
            searchTerms: []
        }, {
            title: "fas fa-person-booth",
            searchTerms: ["changing", "changing room", "election", "human", "person", "vote", "voting"]
        }, {title: "fab fa-phabricator", searchTerms: []}, {
            title: "fab fa-phoenix-framework",
            searchTerms: []
        }, {title: "fab fa-phoenix-squadron", searchTerms: []}, {
            title: "fas fa-phone",
            searchTerms: ["call", "earphone", "number", "support", "telephone", "voice"]
        }, {title: "fas fa-phone-slash", searchTerms: []}, {
            title: "fas fa-phone-square",
            searchTerms: ["call", "number", "support", "telephone", "voice"]
        }, {title: "fas fa-phone-volume", searchTerms: ["telephone", "volume-control-phone"]}, {
            title: "fab fa-php",
            searchTerms: []
        }, {title: "fab fa-pied-piper", searchTerms: []}, {
            title: "fab fa-pied-piper-alt",
            searchTerms: []
        }, {title: "fab fa-pied-piper-hat", searchTerms: ["clothing"]}, {
            title: "fab fa-pied-piper-pp",
            searchTerms: []
        }, {title: "fas fa-piggy-bank", searchTerms: ["save", "savings"]}, {
            title: "fas fa-pills",
            searchTerms: ["drugs", "medicine"]
        }, {title: "fab fa-pinterest", searchTerms: []}, {
            title: "fab fa-pinterest-p",
            searchTerms: []
        }, {title: "fab fa-pinterest-square", searchTerms: []}, {
            title: "fas fa-place-of-worship",
            searchTerms: []
        }, {
            title: "fas fa-plane",
            searchTerms: ["airplane", "destination", "fly", "location", "mode", "travel", "trip"]
        }, {
            title: "fas fa-plane-arrival",
            searchTerms: ["airplane", "arriving", "destination", "fly", "land", "landing", "location", "mode", "travel", "trip"]
        }, {
            title: "fas fa-plane-departure",
            searchTerms: ["airplane", "departing", "destination", "fly", "location", "mode", "take off", "taking off", "travel", "trip"]
        }, {title: "fas fa-play", searchTerms: ["music", "playing", "sound", "start"]}, {
            title: "fas fa-play-circle",
            searchTerms: ["playing", "start"]
        }, {title: "far fa-play-circle", searchTerms: ["playing", "start"]}, {
            title: "fab fa-playstation",
            searchTerms: []
        }, {title: "fas fa-plug", searchTerms: ["connect", "online", "power"]}, {
            title: "fas fa-plus",
            searchTerms: ["add", "create", "expand", "new", "positive"]
        }, {
            title: "fas fa-plus-circle",
            searchTerms: ["add", "create", "expand", "new", "positive"]
        }, {
            title: "fas fa-plus-square",
            searchTerms: ["add", "create", "expand", "new", "positive"]
        }, {
            title: "far fa-plus-square",
            searchTerms: ["add", "create", "expand", "new", "positive"]
        }, {title: "fas fa-podcast", searchTerms: []}, {
            title: "fas fa-poll",
            searchTerms: ["results", "survey", "vote", "voting"]
        }, {title: "fas fa-poll-h", searchTerms: ["results", "survey", "vote", "voting"]}, {
            title: "fas fa-poo",
            searchTerms: []
        }, {title: "fas fa-poo-storm", searchTerms: ["mess", "poop", "shit"]}, {
            title: "fas fa-poop",
            searchTerms: []
        }, {title: "fas fa-portrait", searchTerms: []}, {
            title: "fas fa-pound-sign",
            searchTerms: ["gbp"]
        }, {title: "fas fa-power-off", searchTerms: ["on", "reboot", "restart"]}, {
            title: "fas fa-pray",
            searchTerms: []
        }, {title: "fas fa-praying-hands", searchTerms: []}, {
            title: "fas fa-prescription",
            searchTerms: ["drugs", "medical", "medicine", "rx"]
        }, {
            title: "fas fa-prescription-bottle",
            searchTerms: ["drugs", "medical", "medicine", "rx"]
        }, {
            title: "fas fa-prescription-bottle-alt",
            searchTerms: ["drugs", "medical", "medicine", "rx"]
        }, {title: "fas fa-print", searchTerms: []}, {
            title: "fas fa-procedures",
            searchTerms: []
        }, {title: "fab fa-product-hunt", searchTerms: []}, {
            title: "fas fa-project-diagram",
            searchTerms: []
        }, {title: "fab fa-pushed", searchTerms: []}, {
            title: "fas fa-puzzle-piece",
            searchTerms: ["add-on", "addon", "section"]
        }, {title: "fab fa-python", searchTerms: []}, {title: "fab fa-qq", searchTerms: []}, {
            title: "fas fa-qrcode",
            searchTerms: ["scan"]
        }, {
            title: "fas fa-question",
            searchTerms: ["help", "information", "support", "unknown"]
        }, {
            title: "fas fa-question-circle",
            searchTerms: ["help", "information", "support", "unknown"]
        }, {
            title: "far fa-question-circle",
            searchTerms: ["help", "information", "support", "unknown"]
        }, {title: "fas fa-quidditch", searchTerms: []}, {
            title: "fab fa-quinscape",
            searchTerms: []
        }, {title: "fab fa-quora", searchTerms: []}, {
            title: "fas fa-quote-left",
            searchTerms: []
        }, {title: "fas fa-quote-right", searchTerms: []}, {
            title: "fas fa-quran",
            searchTerms: ["book", "islam", "muslim"]
        }, {title: "fab fa-r-project", searchTerms: []}, {
            title: "fas fa-rainbow",
            searchTerms: []
        }, {title: "fas fa-random", searchTerms: ["shuffle", "sort"]}, {
            title: "fab fa-ravelry",
            searchTerms: []
        }, {title: "fab fa-react", searchTerms: []}, {
            title: "fab fa-reacteurope",
            searchTerms: []
        }, {title: "fab fa-readme", searchTerms: []}, {
            title: "fab fa-rebel",
            searchTerms: []
        }, {title: "fas fa-receipt", searchTerms: ["check", "invoice", "table"]}, {
            title: "fas fa-recycle",
            searchTerms: []
        }, {title: "fab fa-red-river", searchTerms: []}, {
            title: "fab fa-reddit",
            searchTerms: []
        }, {title: "fab fa-reddit-alien", searchTerms: []}, {
            title: "fab fa-reddit-square",
            searchTerms: []
        }, {title: "fas fa-redo", searchTerms: ["forward", "refresh", "reload", "repeat"]}, {
            title: "fas fa-redo-alt",
            searchTerms: ["forward", "refresh", "reload", "repeat"]
        }, {title: "fas fa-registered", searchTerms: []}, {
            title: "far fa-registered",
            searchTerms: []
        }, {title: "fab fa-renren", searchTerms: []}, {
            title: "fas fa-reply",
            searchTerms: []
        }, {title: "fas fa-reply-all", searchTerms: []}, {
            title: "fab fa-replyd",
            searchTerms: []
        }, {
            title: "fas fa-republican",
            searchTerms: ["american", "conservative", "election", "elephant", "politics", "republican party", "right", "right-wing", "usa"]
        }, {title: "fab fa-researchgate", searchTerms: []}, {
            title: "fab fa-resolving",
            searchTerms: []
        }, {title: "fas fa-retweet", searchTerms: ["refresh", "reload", "share", "swap"]}, {
            title: "fab fa-rev",
            searchTerms: []
        }, {title: "fas fa-ribbon", searchTerms: ["badge", "cause", "lapel", "pin"]}, {
            title: "fas fa-ring",
            searchTerms: ["Dungeons & Dragons", "Gollum", "band", "binding", "d&d", "dnd", "fantasy", "jewelry", "precious"]
        }, {title: "fas fa-road", searchTerms: ["street"]}, {
            title: "fas fa-robot",
            searchTerms: []
        }, {title: "fas fa-rocket", searchTerms: ["app"]}, {
            title: "fab fa-rocketchat",
            searchTerms: []
        }, {title: "fab fa-rockrms", searchTerms: []}, {title: "fas fa-route", searchTerms: []}, {
            title: "fas fa-rss",
            searchTerms: ["blog"]
        }, {title: "fas fa-rss-square", searchTerms: ["blog", "feed"]}, {
            title: "fas fa-ruble-sign",
            searchTerms: ["rub"]
        }, {title: "fas fa-ruler", searchTerms: []}, {
            title: "fas fa-ruler-combined",
            searchTerms: []
        }, {title: "fas fa-ruler-horizontal", searchTerms: []}, {
            title: "fas fa-ruler-vertical",
            searchTerms: []
        }, {title: "fas fa-running", searchTerms: ["jog", "sprint"]}, {
            title: "fas fa-rupee-sign",
            searchTerms: ["indian", "inr"]
        }, {title: "fas fa-sad-cry", searchTerms: ["emoticon", "face", "tear", "tears"]}, {
            title: "far fa-sad-cry",
            searchTerms: ["emoticon", "face", "tear", "tears"]
        }, {title: "fas fa-sad-tear", searchTerms: ["emoticon", "face", "tear", "tears"]}, {
            title: "far fa-sad-tear",
            searchTerms: ["emoticon", "face", "tear", "tears"]
        }, {title: "fab fa-safari", searchTerms: ["browser"]}, {
            title: "fab fa-sass",
            searchTerms: []
        }, {title: "fas fa-save", searchTerms: ["floppy", "floppy-o"]}, {
            title: "far fa-save",
            searchTerms: ["floppy", "floppy-o"]
        }, {title: "fab fa-schlix", searchTerms: []}, {
            title: "fas fa-school",
            searchTerms: []
        }, {
            title: "fas fa-screwdriver",
            searchTerms: ["admin", "fix", "repair", "settings", "tool"]
        }, {title: "fab fa-scribd", searchTerms: []}, {
            title: "fas fa-scroll",
            searchTerms: ["Dungeons & Dragons", "announcement", "d&d", "dnd", "fantasy", "paper"]
        }, {
            title: "fas fa-search",
            searchTerms: ["bigger", "enlarge", "magnify", "preview", "zoom"]
        }, {title: "fas fa-search-dollar", searchTerms: []}, {
            title: "fas fa-search-location",
            searchTerms: []
        }, {
            title: "fas fa-search-minus",
            searchTerms: ["minify", "negative", "smaller", "zoom", "zoom out"]
        }, {
            title: "fas fa-search-plus",
            searchTerms: ["bigger", "enlarge", "magnify", "positive", "zoom", "zoom in"]
        }, {title: "fab fa-searchengin", searchTerms: []}, {
            title: "fas fa-seedling",
            searchTerms: []
        }, {title: "fab fa-sellcast", searchTerms: ["eercast"]}, {
            title: "fab fa-sellsy",
            searchTerms: []
        }, {title: "fas fa-server", searchTerms: ["cpu"]}, {
            title: "fab fa-servicestack",
            searchTerms: []
        }, {title: "fas fa-shapes", searchTerms: ["circle", "square", "triangle"]}, {
            title: "fas fa-share",
            searchTerms: []
        }, {title: "fas fa-share-alt", searchTerms: []}, {
            title: "fas fa-share-alt-square",
            searchTerms: []
        }, {title: "fas fa-share-square", searchTerms: ["send", "social"]}, {
            title: "far fa-share-square",
            searchTerms: ["send", "social"]
        }, {title: "fas fa-shekel-sign", searchTerms: ["ils"]}, {
            title: "fas fa-shield-alt",
            searchTerms: ["achievement", "award", "block", "defend", "security", "winner"]
        }, {title: "fas fa-ship", searchTerms: ["boat", "sea"]}, {
            title: "fas fa-shipping-fast",
            searchTerms: []
        }, {title: "fab fa-shirtsinbulk", searchTerms: []}, {
            title: "fas fa-shoe-prints",
            searchTerms: ["feet", "footprints", "steps"]
        }, {title: "fas fa-shopping-bag", searchTerms: []}, {
            title: "fas fa-shopping-basket",
            searchTerms: []
        }, {
            title: "fas fa-shopping-cart",
            searchTerms: ["buy", "checkout", "payment", "purchase"]
        }, {title: "fab fa-shopware", searchTerms: []}, {
            title: "fas fa-shower",
            searchTerms: []
        }, {
            title: "fas fa-shuttle-van",
            searchTerms: ["machine", "public-transportation", "transportation", "vehicle"]
        }, {title: "fas fa-sign", searchTerms: []}, {
            title: "fas fa-sign-in-alt",
            searchTerms: ["arrow", "enter", "join", "log in", "login", "sign in", "sign up", "sign-in", "signin", "signup"]
        }, {title: "fas fa-sign-language", searchTerms: []}, {
            title: "fas fa-sign-out-alt",
            searchTerms: ["arrow", "exit", "leave", "log out", "logout", "sign-out"]
        }, {title: "fas fa-signal", searchTerms: ["bars", "graph", "online", "status"]}, {
            title: "fas fa-signature",
            searchTerms: ["John Hancock", "cursive", "name", "writing"]
        }, {title: "fab fa-simplybuilt", searchTerms: []}, {
            title: "fab fa-sistrix",
            searchTerms: []
        }, {
            title: "fas fa-sitemap",
            searchTerms: ["directory", "hierarchy", "ia", "information architecture", "organization"]
        }, {title: "fab fa-sith", searchTerms: []}, {
            title: "fas fa-skull",
            searchTerms: ["bones", "skeleton", "yorick"]
        }, {
            title: "fas fa-skull-crossbones",
            searchTerms: ["Dungeons & Dragons", "alert", "bones", "d&d", "danger", "dead", "deadly", "death", "dnd", "fantasy", "halloween", "holiday", "jolly-roger", "pirate", "poison", "skeleton", "warning"]
        }, {title: "fab fa-skyatlas", searchTerms: []}, {
            title: "fab fa-skype",
            searchTerms: []
        }, {title: "fab fa-slack", searchTerms: ["anchor", "hash", "hashtag"]}, {
            title: "fab fa-slack-hash",
            searchTerms: ["anchor", "hash", "hashtag"]
        }, {title: "fas fa-slash", searchTerms: []}, {
            title: "fas fa-sliders-h",
            searchTerms: ["settings", "sliders"]
        }, {title: "fab fa-slideshare", searchTerms: []}, {
            title: "fas fa-smile",
            searchTerms: ["approve", "emoticon", "face", "happy", "rating", "satisfied"]
        }, {
            title: "far fa-smile",
            searchTerms: ["approve", "emoticon", "face", "happy", "rating", "satisfied"]
        }, {
            title: "fas fa-smile-beam",
            searchTerms: ["emoticon", "face", "happy", "positive"]
        }, {
            title: "far fa-smile-beam",
            searchTerms: ["emoticon", "face", "happy", "positive"]
        }, {title: "fas fa-smile-wink", searchTerms: ["emoticon", "face", "happy"]}, {
            title: "far fa-smile-wink",
            searchTerms: ["emoticon", "face", "happy"]
        }, {title: "fas fa-smog", searchTerms: ["dragon"]}, {
            title: "fas fa-smoking",
            searchTerms: ["cigarette", "nicotine", "smoking status"]
        }, {title: "fas fa-smoking-ban", searchTerms: ["no smoking", "non-smoking"]}, {
            title: "fab fa-snapchat",
            searchTerms: []
        }, {title: "fab fa-snapchat-ghost", searchTerms: []}, {
            title: "fab fa-snapchat-square",
            searchTerms: []
        }, {
            title: "fas fa-snowflake",
            searchTerms: ["precipitation", "seasonal", "winter"]
        }, {title: "far fa-snowflake", searchTerms: ["precipitation", "seasonal", "winter"]}, {
            title: "fas fa-socks",
            searchTerms: ["business socks", "business time", "flight of the conchords", "wednesday"]
        }, {
            title: "fas fa-solar-panel",
            searchTerms: ["clean", "eco-friendly", "energy", "green", "sun"]
        }, {title: "fas fa-sort", searchTerms: ["order"]}, {
            title: "fas fa-sort-alpha-down",
            searchTerms: ["sort-alpha-asc"]
        }, {title: "fas fa-sort-alpha-up", searchTerms: ["sort-alpha-desc"]}, {
            title: "fas fa-sort-amount-down",
            searchTerms: ["sort-amount-asc"]
        }, {title: "fas fa-sort-amount-up", searchTerms: ["sort-amount-desc"]}, {
            title: "fas fa-sort-down",
            searchTerms: ["arrow", "descending", "sort-desc"]
        }, {
            title: "fas fa-sort-numeric-down",
            searchTerms: ["numbers", "sort-numeric-asc"]
        }, {title: "fas fa-sort-numeric-up", searchTerms: ["numbers", "sort-numeric-desc"]}, {
            title: "fas fa-sort-up",
            searchTerms: ["arrow", "ascending", "sort-asc"]
        }, {title: "fab fa-soundcloud", searchTerms: []}, {
            title: "fas fa-spa",
            searchTerms: ["flora", "mindfullness", "plant", "wellness"]
        }, {
            title: "fas fa-space-shuttle",
            searchTerms: ["astronaut", "machine", "nasa", "rocket", "transportation"]
        }, {title: "fab fa-speakap", searchTerms: []}, {
            title: "fas fa-spider",
            searchTerms: ["arachnid", "bug", "charlotte", "crawl", "eight", "halloween", "holiday"]
        }, {title: "fas fa-spinner", searchTerms: ["loading", "progress"]}, {
            title: "fas fa-splotch",
            searchTerms: []
        }, {title: "fab fa-spotify", searchTerms: []}, {
            title: "fas fa-spray-can",
            searchTerms: []
        }, {title: "fas fa-square", searchTerms: ["block", "box"]}, {
            title: "far fa-square",
            searchTerms: ["block", "box"]
        }, {title: "fas fa-square-full", searchTerms: []}, {
            title: "fas fa-square-root-alt",
            searchTerms: []
        }, {title: "fab fa-squarespace", searchTerms: []}, {
            title: "fab fa-stack-exchange",
            searchTerms: []
        }, {title: "fab fa-stack-overflow", searchTerms: []}, {
            title: "fas fa-stamp",
            searchTerms: []
        }, {
            title: "fas fa-star",
            searchTerms: ["achievement", "award", "favorite", "important", "night", "rating", "score"]
        }, {
            title: "far fa-star",
            searchTerms: ["achievement", "award", "favorite", "important", "night", "rating", "score"]
        }, {title: "fas fa-star-and-crescent", searchTerms: ["islam", "muslim"]}, {
            title: "fas fa-star-half",
            searchTerms: ["achievement", "award", "rating", "score", "star-half-empty", "star-half-full"]
        }, {
            title: "far fa-star-half",
            searchTerms: ["achievement", "award", "rating", "score", "star-half-empty", "star-half-full"]
        }, {
            title: "fas fa-star-half-alt",
            searchTerms: ["achievement", "award", "rating", "score", "star-half-empty", "star-half-full"]
        }, {title: "fas fa-star-of-david", searchTerms: ["jewish", "judaism"]}, {
            title: "fas fa-star-of-life",
            searchTerms: []
        }, {title: "fab fa-staylinked", searchTerms: []}, {
            title: "fab fa-steam",
            searchTerms: []
        }, {title: "fab fa-steam-square", searchTerms: []}, {
            title: "fab fa-steam-symbol",
            searchTerms: []
        }, {
            title: "fas fa-step-backward",
            searchTerms: ["beginning", "first", "previous", "rewind", "start"]
        }, {title: "fas fa-step-forward", searchTerms: ["end", "last", "next"]}, {
            title: "fas fa-stethoscope",
            searchTerms: []
        }, {title: "fab fa-sticker-mule", searchTerms: []}, {
            title: "fas fa-sticky-note",
            searchTerms: []
        }, {title: "far fa-sticky-note", searchTerms: []}, {
            title: "fas fa-stop",
            searchTerms: ["block", "box", "square"]
        }, {title: "fas fa-stop-circle", searchTerms: []}, {
            title: "far fa-stop-circle",
            searchTerms: []
        }, {title: "fas fa-stopwatch", searchTerms: ["time"]}, {
            title: "fas fa-store",
            searchTerms: []
        }, {title: "fas fa-store-alt", searchTerms: []}, {
            title: "fab fa-strava",
            searchTerms: []
        }, {title: "fas fa-stream", searchTerms: []}, {
            title: "fas fa-street-view",
            searchTerms: ["map"]
        }, {title: "fas fa-strikethrough", searchTerms: []}, {
            title: "fab fa-stripe",
            searchTerms: []
        }, {title: "fab fa-stripe-s", searchTerms: []}, {
            title: "fas fa-stroopwafel",
            searchTerms: ["dessert", "food", "sweets", "waffle"]
        }, {title: "fab fa-studiovinari", searchTerms: []}, {
            title: "fab fa-stumbleupon",
            searchTerms: []
        }, {title: "fab fa-stumbleupon-circle", searchTerms: []}, {
            title: "fas fa-subscript",
            searchTerms: []
        }, {
            title: "fas fa-subway",
            searchTerms: ["machine", "railway", "train", "transportation", "vehicle"]
        }, {
            title: "fas fa-suitcase",
            searchTerms: ["baggage", "luggage", "move", "suitcase", "travel", "trip"]
        }, {title: "fas fa-suitcase-rolling", searchTerms: []}, {
            title: "fas fa-sun",
            searchTerms: ["brighten", "contrast", "day", "lighter", "sol", "solar", "star", "weather"]
        }, {
            title: "far fa-sun",
            searchTerms: ["brighten", "contrast", "day", "lighter", "sol", "solar", "star", "weather"]
        }, {title: "fab fa-superpowers", searchTerms: []}, {
            title: "fas fa-superscript",
            searchTerms: ["exponential"]
        }, {title: "fab fa-supple", searchTerms: []}, {
            title: "fas fa-surprise",
            searchTerms: ["emoticon", "face", "shocked"]
        }, {title: "far fa-surprise", searchTerms: ["emoticon", "face", "shocked"]}, {
            title: "fas fa-swatchbook",
            searchTerms: []
        }, {
            title: "fas fa-swimmer",
            searchTerms: ["athlete", "head", "man", "person", "water"]
        }, {title: "fas fa-swimming-pool", searchTerms: ["ladder", "recreation", "water"]}, {
            title: "fas fa-synagogue",
            searchTerms: ["building", "jewish", "judaism", "star of david", "temple"]
        }, {
            title: "fas fa-sync",
            searchTerms: ["exchange", "refresh", "reload", "rotate", "swap"]
        }, {title: "fas fa-sync-alt", searchTerms: ["refresh", "reload", "rotate"]}, {
            title: "fas fa-syringe",
            searchTerms: ["immunizations", "needle"]
        }, {title: "fas fa-table", searchTerms: ["data", "excel", "spreadsheet"]}, {
            title: "fas fa-table-tennis",
            searchTerms: []
        }, {
            title: "fas fa-tablet",
            searchTerms: ["apple", "device", "ipad", "kindle", "screen"]
        }, {
            title: "fas fa-tablet-alt",
            searchTerms: ["apple", "device", "ipad", "kindle", "screen"]
        }, {title: "fas fa-tablets", searchTerms: ["drugs", "medicine"]}, {
            title: "fas fa-tachometer-alt",
            searchTerms: ["dashboard", "tachometer"]
        }, {title: "fas fa-tag", searchTerms: ["label"]}, {
            title: "fas fa-tags",
            searchTerms: ["labels"]
        }, {title: "fas fa-tape", searchTerms: []}, {
            title: "fas fa-tasks",
            searchTerms: ["downloading", "downloads", "loading", "progress", "settings"]
        }, {
            title: "fas fa-taxi",
            searchTerms: ["cab", "cabbie", "car", "car service", "lyft", "machine", "transportation", "uber", "vehicle"]
        }, {title: "fab fa-teamspeak", searchTerms: []}, {
            title: "fas fa-teeth",
            searchTerms: []
        }, {title: "fas fa-teeth-open", searchTerms: []}, {
            title: "fab fa-telegram",
            searchTerms: []
        }, {title: "fab fa-telegram-plane", searchTerms: []}, {
            title: "fas fa-temperature-high",
            searchTerms: ["mercury", "thermometer", "warm"]
        }, {
            title: "fas fa-temperature-low",
            searchTerms: ["cool", "mercury", "thermometer"]
        }, {title: "fab fa-tencent-weibo", searchTerms: []}, {
            title: "fas fa-terminal",
            searchTerms: ["code", "command", "console", "prompt"]
        }, {title: "fas fa-text-height", searchTerms: []}, {
            title: "fas fa-text-width",
            searchTerms: []
        }, {title: "fas fa-th", searchTerms: ["blocks", "boxes", "grid", "squares"]}, {
            title: "fas fa-th-large",
            searchTerms: ["blocks", "boxes", "grid", "squares"]
        }, {
            title: "fas fa-th-list",
            searchTerms: ["checklist", "completed", "done", "finished", "ol", "todo", "ul"]
        }, {title: "fab fa-the-red-yeti", searchTerms: []}, {
            title: "fas fa-theater-masks",
            searchTerms: []
        }, {title: "fab fa-themeco", searchTerms: []}, {
            title: "fab fa-themeisle",
            searchTerms: []
        }, {
            title: "fas fa-thermometer",
            searchTerms: ["mercury", "status", "temperature"]
        }, {
            title: "fas fa-thermometer-empty",
            searchTerms: ["mercury", "status", "temperature"]
        }, {
            title: "fas fa-thermometer-full",
            searchTerms: ["fever", "mercury", "status", "temperature"]
        }, {
            title: "fas fa-thermometer-half",
            searchTerms: ["mercury", "status", "temperature"]
        }, {
            title: "fas fa-thermometer-quarter",
            searchTerms: ["mercury", "status", "temperature"]
        }, {
            title: "fas fa-thermometer-three-quarters",
            searchTerms: ["mercury", "status", "temperature"]
        }, {title: "fab fa-think-peaks", searchTerms: []}, {
            title: "fas fa-thumbs-down",
            searchTerms: ["disagree", "disapprove", "dislike", "hand", "thumbs-o-down"]
        }, {
            title: "far fa-thumbs-down",
            searchTerms: ["disagree", "disapprove", "dislike", "hand", "thumbs-o-down"]
        }, {
            title: "fas fa-thumbs-up",
            searchTerms: ["agree", "approve", "favorite", "hand", "like", "ok", "okay", "success", "thumbs-o-up", "yes", "you got it dude"]
        }, {
            title: "far fa-thumbs-up",
            searchTerms: ["agree", "approve", "favorite", "hand", "like", "ok", "okay", "success", "thumbs-o-up", "yes", "you got it dude"]
        }, {
            title: "fas fa-thumbtack",
            searchTerms: ["coordinates", "location", "marker", "pin", "thumb-tack"]
        }, {title: "fas fa-ticket-alt", searchTerms: ["ticket"]}, {
            title: "fas fa-times",
            searchTerms: ["close", "cross", "error", "exit", "incorrect", "notice", "notification", "notify", "problem", "wrong", "x"]
        }, {
            title: "fas fa-times-circle",
            searchTerms: ["close", "cross", "exit", "incorrect", "notice", "notification", "notify", "problem", "wrong", "x"]
        }, {
            title: "far fa-times-circle",
            searchTerms: ["close", "cross", "exit", "incorrect", "notice", "notification", "notify", "problem", "wrong", "x"]
        }, {
            title: "fas fa-tint",
            searchTerms: ["drop", "droplet", "raindrop", "waterdrop"]
        }, {title: "fas fa-tint-slash", searchTerms: []}, {
            title: "fas fa-tired",
            searchTerms: ["emoticon", "face", "grumpy"]
        }, {title: "far fa-tired", searchTerms: ["emoticon", "face", "grumpy"]}, {
            title: "fas fa-toggle-off",
            searchTerms: ["switch"]
        }, {title: "fas fa-toggle-on", searchTerms: ["switch"]}, {
            title: "fas fa-toilet-paper",
            searchTerms: ["bathroom", "halloween", "holiday", "lavatory", "prank", "restroom", "roll"]
        }, {
            title: "fas fa-toolbox",
            searchTerms: ["admin", "container", "fix", "repair", "settings", "tools"]
        }, {
            title: "fas fa-tooth",
            searchTerms: ["bicuspid", "dental", "molar", "mouth", "teeth"]
        }, {title: "fas fa-torah", searchTerms: ["book", "jewish", "judaism"]}, {
            title: "fas fa-torii-gate",
            searchTerms: ["building", "shintoism"]
        }, {title: "fas fa-tractor", searchTerms: []}, {
            title: "fab fa-trade-federation",
            searchTerms: []
        }, {title: "fas fa-trademark", searchTerms: []}, {
            title: "fas fa-traffic-light",
            searchTerms: []
        }, {title: "fas fa-train", searchTerms: ["bullet", "locomotive", "railway"]}, {
            title: "fas fa-transgender",
            searchTerms: ["intersex"]
        }, {title: "fas fa-transgender-alt", searchTerms: []}, {
            title: "fas fa-trash",
            searchTerms: ["delete", "garbage", "hide", "remove"]
        }, {
            title: "fas fa-trash-alt",
            searchTerms: ["delete", "garbage", "hide", "remove", "trash", "trash-o"]
        }, {
            title: "far fa-trash-alt",
            searchTerms: ["delete", "garbage", "hide", "remove", "trash", "trash-o"]
        }, {
            title: "fas fa-tree",
            searchTerms: ["bark", "fall", "flora", "forest", "nature", "plant", "seasonal"]
        }, {title: "fab fa-trello", searchTerms: []}, {
            title: "fab fa-tripadvisor",
            searchTerms: []
        }, {
            title: "fas fa-trophy",
            searchTerms: ["achievement", "award", "cup", "game", "winner"]
        }, {title: "fas fa-truck", searchTerms: ["delivery", "shipping"]}, {
            title: "fas fa-truck-loading",
            searchTerms: []
        }, {title: "fas fa-truck-monster", searchTerms: []}, {
            title: "fas fa-truck-moving",
            searchTerms: []
        }, {title: "fas fa-truck-pickup", searchTerms: []}, {
            title: "fas fa-tshirt",
            searchTerms: ["cloth", "clothing"]
        }, {title: "fas fa-tty", searchTerms: []}, {
            title: "fab fa-tumblr",
            searchTerms: []
        }, {title: "fab fa-tumblr-square", searchTerms: []}, {
            title: "fas fa-tv",
            searchTerms: ["computer", "display", "monitor", "television"]
        }, {title: "fab fa-twitch", searchTerms: []}, {
            title: "fab fa-twitter",
            searchTerms: ["social network", "tweet"]
        }, {title: "fab fa-twitter-square", searchTerms: ["social network", "tweet"]}, {
            title: "fab fa-typo3",
            searchTerms: []
        }, {title: "fab fa-uber", searchTerms: []}, {title: "fab fa-uikit", searchTerms: []}, {
            title: "fas fa-umbrella",
            searchTerms: ["protection", "rain"]
        }, {
            title: "fas fa-umbrella-beach",
            searchTerms: ["protection", "recreation", "sun"]
        }, {title: "fas fa-underline", searchTerms: []}, {
            title: "fas fa-undo",
            searchTerms: ["back", "control z", "exchange", "oops", "return", "rotate", "swap"]
        }, {
            title: "fas fa-undo-alt",
            searchTerms: ["back", "control z", "exchange", "oops", "return", "swap"]
        }, {title: "fab fa-uniregistry", searchTerms: []}, {
            title: "fas fa-universal-access",
            searchTerms: []
        }, {title: "fas fa-university", searchTerms: ["bank", "institution"]}, {
            title: "fas fa-unlink",
            searchTerms: ["chain", "chain-broken", "remove"]
        }, {title: "fas fa-unlock", searchTerms: ["admin", "lock", "password", "protect"]}, {
            title: "fas fa-unlock-alt",
            searchTerms: ["admin", "lock", "password", "protect"]
        }, {title: "fab fa-untappd", searchTerms: []}, {
            title: "fas fa-upload",
            searchTerms: ["export", "publish"]
        }, {title: "fab fa-usb", searchTerms: []}, {
            title: "fas fa-user",
            searchTerms: ["account", "avatar", "head", "human", "man", "person", "profile"]
        }, {
            title: "far fa-user",
            searchTerms: ["account", "avatar", "head", "human", "man", "person", "profile"]
        }, {
            title: "fas fa-user-alt",
            searchTerms: ["account", "avatar", "head", "human", "man", "person", "profile"]
        }, {title: "fas fa-user-alt-slash", searchTerms: []}, {
            title: "fas fa-user-astronaut",
            searchTerms: ["avatar", "clothing", "cosmonaut", "space", "suit"]
        }, {title: "fas fa-user-check", searchTerms: []}, {
            title: "fas fa-user-circle",
            searchTerms: ["account", "avatar", "head", "human", "man", "person", "profile"]
        }, {
            title: "far fa-user-circle",
            searchTerms: ["account", "avatar", "head", "human", "man", "person", "profile"]
        }, {title: "fas fa-user-clock", searchTerms: []}, {
            title: "fas fa-user-cog",
            searchTerms: []
        }, {title: "fas fa-user-edit", searchTerms: []}, {
            title: "fas fa-user-friends",
            searchTerms: []
        }, {
            title: "fas fa-user-graduate",
            searchTerms: ["cap", "clothing", "commencement", "gown", "graduation", "student"]
        }, {title: "fas fa-user-injured", searchTerms: ["cast", "ouch", "sling"]}, {
            title: "fas fa-user-lock",
            searchTerms: []
        }, {
            title: "fas fa-user-md",
            searchTerms: ["doctor", "job", "medical", "nurse", "occupation", "profile"]
        }, {title: "fas fa-user-minus", searchTerms: ["delete", "negative", "remove"]}, {
            title: "fas fa-user-ninja",
            searchTerms: ["assassin", "avatar", "dangerous", "deadly", "sneaky"]
        }, {title: "fas fa-user-plus", searchTerms: ["positive", "sign up", "signup"]}, {
            title: "fas fa-user-secret",
            searchTerms: ["clothing", "coat", "hat", "incognito", "privacy", "spy", "whisper"]
        }, {title: "fas fa-user-shield", searchTerms: []}, {
            title: "fas fa-user-slash",
            searchTerms: ["ban", "remove"]
        }, {title: "fas fa-user-tag", searchTerms: []}, {
            title: "fas fa-user-tie",
            searchTerms: ["avatar", "business", "clothing", "formal"]
        }, {title: "fas fa-user-times", searchTerms: ["archive", "delete", "remove", "x"]}, {
            title: "fas fa-users",
            searchTerms: ["people", "persons", "profiles"]
        }, {title: "fas fa-users-cog", searchTerms: []}, {
            title: "fab fa-ussunnah",
            searchTerms: []
        }, {title: "fas fa-utensil-spoon", searchTerms: ["spoon"]}, {
            title: "fas fa-utensils",
            searchTerms: ["cutlery", "dinner", "eat", "food", "knife", "restaurant", "spoon"]
        }, {title: "fab fa-vaadin", searchTerms: []}, {
            title: "fas fa-vector-square",
            searchTerms: ["anchors", "lines", "object"]
        }, {title: "fas fa-venus", searchTerms: ["female"]}, {
            title: "fas fa-venus-double",
            searchTerms: []
        }, {title: "fas fa-venus-mars", searchTerms: []}, {
            title: "fab fa-viacoin",
            searchTerms: []
        }, {title: "fab fa-viadeo", searchTerms: []}, {
            title: "fab fa-viadeo-square",
            searchTerms: []
        }, {title: "fas fa-vial", searchTerms: ["test tube"]}, {
            title: "fas fa-vials",
            searchTerms: ["lab results", "test tubes"]
        }, {title: "fab fa-viber", searchTerms: []}, {
            title: "fas fa-video",
            searchTerms: ["camera", "film", "movie", "record", "video-camera"]
        }, {title: "fas fa-video-slash", searchTerms: []}, {
            title: "fas fa-vihara",
            searchTerms: ["buddhism", "buddhist", "building", "monastery"]
        }, {title: "fab fa-vimeo", searchTerms: []}, {
            title: "fab fa-vimeo-square",
            searchTerms: []
        }, {title: "fab fa-vimeo-v", searchTerms: ["vimeo"]}, {
            title: "fab fa-vine",
            searchTerms: []
        }, {title: "fab fa-vk", searchTerms: []}, {
            title: "fab fa-vnv",
            searchTerms: []
        }, {title: "fas fa-volleyball-ball", searchTerms: []}, {
            title: "fas fa-volume-down",
            searchTerms: ["audio", "lower", "music", "quieter", "sound", "speaker"]
        }, {title: "fas fa-volume-mute", searchTerms: []}, {
            title: "fas fa-volume-off",
            searchTerms: ["audio", "music", "mute", "sound"]
        }, {
            title: "fas fa-volume-up",
            searchTerms: ["audio", "higher", "louder", "music", "sound", "speaker"]
        }, {
            title: "fas fa-vote-yea",
            searchTerms: ["accept", "cast", "election", "politics", "positive", "yes"]
        }, {title: "fas fa-vr-cardboard", searchTerms: ["google", "reality", "virtual"]}, {
            title: "fab fa-vuejs",
            searchTerms: []
        }, {title: "fas fa-walking", searchTerms: []}, {
            title: "fas fa-wallet",
            searchTerms: []
        }, {title: "fas fa-warehouse", searchTerms: []}, {
            title: "fas fa-water",
            searchTerms: []
        }, {title: "fab fa-weebly", searchTerms: []}, {title: "fab fa-weibo", searchTerms: []}, {
            title: "fas fa-weight",
            searchTerms: ["measurement", "scale", "weight"]
        }, {title: "fas fa-weight-hanging", searchTerms: ["anvil", "heavy", "measurement"]}, {
            title: "fab fa-weixin",
            searchTerms: []
        }, {title: "fab fa-whatsapp", searchTerms: []}, {
            title: "fab fa-whatsapp-square",
            searchTerms: []
        }, {title: "fas fa-wheelchair", searchTerms: ["handicap", "person"]}, {
            title: "fab fa-whmcs",
            searchTerms: []
        }, {title: "fas fa-wifi", searchTerms: []}, {
            title: "fab fa-wikipedia-w",
            searchTerms: []
        }, {
            title: "fas fa-wind",
            searchTerms: ["air", "blow", "breeze", "fall", "seasonal"]
        }, {title: "fas fa-window-close", searchTerms: []}, {
            title: "far fa-window-close",
            searchTerms: []
        }, {title: "fas fa-window-maximize", searchTerms: []}, {
            title: "far fa-window-maximize",
            searchTerms: []
        }, {title: "fas fa-window-minimize", searchTerms: []}, {
            title: "far fa-window-minimize",
            searchTerms: []
        }, {title: "fas fa-window-restore", searchTerms: []}, {
            title: "far fa-window-restore",
            searchTerms: []
        }, {title: "fab fa-windows", searchTerms: ["microsoft"]}, {
            title: "fas fa-wine-bottle",
            searchTerms: ["alcohol", "beverage", "drink", "glass", "grapes"]
        }, {
            title: "fas fa-wine-glass",
            searchTerms: ["alcohol", "beverage", "drink", "grapes"]
        }, {
            title: "fas fa-wine-glass-alt",
            searchTerms: ["alcohol", "beverage", "drink", "grapes"]
        }, {title: "fab fa-wix", searchTerms: []}, {
            title: "fab fa-wizards-of-the-coast",
            searchTerms: ["Dungeons & Dragons", "d&d", "dnd", "fantasy", "game", "gaming", "tabletop"]
        }, {title: "fab fa-wolf-pack-battalion", searchTerms: []}, {
            title: "fas fa-won-sign",
            searchTerms: ["krw"]
        }, {title: "fab fa-wordpress", searchTerms: []}, {
            title: "fab fa-wordpress-simple",
            searchTerms: []
        }, {title: "fab fa-wpbeginner", searchTerms: []}, {
            title: "fab fa-wpexplorer",
            searchTerms: []
        }, {title: "fab fa-wpforms", searchTerms: []}, {
            title: "fab fa-wpressr",
            searchTerms: ["rendact"]
        }, {
            title: "fas fa-wrench",
            searchTerms: ["fix", "settings", "spanner", "tool", "update"]
        }, {title: "fas fa-x-ray", searchTerms: ["radiological images", "radiology"]}, {
            title: "fab fa-xbox",
            searchTerms: []
        }, {title: "fab fa-xing", searchTerms: []}, {
            title: "fab fa-xing-square",
            searchTerms: []
        }, {title: "fab fa-y-combinator", searchTerms: []}, {
            title: "fab fa-yahoo",
            searchTerms: []
        }, {title: "fab fa-yandex", searchTerms: []}, {
            title: "fab fa-yandex-international",
            searchTerms: []
        }, {title: "fab fa-yelp", searchTerms: []}, {
            title: "fas fa-yen-sign",
            searchTerms: ["jpy", "money"]
        }, {title: "fas fa-yin-yang", searchTerms: ["daoism", "opposites", "taoism"]}, {
            title: "fab fa-yoast",
            searchTerms: []
        }, {
            title: "fab fa-youtube",
            searchTerms: ["film", "video", "youtube-play", "youtube-square", "abcdef"]
        }, {title: "fab fa-youtube-square", searchTerms: []},
            {title: "ficon icon-1ah", searchTerms: ["1ah"]},
            {title: "ficon icon-24-hour-finess-center", searchTerms: ["24", "hour", "finess", "center"]},
            {title: "ficon icon-24hour-check-in", searchTerms: ["24hour", "check", "in"]},
            {title: "ficon icon-24hour-frontdesk", searchTerms: ["24hour", "frontdesk"]},
            {title: "ficon icon-24hour-room-service", searchTerms: ["24hour", "room", "service"]},
            {title: "ficon icon-24hour-security", searchTerms: ["24hour", "security"]},
            {title: "ficon icon-5-star-deal", searchTerms: ["5", "star", "deal"]},
            {title: "ficon icon-desktop-ic-black-down", searchTerms: ["desktop", "ic", "black", "down"]},
            {title: "ficon icon-desktop-ic-black-down-arrow", searchTerms: ["desktop", "ic", "black", "down", "arrow"]},
            {title: "ficon icon-desktop-ic-profile", searchTerms: ["desktop", "ic", "profile"]},
            {title: "ficon icon-installment-payment", searchTerms: ["installment", "payment"]},
            {title: "ficon icon-internet", searchTerms: ["internet"]},
            {title: "ficon icon-iron", searchTerms: ["iron"]},
            {title: "ficon icon-add-a-website", searchTerms: ["add", "a", "website"]},
            {title: "ficon icon-additional-bathroom", searchTerms: ["additional", "bathroom"]},
            {title: "ficon icon-additional-fee-for-pets", searchTerms: ["additional", "fee", "for", "pets"]},
            {title: "ficon icon-additional-information", searchTerms: ["additional", "information"]},
            {title: "ficon icon-additional-toilet", searchTerms: ["additional", "toilet"]},
            {title: "ficon icon-address", searchTerms: ["address"]},
            {title: "ficon icon-adults-line", searchTerms: ["adults", "line"]},
            {title: "ficon icon-adults-one", searchTerms: ["adults", "one"]},
            {title: "ficon icon-after-hours", searchTerms: ["after", "hours"]},
            {title: "ficon icon-afternoon-tea", searchTerms: ["afternoon", "tea"]},
            {title: "ficon icon-agoda-cash", searchTerms: ["agoda", "cash"]},
            {title: "ficon icon-agoda-homes", searchTerms: ["agoda", "homes"]},
            {title: "ficon icon-agoda-price-guarante-filled", searchTerms: ["agoda", "price", "guarante", "filled"]},
            {title: "ficon icon-agoda-price-guarante", searchTerms: ["agoda", "price", "guarante"]},
            {title: "ficon icon-air-bath-access", searchTerms: ["air", "bath", "access"]},
            {title: "ficon icon-air-conditioning", searchTerms: ["air", "conditioning"]},
            {title: "ficon icon-air-purifier", searchTerms: ["air", "purifier"]},
            {title: "ficon icon-airport-transfer-big", searchTerms: ["airport", "transfer", "big"]},
            {title: "ficon icon-airport-transfer-oneway", searchTerms: ["airport", "transfer", "oneway"]},
            {title: "ficon icon-airport-transfer-roundtrip", searchTerms: ["airport", "transfer", "roundtrip"]},
            {title: "ficon icon-airport-transfer-small", searchTerms: ["airport", "transfer", "small"]},
            {title: "ficon icon-airport-transfer-solid", searchTerms: ["airport", "transfer", "solid"]},
            {title: "ficon icon-airport-transfer", searchTerms: ["airport", "transfer"]},
            {title: "ficon icon-airports-plane", searchTerms: ["airports", "plane"]},
            {title: "ficon icon-airports", searchTerms: ["airports"]},
            {title: "ficon icon-alarm-clock", searchTerms: ["alarm", "clock"]},
            {title: "ficon icon-alipay", searchTerms: ["alipay"]},
            {title: "ficon icon-already-booking", searchTerms: ["already", "booking"]},
            {title: "ficon icon-american-express", searchTerms: ["american", "express"]},
            {title: "ficon icon-apartment", searchTerms: ["apartment"]},
            {title: "ficon icon-app-android", searchTerms: ["app", "android"]},
            {title: "ficon icon-app-apple", searchTerms: ["app", "apple"]},
            {title: "ficon icon-app-windos", searchTerms: ["app", "windos"]},
            {title: "ficon icon-aps-lock", searchTerms: ["aps", "lock"]},
            {title: "ficon icon-area", searchTerms: ["area"]},
            {title: "ficon icon-arrow-big-down", searchTerms: ["arrow", "big", "down"]},
            {title: "ficon icon-arrow-big-right", searchTerms: ["arrow", "big", "right"]},
            {title: "ficon icon-arrow-big-up", searchTerms: ["arrow", "big", "up"]},
            {title: "ficon icon-arrow-right-box", searchTerms: ["arrow", "right", "box"]},
            {title: "ficon icon-arrow-right", searchTerms: ["arrow", "right"]},
            {title: "ficon icon-assembly-pin-border", searchTerms: ["assembly", "pin", "border"]},
            {title: "ficon icon-assembly-restaurant-line", searchTerms: ["assembly", "restaurant", "line"]},
            {title: "ficon icon-assembly-restaurant", searchTerms: ["assembly", "restaurant"]},
            {title: "ficon icon-atm-cash-machine-on-site", searchTerms: ["atm", "cash", "machine", "on", "site"]},
            {title: "ficon icon-attractions", searchTerms: ["attractions"]},
            {title: "ficon icon-avatar-hotel", searchTerms: ["avatar", "hotel"]},
            {title: "ficon icon-avatar-property", searchTerms: ["avatar", "property"]},
            {title: "ficon icon-baby-cot", searchTerms: ["baby", "cot"]},
            {title: "ficon icon-babysitting", searchTerms: ["babysitting"]},
            {title: "ficon icon-back-to-top", searchTerms: ["back", "to", "top"]},
            {title: "ficon icon-badge-insider", searchTerms: ["badge", "insider"]},
            {title: "ficon icon-badminton-court", searchTerms: ["badminton", "court"]},
            {title: "ficon icon-balcony-terrace", searchTerms: ["balcony", "terrace"]},
            {title: "ficon icon-balloon-minus", searchTerms: ["balloon", "minus"]},
            {title: "ficon icon-balloon-plus", searchTerms: ["balloon", "plus"]},
            {title: "ficon icon-balloon", searchTerms: ["balloon"]},
            {title: "ficon icon-bathrobes", searchTerms: ["bathrobes"]},
            {title: "ficon icon-bathroom-basics", searchTerms: ["bathroom", "basics"]},
            {title: "ficon icon-bathroom-phone", searchTerms: ["bathroom", "phone"]},
            {title: "ficon icon-bathrooms", searchTerms: ["bathrooms"]},
            {title: "ficon icon-bathtub", searchTerms: ["bathtub"]},
            {title: "ficon icon-bbq-facilities", searchTerms: ["bbq", "facilities"]},
            {title: "ficon icon-bc-global-card", searchTerms: ["bc", "global", "card"]},
            {title: "ficon icon-beach", searchTerms: ["beach"]},
            {title: "ficon icon-bed-property", searchTerms: ["bed", "property"]},
            {title: "ficon icon-bed", searchTerms: ["bed"]},
            {title: "ficon icon-bedroom-door", searchTerms: ["bedroom", "door"]},
            {title: "ficon icon-bedroom", searchTerms: ["bedroom"]},
            {title: "ficon icon-bell-alerts", searchTerms: ["bell", "alerts"]},
            {title: "ficon icon-best-value", searchTerms: ["best", "value"]},
            {title: "ficon icon-bicycle-rental", searchTerms: ["bicycle", "rental"]},
            {title: "ficon icon-billiards", searchTerms: ["billiards"]},
            {title: "ficon icon-blackout-curtains", searchTerms: ["blackout", "curtains"]},
            {title: "ficon icon-blizzard", searchTerms: ["blizzard"]},
            {title: "ficon icon-blowing-drifting-snow", searchTerms: ["blowing", "drifting", "snow"]},
            {title: "ficon icon-blowing-dust-sandstorm", searchTerms: ["blowing", "dust", "sandstorm"]},
            {title: "ficon icon-blowing-spray-windy", searchTerms: ["blowing", "spray", "windy"]},
            {title: "ficon icon-boat", searchTerms: ["boat"]},
            {title: "ficon icon-bold-baby-diaper", searchTerms: ["bold", "baby", "diaper"]},
            {title: "ficon icon-bold-baby", searchTerms: ["bold", "baby"]},
            {title: "ficon icon-bold-bath-materials", searchTerms: ["bold", "bath", "materials"]},
            {title: "ficon icon-bold-bath-temperature", searchTerms: ["bold", "bath", "temperature"]},
            {title: "ficon icon-bold-bath-water", searchTerms: ["bold", "bath", "water"]},
            {title: "ficon icon-bold-capacity", searchTerms: ["bold", "capacity"]},
            {title: "ficon icon-bold-change-flight", searchTerms: ["bold", "change", "flight"]},
            {title: "ficon icon-bold-child-meal-a", searchTerms: ["bold", "child", "meal", "a"]},
            {title: "ficon icon-bold-child-meal-b", searchTerms: ["bold", "child", "meal", "b"]},
            {title: "ficon icon-bold-children-yukata", searchTerms: ["bold", "children", "yukata"]},
            {title: "ficon icon-bold-family-with-teens-new", searchTerms: ["bold", "family", "with", "teens", "new"]},
            {title: "ficon icon-bold-female-only", searchTerms: ["bold", "female", "only"]},
            {title: "ficon icon-bold-first-aid", searchTerms: ["bold", "first", "aid"]},
            {title: "ficon icon-bold-floor-plan", searchTerms: ["bold", "floor", "plan"]},
            {title: "ficon icon-bold-gender", searchTerms: ["bold", "gender"]},
            {title: "ficon icon-bold-group-travelers", searchTerms: ["bold", "group", "travelers"]},
            {title: "ficon icon-bold-hot-spring-access", searchTerms: ["bold", "hot", "spring", "access"]},
            {title: "ficon icon-bold-hot-spring-bath", searchTerms: ["bold", "hot", "spring", "bath"]},
            {title: "ficon icon-bold-infant", searchTerms: ["bold", "infant"]},
            {title: "ficon icon-bold-itinerary", searchTerms: ["bold", "itinerary"]},
            {title: "ficon icon-bold-male-only", searchTerms: ["bold", "male", "only"]},
            {title: "ficon icon-bold-meal", searchTerms: ["bold", "meal"]},
            {title: "ficon icon-bold-modify-search", searchTerms: ["bold", "modify", "search"]},
            {title: "ficon icon-bold-mountain-view", searchTerms: ["bold", "mountain", "view"]},
            {title: "ficon icon-bold-package-savings", searchTerms: ["bold", "package", "savings"]},
            {title: "ficon icon-bold-package", searchTerms: ["bold", "package"]},
            {title: "ficon icon-bold-preselected-flight", searchTerms: ["bold", "preselected", "flight"]},
            {title: "ficon icon-bold-price-fluctuates", searchTerms: ["bold", "price", "fluctuates"]},
            {title: "ficon icon-bold-salt", searchTerms: ["bold", "salt"]},
            {title: "ficon icon-bold-spa-sauna", searchTerms: ["bold", "spa", "sauna"]},
            {title: "ficon icon-bold-tap-water", searchTerms: ["bold", "tap", "water"]},
            {title: "ficon icon-bold-time-icon", searchTerms: ["bold", "time", "icon"]},
            {title: "ficon icon-bold-travel-protection", searchTerms: ["bold", "travel", "protection"]},
            {title: "ficon icon-bold-walkability", searchTerms: ["bold", "walkability"]},
            {title: "ficon icon-book-now-pay-later", searchTerms: ["book", "now", "pay", "later"]},
            {title: "ficon icon-book-without-a-creditcard", searchTerms: ["book", "without", "a", "creditcard"]},
            {title: "ficon icon-bottle-of-sparkling-wine", searchTerms: ["bottle", "of", "sparkling", "wine"]},
            {title: "ficon icon-bottle-of-wine", searchTerms: ["bottle", "of", "wine"]},
            {title: "ficon icon-bowling-alley", searchTerms: ["bowling", "alley"]},
            {title: "ficon icon-bracket-left", searchTerms: ["bracket", "left"]},
            {title: "ficon icon-bracket-right", searchTerms: ["bracket", "right"]},
            {title: "ficon icon-breakfast-buffet", searchTerms: ["breakfast", "buffet"]},
            {title: "ficon icon-breakfast-one-person", searchTerms: ["breakfast", "one", "person"]},
            {title: "ficon icon-breakfast", searchTerms: ["breakfast"]},
            {title: "ficon icon-breezy", searchTerms: ["breezy"]},
            {title: "ficon icon-broken-clouds", searchTerms: ["broken", "clouds"]},
            {title: "ficon icon-budget", searchTerms: ["budget"]},
            {title: "ficon icon-bullet", searchTerms: ["bullet"]},
            {title: "ficon icon-bungalow", searchTerms: ["bungalow"]},
            {title: "ficon icon-bunk-bed", searchTerms: ["bunk", "bed"]},
            {title: "ficon icon-bus-and-rail-stations", searchTerms: ["bus", "and", "rail", "stations"]},
            {title: "ficon icon-bus-station", searchTerms: ["bus", "station"]},
            {title: "ficon icon-business-center", searchTerms: ["business", "center"]},
            {title: "ficon icon-business-facilities", searchTerms: ["business", "facilities"]},
            {title: "ficon icon-business-hover", searchTerms: ["business", "hover"]},
            {title: "ficon icon-business", searchTerms: ["business"]},
            {title: "ficon icon-buzzer-wireless-intercom", searchTerms: ["buzzer", "wireless", "intercom"]},
            {title: "ficon icon-c-coupon", searchTerms: ["c", "coupon"]},
            {title: "ficon icon-cable-car-station", searchTerms: ["cable", "car", "station"]},
            {title: "ficon icon-calendar-onmap", searchTerms: ["calendar", "onmap"]},
            {title: "ficon icon-callcenter-24hour", searchTerms: ["callcenter", "24hour"]},
            {title: "ficon icon-callcenter-line", searchTerms: ["callcenter", "line"]},
            {title: "ficon icon-callcenter", searchTerms: ["callcenter"]},
            {title: "ficon icon-camera-hover", searchTerms: ["camera", "hover"]},
            {title: "ficon icon-cancel-anytime", searchTerms: ["cancel", "anytime"]},
            {title: "ficon icon-cancel-booking", searchTerms: ["cancel", "booking"]},
            {title: "ficon icon-cancellation-policy-non-refund-special-condition", searchTerms: ["cancellation", "policy", "non", "refund", "special", "condition"]},
            {title: "ficon icon-canoe", searchTerms: ["canoe"]},
            {title: "ficon icon-capsule", searchTerms: ["capsule"]},
            {title: "ficon icon-car-hire", searchTerms: ["car", "hire"]},
            {title: "ficon icon-car-park-charges", searchTerms: ["car", "park", "charges"]},
            {title: "ficon icon-car-park-free-charge", searchTerms: ["car", "park", "free", "charge"]},
            {title: "ficon icon-car-park-nearby", searchTerms: ["car", "park", "nearby"]},
            {title: "ficon icon-car-park-onsite", searchTerms: ["car", "park", "onsite"]},
            {title: "ficon icon-car-park", searchTerms: ["car", "park"]},
            {title: "ficon icon-car-power-charging-station", searchTerms: ["car", "power", "charging", "station"]},
            {title: "ficon icon-carbon-monoxide-detector", searchTerms: ["carbon", "monoxide", "detector"]},
            {title: "ficon icon-carpeting", searchTerms: ["carpeting"]},
            {title: "ficon icon-carrouselarrow-left", searchTerms: ["carrouselarrow", "left"]},
            {title: "ficon icon-carrouselarrow-right", searchTerms: ["carrouselarrow", "right"]},
            {title: "ficon icon-cash-a-line-new", searchTerms: ["cash", "a", "line", "new"]},
            {title: "ficon icon-cash-a-solid-new", searchTerms: ["cash", "a", "solid", "new"]},
            {title: "ficon icon-cash-circle-h", searchTerms: ["cash", "circle", "h"]},
            {title: "ficon icon-cash-circle-s", searchTerms: ["cash", "circle", "s"]},
            {title: "ficon icon-cash-h", searchTerms: ["cash", "h"]},
            {title: "ficon icon-cash-line-circle-h", searchTerms: ["cash", "line", "circle", "h"]},
            {title: "ficon icon-cash-line-circle-s", searchTerms: ["cash", "line", "circle", "s"]},
            {title: "ficon icon-cash-line-h", searchTerms: ["cash", "line", "h"]},
            {title: "ficon icon-cash-line-new", searchTerms: ["cash", "line", "new"]},
            {title: "ficon icon-cash-line-s", searchTerms: ["cash", "line", "s"]},
            {title: "ficon icon-cash-s", searchTerms: ["cash", "s"]},
            {title: "ficon icon-cash-solid-new", searchTerms: ["cash", "solid", "new"]},
            {title: "ficon icon-cash", searchTerms: ["cash"]},
            {title: "ficon icon-casino", searchTerms: ["casino"]},
            {title: "ficon icon-cats-allowed", searchTerms: ["cats", "allowed"]},
            {title: "ficon icon-chapel", searchTerms: ["chapel"]},
            {title: "ficon icon-chat", searchTerms: ["chat"]},
            {title: "ficon icon-check-box", searchTerms: ["check", "box"]},
            {title: "ficon icon-check-circle-o", searchTerms: ["check", "circle", "o"]},
            {title: "ficon icon-check-circle", searchTerms: ["check", "circle"]},
            {title: "ficon icon-check-in", searchTerms: ["check", "in"]},
            {title: "ficon icon-check-out", searchTerms: ["check", "out"]},
            {title: "ficon icon-check-valid-state", searchTerms: ["check", "valid", "state"]},
            {title: "ficon icon-check", searchTerms: ["check"]},
            {title: "ficon icon-checkbox-icon", searchTerms: ["checkbox", "icon"]},
            {title: "ficon icon-checkin-date", searchTerms: ["checkin", "date"]},
            {title: "ficon icon-checkin-hover-date", searchTerms: ["checkin", "hover", "date"]},
            {title: "ficon icon-checkout-date", searchTerms: ["checkout", "date"]},
            {title: "ficon icon-checkout-hover-date", searchTerms: ["checkout", "hover", "date"]},
            {title: "ficon icon-child-icon", searchTerms: ["child", "icon"]},
            {title: "ficon icon-child-line", searchTerms: ["child", "line"]},
            {title: "ficon icon-child", searchTerms: ["child"]},
            {title: "ficon icon-children-high-chair", searchTerms: ["children", "high", "chair"]},
            {title: "ficon icon-children-one", searchTerms: ["children", "one"]},
            {title: "ficon icon-children-playground", searchTerms: ["children", "playground"]},
            {title: "ficon icon-china-cuisine", searchTerms: ["china", "cuisine"]},
            {title: "ficon icon-china-event", searchTerms: ["china", "event"]},
            {title: "ficon icon-china-only", searchTerms: ["china", "only"]},
            {title: "ficon icon-chinese-cuisine-noodle", searchTerms: ["chinese", "cuisine", "noodle"]},
            {title: "ficon icon-chinese-friendly", searchTerms: ["chinese", "friendly"]},
            {title: "ficon icon-circle-05", searchTerms: ["circle", "05"]},
            {title: "ficon icon-circle-1", searchTerms: ["circle", "1"]},
            {title: "ficon icon-circle-15", searchTerms: ["circle", "15"]},
            {title: "ficon icon-circle-2", searchTerms: ["circle", "2"]},
            {title: "ficon icon-circle-25", searchTerms: ["circle", "25"]},
            {title: "ficon icon-circle-3", searchTerms: ["circle", "3"]},
            {title: "ficon icon-circle-35", searchTerms: ["circle", "35"]},
            {title: "ficon icon-circle-4", searchTerms: ["circle", "4"]},
            {title: "ficon icon-circle-45", searchTerms: ["circle", "45"]},
            {title: "ficon icon-circle-5", searchTerms: ["circle", "5"]},
            {title: "ficon icon-circle-arrow-left", searchTerms: ["circle", "arrow", "left"]},
            {title: "ficon icon-circle-arrow-right", searchTerms: ["circle", "arrow", "right"]},
            {title: "ficon icon-circle-bus", searchTerms: ["circle", "bus"]},
            {title: "ficon icon-circle-home", searchTerms: ["circle", "home"]},
            {title: "ficon icon-circle-pin", searchTerms: ["circle", "pin"]},
            {title: "ficon icon-circle-plane", searchTerms: ["circle", "plane"]},
            {title: "ficon icon-circle-star", searchTerms: ["circle", "star"]},
            {title: "ficon icon-cities", searchTerms: ["cities"]},
            {title: "ficon icon-city-buses", searchTerms: ["city", "buses"]},
            {title: "ficon icon-city-view", searchTerms: ["city", "view"]},
            {title: "ficon icon-cleaning-products", searchTerms: ["cleaning", "products"]},
            {title: "ficon icon-clear-mark", searchTerms: ["clear", "mark"]},
            {title: "ficon icon-clear-sky-b", searchTerms: ["clear", "sky", "b"]},
            {title: "ficon icon-clear-sky", searchTerms: ["clear", "sky"]},
            {title: "ficon icon-clear", searchTerms: ["clear"]},
            {title: "ficon icon-close-popup-solid", searchTerms: ["close", "popup", "solid"]},
            {title: "ficon icon-close-popup", searchTerms: ["close", "popup"]},
            {title: "ficon icon-closest-bar", searchTerms: ["closest", "bar"]},
            {title: "ficon icon-closest-market", searchTerms: ["closest", "market"]},
            {title: "ficon icon-closet", searchTerms: ["closet"]},
            {title: "ficon icon-clothes-dryer-pay", searchTerms: ["clothes", "dryer", "pay"]},
            {title: "ficon icon-clothes-rack", searchTerms: ["clothes", "rack"]},
            {title: "ficon icon-clothes-washer-free", searchTerms: ["clothes", "washer", "free"]},
            {title: "ficon icon-cloudy", searchTerms: ["cloudy"]},
            {title: "ficon icon-club-lounge-access", searchTerms: ["club", "lounge", "access"]},
            {title: "ficon icon-coffee-shop", searchTerms: ["coffee", "shop"]},
            {title: "ficon icon-coffee-tea-maker", searchTerms: ["coffee", "tea", "maker"]},
            {title: "ficon icon-complimentary-bottled-water", searchTerms: ["complimentary", "bottled", "water"]},
            {title: "ficon icon-complimentary-instant-coffee", searchTerms: ["complimentary", "instant", "coffee"]},
            {title: "ficon icon-complimentary-tea", searchTerms: ["complimentary", "tea"]},
            {title: "ficon icon-compset-comparison", searchTerms: ["compset", "comparison"]},
            {title: "ficon icon-concierge", searchTerms: ["concierge"]},
            {title: "ficon icon-confirmation-instant", searchTerms: ["confirmation", "instant"]},
            {title: "ficon icon-confirmation-later", searchTerms: ["confirmation", "later"]},
            {title: "ficon icon-confirmation-solid", searchTerms: ["confirmation", "solid"]},
            {title: "ficon icon-confirmation", searchTerms: ["confirmation"]},
            {title: "ficon icon-congratulations", searchTerms: ["congratulations"]},
            {title: "ficon icon-continental-breakfast", searchTerms: ["continental", "breakfast"]},
            {title: "ficon icon-control-close-circle", searchTerms: ["control", "close", "circle"]},
            {title: "ficon icon-control-collapse", searchTerms: ["control", "collapse"]},
            {title: "ficon icon-control-expand", searchTerms: ["control", "expand"]},
            {title: "ficon icon-cookie", searchTerms: ["cookie"]},
            {title: "ficon icon-couple-hover", searchTerms: ["couple", "hover"]},
            {title: "ficon icon-couple", searchTerms: ["couple"]},
            {title: "ficon icon-coupon-card-no-circle", searchTerms: ["coupon", "card", "no", "circle"]},
            {title: "ficon icon-coupon-card-solid", searchTerms: ["coupon", "card", "solid"]},
            {title: "ficon icon-coupon-card", searchTerms: ["coupon", "card"]},
            {title: "ficon icon-coupon-promo", searchTerms: ["coupon", "promo"]},
            {title: "ficon icon-coupon", searchTerms: ["coupon"]},
            {title: "ficon icon-credit-card-bf", searchTerms: ["credit", "card", "bf"]},
            {title: "ficon icon-credit-card-required", searchTerms: ["credit", "card", "required"]},
            {title: "ficon icon-credit-card", searchTerms: ["credit", "card"]},
            {title: "ficon icon-culture", searchTerms: ["culture"]},
            {title: "ficon icon-currency-exchange", searchTerms: ["currency", "exchange"]},
            {title: "ficon icon-daily-housekeeping", searchTerms: ["daily", "housekeeping"]},
            {title: "ficon icon-daily-newspaper", searchTerms: ["daily", "newspaper"]},
            {title: "ficon icon-dart-board", searchTerms: ["dart", "board"]},
            {title: "ficon icon-dashboard", searchTerms: ["dashboard"]},
            {title: "ficon icon-data-at-your-fingertips", searchTerms: ["data", "at", "your", "fingertips"]},
            {title: "ficon icon-deal-gift-card", searchTerms: ["deal", "gift", "card"]},
            {title: "ficon icon-deal-insider", searchTerms: ["deal", "insider"]},
            {title: "ficon icon-default-feedback", searchTerms: ["default", "feedback"]},
            {title: "ficon icon-desk", searchTerms: ["desk"]},
            {title: "ficon icon-diners-clubs-card", searchTerms: ["diners", "clubs", "card"]},
            {title: "ficon icon-dinner", searchTerms: ["dinner"]},
            {title: "ficon icon-discount", searchTerms: ["discount"]},
            {title: "ficon icon-discover-card", searchTerms: ["discover", "card"]},
            {title: "ficon icon-dishwasher", searchTerms: ["dishwasher"]},
            {title: "ficon icon-distance-from-city-center", searchTerms: ["distance", "from", "city", "center"]},
            {title: "ficon icon-distance-to-airport", searchTerms: ["distance", "to", "airport"]},
            {title: "ficon icon-diving", searchTerms: ["diving"]},
            {title: "ficon icon-document", searchTerms: ["document"]},
            {title: "ficon icon-dog-allowed", searchTerms: ["dog", "allowed"]},
            {title: "ficon icon-domestic-rates", searchTerms: ["domestic", "rates"]},
            {title: "ficon icon-dot-seperater", searchTerms: ["dot", "seperater"]},
            {title: "ficon icon-double-bed", searchTerms: ["double", "bed"]},
            {title: "ficon icon-double-super-king-queen", searchTerms: ["double", "super", "king", "queen"]},
            {title: "ficon icon-download-apps", searchTerms: ["download", "apps"]},
            {title: "ficon icon-download", searchTerms: ["download"]},
            {title: "ficon icon-dressing-room", searchTerms: ["dressing", "room"]},
            {title: "ficon icon-drinks", searchTerms: ["drinks"]},
            {title: "ficon icon-drizzle", searchTerms: ["drizzle"]},
            {title: "ficon icon-drug-stores", searchTerms: ["drug", "stores"]},
            {title: "ficon icon-dryer", searchTerms: ["dryer"]},
            {title: "ficon icon-dvd-cd-player", searchTerms: ["dvd", "cd", "player"]},
            {title: "ficon icon-dynamic-banner", searchTerms: ["dynamic", "banner"]},
            {title: "ficon icon-early-bird-deal-solid", searchTerms: ["early", "bird", "deal", "solid"]},
            {title: "ficon icon-early-bird-deal", searchTerms: ["early", "bird", "deal"]},
            {title: "ficon icon-early-check-in", searchTerms: ["early", "check", "in"]},
            {title: "ficon icon-easy-integration", searchTerms: ["easy", "integration"]},
            {title: "ficon icon-edge-arrow-left", searchTerms: ["edge", "arrow", "left"]},
            {title: "ficon icon-edge-arrow-right", searchTerms: ["edge", "arrow", "right"]},
            {title: "ficon icon-edit-filled", searchTerms: ["edit", "filled"]},
            {title: "ficon icon-edit", searchTerms: ["edit"]},
            {title: "ficon icon-electric-blanket", searchTerms: ["electric", "blanket"]},
            {title: "ficon icon-elevator", searchTerms: ["elevator"]},
            {title: "ficon icon-email-envelope", searchTerms: ["email", "envelope"]},
            {title: "ficon icon-emo-dislike-ani1", searchTerms: ["emo", "dislike", "ani1"]},
            {title: "ficon icon-emo-dislike-ani2", searchTerms: ["emo", "dislike", "ani2"]},
            {title: "ficon icon-emo-dislike-ani3", searchTerms: ["emo", "dislike", "ani3"]},
            {title: "ficon icon-emo-dislike-click", searchTerms: ["emo", "dislike", "click"]},
            {title: "ficon icon-emo-dont-care-ani1", searchTerms: ["emo", "dont", "care", "ani1"]},
            {title: "ficon icon-emo-dont-care-ani2", searchTerms: ["emo", "dont", "care", "ani2"]},
            {title: "ficon icon-emo-dont-care-ani3", searchTerms: ["emo", "dont", "care", "ani3"]},
            {title: "ficon icon-emo-dont-care-click", searchTerms: ["emo", "dont", "care", "click"]},
            {title: "ficon icon-emo-great-ani1", searchTerms: ["emo", "great", "ani1"]},
            {title: "ficon icon-emo-great-ani2", searchTerms: ["emo", "great", "ani2"]},
            {title: "ficon icon-emo-great-ani3", searchTerms: ["emo", "great", "ani3"]},
            {title: "ficon icon-emo-great-click", searchTerms: ["emo", "great", "click"]},
            {title: "ficon icon-emo-nice-ani1", searchTerms: ["emo", "nice", "ani1"]},
            {title: "ficon icon-emo-nice-ani2", searchTerms: ["emo", "nice", "ani2"]},
            {title: "ficon icon-emo-nice-ani3", searchTerms: ["emo", "nice", "ani3"]},
            {title: "ficon icon-emo-nice-click", searchTerms: ["emo", "nice", "click"]},
            {title: "ficon icon-emo-sad-ani1", searchTerms: ["emo", "sad", "ani1"]},
            {title: "ficon icon-emo-sad-ani2", searchTerms: ["emo", "sad", "ani2"]},
            {title: "ficon icon-emo-sad-ani3", searchTerms: ["emo", "sad", "ani3"]},
            {title: "ficon icon-emo-sad-click", searchTerms: ["emo", "sad", "click"]},
            {title: "ficon icon-entire-place", searchTerms: ["entire", "place"]},
            {title: "ficon icon-essentials", searchTerms: ["essentials"]},
            {title: "ficon icon-everybody-fits", searchTerms: ["everybody", "fits"]},
            {title: "ficon icon-exclusive-sale", searchTerms: ["exclusive", "sale"]},
            {title: "ficon icon-executive-floor", searchTerms: ["executive", "floor"]},
            {title: "ficon icon-executive-lounge-access", searchTerms: ["executive", "lounge", "access"]},
            {title: "ficon icon-export-calendar", searchTerms: ["export", "calendar"]},
            {title: "ficon icon-express-check-in-check-out", searchTerms: ["express", "check", "in", "check", "out"]},
            {title: "ficon icon-exterior", searchTerms: ["exterior"]},
            {title: "ficon icon-extra-bed", searchTerms: ["extra", "bed"]},
            {title: "ficon icon-extra-long-beds", searchTerms: ["extra", "long", "beds"]},
            {title: "ficon icon-facebook-logo", searchTerms: ["facebook", "logo"]},
            {title: "ficon icon-facilities-for-disabled-guests", searchTerms: ["facilities", "for", "disabled", "guests"]},
            {title: "ficon icon-facilities-rich", searchTerms: ["facilities", "rich"]},
            {title: "ficon icon-failed-o", searchTerms: ["failed", "o"]},
            {title: "ficon icon-failed", searchTerms: ["failed"]},
            {title: "ficon icon-fair-mostly-clear", searchTerms: ["fair", "mostly", "clear"]},
            {title: "ficon icon-fair-mostly-sunny", searchTerms: ["fair", "mostly", "sunny"]},
            {title: "ficon icon-family-friendly", searchTerms: ["family", "friendly"]},
            {title: "ficon icon-family-fun", searchTerms: ["family", "fun"]},
            {title: "ficon icon-family-line", searchTerms: ["family", "line"]},
            {title: "ficon icon-family-room", searchTerms: ["family", "room"]},
            {title: "ficon icon-family-special-deal", searchTerms: ["family", "special", "deal"]},
            {title: "ficon icon-family-with-small-kids-hover", searchTerms: ["family", "with", "small", "kids", "hover"]},
            {title: "ficon icon-family-with-teens-hover", searchTerms: ["family", "with", "teens", "hover"]},
            {title: "ficon icon-family-with-teens", searchTerms: ["family", "with", "teens"]},
            {title: "ficon icon-fan", searchTerms: ["fan"]},
            {title: "ficon icon-faq", searchTerms: ["faq"]},
            {title: "ficon icon-favorite-filled", searchTerms: ["favorite", "filled"]},
            {title: "ficon icon-favorite", searchTerms: ["favorite"]},
            {title: "ficon icon-fax-machine", searchTerms: ["fax", "machine"]},
            {title: "ficon icon-fax-or-photo", searchTerms: ["fax", "or", "photo"]},
            {title: "ficon icon-feedback", searchTerms: ["feedback"]},
            {title: "ficon icon-female-capsule", searchTerms: ["female", "capsule"]},
            {title: "ficon icon-few-clouds", searchTerms: ["few", "clouds"]},
            {title: "ficon icon-filled-baby-diaper", searchTerms: ["filled", "baby", "diaper"]},
            {title: "ficon icon-filled-baby", searchTerms: ["filled", "baby"]},
            {title: "ficon icon-filled-bath-materials", searchTerms: ["filled", "bath", "materials"]},
            {title: "ficon icon-filled-bath-temperature", searchTerms: ["filled", "bath", "temperature"]},
            {title: "ficon icon-filled-bath-water", searchTerms: ["filled", "bath", "water"]},
            {title: "ficon icon-filled-capacity", searchTerms: ["filled", "capacity"]},
            {title: "ficon icon-filled-change-flight", searchTerms: ["filled", "change", "flight"]},
            {title: "ficon icon-filled-child-meal-a", searchTerms: ["filled", "child", "meal", "a"]},
            {title: "ficon icon-filled-child-meal-b", searchTerms: ["filled", "child", "meal", "b"]},
            {title: "ficon icon-filled-children-yukata", searchTerms: ["filled", "children", "yukata"]},
            {title: "ficon icon-filled-family-with-teens-new", searchTerms: ["filled", "family", "with", "teens", "new"]},
            {title: "ficon icon-filled-female-only", searchTerms: ["filled", "female", "only"]},
            {title: "ficon icon-filled-first-aid", searchTerms: ["filled", "first", "aid"]},
            {title: "ficon icon-filled-gender", searchTerms: ["filled", "gender"]},
            {title: "ficon icon-filled-hot-spring-access", searchTerms: ["filled", "hot", "spring", "access"]},
            {title: "ficon icon-filled-hot-spring-bath", searchTerms: ["filled", "hot", "spring", "bath"]},
            {title: "ficon icon-filled-infant", searchTerms: ["filled", "infant"]},
            {title: "ficon icon-filled-itinerary", searchTerms: ["filled", "itinerary"]},
            {title: "ficon icon-filled-male-only", searchTerms: ["filled", "male", "only"]},
            {title: "ficon icon-filled-meal", searchTerms: ["filled", "meal"]},
            {title: "ficon icon-filled-modify-search", searchTerms: ["filled", "modify", "search"]},
            {title: "ficon icon-filled-mountain-view", searchTerms: ["filled", "mountain", "view"]},
            {title: "ficon icon-filled-package-savings", searchTerms: ["filled", "package", "savings"]},
            {title: "ficon icon-filled-package", searchTerms: ["filled", "package"]},
            {title: "ficon icon-filled-preselected-flight", searchTerms: ["filled", "preselected", "flight"]},
            {title: "ficon icon-filled-price-fluctuates", searchTerms: ["filled", "price", "fluctuates"]},
            {title: "ficon icon-filled-salt", searchTerms: ["filled", "salt"]},
            {title: "ficon icon-filled-spa-sauna", searchTerms: ["filled", "spa", "sauna"]},
            {title: "ficon icon-filled-tap-water", searchTerms: ["filled", "tap", "water"]},
            {title: "ficon icon-filled-time-icon", searchTerms: ["filled", "time", "icon"]},
            {title: "ficon icon-filled-travel-protection", searchTerms: ["filled", "travel", "protection"]},
            {title: "ficon icon-filled-walkability", searchTerms: ["filled", "walkability"]},
            {title: "ficon icon-filter-icon", searchTerms: ["filter", "icon"]},
            {title: "ficon icon-filter-line", searchTerms: ["filter", "line"]},
            {title: "ficon icon-fire-extinguisher", searchTerms: ["fire", "extinguisher"]},
            {title: "ficon icon-fire-solid", searchTerms: ["fire", "solid"]},
            {title: "ficon icon-fireplace", searchTerms: ["fireplace"]},
            {title: "ficon icon-first-aid-kit", searchTerms: ["first", "aid", "kit"]},
            {title: "ficon icon-fishing", searchTerms: ["fishing"]},
            {title: "ficon icon-fitness-center-charge", searchTerms: ["fitness", "center", "charge"]},
            {title: "ficon icon-fitness-center", searchTerms: ["fitness", "center"]},
            {title: "ficon icon-fitness-club", searchTerms: ["fitness", "club"]},
            {title: "ficon icon-flash-deal-solid", searchTerms: ["flash", "deal", "solid"]},
            {title: "ficon icon-flash-deal", searchTerms: ["flash", "deal"]},
            {title: "ficon icon-flash-sale", searchTerms: ["flash", "sale"]},
            {title: "ficon icon-flight-earn", searchTerms: ["flight", "earn"]},
            {title: "ficon icon-flights-airplane", searchTerms: ["flights", "airplane"]},
            {title: "ficon icon-flights-destination-line", searchTerms: ["flights", "destination", "line"]},
            {title: "ficon icon-flights-hotel-line", searchTerms: ["flights", "hotel", "line"]},
            {title: "ficon icon-flights-layover-exchange-line", searchTerms: ["flights", "layover", "exchange", "line"]},
            {title: "ficon icon-flights-one-ways", searchTerms: ["flights", "one", "ways"]},
            {title: "ficon icon-flights-pin", searchTerms: ["flights", "pin"]},
            {title: "ficon icon-flights-round-trip", searchTerms: ["flights", "round", "trip"]},
            {title: "ficon icon-flights-stop-layover-line", searchTerms: ["flights", "stop", "layover", "line"]},
            {title: "ficon icon-foggy", searchTerms: ["foggy"]},
            {title: "ficon icon-forgot-pass", searchTerms: ["forgot", "pass"]},
            {title: "ficon icon-free-bicycle", searchTerms: ["free", "bicycle"]},
            {title: "ficon icon-free-breakfast-line", searchTerms: ["free", "breakfast", "line"]},
            {title: "ficon icon-free-breakfast", searchTerms: ["free", "breakfast"]},
            {title: "ficon icon-free-cancellation", searchTerms: ["free", "cancellation"]},
            {title: "ficon icon-free-fitness-center", searchTerms: ["free", "fitness", "center"]},
            {title: "ficon icon-free-night-stay-circle", searchTerms: ["free", "night", "stay", "circle"]},
            {title: "ficon icon-free-night-stay-solid", searchTerms: ["free", "night", "stay", "solid"]},
            {title: "ficon icon-free-night-stay", searchTerms: ["free", "night", "stay"]},
            {title: "ficon icon-free-night", searchTerms: ["free", "night"]},
            {title: "ficon icon-free-welcome-drink", searchTerms: ["free", "welcome", "drink"]},
            {title: "ficon icon-free-wifi-in-all-rooms", searchTerms: ["free", "wifi", "in", "all", "rooms"]},
            {title: "ficon icon-freezing-drizzle", searchTerms: ["freezing", "drizzle"]},
            {title: "ficon icon-freezing-rain", searchTerms: ["freezing", "rain"]},
            {title: "ficon icon-frequent-traveler", searchTerms: ["frequent", "traveler"]},
            {title: "ficon icon-fresh-newly-built-property", searchTerms: ["fresh", "newly", "built", "property"]},
            {title: "ficon icon-frigid-ice-crystals", searchTerms: ["frigid", "ice", "crystals"]},
            {title: "ficon icon-g-giftcard", searchTerms: ["g", "giftcard"]},
            {title: "ficon icon-garden", searchTerms: ["garden"]},
            {title: "ficon icon-gca-wreath-left", searchTerms: ["gca", "wreath", "left"]},
            {title: "ficon icon-gca-wreath-right", searchTerms: ["gca", "wreath", "right"]},
            {title: "ficon icon-get-extra-space", searchTerms: ["get", "extra", "space"]},
            {title: "ficon icon-gift-card", searchTerms: ["gift", "card"]},
            {title: "ficon icon-gift-souvenir-shop", searchTerms: ["gift", "souvenir", "shop"]},
            {title: "ficon icon-giftcard-instant", searchTerms: ["giftcard", "instant"]},
            {title: "ficon icon-giftcard", searchTerms: ["giftcard"]},
            {title: "ficon icon-give-us-feedback", searchTerms: ["give", "us", "feedback"]},
            {title: "ficon icon-global", searchTerms: ["global"]},
            {title: "ficon icon-golf-course-on-site", searchTerms: ["golf", "course", "on", "site"]},
            {title: "ficon icon-golf-course-within-3k", searchTerms: ["golf", "course", "within", "3k"]},
            {title: "ficon icon-grocery-deliveries", searchTerms: ["grocery", "deliveries"]},
            {title: "ficon icon-ground-floor", searchTerms: ["ground", "floor"]},
            {title: "ficon icon-group-travelers-hover", searchTerms: ["group", "travelers", "hover"]},
            {title: "ficon icon-group-travelers", searchTerms: ["group", "travelers"]},
            {title: "ficon icon-guest-house", searchTerms: ["guest", "house"]},
            {title: "ficon icon-gym", searchTerms: ["gym"]},
            {title: "ficon icon-hail", searchTerms: ["hail"]},
            {title: "ficon icon-hair-dryer", searchTerms: ["hair", "dryer"]},
            {title: "ficon icon-halal-restaurant-b", searchTerms: ["halal", "restaurant", "b"]},
            {title: "ficon icon-halal-restaurant", searchTerms: ["halal", "restaurant"]},
            {title: "ficon icon-half-full-board", searchTerms: ["half", "full", "board"]},
            {title: "ficon icon-hamburger-dote", searchTerms: ["hamburger", "dote"]},
            {title: "ficon icon-hamburger-menu", searchTerms: ["hamburger", "menu"]},
            {title: "ficon icon-hangers", searchTerms: ["hangers"]},
            {title: "ficon icon-haze-windy", searchTerms: ["haze", "windy"]},
            {title: "ficon icon-heart-of-the-city", searchTerms: ["heart", "of", "the", "city"]},
            {title: "ficon icon-heating", searchTerms: ["heating"]},
            {title: "ficon icon-heavy-rain", searchTerms: ["heavy", "rain"]},
            {title: "ficon icon-heavy-snow", searchTerms: ["heavy", "snow"]},
            {title: "ficon icon-high-floor", searchTerms: ["high", "floor"]},
            {title: "ficon icon-high-to-low", searchTerms: ["high", "to", "low"]},
            {title: "ficon icon-hiking-trails", searchTerms: ["hiking", "trails"]},
            {title: "ficon icon-holiday-house", searchTerms: ["holiday", "house"]},
            {title: "ficon icon-homestay", searchTerms: ["homestay"]},
            {title: "ficon icon-horse-riding", searchTerms: ["horse", "riding"]},
            {title: "ficon icon-hospitals-clinics", searchTerms: ["hospitals", "clinics"]},
            {title: "ficon icon-hot-spring-access", searchTerms: ["hot", "spring", "access"]},
            {title: "ficon icon-hot-spring-bath", searchTerms: ["hot", "spring", "bath"]},
            {title: "ficon icon-hot-tub", searchTerms: ["hot", "tub"]},
            {title: "ficon icon-hot", searchTerms: ["hot"]},
            {title: "ficon icon-hotel-benefit", searchTerms: ["hotel", "benefit"]},
            {title: "ficon icon-hotel-book-last", searchTerms: ["hotel", "book", "last"]},
            {title: "ficon icon-hotel-data", searchTerms: ["hotel", "data"]},
            {title: "ficon icon-hotel-great-location", searchTerms: ["hotel", "great", "location"]},
            {title: "ficon icon-hotel-people-looking", searchTerms: ["hotel", "people", "looking"]},
            {title: "ficon icon-hotel-star-half", searchTerms: ["hotel", "star", "half"]},
            {title: "ficon icon-hotel-star", searchTerms: ["hotel", "star"]},
            {title: "ficon icon-hotel-wifi", searchTerms: ["hotel", "wifi"]},
            {title: "ficon icon-hotel", searchTerms: ["hotel"]},
            {title: "ficon icon-hover-details", searchTerms: ["hover", "details"]},
            {title: "ficon icon-human-large", searchTerms: ["human", "large"]},
            {title: "ficon icon-humidifier", searchTerms: ["humidifier"]},
            {title: "ficon icon-hurricane", searchTerms: ["hurricane"]},
            {title: "ficon icon-ic-filter-bestseller", searchTerms: ["ic", "filter", "bestseller"]},
            {title: "ficon icon-icon-arrow-down", searchTerms: ["icon", "arrow", "down"]},
            {title: "ficon icon-icon-arrow-up", searchTerms: ["icon", "arrow", "up"]},
            {title: "ficon icon-iftar", searchTerms: ["iftar"]},
            {title: "ficon icon-import-calendar", searchTerms: ["import", "calendar"]},
            {title: "ficon icon-in-room-safe", searchTerms: ["in", "room", "safe"]},
            {title: "ficon icon-in-room-tablet", searchTerms: ["in", "room", "tablet"]},
            {title: "ficon icon-in-room-video-games", searchTerms: ["in", "room", "video", "games"]},
            {title: "ficon icon-indoor-poor", searchTerms: ["indoor", "poor"]},
            {title: "ficon icon-infant", searchTerms: ["infant"]},
            {title: "ficon icon-infirmary", searchTerms: ["infirmary"]},
            {title: "ficon icon-info-alert", searchTerms: ["info", "alert"]},
            {title: "ficon icon-info-with-circle", searchTerms: ["info", "with", "circle"]},
            {title: "ficon icon-information", searchTerms: ["information"]},
            {title: "ficon icon-inhouse-movies", searchTerms: ["inhouse", "movies"]},
            {title: "ficon icon-insider-deal-desktop", searchTerms: ["insider", "deal", "desktop"]},
            {title: "ficon icon-installment-graph", searchTerms: ["installment", "graph"]},
            {title: "ficon icon-installment-line", searchTerms: ["installment", "line"]},
            {title: "ficon icon-installment-solid", searchTerms: ["installment", "solid"]},
            {title: "ficon icon-instant-booking", searchTerms: ["instant", "booking"]},
            {title: "ficon icon-instant", searchTerms: ["instant"]},
            {title: "ficon icon-insure-your-hotel", searchTerms: ["insure", "your", "hotel"]},
            {title: "ficon icon-interconnecting-room-available", searchTerms: ["interconnecting", "room", "available"]},
            {title: "ficon icon-invalided-file", searchTerms: ["invalided", "file"]},
            {title: "ficon icon-ipod-docking-station", searchTerms: ["ipod", "docking", "station"]},
            {title: "ficon icon-islamic-prayer-room", searchTerms: ["islamic", "prayer", "room"]},
            {title: "ficon icon-isolated-thunderstorms", searchTerms: ["isolated", "thunderstorms"]},
            {title: "ficon icon-jacuzzi-bathtub", searchTerms: ["jacuzzi", "bathtub"]},
            {title: "ficon icon-japanese-futon", searchTerms: ["japanese", "futon"]},
            {title: "ficon icon-japanese-western-mix", searchTerms: ["japanese", "western", "mix"]},
            {title: "ficon icon-jcb", searchTerms: ["jcb"]},
            {title: "ficon icon-karaoke", searchTerms: ["karaoke"]},
            {title: "ficon icon-keyless-access", searchTerms: ["keyless", "access"]},
            {title: "ficon icon-kids-club", searchTerms: ["kids", "club"]},
            {title: "ficon icon-king-bed", searchTerms: ["king", "bed"]},
            {title: "ficon icon-kitchen-new", searchTerms: ["kitchen", "new"]},
            {title: "ficon icon-kitchen", searchTerms: ["kitchen"]},
            {title: "ficon icon-kitchenette-bold", searchTerms: ["kitchenette", "bold"]},
            {title: "ficon icon-kitchenette", searchTerms: ["kitchenette"]},
            {title: "ficon icon-kitchenware", searchTerms: ["kitchenware"]},
            {title: "ficon icon-kosher-restaurant", searchTerms: ["kosher", "restaurant"]},
            {title: "ficon icon-lake-view", searchTerms: ["lake", "view"]},
            {title: "ficon icon-landmark", searchTerms: ["landmark"]},
            {title: "ficon icon-lantern", searchTerms: ["lantern"]},
            {title: "ficon icon-laptop-friendly-workspace", searchTerms: ["laptop", "friendly", "workspace"]},
            {title: "ficon icon-laptop-safe-box", searchTerms: ["laptop", "safe", "box"]},
            {title: "ficon icon-last-minute-deal-2", searchTerms: ["last", "minute", "deal", "2"]},
            {title: "ficon icon-last-minute-deal-solid", searchTerms: ["last", "minute", "deal", "solid"]},
            {title: "ficon icon-last-minute-deal", searchTerms: ["last", "minute", "deal"]},
            {title: "ficon icon-last-viewed", searchTerms: ["last", "viewed"]},
            {title: "ficon icon-late-check-out", searchTerms: ["late", "check", "out"]},
            {title: "ficon icon-laundromat", searchTerms: ["laundromat"]},
            {title: "ficon icon-laundry-service", searchTerms: ["laundry", "service"]},
            {title: "ficon icon-length-of-stay", searchTerms: ["length", "of", "stay"]},
            {title: "ficon icon-library", searchTerms: ["library"]},
            {title: "ficon icon-light-rain", searchTerms: ["light", "rain"]},
            {title: "ficon icon-light-snow", searchTerms: ["light", "snow"]},
            {title: "ficon icon-lightbox", searchTerms: ["lightbox"]},
            {title: "ficon icon-limit-deal", searchTerms: ["limit", "deal"]},
            {title: "ficon icon-limited-access-floor", searchTerms: ["limited", "access", "floor"]},
            {title: "ficon icon-line-close", searchTerms: ["line", "close"]},
            {title: "ficon icon-line-empty-circle", searchTerms: ["line", "empty", "circle"]},
            {title: "ficon icon-linens", searchTerms: ["linens"]},
            {title: "ficon icon-link-out-bold", searchTerms: ["link", "out", "bold"]},
            {title: "ficon icon-link-out", searchTerms: ["link", "out"]},
            {title: "ficon icon-lobby", searchTerms: ["lobby"]},
            {title: "ficon icon-lockers", searchTerms: ["lockers"]},
            {title: "ficon icon-logo-ah", searchTerms: ["logo", "ah"]},
            {title: "ficon icon-logo-wechat", searchTerms: ["logo", "wechat"]},
            {title: "ficon icon-long-stay-deal", searchTerms: ["long", "stay", "deal"]},
            {title: "ficon icon-long-stays-promotion", searchTerms: ["long", "stays", "promotion"]},
            {title: "ficon icon-long-stays", searchTerms: ["long", "stays"]},
            {title: "ficon icon-low-floor", searchTerms: ["low", "floor"]},
            {title: "ficon icon-low-to-high", searchTerms: ["low", "to", "high"]},
            {title: "ficon icon-luggage-storage", searchTerms: ["luggage", "storage"]},
            {title: "ficon icon-luggage", searchTerms: ["luggage"]},
            {title: "ficon icon-lunch", searchTerms: ["lunch"]},
            {title: "ficon icon-luxury", searchTerms: ["luxury"]},
            {title: "ficon icon-m-and-s", searchTerms: ["m", "and", "s"]},
            {title: "ficon icon-male-capsule-b", searchTerms: ["male", "capsule", "b"]},
            {title: "ficon icon-male-capsule", searchTerms: ["male", "capsule"]},
            {title: "ficon icon-mandarin", searchTerms: ["mandarin"]},
            {title: "ficon icon-map-airport", searchTerms: ["map", "airport"]},
            {title: "ficon icon-map-attraction", searchTerms: ["map", "attraction"]},
            {title: "ficon icon-map-city", searchTerms: ["map", "city"]},
            {title: "ficon icon-map-night", searchTerms: ["map", "night"]},
            {title: "ficon icon-map-pin-fat", searchTerms: ["map", "pin", "fat"]},
            {title: "ficon icon-map-room", searchTerms: ["map", "room"]},
            {title: "ficon icon-map-transportation", searchTerms: ["map", "transportation"]},
            {title: "ficon icon-map-view", searchTerms: ["map", "view"]},
            {title: "ficon icon-map-entry", searchTerms: ["map", "entry"]},
            {title: "ficon icon-massage", searchTerms: ["massage"]},
            {title: "ficon icon-mastercard", searchTerms: ["mastercard"]},
            {title: "ficon icon-max-occupancy-plus", searchTerms: ["max", "occupancy", "plus"]},
            {title: "ficon icon-max-occupancy", searchTerms: ["max", "occupancy"]},
            {title: "ficon icon-meeting-banquet", searchTerms: ["meeting", "banquet"]},
            {title: "ficon icon-meeting-facilities", searchTerms: ["meeting", "facilities"]},
            {title: "ficon icon-menu-about", searchTerms: ["menu", "about"]},
            {title: "ficon icon-menu-account-hover", searchTerms: ["menu", "account", "hover"]},
            {title: "ficon icon-menu-account", searchTerms: ["menu", "account"]},
            {title: "ficon icon-menu-bookings", searchTerms: ["menu", "bookings"]},
            {title: "ficon icon-menu-bug", searchTerms: ["menu", "bug"]},
            {title: "ficon icon-menu-calendar-hover", searchTerms: ["menu", "calendar", "hover"]},
            {title: "ficon icon-menu-calendar", searchTerms: ["menu", "calendar"]},
            {title: "ficon icon-menu-contact-us", searchTerms: ["menu", "contact", "us"]},
            {title: "ficon icon-menu-favorite", searchTerms: ["menu", "favorite"]},
            {title: "ficon icon-menu-inbox-hover", searchTerms: ["menu", "inbox", "hover"]},
            {title: "ficon icon-menu-inbox", searchTerms: ["menu", "inbox"]},
            {title: "ficon icon-menu-listings-hover", searchTerms: ["menu", "listings", "hover"]},
            {title: "ficon icon-menu-listings", searchTerms: ["menu", "listings"]},
            {title: "ficon icon-menu-overviews-hover", searchTerms: ["menu", "overviews", "hover"]},
            {title: "ficon icon-menu-overviews", searchTerms: ["menu", "overviews"]},
            {title: "ficon icon-menu-price-display", searchTerms: ["menu", "price", "display"]},
            {title: "ficon icon-menu-reservations-hover", searchTerms: ["menu", "reservations", "hover"]},
            {title: "ficon icon-menu-reservations", searchTerms: ["menu", "reservations"]},
            {title: "ficon icon-menu-reviews", searchTerms: ["menu", "reviews"]},
            {title: "ficon icon-menu-today-deals", searchTerms: ["menu", "today", "deals"]},
            {title: "ficon icon-message-left", searchTerms: ["message", "left"]},
            {title: "ficon icon-message-right", searchTerms: ["message", "right"]},
            {title: "ficon icon-metro-subway-station", searchTerms: ["metro", "subway", "station"]},
            {title: "ficon icon-microwave", searchTerms: ["microwave"]},
            {title: "ficon icon-mini-bar", searchTerms: ["mini", "bar"]},
            {title: "ficon icon-minibar-b", searchTerms: ["minibar", "b"]},
            {title: "ficon icon-minus-thin", searchTerms: ["minus", "thin"]},
            {title: "ficon icon-minus-with-circle", searchTerms: ["minus", "with", "circle"]},
            {title: "ficon icon-mirror", searchTerms: ["mirror"]},
            {title: "ficon icon-mist", searchTerms: ["mist"]},
            {title: "ficon icon-mixed-rain-hail", searchTerms: ["mixed", "rain", "hail"]},
            {title: "ficon icon-mmb-account", searchTerms: ["mmb", "account"]},
            {title: "ficon icon-mmb-booking", searchTerms: ["mmb", "booking"]},
            {title: "ficon icon-mmb-g-giftcard", searchTerms: ["mmb", "g", "giftcard"]},
            {title: "ficon icon-mmb-gift-cards", searchTerms: ["mmb", "gift", "cards"]},
            {title: "ficon icon-mmb-inbox", searchTerms: ["mmb", "inbox"]},
            {title: "ficon icon-mmb-my-booking", searchTerms: ["mmb", "my", "booking"]},
            {title: "ficon icon-mmb-my-rewards", searchTerms: ["mmb", "my", "rewards"]},
            {title: "ficon icon-mmb-payment-methods", searchTerms: ["mmb", "payment", "methods"]},
            {title: "ficon icon-mmb-pointsmax", searchTerms: ["mmb", "pointsmax"]},
            {title: "ficon icon-mmb-refer-a-friend", searchTerms: ["mmb", "refer", "a", "friend"]},
            {title: "ficon icon-mmb-reviews-b", searchTerms: ["mmb", "reviews", "b"]},
            {title: "ficon icon-mmb-reviews", searchTerms: ["mmb", "reviews"]},
            {title: "ficon icon-mmb-vip", searchTerms: ["mmb", "vip"]},
            {title: "ficon icon-more-bedrooms", searchTerms: ["more", "bedrooms"]},
            {title: "ficon icon-more-images", searchTerms: ["more", "images"]},
            {title: "ficon icon-more-money", searchTerms: ["more", "money"]},
            {title: "ficon icon-mosquitonet", searchTerms: ["mosquitonet"]},
            {title: "ficon icon-most-popular-destinations", searchTerms: ["most", "popular", "destinations"]},
            {title: "ficon icon-mostly-cloudy-day", searchTerms: ["mostly", "cloudy", "day"]},
            {title: "ficon icon-mostly-cloudy-night", searchTerms: ["mostly", "cloudy", "night"]},
            {title: "ficon icon-motorbike", searchTerms: ["motorbike"]},
            {title: "ficon icon-mountain-view", searchTerms: ["mountain", "view"]},
            {title: "ficon icon-mse-icon", searchTerms: ["mse", "icon"]},
            {title: "ficon icon-mse-price-icon", searchTerms: ["mse", "price", "icon"]},
            {title: "ficon icon-museum-arts", searchTerms: ["museum", "arts"]},
            {title: "ficon icon-nav-back", searchTerms: ["nav", "back"]},
            {title: "ficon icon-nav-down-bold", searchTerms: ["nav", "down", "bold"]},
            {title: "ficon icon-nav-down-thin", searchTerms: ["nav", "down", "thin"]},
            {title: "ficon icon-nav-left-bold", searchTerms: ["nav", "left", "bold"]},
            {title: "ficon icon-nav-left-thin", searchTerms: ["nav", "left", "thin"]},
            {title: "ficon icon-nav-right-bold", searchTerms: ["nav", "right", "bold"]},
            {title: "ficon icon-nav-right-thin", searchTerms: ["nav", "right", "thin"]},
            {title: "ficon icon-nav-up-bold", searchTerms: ["nav", "up", "bold"]},
            {title: "ficon icon-nav-up-thin", searchTerms: ["nav", "up", "thin"]},
            {title: "ficon icon-negative", searchTerms: ["negative"]},
            {title: "ficon icon-neighborhood-line", searchTerms: ["neighborhood", "line"]},
            {title: "ficon icon-neighborhood", searchTerms: ["neighborhood"]},
            {title: "ficon icon-new-property", searchTerms: ["new", "property"]},
            {title: "ficon icon-nha-icon", searchTerms: ["nha", "icon"]},
            {title: "ficon icon-nha-logo", searchTerms: ["nha", "logo"]},
            {title: "ficon icon-nightclub", searchTerms: ["nightclub"]},
            {title: "ficon icon-nightlife", searchTerms: ["nightlife"]},
            {title: "ficon icon-no-breakfast-a", searchTerms: ["no", "breakfast", "a"]},
            {title: "ficon icon-no-breakfast-b", searchTerms: ["no", "breakfast", "b"]},
            {title: "ficon icon-no-cc-fee", searchTerms: ["no", "cc", "fee"]},
            {title: "ficon icon-no-cc", searchTerms: ["no", "cc"]},
            {title: "ficon icon-no-children-allowed", searchTerms: ["no", "children", "allowed"]},
            {title: "ficon icon-non-smoking-room", searchTerms: ["non", "smoking", "room"]},
            {title: "ficon icon-non-smoking", searchTerms: ["non", "smoking"]},
            {title: "ficon icon-not-available", searchTerms: ["not", "available"]},
            {title: "ficon icon-noti-balloon-answer", searchTerms: ["noti", "balloon", "answer"]},
            {title: "ficon icon-noti-balloon-question", searchTerms: ["noti", "balloon", "question"]},
            {title: "ficon icon-noti-check-mark-rounded-inner", searchTerms: ["noti", "check", "mark", "rounded", "inner"]},
            {title: "ficon icon-noti-check-mark-sharp", searchTerms: ["noti", "check", "mark", "sharp"]},
            {title: "ficon icon-notice-info", searchTerms: ["notice", "info"]},
            {title: "ficon icon-number-of-floors", searchTerms: ["number", "of", "floors"]},
            {title: "ficon icon-number-of-rooms", searchTerms: ["number", "of", "rooms"]},
            {title: "ficon icon-number-reviews", searchTerms: ["number", "reviews"]},
            {title: "ficon icon-number1", searchTerms: ["number1"]},
            {title: "ficon icon-occupancy", searchTerms: ["occupancy"]},
            {title: "ficon icon-ocean-view", searchTerms: ["ocean", "view"]},
            {title: "ficon icon-one-click", searchTerms: ["one", "click"]},
            {title: "ficon icon-open-a-new-tab", searchTerms: ["open", "a", "new", "tab"]},
            {title: "ficon icon-outdoor-pool", searchTerms: ["outdoor", "pool"]},
            {title: "ficon icon-paperclip", searchTerms: ["paperclip"]},
            {title: "ficon icon-partly-cloudy-day", searchTerms: ["partly", "cloudy", "day"]},
            {title: "ficon icon-partly-cloudy-night", searchTerms: ["partly", "cloudy", "night"]},
            {title: "ficon icon-password", searchTerms: ["password"]},
            {title: "ficon icon-pay-at-hotel-in-cash", searchTerms: ["pay", "at", "hotel", "in", "cash"]},
            {title: "ficon icon-pay-at-the-place", searchTerms: ["pay", "at", "the", "place"]},
            {title: "ficon icon-pay-on-checkin", searchTerms: ["pay", "on", "checkin"]},
            {title: "ficon icon-payment-option-no-credit-card", searchTerms: ["payment", "option", "no", "credit", "card"]},
            {title: "ficon icon-payments-active", searchTerms: ["payments", "active"]},
            {title: "ficon icon-payments", searchTerms: ["payments"]},
            {title: "ficon icon-pending-bold", searchTerms: ["pending", "bold"]},
            {title: "ficon icon-pending", searchTerms: ["pending"]},
            {title: "ficon icon-personal-cheque", searchTerms: ["personal", "cheque"]},
            {title: "ficon icon-personal-details-filled", searchTerms: ["personal", "details", "filled"]},
            {title: "ficon icon-personal-details", searchTerms: ["personal", "details"]},
            {title: "ficon icon-pet-allowed-room", searchTerms: ["pet", "allowed", "room"]},
            {title: "ficon icon-pets-allowed", searchTerms: ["pets", "allowed"]},
            {title: "ficon icon-photo-uploader", searchTerms: ["photo", "uploader"]},
            {title: "ficon icon-photocopying", searchTerms: ["photocopying"]},
            {title: "ficon icon-pin-airport", searchTerms: ["pin", "airport"]},
            {title: "ficon icon-pin-beach", searchTerms: ["pin", "beach"]},
            {title: "ficon icon-pin-business", searchTerms: ["pin", "business"]},
            {title: "ficon icon-pin-casino", searchTerms: ["pin", "casino"]},
            {title: "ficon icon-pin-culture", searchTerms: ["pin", "culture"]},
            {title: "ficon icon-pin-excellent", searchTerms: ["pin", "excellent"]},
            {title: "ficon icon-pin-family-fun", searchTerms: ["pin", "family", "fun"]},
            {title: "ficon icon-pin-heart-of-city-building", searchTerms: ["pin", "heart", "of", "city", "building"]},
            {title: "ficon icon-pin-heart-of-city-mind", searchTerms: ["pin", "heart", "of", "city", "mind"]},
            {title: "ficon icon-pin-heart-of-city", searchTerms: ["pin", "heart", "of", "city"]},
            {title: "ficon icon-pin-heart-of-the-city", searchTerms: ["pin", "heart", "of", "the", "city"]},
            {title: "ficon icon-pin-mountain-view", searchTerms: ["pin", "mountain", "view"]},
            {title: "ficon icon-pin-museum-arts", searchTerms: ["pin", "museum", "arts"]},
            {title: "ficon icon-pin-nightlife", searchTerms: ["pin", "nightlife"]},
            {title: "ficon icon-pin-poi", searchTerms: ["pin", "poi"]},
            {title: "ficon icon-pin-religious", searchTerms: ["pin", "religious"]},
            {title: "ficon icon-pin-restaurant", searchTerms: ["pin", "restaurant"]},
            {title: "ficon icon-pin-romance", searchTerms: ["pin", "romance"]},
            {title: "ficon icon-pin-shopping-markets", searchTerms: ["pin", "shopping", "markets"]},
            {title: "ficon icon-pin-sightseeing", searchTerms: ["pin", "sightseeing"]},
            {title: "ficon icon-pin-skiing", searchTerms: ["pin", "skiing"]},
            {title: "ficon icon-pin-spas", searchTerms: ["pin", "spas"]},
            {title: "ficon icon-pin-tennis-courts", searchTerms: ["pin", "tennis", "courts"]},
            {title: "ficon icon-pin-transportation-hub", searchTerms: ["pin", "transportation", "hub"]},
            {title: "ficon icon-pin", searchTerms: ["pin"]},
            {title: "ficon icon-pin-star", searchTerms: ["pin", "star"]},
            {title: "ficon icon-plus-thin", searchTerms: ["plus", "thin"]},
            {title: "ficon icon-plus-with-circle", searchTerms: ["plus", "with", "circle"]},
            {title: "ficon icon-poi-text-search", searchTerms: ["poi", "text", "search"]},
            {title: "ficon icon-poi", searchTerms: ["poi"]},
            {title: "ficon icon-pointsmax-line-logo", searchTerms: ["pointsmax", "line", "logo"]},
            {title: "ficon icon-pointsmax-logo", searchTerms: ["pointsmax", "logo"]},
            {title: "ficon icon-pointsmax-placeholder", searchTerms: ["pointsmax", "placeholder"]},
            {title: "ficon icon-pointsmax", searchTerms: ["pointsmax"]},
            {title: "ficon icon-police", searchTerms: ["police"]},
            {title: "ficon icon-pool-kids", searchTerms: ["pool", "kids"]},
            {title: "ficon icon-pool", searchTerms: ["pool"]},
            {title: "ficon icon-poolside-bar", searchTerms: ["poolside", "bar"]},
            {title: "ficon icon-popular-guests", searchTerms: ["popular", "guests"]},
            {title: "ficon icon-popular-icon", searchTerms: ["popular", "icon"]},
            {title: "ficon icon-portable-wifi-rental", searchTerms: ["portable", "wifi", "rental"]},
            {title: "ficon icon-positive", searchTerms: ["positive"]},
            {title: "ficon icon-postal-service", searchTerms: ["postal", "service"]},
            {title: "ficon icon-prepayment", searchTerms: ["prepayment"]},
            {title: "ficon icon-price-display", searchTerms: ["price", "display"]},
            {title: "ficon icon-price-drop", searchTerms: ["price", "drop"]},
            {title: "ficon icon-price-messaging", searchTerms: ["price", "messaging"]},
            {title: "ficon icon-price-seen-user", searchTerms: ["price", "seen", "user"]},
            {title: "ficon icon-printer", searchTerms: ["printer"]},
            {title: "ficon icon-privacy-policy", searchTerms: ["privacy", "policy"]},
            {title: "ficon icon-private-bath", searchTerms: ["private", "bath"]},
            {title: "ficon icon-private-beach", searchTerms: ["private", "beach"]},
            {title: "ficon icon-private-entrance", searchTerms: ["private", "entrance"]},
            {title: "ficon icon-private-pool", searchTerms: ["private", "pool"]},
            {title: "ficon icon-profiles", searchTerms: ["profiles"]},
            {title: "ficon icon-promo-code", searchTerms: ["promo", "code"]},
            {title: "ficon icon-promo-score", searchTerms: ["promo", "score"]},
            {title: "ficon icon-promoeligible-star", searchTerms: ["promoeligible", "star"]},
            {title: "ficon icon-promotion-right", searchTerms: ["promotion", "right"]},
            {title: "ficon icon-properties-without-price", searchTerms: ["properties", "without", "price"]},
            {title: "ficon icon-properties", searchTerms: ["properties"]},
            {title: "ficon icon-property-name", searchTerms: ["property", "name"]},
            {title: "ficon icon-property-owner", searchTerms: ["property", "owner"]},
            {title: "ficon icon-property-tooltip", searchTerms: ["property", "tooltip"]},
            {title: "ficon icon-property-upgrades-line", searchTerms: ["property", "upgrades", "line"]},
            {title: "ficon icon-queen-bed-bold", searchTerms: ["queen", "bed", "bold"]},
            {title: "ficon icon-queen-bed", searchTerms: ["queen", "bed"]},
            {title: "ficon icon-question-mark", searchTerms: ["question", "mark"]},
            {title: "ficon icon-questions", searchTerms: ["questions"]},
            {title: "ficon icon-quick-filter", searchTerms: ["quick", "filter"]},
            {title: "ficon icon-rain-sleet", searchTerms: ["rain", "sleet"]},
            {title: "ficon icon-rain-to-snow-showers", searchTerms: ["rain", "to", "snow", "showers"]},
            {title: "ficon icon-rain", searchTerms: ["rain"]},
            {title: "ficon icon-ratings", searchTerms: ["ratings"]},
            {title: "ficon icon-recently", searchTerms: ["recently"]},
            {title: "ficon icon-reception", searchTerms: ["reception"]},
            {title: "ficon icon-recommendations", searchTerms: ["recommendations"]},
            {title: "ficon icon-refresh-bold", searchTerms: ["refresh", "bold"]},
            {title: "ficon icon-refresh", searchTerms: ["refresh"]},
            {title: "ficon icon-refrigerator", searchTerms: ["refrigerator"]},
            {title: "ficon icon-regular-in-room-emergency-alarm", searchTerms: ["regular", "in", "room", "emergency", "alarm"]},
            {title: "ficon icon-regular-in-room-toilet-and-bathtub-with-handrails", searchTerms: ["regular", "in", "room", "toilet", "and", "bathtub", "with", "handrails"]},
            {title: "ficon icon-regular-internet-access-connecting-port-only", searchTerms: ["regular", "internet", "access", "connecting", "port", "only"]},
            {title: "ficon icon-regular-accept-guide-dog-in-guest-room", searchTerms: ["regular", "accept", "guide", "dog", "in", "guest", "room"]},
            {title: "ficon icon-regular-accept-guide-dog", searchTerms: ["regular", "accept", "guide", "dog"]},
            {title: "ficon icon-regular-aesthetic-salon", searchTerms: ["regular", "aesthetic", "salon"]},
            {title: "ficon icon-regular-airline-counter", searchTerms: ["regular", "airline", "counter"]},
            {title: "ficon icon-regular-amusement-arcade", searchTerms: ["regular", "amusement", "arcade"]},
            {title: "ficon icon-regular-amusement-park", searchTerms: ["regular", "amusement", "park"]},
            {title: "ficon icon-regular-ana-crowne-plaza", searchTerms: ["regular", "ana", "crowne", "plaza"]},
            {title: "ficon icon-regular-annex", searchTerms: ["regular", "annex"]},
            {title: "ficon icon-regular-archery", searchTerms: ["regular", "archery"]},
            {title: "ficon icon-regular-aromatherapy", searchTerms: ["regular", "aromatherapy"]},
            {title: "ficon icon-regular-automatic-mahjong", searchTerms: ["regular", "automatic", "mahjong"]},
            {title: "ficon icon-regular-baby-diaper", searchTerms: ["regular", "baby", "diaper"]},
            {title: "ficon icon-regular-baby-food-prepared", searchTerms: ["regular", "baby", "food", "prepared"]},
            {title: "ficon icon-regular-baby-kids-room", searchTerms: ["regular", "baby", "kids", "room"]},
            {title: "ficon icon-regular-baby", searchTerms: ["regular", "baby"]},
            {title: "ficon icon-regular-barber-shop", searchTerms: ["regular", "barber", "shop"]},
            {title: "ficon icon-regular-baseball", searchTerms: ["regular", "baseball"]},
            {title: "ficon icon-regular-bath-materials", searchTerms: ["regular", "bath", "materials"]},
            {title: "ficon icon-regular-bath-temperature", searchTerms: ["regular", "bath", "temperature"]},
            {title: "ficon icon-regular-bath-water", searchTerms: ["regular", "bath", "water"]},
            {title: "ficon icon-regular-beauty-salon", searchTerms: ["regular", "beauty", "salon"]},
            {title: "ficon icon-regular-bicycle-rental-for-kids", searchTerms: ["regular", "bicycle", "rental", "for", "kids"]},
            {title: "ficon icon-regular-big-dog-allowed", searchTerms: ["regular", "big", "dog", "allowed"]},
            {title: "ficon icon-regular-botanical-observation", searchTerms: ["regular", "botanical", "observation"]},
            {title: "ficon icon-regular-braille-support-in-rooms-and-public-spaces", searchTerms: ["regular", "braille", "support", "in", "rooms", "and", "public", "spaces"]},
            {title: "ficon icon-regular-braille-support", searchTerms: ["regular", "braille", "support"]},
            {title: "ficon icon-regular-capacity", searchTerms: ["regular", "capacity"]},
            {title: "ficon icon-regular-change-flight", searchTerms: ["regular", "change", "flight"]},
            {title: "ficon icon-regular-chargeable-internet-access", searchTerms: ["regular", "chargeable", "internet", "access"]},
            {title: "ficon icon-regular-child-meal-a", searchTerms: ["regular", "child", "meal", "a"]},
            {title: "ficon icon-regular-child-meal-b", searchTerms: ["regular", "child", "meal", "b"]},
            {title: "ficon icon-regular-children-yukata", searchTerms: ["regular", "children", "yukata"]},
            {title: "ficon icon-regular-chinese-cuisine", searchTerms: ["regular", "chinese", "cuisine"]},
            {title: "ficon icon-regular-clam-digging", searchTerms: ["regular", "clam", "digging"]},
            {title: "ficon icon-regular-complimentary-shuttle-service-reservation-required", searchTerms: ["regular", "complimentary", "shuttle", "service", "reservation", "required"]},
            {title: "ficon icon-regular-complimentary-shuttle-service", searchTerms: ["regular", "complimentary", "shuttle", "service"]},
            {title: "ficon icon-regular-computer", searchTerms: ["regular", "computer"]},
            {title: "ficon icon-regular-cormorant-fishing", searchTerms: ["regular", "cormorant", "fishing"]},
            {title: "ficon icon-regular-cosmetics", searchTerms: ["regular", "cosmetics"]},
            {title: "ficon icon-regular-countryside", searchTerms: ["regular", "countryside"]},
            {title: "ficon icon-regular-craft-workshop", searchTerms: ["regular", "craft", "workshop"]},
            {title: "ficon icon-regular-cycling", searchTerms: ["regular", "cycling"]},
            {title: "ficon icon-regular-dance-hall", searchTerms: ["regular", "dance", "hall"]},
            {title: "ficon icon-regular-electric-hot-water-pot", searchTerms: ["regular", "electric", "hot", "water", "pot"]},
            {title: "ficon icon-regular-elevator-for-wheelchair", searchTerms: ["regular", "elevator", "for", "wheelchair"]},
            {title: "ficon icon-regular-emergency-alarm-for-hearing-impaired", searchTerms: ["regular", "emergency", "alarm", "for", "hearing", "impaired"]},
            {title: "ficon icon-regular-emergency-alarm", searchTerms: ["regular", "emergency", "alarm"]},
            {title: "ficon icon-regular-english", searchTerms: ["regular", "english"]},
            {title: "ficon icon-regular-exposition-hall", searchTerms: ["regular", "exposition", "hall"]},
            {title: "ficon icon-regular-family-bath", searchTerms: ["regular", "family", "bath"]},
            {title: "ficon icon-regular-family-with-teens-new", searchTerms: ["regular", "family", "with", "teens", "new"]},
            {title: "ficon icon-regular-farm-fish-tourism", searchTerms: ["regular", "farm", "fish", "tourism"]},
            {title: "ficon icon-regular-female-only", searchTerms: ["regular", "female", "only"]},
            {title: "ficon icon-regular-first-aid", searchTerms: ["regular", "first", "aid"]},
            {title: "ficon icon-regular-fishing-gear-rental", searchTerms: ["regular", "fishing", "gear", "rental"]},
            {title: "ficon icon-regular-gallery", searchTerms: ["regular", "gallery"]},
            {title: "ficon icon-regular-gateball-field", searchTerms: ["regular", "gateball", "field"]},
            {title: "ficon icon-regular-gateball", searchTerms: ["regular", "gateball"]},
            {title: "ficon icon-regular-gender", searchTerms: ["regular", "gender"]},
            {title: "ficon icon-regular-go-game", searchTerms: ["regular", "go", "game"]},
            {title: "ficon icon-regular-gravel-path-at-entrance", searchTerms: ["regular", "gravel", "path", "at", "entrance"]},
            {title: "ficon icon-regular-gymnasium", searchTerms: ["regular", "gymnasium"]},
            {title: "ficon icon-regular-handrails-in-stairs-and-hallways", searchTerms: ["regular", "handrails", "in", "stairs", "and", "hallways"]},
            {title: "ficon icon-regular-hang-gliding", searchTerms: ["regular", "hang", "gliding"]},
            {title: "ficon icon-regular-hiking", searchTerms: ["regular", "hiking"]},
            {title: "ficon icon-regular-hot-spring-access", searchTerms: ["regular", "hot", "spring", "access"]},
            {title: "ficon icon-regular-hot-spring-bath", searchTerms: ["regular", "hot", "spring", "bath"]},
            {title: "ficon icon-regular-hotel-chain", searchTerms: ["regular", "hotel", "chain"]},
            {title: "ficon icon-regular-hotel-inside-accessible-by-wheelchair", searchTerms: ["regular", "hotel", "inside", "accessible", "by", "wheelchair"]},
            {title: "ficon icon-regular-hunting", searchTerms: ["regular", "hunting"]},
            {title: "ficon icon-regular-in-room-emergency-alarm-for-hearing-impaired", searchTerms: ["regular", "in", "room", "emergency", "alarm", "for", "hearing", "impaired"]},
            {title: "ficon icon-regular-infant", searchTerms: ["regular", "infant"]},
            {title: "ficon icon-regular-insect-collection", searchTerms: ["regular", "insect", "collection"]},
            {title: "ficon icon-regular-internet-access-from-all-rooms", searchTerms: ["regular", "internet", "access", "from", "all", "rooms"]},
            {title: "ficon icon-regular-internet-access-limited-number-of-rooms", searchTerms: ["regular", "internet", "access", "limited", "number", "of", "rooms"]},
            {title: "ficon icon-regular-itinerary", searchTerms: ["regular", "itinerary"]},
            {title: "ficon icon-regular-japanese-cuisine", searchTerms: ["regular", "japanese", "cuisine"]},
            {title: "ficon icon-regular-japanese-style-tea-room", searchTerms: ["regular", "japanese", "style", "tea", "room"]},
            {title: "ficon icon-regular-japanese-style-toilet-squat-type", searchTerms: ["regular", "japanese", "style", "toilet", "squat", "type"]},
            {title: "ficon icon-regular-large-indoor-bath", searchTerms: ["regular", "large", "indoor", "bath"]},
            {title: "ficon icon-regular-light-meal-corner", searchTerms: ["regular", "light", "meal", "corner"]},
            {title: "ficon icon-regular-local-event", searchTerms: ["regular", "local", "event"]},
            {title: "ficon icon-regular-mahjong", searchTerms: ["regular", "mahjong"]},
            {title: "ficon icon-regular-male-only", searchTerms: ["regular", "male", "only"]},
            {title: "ficon icon-regular-martial-arts-gym", searchTerms: ["regular", "martial", "arts", "gym"]},
            {title: "ficon icon-regular-meal", searchTerms: ["regular", "meal"]},
            {title: "ficon icon-regular-meditation-hall", searchTerms: ["regular", "meditation", "hall"]},
            {title: "ficon icon-regular-modify-search", searchTerms: ["regular", "modify", "search"]},
            {title: "ficon icon-regular-mountain-view", searchTerms: ["regular", "mountain", "view"]},
            {title: "ficon icon-regular-multipurpose-sports-ground", searchTerms: ["regular", "multipurpose", "sports", "ground"]},
            {title: "ficon icon-regular-night", searchTerms: ["regular", "night"]},
            {title: "ficon icon-regular-noh-stage", searchTerms: ["regular", "noh", "stage"]},
            {title: "ficon icon-regular-open-air-bath-mixed-gender", searchTerms: ["regular", "open", "air", "bath", "mixed", "gender"]},
            {title: "ficon icon-regular-open-air-bath-none-mixed", searchTerms: ["regular", "open", "air", "bath", "none", "mixed"]},
            {title: "ficon icon-regular-open-air-workshop", searchTerms: ["regular", "open", "air", "workshop"]},
            {title: "ficon icon-regular-orienteering", searchTerms: ["regular", "orienteering"]},
            {title: "ficon icon-regular-package-savings", searchTerms: ["regular", "package", "savings"]},
            {title: "ficon icon-regular-package", searchTerms: ["regular", "package"]},
            {title: "ficon icon-regular-pet-can-bath-in-room", searchTerms: ["regular", "pet", "can", "bath", "in", "room"]},
            {title: "ficon icon-regular-pet-can-eat-in-room", searchTerms: ["regular", "pet", "can", "eat", "in", "room"]},
            {title: "ficon icon-regular-pharmacy", searchTerms: ["regular", "pharmacy"]},
            {title: "ficon icon-regular-pool-seasonal-opening", searchTerms: ["regular", "pool", "seasonal", "opening"]},
            {title: "ficon icon-regular-pool-year-round", searchTerms: ["regular", "pool", "year", "round"]},
            {title: "ficon icon-regular-prayer-room", searchTerms: ["regular", "prayer", "room"]},
            {title: "ficon icon-regular-preselected-flight", searchTerms: ["regular", "preselected", "flight"]},
            {title: "ficon icon-regular-price-fluctuates", searchTerms: ["regular", "price", "fluctuates"]},
            {title: "ficon icon-regular-private-open-air-bath", searchTerms: ["regular", "private", "open", "air", "bath"]},
            {title: "ficon icon-regular-queen-bed", searchTerms: ["regular", "queen", "bed"]},
            {title: "ficon icon-regular-razor", searchTerms: ["regular", "razor"]},
            {title: "ficon icon-regular-reading-room", searchTerms: ["regular", "reading", "room"]},
            {title: "ficon icon-regular-rooms-with-kotatsu", searchTerms: ["regular", "rooms", "with", "kotatsu"]},
            {title: "ficon icon-regular-ropes-course", searchTerms: ["regular", "ropes", "course"]},
            {title: "ficon icon-regular-rugby", searchTerms: ["regular", "rugby"]},
            {title: "ficon icon-regular-salt", searchTerms: ["regular", "salt"]},
            {title: "ficon icon-regular-scuba-diving", searchTerms: ["regular", "scuba", "diving"]},
            {title: "ficon icon-regular-seat-with-leg-room", searchTerms: ["regular", "seat", "with", "leg", "room"]},
            {title: "ficon icon-regular-seine-fishing", searchTerms: ["regular", "seine", "fishing"]},
            {title: "ficon icon-regular-shared-acccessible-toilet", searchTerms: ["regular", "shared", "acccessible", "toilet"]},
            {title: "ficon icon-regular-shared-private-hot-bath-accessible-by-wheelchair", searchTerms: ["regular", "shared", "private", "hot", "bath", "accessible", "by", "wheelchair"]},
            {title: "ficon icon-regular-shared-western-style-toilets", searchTerms: ["regular", "shared", "western", "style", "toilets"]},
            {title: "ficon icon-regular-shogi", searchTerms: ["regular", "shogi"]},
            {title: "ficon icon-regular-show-more", searchTerms: ["regular", "show", "more"]},
            {title: "ficon icon-regular-shower-booth", searchTerms: ["regular", "shower", "booth"]},
            {title: "ficon icon-regular-sign-language-support-at-reception", searchTerms: ["regular", "sign", "language", "support", "at", "reception"]},
            {title: "ficon icon-regular-skating", searchTerms: ["regular", "skating"]},
            {title: "ficon icon-regular-ski-clothes-rental", searchTerms: ["regular", "ski", "clothes", "rental"]},
            {title: "ficon icon-regular-ski-equipment-rentals-for-kids", searchTerms: ["regular", "ski", "equipment", "rentals", "for", "kids"]},
            {title: "ficon icon-regular-ski-rental", searchTerms: ["regular", "ski", "rental"]},
            {title: "ficon icon-regular-ski-shoes-rental", searchTerms: ["regular", "ski", "shoes", "rental"]},
            {title: "ficon icon-regular-ski-slope", searchTerms: ["regular", "ski", "slope"]},
            {title: "ficon icon-regular-skin-diving", searchTerms: ["regular", "skin", "diving"]},
            {title: "ficon icon-regular-skylounge", searchTerms: ["regular", "skylounge"]},
            {title: "ficon icon-regular-sled-rental-for-kids", searchTerms: ["regular", "sled", "rental", "for", "kids"]},
            {title: "ficon icon-regular-slippers-for-kids", searchTerms: ["regular", "slippers", "for", "kids"]},
            {title: "ficon icon-regular-slope-at-entrance", searchTerms: ["regular", "slope", "at", "entrance"]},
            {title: "ficon icon-regular-small-dog-allowed-indoor-dogs", searchTerms: ["regular", "small", "dog", "allowed", "indoor", "dogs"]},
            {title: "ficon icon-regular-snowboard-rental", searchTerms: ["regular", "snowboard", "rental"]},
            {title: "ficon icon-regular-soccer", searchTerms: ["regular", "soccer"]},
            {title: "ficon icon-regular-spa-sauna", searchTerms: ["regular", "spa", "sauna"]},
            {title: "ficon icon-regular-sudate", searchTerms: ["regular", "sudate"]},
            {title: "ficon icon-regular-tap-water", searchTerms: ["regular", "tap", "water"]},
            {title: "ficon icon-regular-tea-lounge", searchTerms: ["regular", "tea", "lounge"]},
            {title: "ficon icon-regular-tennis-racket-rental", searchTerms: ["regular", "tennis", "racket", "rental"]},
            {title: "ficon icon-regular-tennis", searchTerms: ["regular", "tennis"]},
            {title: "ficon icon-regular-theatre", searchTerms: ["regular", "theatre"]},
            {title: "ficon icon-regular-time-icon", searchTerms: ["regular", "time", "icon"]},
            {title: "ficon icon-regular-toilet-and-bathtub-with-handrails", searchTerms: ["regular", "toilet", "and", "bathtub", "with", "handrails"]},
            {title: "ficon icon-regular-toilet-with-bidet", searchTerms: ["regular", "toilet", "with", "bidet"]},
            {title: "ficon icon-regular-toilet", searchTerms: ["regular", "toilet"]},
            {title: "ficon icon-regular-toothbrush", searchTerms: ["regular", "toothbrush"]},
            {title: "ficon icon-regular-travel-agency", searchTerms: ["regular", "travel", "agency"]},
            {title: "ficon icon-regular-travel-protection", searchTerms: ["regular", "travel", "protection"]},
            {title: "ficon icon-regular-travelers", searchTerms: ["regular", "travelers"]},
            {title: "ficon icon-regular-valley", searchTerms: ["regular", "valley"]},
            {title: "ficon icon-regular-voleyball", searchTerms: ["regular", "voleyball"]},
            {title: "ficon icon-regular-walkability", searchTerms: ["regular", "walkability"]},
            {title: "ficon icon-regular-wedding-venue", searchTerms: ["regular", "wedding", "venue"]},
            {title: "ficon icon-regular-western-cuisine", searchTerms: ["regular", "western", "cuisine"]},
            {title: "ficon icon-regular-wheel-chair-friendly-rooms", searchTerms: ["regular", "wheel", "chair", "friendly", "rooms"]},
            {title: "ficon icon-regular-wheelchair-accessible-rooms", searchTerms: ["regular", "wheelchair", "accessible", "rooms"]},
            {title: "ficon icon-regular-wild-bird-observation", searchTerms: ["regular", "wild", "bird", "observation"]},
            {title: "ficon icon-regular-workshop", searchTerms: ["regular", "workshop"]},
            {title: "ficon icon-regular-yachting", searchTerms: ["regular", "yachting"]},
            {title: "ficon icon-regular-yukata-for-kids", searchTerms: ["regular", "yukata", "for", "kids"]},
            {title: "ficon icon-regular-yukata-japanese-pajamas", searchTerms: ["regular", "yukata", "japanese", "pajamas"]},
            {title: "ficon icon-religious", searchTerms: ["religious"]},
            {title: "ficon icon-reporting-property", searchTerms: ["reporting", "property"]},
            {title: "ficon icon-reporting", searchTerms: ["reporting"]},
            {title: "ficon icon-reservation-active", searchTerms: ["reservation", "active"]},
            {title: "ficon icon-reservation", searchTerms: ["reservation"]},
            {title: "ficon icon-residence", searchTerms: ["residence"]},
            {title: "ficon icon-resort-property-plan", searchTerms: ["resort", "property", "plan"]},
            {title: "ficon icon-restaurant-credit", searchTerms: ["restaurant", "credit"]},
            {title: "ficon icon-restaurant", searchTerms: ["restaurant"]},
            {title: "ficon icon-review-icon", searchTerms: ["review", "icon"]},
            {title: "ficon icon-review-line", searchTerms: ["review", "line"]},
            {title: "ficon icon-review-your-stay", searchTerms: ["review", "your", "stay"]},
            {title: "ficon icon-reviewbubble-icon", searchTerms: ["reviewbubble", "icon"]},
            {title: "ficon icon-reviewbubble-line", searchTerms: ["reviewbubble", "line"]},
            {title: "ficon icon-ribbon-card-no-circle", searchTerms: ["ribbon", "card", "no", "circle"]},
            {title: "ficon icon-ribbon-card-solid", searchTerms: ["ribbon", "card", "solid"]},
            {title: "ficon icon-ribbon-card", searchTerms: ["ribbon", "card"]},
            {title: "ficon icon-right-tick", searchTerms: ["right", "tick"]},
            {title: "ficon icon-romance", searchTerms: ["romance"]},
            {title: "ficon icon-room-plan", searchTerms: ["room", "plan"]},
            {title: "ficon icon-room-promotion-for-flashdeal", searchTerms: ["room", "promotion", "for", "flashdeal"]},
            {title: "ficon icon-room-promotion-for-mobiledeal", searchTerms: ["room", "promotion", "for", "mobiledeal"]},
            {title: "ficon icon-room-promotion-for-otherdeal", searchTerms: ["room", "promotion", "for", "otherdeal"]},
            {title: "ficon icon-room-promotion-for-smartdeal", searchTerms: ["room", "promotion", "for", "smartdeal"]},
            {title: "ficon icon-room-promotion-super-savedeal", searchTerms: ["room", "promotion", "super", "savedeal"]},
            {title: "ficon icon-room-promotion", searchTerms: ["room", "promotion"]},
            {title: "ficon icon-room-service", searchTerms: ["room", "service"]},
            {title: "ficon icon-room-size", searchTerms: ["room", "size"]},
            {title: "ficon icon-room-voltage", searchTerms: ["room", "voltage"]},
            {title: "ficon icon-round-trip", searchTerms: ["round", "trip"]},
            {title: "ficon icon-safety-deposit-boxes", searchTerms: ["safety", "deposit", "boxes"]},
            {title: "ficon icon-salon", searchTerms: ["salon"]},
            {title: "ficon icon-satellite-cable-channels", searchTerms: ["satellite", "cable", "channels"]},
            {title: "ficon icon-satellite-television", searchTerms: ["satellite", "television"]},
            {title: "ficon icon-sauna", searchTerms: ["sauna"]},
            {title: "ficon icon-save-to-pdf", searchTerms: ["save", "to", "pdf"]},
            {title: "ficon icon-scale", searchTerms: ["scale"]},
            {title: "ficon icon-scattered-clouds", searchTerms: ["scattered", "clouds"]},
            {title: "ficon icon-scattered-flurries", searchTerms: ["scattered", "flurries"]},
            {title: "ficon icon-scattered-showers", searchTerms: ["scattered", "showers"]},
            {title: "ficon icon-scattered-snow-showe", searchTerms: ["scattered", "snow", "showe"]},
            {title: "ficon icon-scattered-thunderstorms", searchTerms: ["scattered", "thunderstorms"]},
            {title: "ficon icon-scissor", searchTerms: ["scissor"]},
            {title: "ficon icon-search-box", searchTerms: ["search", "box"]},
            {title: "ficon icon-search-calendar", searchTerms: ["search", "calendar"]},
            {title: "ficon icon-search-icon", searchTerms: ["search", "icon"]},
            {title: "ficon icon-seating-area", searchTerms: ["seating", "area"]},
            {title: "ficon icon-sec", searchTerms: ["sec"]},
            {title: "ficon icon-secure-icon", searchTerms: ["secure", "icon"]},
            {title: "ficon icon-secure-payment", searchTerms: ["secure", "payment"]},
            {title: "ficon icon-selected-property", searchTerms: ["selected", "property"]},
            {title: "ficon icon-self-parking", searchTerms: ["self", "parking"]},
            {title: "ficon icon-semi-double-bed-b", searchTerms: ["semi", "double", "bed", "b"]},
            {title: "ficon icon-semi-double-bed", searchTerms: ["semi", "double", "bed"]},
            {title: "ficon icon-send-arrow", searchTerms: ["send", "arrow"]},
            {title: "ficon icon-separate-dining-area", searchTerms: ["separate", "dining", "area"]},
            {title: "ficon icon-separate-dinning-area-regular", searchTerms: ["separate", "dinning", "area", "regular"]},
            {title: "ficon icon-separate-living-room", searchTerms: ["separate", "living", "room"]},
            {title: "ficon icon-separate-shower-and-tub", searchTerms: ["separate", "shower", "and", "tub"]},
            {title: "ficon icon-seriously-multilingual", searchTerms: ["seriously", "multilingual"]},
            {title: "ficon icon-sewing-kit", searchTerms: ["sewing", "kit"]},
            {title: "ficon icon-shampoo", searchTerms: ["shampoo"]},
            {title: "ficon icon-share", searchTerms: ["share"]},
            {title: "ficon icon-shared-bath", searchTerms: ["shared", "bath"]},
            {title: "ficon icon-shared-kitchen", searchTerms: ["shared", "kitchen"]},
            {title: "ficon icon-shoeshine-kit", searchTerms: ["shoeshine", "kit"]},
            {title: "ficon icon-shopping-markets", searchTerms: ["shopping", "markets"]},
            {title: "ficon icon-shopping", searchTerms: ["shopping"]},
            {title: "ficon icon-shops", searchTerms: ["shops"]},
            {title: "ficon icon-show-more", searchTerms: ["show", "more"]},
            {title: "ficon icon-shower-and-bathtub", searchTerms: ["shower", "and", "bathtub"]},
            {title: "ficon icon-shower-rain", searchTerms: ["shower", "rain"]},
            {title: "ficon icon-shower", searchTerms: ["shower"]},
            {title: "ficon icon-shrine", searchTerms: ["shrine"]},
            {title: "ficon icon-shuttle-service", searchTerms: ["shuttle", "service"]},
            {title: "ficon icon-sightseeing", searchTerms: ["sightseeing"]},
            {title: "ficon icon-single-bed-b", searchTerms: ["single", "bed", "b"]},
            {title: "ficon icon-single-bed", searchTerms: ["single", "bed"]},
            {title: "ficon icon-size-of-rooms", searchTerms: ["size", "of", "rooms"]},
            {title: "ficon icon-ski-equipment-rentals", searchTerms: ["ski", "equipment", "rentals"]},
            {title: "ficon icon-ski-lessons", searchTerms: ["ski", "lessons"]},
            {title: "ficon icon-skiing", searchTerms: ["skiing"]},
            {title: "ficon icon-sleet", searchTerms: ["sleet"]},
            {title: "ficon icon-slippers", searchTerms: ["slippers"]},
            {title: "ficon icon-smoke-detector", searchTerms: ["smoke", "detector"]},
            {title: "ficon icon-smoke-windy", searchTerms: ["smoke", "windy"]},
            {title: "ficon icon-smoking-allowed", searchTerms: ["smoking", "allowed"]},
            {title: "ficon icon-smoking-area", searchTerms: ["smoking", "area"]},
            {title: "ficon icon-smorking-yes-no", searchTerms: ["smorking", "yes", "no"]},
            {title: "ficon icon-snorkeling", searchTerms: ["snorkeling"]},
            {title: "ficon icon-snow", searchTerms: ["snow"]},
            {title: "ficon icon-sofa-bed", searchTerms: ["sofa", "bed"]},
            {title: "ficon icon-sofa", searchTerms: ["sofa"]},
            {title: "ficon icon-solarium", searchTerms: ["solarium"]},
            {title: "ficon icon-solid-24hour-front-desk", searchTerms: ["solid", "24hour", "front", "desk"]},
            {title: "ficon icon-solid-24hour-room-service", searchTerms: ["solid", "24hour", "room", "service"]},
            {title: "ficon icon-solid-adults-b", searchTerms: ["solid", "adults", "b"]},
            {title: "ficon icon-solid-adults-c", searchTerms: ["solid", "adults", "c"]},
            {title: "ficon icon-solid-adults", searchTerms: ["solid", "adults"]},
            {title: "ficon icon-solid-air-conditioning", searchTerms: ["solid", "air", "conditioning"]},
            {title: "ficon icon-solid-airport-transfer-service", searchTerms: ["solid", "airport", "transfer", "service"]},
            {title: "ficon icon-solid-airport-transfer", searchTerms: ["solid", "airport", "transfer"]},
            {title: "ficon icon-solid-babies", searchTerms: ["solid", "babies"]},
            {title: "ficon icon-solid-bar", searchTerms: ["solid", "bar"]},
            {title: "ficon icon-solid-beach", searchTerms: ["solid", "beach"]},
            {title: "ficon icon-solid-bedroom-door", searchTerms: ["solid", "bedroom", "door"]},
            {title: "ficon icon-solid-bedroom", searchTerms: ["solid", "bedroom"]},
            {title: "ficon icon-solid-best-seller", searchTerms: ["solid", "best", "seller"]},
            {title: "ficon icon-solid-breakfast", searchTerms: ["solid", "breakfast"]},
            {title: "ficon icon-solid-business-hover", searchTerms: ["solid", "business", "hover"]},
            {title: "ficon icon-solid-business-travel", searchTerms: ["solid", "business", "travel"]},
            {title: "ficon icon-solid-calendar", searchTerms: ["solid", "calendar"]},
            {title: "ficon icon-solid-car-park", searchTerms: ["solid", "car", "park"]},
            {title: "ficon icon-solid-chat", searchTerms: ["solid", "chat"]},
            {title: "ficon icon-solid-cleanliness", searchTerms: ["solid", "cleanliness"]},
            {title: "ficon icon-solid-clock", searchTerms: ["solid", "clock"]},
            {title: "ficon icon-solid-compset-comparison", searchTerms: ["solid", "compset", "comparison"]},
            {title: "ficon icon-solid-contact-details", searchTerms: ["solid", "contact", "details"]},
            {title: "ficon icon-solid-couple-hover", searchTerms: ["solid", "couple", "hover"]},
            {title: "ficon icon-solid-couple", searchTerms: ["solid", "couple"]},
            {title: "ficon icon-solid-email-envelope", searchTerms: ["solid", "email", "envelope"]},
            {title: "ficon icon-solid-everybody-fits", searchTerms: ["solid", "everybody", "fits"]},
            {title: "ficon icon-solid-families-b", searchTerms: ["solid", "families", "b"]},
            {title: "ficon icon-solid-families", searchTerms: ["solid", "families"]},
            {title: "ficon icon-solid-family-friendly", searchTerms: ["solid", "family", "friendly"]},
            {title: "ficon icon-solid-family-with-teens", searchTerms: ["solid", "family", "with", "teens"]},
            {title: "ficon icon-solid-fitness", searchTerms: ["solid", "fitness"]},
            {title: "ficon icon-solid-flash", searchTerms: ["solid", "flash"]},
            {title: "ficon icon-solid-flights-airplane", searchTerms: ["solid", "flights", "airplane"]},
            {title: "ficon icon-solid-flights-destination", searchTerms: ["solid", "flights", "destination"]},
            {title: "ficon icon-solid-flights-hotel", searchTerms: ["solid", "flights", "hotel"]},
            {title: "ficon icon-solid-flights-layover-exchange", searchTerms: ["solid", "flights", "layover", "exchange"]},
            {title: "ficon icon-solid-flights-stop-layover", searchTerms: ["solid", "flights", "stop", "layover"]},
            {title: "ficon icon-solid-free-cancellation", searchTerms: ["solid", "free", "cancellation"]},
            {title: "ficon icon-solid-free-extra-bed", searchTerms: ["solid", "free", "extra", "bed"]},
            {title: "ficon icon-solid-free-wi-fi", searchTerms: ["solid", "free", "wi", "fi"]},
            {title: "ficon icon-solid-garden", searchTerms: ["solid", "garden"]},
            {title: "ficon icon-solid-generic-traveler-type", searchTerms: ["solid", "generic", "traveler", "type"]},
            {title: "ficon icon-solid-green-credit-card", searchTerms: ["solid", "green", "credit", "card"]},
            {title: "ficon icon-solid-groups-b", searchTerms: ["solid", "groups", "b"]},
            {title: "ficon icon-solid-groups", searchTerms: ["solid", "groups"]},
            {title: "ficon icon-solid-hair-dryer", searchTerms: ["solid", "hair", "dryer"]},
            {title: "ficon icon-solid-insider-deal-flag", searchTerms: ["solid", "insider", "deal", "flag"]},
            {title: "ficon icon-solid-jtb-loyalty", searchTerms: ["solid", "jtb", "loyalty"]},
            {title: "ficon icon-solid-ms-key-dark-grey", searchTerms: ["solid", "ms", "key", "dark", "grey"]},
            {title: "ficon icon-solid-number-of-rooms", searchTerms: ["solid", "number", "of", "rooms"]},
            {title: "ficon icon-solid-payment-options", searchTerms: ["solid", "payment", "options"]},
            {title: "ficon icon-solid-price-match", searchTerms: ["solid", "price", "match"]},
            {title: "ficon icon-solid-private-entrance", searchTerms: ["solid", "private", "entrance"]},
            {title: "ficon icon-solid-property-upgrades", searchTerms: ["solid", "property", "upgrades"]},
            {title: "ficon icon-solid-restaurant", searchTerms: ["solid", "restaurant"]},
            {title: "ficon icon-solid-room-offers", searchTerms: ["solid", "room", "offers"]},
            {title: "ficon icon-solid-secure-icon", searchTerms: ["solid", "secure", "icon"]},
            {title: "ficon icon-solid-spa", searchTerms: ["solid", "spa"]},
            {title: "ficon icon-solid-special-requests-new", searchTerms: ["solid", "special", "requests", "new"]},
            {title: "ficon icon-solid-special-requests", searchTerms: ["solid", "special", "requests"]},
            {title: "ficon icon-solid-swimming-pool", searchTerms: ["solid", "swimming", "pool"]},
            {title: "ficon icon-solid-tax-receipt", searchTerms: ["solid", "tax", "receipt"]},
            {title: "ficon icon-solid-telephone", searchTerms: ["solid", "telephone"]},
            {title: "ficon icon-solid-total-support", searchTerms: ["solid", "total", "support"]},
            {title: "ficon icon-solid-transportation", searchTerms: ["solid", "transportation"]},
            {title: "ficon icon-solid-unlock", searchTerms: ["solid", "unlock"]},
            {title: "ficon icon-solo-hover", searchTerms: ["solo", "hover"]},
            {title: "ficon icon-solo", searchTerms: ["solo"]},
            {title: "ficon icon-sort-line", searchTerms: ["sort", "line"]},
            {title: "ficon icon-soundproofing", searchTerms: ["soundproofing"]},
            {title: "ficon icon-spa-credit", searchTerms: ["spa", "credit"]},
            {title: "ficon icon-spa-sauna", searchTerms: ["spa", "sauna"]},
            {title: "ficon icon-spas", searchTerms: ["spas"]},
            {title: "ficon icon-special-condition", searchTerms: ["special", "condition"]},
            {title: "ficon icon-spoken-1", searchTerms: ["spoken", "1"]},
            {title: "ficon icon-spoken-10", searchTerms: ["spoken", "10"]},
            {title: "ficon icon-spoken-11", searchTerms: ["spoken", "11"]},
            {title: "ficon icon-spoken-12", searchTerms: ["spoken", "12"]},
            {title: "ficon icon-spoken-13", searchTerms: ["spoken", "13"]},
            {title: "ficon icon-spoken-2", searchTerms: ["spoken", "2"]},
            {title: "ficon icon-spoken-20", searchTerms: ["spoken", "20"]},
            {title: "ficon icon-spoken-22", searchTerms: ["spoken", "22"]},
            {title: "ficon icon-spoken-23", searchTerms: ["spoken", "23"]},
            {title: "ficon icon-spoken-24", searchTerms: ["spoken", "24"]},
            {title: "ficon icon-spoken-25", searchTerms: ["spoken", "25"]},
            {title: "ficon icon-spoken-26", searchTerms: ["spoken", "26"]},
            {title: "ficon icon-spoken-27", searchTerms: ["spoken", "27"]},
            {title: "ficon icon-spoken-28", searchTerms: ["spoken", "28"]},
            {title: "ficon icon-spoken-29", searchTerms: ["spoken", "29"]},
            {title: "ficon icon-spoken-3", searchTerms: ["spoken", "3"]},
            {title: "ficon icon-spoken-30", searchTerms: ["spoken", "30"]},
            {title: "ficon icon-spoken-31", searchTerms: ["spoken", "31"]},
            {title: "ficon icon-spoken-32", searchTerms: ["spoken", "32"]},
            {title: "ficon icon-spoken-33", searchTerms: ["spoken", "33"]},
            {title: "ficon icon-spoken-34", searchTerms: ["spoken", "34"]},
            {title: "ficon icon-spoken-35", searchTerms: ["spoken", "35"]},
            {title: "ficon icon-spoken-36", searchTerms: ["spoken", "36"]},
            {title: "ficon icon-spoken-37", searchTerms: ["spoken", "37"]},
            {title: "ficon icon-spoken-38", searchTerms: ["spoken", "38"]},
            {title: "ficon icon-spoken-39", searchTerms: ["spoken", "39"]},
            {title: "ficon icon-spoken-4", searchTerms: ["spoken", "4"]},
            {title: "ficon icon-spoken-40", searchTerms: ["spoken", "40"]},
            {title: "ficon icon-spoken-43", searchTerms: ["spoken", "43"]},
            {title: "ficon icon-spoken-46", searchTerms: ["spoken", "46"]},
            {title: "ficon icon-spoken-47", searchTerms: ["spoken", "47"]},
            {title: "ficon icon-spoken-48", searchTerms: ["spoken", "48"]},
            {title: "ficon icon-spoken-49", searchTerms: ["spoken", "49"]},
            {title: "ficon icon-spoken-5", searchTerms: ["spoken", "5"]},
            {title: "ficon icon-spoken-50", searchTerms: ["spoken", "50"]},
            {title: "ficon icon-spoken-6", searchTerms: ["spoken", "6"]},
            {title: "ficon icon-spoken-7", searchTerms: ["spoken", "7"]},
            {title: "ficon icon-spoken-8", searchTerms: ["spoken", "8"]},
            {title: "ficon icon-spoken-9", searchTerms: ["spoken", "9"]},
            {title: "ficon icon-sqm", searchTerms: ["sqm"]},
            {title: "ficon icon-squash-courts", searchTerms: ["squash", "courts"]},
            {title: "ficon icon-stack-of-square-papers", searchTerms: ["stack", "of", "square", "papers"]},
            {title: "ficon icon-star-1", searchTerms: ["star", "1"]},
            {title: "ficon icon-star-15", searchTerms: ["star", "15"]},
            {title: "ficon icon-star-2", searchTerms: ["star", "2"]},
            {title: "ficon icon-star-25", searchTerms: ["star", "25"]},
            {title: "ficon icon-star-3", searchTerms: ["star", "3"]},
            {title: "ficon icon-star-35", searchTerms: ["star", "35"]},
            {title: "ficon icon-star-4", searchTerms: ["star", "4"]},
            {title: "ficon icon-star-45", searchTerms: ["star", "45"]},
            {title: "ficon icon-star-5", searchTerms: ["star", "5"]},
            {title: "ficon icon-steamroom", searchTerms: ["steamroom"]},
            {title: "ficon icon-strong-storms", searchTerms: ["strong", "storms"]},
            {title: "ficon icon-subways", searchTerms: ["subways"]},
            {title: "ficon icon-suitable-for-events", searchTerms: ["suitable", "for", "events"]},
            {title: "ficon icon-sunny", searchTerms: ["sunny"]},
            {title: "ficon icon-super-king-bed", searchTerms: ["super", "king", "bed"]},
            {title: "ficon icon-surfing-lessons", searchTerms: ["surfing", "lessons"]},
            {title: "ficon icon-swimming-pool-access", searchTerms: ["swimming", "pool", "access"]},
            {title: "ficon icon-table-tennis", searchTerms: ["table", "tennis"]},
            {title: "ficon icon-tamil", searchTerms: ["tamil"]},
            {title: "ficon icon-tatami", searchTerms: ["tatami"]},
            {title: "ficon icon-tax-id", searchTerms: ["tax", "id"]},
            {title: "ficon icon-tax-receipt-available", searchTerms: ["tax", "receipt", "available"]},
            {title: "ficon icon-taxi-service", searchTerms: ["taxi", "service"]},
            {title: "ficon icon-tea-maker", searchTerms: ["tea", "maker"]},
            {title: "ficon icon-telephone", searchTerms: ["telephone"]},
            {title: "ficon icon-television-plasma", searchTerms: ["television", "plasma"]},
            {title: "ficon icon-tennis-courts", searchTerms: ["tennis", "courts"]},
            {title: "ficon icon-text-area", searchTerms: ["text", "area"]},
            {title: "ficon icon-text-links", searchTerms: ["text", "links"]},
            {title: "ficon icon-theme-park", searchTerms: ["theme", "park"]},
            {title: "ficon icon-thin-arrow-down", searchTerms: ["thin", "arrow", "down"]},
            {title: "ficon icon-thin-arrow-left", searchTerms: ["thin", "arrow", "left"]},
            {title: "ficon icon-thin-arrow-right", searchTerms: ["thin", "arrow", "right"]},
            {title: "ficon icon-thin-arrow-up", searchTerms: ["thin", "arrow", "up"]},
            {title: "ficon icon-thin-circle-arrow-left", searchTerms: ["thin", "circle", "arrow", "left"]},
            {title: "ficon icon-thin-sub-arrow", searchTerms: ["thin", "sub", "arrow"]},
            {title: "ficon icon-thumb-down-line", searchTerms: ["thumb", "down", "line"]},
            {title: "ficon icon-thumb-up-line", searchTerms: ["thumb", "up", "line"]},
            {title: "ficon icon-thumb-up-solid-map", searchTerms: ["thumb", "up", "solid", "map"]},
            {title: "ficon icon-thumb-up-solid", searchTerms: ["thumb", "up", "solid"]},
            {title: "ficon icon-thumb-up", searchTerms: ["thumb", "up"]},
            {title: "ficon icon-thunder-and-hail", searchTerms: ["thunder", "and", "hail"]},
            {title: "ficon icon-thunderstorm", searchTerms: ["thunderstorm"]},
            {title: "ficon icon-ticket-service", searchTerms: ["ticket", "service"]},
            {title: "ficon icon-time-clock", searchTerms: ["time", "clock"]},
            {title: "ficon icon-time-filled-icon", searchTerms: ["time", "filled", "icon"]},
            {title: "ficon icon-time-icon", searchTerms: ["time", "icon"]},
            {title: "ficon icon-time-to-airport", searchTerms: ["time", "to", "airport"]},
            {title: "ficon icon-timer", searchTerms: ["timer"]},
            {title: "ficon icon-toiletries", searchTerms: ["toiletries"]},
            {title: "ficon icon-tonight-only", searchTerms: ["tonight", "only"]},
            {title: "ficon icon-tools", searchTerms: ["tools"]},
            {title: "ficon icon-tooltip-details", searchTerms: ["tooltip", "details"]},
            {title: "ficon icon-tooltip", searchTerms: ["tooltip"]},
            {title: "ficon icon-top-floor", searchTerms: ["top", "floor"]},
            {title: "ficon icon-top-rated", searchTerms: ["top", "rated"]},
            {title: "ficon icon-topic-calendar", searchTerms: ["topic", "calendar"]},
            {title: "ficon icon-topic-filter", searchTerms: ["topic", "filter"]},
            {title: "ficon icon-topic-hotel-highlight", searchTerms: ["topic", "hotel", "highlight"]},
            {title: "ficon icon-topic-search", searchTerms: ["topic", "search"]},
            {title: "ficon icon-topic-user", searchTerms: ["topic", "user"]},
            {title: "ficon icon-tornado", searchTerms: ["tornado"]},
            {title: "ficon icon-total-savings", searchTerms: ["total", "savings"]},
            {title: "ficon icon-total-support", searchTerms: ["total", "support"]},
            {title: "ficon icon-tours", searchTerms: ["tours"]},
            {title: "ficon icon-towels", searchTerms: ["towels"]},
            {title: "ficon icon-train-new-solid", searchTerms: ["train", "new", "solid"]},
            {title: "ficon icon-train-new", searchTerms: ["train", "new"]},
            {title: "ficon icon-train-station", searchTerms: ["train", "station"]},
            {title: "ficon icon-tram-station", searchTerms: ["tram", "station"]},
            {title: "ficon icon-transfer-both-ways", searchTerms: ["transfer", "both", "ways"]},
            {title: "ficon icon-transfer-one-ways", searchTerms: ["transfer", "one", "ways"]},
            {title: "ficon icon-transportation-hub", searchTerms: ["transportation", "hub"]},
            {title: "ficon icon-transportation", searchTerms: ["transportation"]},
            {title: "ficon icon-trash-b", searchTerms: ["trash", "b"]},
            {title: "ficon icon-trash", searchTerms: ["trash"]},
            {title: "ficon icon-travelers", searchTerms: ["travelers"]},
            {title: "ficon icon-trending-up", searchTerms: ["trending", "up"]},
            {title: "ficon icon-triangle-warning", searchTerms: ["triangle", "warning"]},
            {title: "ficon icon-tropical-storm", searchTerms: ["tropical", "storm"]},
            {title: "ficon icon-trouser-press", searchTerms: ["trouser", "press"]},
            {title: "ficon icon-tv-area", searchTerms: ["tv", "area"]},
            {title: "ficon icon-tv-flat-screen", searchTerms: ["tv", "flat", "screen"]},
            {title: "ficon icon-tv", searchTerms: ["tv"]},
            {title: "ficon icon-twin-bed", searchTerms: ["twin", "bed"]},
            {title: "ficon icon-umbrella", searchTerms: ["umbrella"]},
            {title: "ficon icon-unionpay", searchTerms: ["unionpay"]},
            {title: "ficon icon-unlock", searchTerms: ["unlock"]},
            {title: "ficon icon-upload-your-logo", searchTerms: ["upload", "your", "logo"]},
            {title: "ficon icon-user-b", searchTerms: ["user", "b"]},
            {title: "ficon icon-user-font-icon", searchTerms: ["user", "font", "icon"]},
            {title: "ficon icon-user", searchTerms: ["user"]},
            {title: "ficon icon-valet-parking", searchTerms: ["valet", "parking"]},
            {title: "ficon icon-vending-machine", searchTerms: ["vending", "machine"]},
            {title: "ficon icon-verified-checkmark", searchTerms: ["verified", "checkmark"]},
            {title: "ficon icon-view-point", searchTerms: ["view", "point"]},
            {title: "ficon icon-views", searchTerms: ["views"]},
            {title: "ficon icon-villa", searchTerms: ["villa"]},
            {title: "ficon icon-vip", searchTerms: ["vip"]},
            {title: "ficon icon-visa-stamp", searchTerms: ["visa", "stamp"]},
            {title: "ficon icon-visa", searchTerms: ["visa"]},
            {title: "ficon icon-wake-up-service", searchTerms: ["wake", "up", "service"]},
            {title: "ficon icon-walking", searchTerms: ["walking"]},
            {title: "ficon icon-want-to-talk", searchTerms: ["want", "to", "talk"]},
            {title: "ficon icon-washer", searchTerms: ["washer"]},
            {title: "ficon icon-watch", searchTerms: ["watch"]},
            {title: "ficon icon-water-park", searchTerms: ["water", "park"]},
            {title: "ficon icon-water-sports-motorized", searchTerms: ["water", "sports", "motorized"]},
            {title: "ficon icon-water-sports-non-motorized", searchTerms: ["water", "sports", "non", "motorized"]},
            {title: "ficon icon-watersports-equipment-rentals", searchTerms: ["watersports", "equipment", "rentals"]},
            {title: "ficon icon-weekend-discount", searchTerms: ["weekend", "discount"]},
            {title: "ficon icon-wheelchair-accessible", searchTerms: ["wheelchair", "accessible"]},
            {title: "ficon icon-wifi-additional-charge", searchTerms: ["wifi", "additional", "charge"]},
            {title: "ficon icon-wifi-in-public-areas", searchTerms: ["wifi", "in", "public", "areas"]},
            {title: "ficon icon-wifi", searchTerms: ["wifi"]},
            {title: "ficon icon-wind-surfing", searchTerms: ["wind", "surfing"]},
            {title: "ficon icon-wintry-mix-snow-sleet", searchTerms: ["wintry", "mix", "snow", "sleet"]},
            {title: "ficon icon-wired-internet", searchTerms: ["wired", "internet"]},
            {title: "ficon icon-wooden-parqueted-flooring", searchTerms: ["wooden", "parqueted", "flooring"]},
            {title: "ficon icon-world", searchTerms: ["world"]},
            {title: "ficon icon-x-icon", searchTerms: ["x", "icon"]},
            {title: "ficon icon-ycs-channels", searchTerms: ["ycs", "channels"]},
            {title: "ficon icon-ycs-dashboard", searchTerms: ["ycs", "dashboard"]},
            {title: "ficon icon-ycs-doc-csv", searchTerms: ["ycs", "doc", "csv"]},
            {title: "ficon icon-ycs-doc-excel", searchTerms: ["ycs", "doc", "excel"]},
            {title: "ficon icon-ycs-doc-pdf", searchTerms: ["ycs", "doc", "pdf"]},
            {title: "ficon icon-ycs-doc-update", searchTerms: ["ycs", "doc", "update"]},
            {title: "ficon icon-ycs-generic", searchTerms: ["ycs", "generic"]},
            {title: "ficon icon-ycs-no-show", searchTerms: ["ycs", "no", "show"]},
            {title: "ficon icon-year-hotel-built", searchTerms: ["year", "hotel", "built"]},
            {title: "ficon icon-year-hotel-last-renovated", searchTerms: ["year", "hotel", "last", "renovated"]},
            {title: "ficon icon-yoga-room", searchTerms: ["yoga", "room"]},
            {title: "ficon icon-zoom-bold", searchTerms: ["zoom", "bold"]},

            {title: "fab fa-zhihu", searchTerms: []}
            ]
    })
});