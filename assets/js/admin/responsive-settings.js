(function ($, window, document) {
    'use strict';

    $(document).on('click', '.delete-breakpoint', function (e) {
        e.preventDefault();
        if (window.confirm('Are you sure?')) {
            $(this).closest('tr').remove();
        }
    });

    $(document).on('click', '.add-breakpoint', function (e) {
        e.preventDefault();
        var _this = $(this),
            _row = _this.closest('tr'),
            _clone = _row.clone();

        _clone.find('input').val('');
        _row.after(_clone);
    });

})(jQuery, window, document);