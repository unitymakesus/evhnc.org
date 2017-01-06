jQuery( document ).ready( function( $ ) {
    window.cf_translations_has_changes = false;
    if( _.isObject( CFTRANS ) ) {
        var cf_translations = new CF_Translations( CFTRANS, $,  _, Handlebars );
        cf_translations.init();

        var $form = $(document.getElementById('cf-translate-language-control'));
        var $language_selector = $(document.getElementById('cf-translate-language-chooser'));
        var $add_language = $(document.getElementById('cf-translate-add-language'));
        $add_language.select2();
        // $add_language.select2();
        var $add_language_button = $(document.getElementById('cf-translate-add-language-button'));

        cf_translations.populate_language_selector($language_selector);
        cf_translations.setup_language_form($form, $add_language, $add_language_button);

    }

    window.onbeforeunload = function (e) {
        if( true == window.cf_translations_has_changes ) {
            var message = CFTRANS.unsaved_translations;
            e = e || window.event;
            // For IE and Firefox
            if (e) {
                e.returnValue = message;
            }

            // For Safari
            return message;
        }

    };

});



