(function ($, window, document) {
    'use strict';

    /**
     * Update responsive settings name attribute
     */
    function updateNameAttributes() {
        $('.form-breakpoint-table tbody').find('tr').each(function (index) {
            var _tr = $(this),
                breakpoint = _tr.find('.input-responsive-breakpoint'),
                items = _tr.find('.input-responsive-items'),
                breakpointName = '_responsive_settings[' + index + '][breakpoint]',
                itemsName = '_responsive_settings[' + index + '][items]';

            breakpoint.attr('name', breakpointName);
            items.attr('name', itemsName);
        });
    }

    $(document).on('click', '.delete-breakpoint', function (e) {
        e.preventDefault();
        var _rows = $('.form-breakpoint-table tbody').find('tr');

        if (_rows.length === 1) {
            window.alert('At least one breakpoint is required.');
        } else if (_rows.length > 1) {
            if (window.confirm('Are you sure?')) {
                $(this).closest('tr').remove();
            }

            updateNameAttributes();
        }
    });

    $(document).on('click', '.add-breakpoint', function (e) {
        e.preventDefault();
        var _this = $(this),
            _row = _this.closest('tr'),
            _clone = _row.clone();

        _clone.find('input').val('');
        _row.after(_clone);

        updateNameAttributes();
    });

})(jQuery, window, document);