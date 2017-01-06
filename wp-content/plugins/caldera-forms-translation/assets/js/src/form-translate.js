function CF_Translate_Form( form, fields, language_code, save, $ ){
    var self = this;

    this.fields = {};

    this.field_objs = {};

    this.template_engine = new CF_Translate_Template_Engine( $, Handlebars );

    this.$field_selector = $( document.getElementById( 'cf-translate-field-select' ) );

    this.$field_edit_area = $(  document.getElementById( 'cf-translate-field-translator-wrap' ) );

    this.language = language_code;

    this.$save_button = $(  document.getElementById( 'cf-translations-save-button' ) );

    this.update_fields = {};

    this.init = function( ){

        self.$save_button.attr( 'disabled', false );
        self.$field_selector.find('option').remove();
        self.setup_fields();
        self.$field_selector.on( 'change', function(){
            var field = self.get_field( self.$field_selector.val() );
            if( _.isUndefined( field ) ){
                alert( CFTRANS.strings.error );
            }else{
                self.render_field_ui( field, self.language, self.$field_edit_area );

            }
        });

        self.$save_button.on( 'click', function(){
            if( _.isEmpty( self.update_fields ) ){
                alert( CFTRANS.strings.nothing_to_save );
                return;
            }


            var data = {
                action: 'cf_translate_save_translation',
                language: language_code,
                fields: self.update_fields,
                _wpnonce: save.rest_nonce,
                form_id: form.ID
            };

            $.post( save.api.save, data ).success( function(r){
                cf_translation_report( CFTRANS.strings.translations_saved, true );
                window.cf_translations_has_changes = false;
            }).error( function(r){
                cf_translation_report( CFTRANS.strings.save_error, false );
            });
        });
    };

    this.setup_fields = function(){
        var list = {};
        _.each( fields, function( field, id ){

            if( _.has( field, 'label' ) ){
                list[id] = {ID: id, label: field.label};
            }

        });
        self.template_engine.render_template( 'field_list', { fields: list}, self.$field_selector );
        self.$field_selector.parent().parent().show().css( 'visibility', 'visible' ).attr( 'aria-hidden', false );
    };

    this.get_field = function( id ){
        if( _.isUndefined( self.field_objs[ id ] ) ){
            if( _.isUndefined( fields[ id ] ) ){
                alert( settings.strings.error );
            }else{
                self.field_objs[ id ] = new CF_Translate_Field( fields[ id ], self.language );
            }
        }

        return self.field_objs[ id ];
    };

    this.render_field_ui = function( field ){
        self.$field_edit_area.empty();
        self.template_engine.render_template( 'field', field, self.$field_edit_area );
        self.bind( field.ID );
    };

    this.bind = function( id ){
        var $label = $( '#cf-translate-field-label-' + id + '-' + self.language );
        var $caption = $( '#cf-translate-field-caption-' + id + '-' + self.language );
        var $default = $( '#cf-translate-field-default-' + id + '-' + self.language );

        var  handle_click = function() {
            _.debounce( self.add_translation( id, language_code, {
                label : $label.val(),
                caption: $caption.val(),
                default: $default.val()
            }), 3000 );
        };


        var handle_change = function(e){
            window.cf_translations_has_changes = true;
            handle_click();
        };

        $label.on( 'change', handle_change );
        $caption.on( 'change', handle_change );
        $default.on( 'change', handle_change );

    };

    this.add_translation = function( field_id, language, translation ){
        form[ 'fields' ][ language ][ field_id ]  = translation;
        self.update_fields[ field_id ] = translation;
        window.cf_translations_has_changes = true;

    };


}
