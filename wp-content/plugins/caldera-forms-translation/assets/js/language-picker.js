window.addEventListener("load", function(){

    function CF_Translate_Language_Picker( settings, $ ){
        var current = settings.default;
        var $field = $( document.getElementById( settings.field_id_attr ) );
        $field.on( 'change', function(e){
            var new_choice = $field.val();
            if( new_choice != current ){
                current = new_choice;
                $.get( settings.api, {
                    cf_lang: new_choice
                }).success( function( r ){
                    var wrap =  document.getElementById( settings.wrap_id_attr  );
                    wrap.innerHTML = r;
                }).error( function( r ){

                });
            }
        });

    }

    if ( 'object' == typeof CF_LANGUAGE_PICKER_FIELD ) {
        var chooser = CF_Translate_Language_Picker(CF_LANGUAGE_PICKER_FIELD, jQuery);
    }

});