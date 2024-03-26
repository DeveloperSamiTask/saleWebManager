"use strict";
$(function () {
    var e = $(".selectpicker"),
        c = $(".select2-icons");
    function l(e) {
        return e.id
            ? "<i class='" + $(e.element).data("icon") + " me-2'></i>" + e.text
            : e.text;
    }
    e.length && (e.selectpicker(), handleBootstrapSelectEvents()),
        
        c.length &&
            (select2Focus(c),
            c.wrap('<div class="position-relative"></div>').select2({
                dropdownParent: c.parent(),
                templateResult: l,
                templateSelection: l,
                escapeMarkup: function (e) {
                    return e;
                },
            }));
});
