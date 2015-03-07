/* global BtnApp, jQuery */
(function(app, $, undefined){
    'use strict';

    var addEvents = function(context) {
        //submit form on select change
        $(context).find('#pageContainer').on('change', 'select.on-template-change', function() {

            // console.log($('option:selected', this).val());
            $(this).parents('form').submit();

            return false;
        });
    };

    app.init(function(msg, data) {
        addEvents(data.context);
    });

    app.refresh(function(msg, data) {
        addEvents(data.context);
    });

})(BtnApp, jQuery);