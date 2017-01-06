/*! cf-translations - v1.0.0 - 2016-11-07 */function CF_Translate_Field( field_data, language ){
    return  {
        language: language,
        ID: field_data.ID,
        caption: field_data.caption,
        label: field_data.label,
        default: field_data.default
    };

}function CF_Translate_Form( form, fields, language_code, save, $ ){
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



function CF_Translate_Template_Engine( $, Handlebars ){
    var self = this;

    this.templates = {
        field: $( '#tpml--cf-translate-field' ).html(),
        field_list: $( '#tpml--cf-translate-field-list' ).html()
    };

    this.get_template = function( template ){
        if( _.has( self.templates, template ) ){
            return self.templates[ template ];
        }

        return false;
    };

    this.render_template = function( template_name, data, $target ){
        var source   = self.get_template( template_name );
        if( false == source ){
            return false;
        }

        var template = Handlebars.compile(source);
        var html = template( data );
        $target.append( html );
    }

}

function CF_Translations( settings, $, _, Handlebars ){

    var self = this;

    this.languages = [];

    this.language_codes = {};

    this.$language_selector = null;

    var fields = [];

    this.init = function(){
        self.language_codes = cf_lang_codes;

        if( ! _.isEmpty( settings.form.languages ) ){
            self.languages = settings.form.languages;
        }else{
            self.add_language( settings.local );
        }
    };

    this.add_language = function( language ){
        if ( self.is_language_known( language ) ){
            self.languages[ language ] = self.find_language( language );
            return true;
        }

        return false;

    };

    this.populate_language_selector = function( $selector ){
        self.$language_selector = $selector;
        _.each( self.languages, function( code ){
            self.add_language_option( code );
        });
    };

    this.get_language_obj = function( code ){


        if ( _.isObject( self.language_codes[ code ] ) ) {
            return self.language_codes[code];
        } else {
            alert( settings.strings.bad_language );
        }
    };

    this.add_language_option = function( code ){
        var opts = [];
        var $options = $( '#' + self.$language_selector.attr( 'id' ) + ' option' );
        var found = false;
        var lang = self.find_language( code );
        if ( $options.length ) {
            var $opt;
            $options.each(function (i, opt) {
                $opt = $( opt );
                if( _.isString( $opt.val() ) && $opt.val() === code ){
                    found = true;
                    return false;
                }
            });
        }

        if ( ! found && 'object' == typeof lang ) {
            var lang = self.get_language_obj( code );
            self.$language_selector.append('<option value="' + code + '">' + lang.name + '</option>');
        }
    };

    this.setup_language_form = function( $form, $add_language, $add_language_button ){
        $form.on( 'submit',  function(e){
            e.preventDefault();
            self.load_language( self.$language_selector.val() );
        });

        $add_language_button.on( 'click', function(e){
            e.preventDefault();
            var lang = $add_language.val();
            if( self.is_language_known( lang ) ){
                if( ! _.has( settings.form.fields, lang ) ){
                    self.get_language_fields( lang );
                }
                self.add_language( lang );
                self.add_language_option(  lang  );
                self.$language_selector.val( lang );

            }else{
                cf_translation_report( settings.strings.bad_language, false );
            }
        });

    };

    this.get_language_fields = function( language ){
        $.get( settings.data.api.lang, {
            action: 'cf_translate_get_language',
            form_id: settings.form.ID,
            language: language,
            _wpnonce: settings.data.rest_nonce
        }).success( function( r ){
            settings.form.fields[ language ] = language;
            fields[ language ] = r;
        } ).error( function( r ){
            if( _.has( r, 'message' ) ){
                alert( r.message );
            }else{
                alert( settings.string.error );
            }
        });
    };

    this.find_language = function( language ){
        if( self.is_language_known( language ) ){
            return self.language_codes[ language ];
        }
    };

    this.is_language_known = function( language ) {
        return _.has( self.language_codes, language );
    };

    this.find_language_by_name = function( language ){
        //could be valid code
        if( self.is_language_known( language ) ){
            return self.find_language( language );
        }


    };

    this.load_language = function( language_code ){
        var lfields;
        if( _.has( fields, language_code ) ){
            lfields = fields[ language_code ];
        }else if( _.has( settings.form.fields, language_code ) && ! _.isEmpty( settings.form.fields[ language_code ] ) ){
            lfields = settings.form.fields[ language_code ];
        }

        var translator = new CF_Translate_Form( settings.form, lfields, language_code, settings.data, $ );
        translator.init();
    };

}

function cf_translation_report( message, good ){
    var $header = jQuery( document.getElementById( 'cf-translate-header' ) );
    var $not_saved = jQuery( document.getElementById( 'cf-translations-not-saved' ) );
    var $saved = jQuery( document.getElementById( 'cf-translations-saved' ) );
    if( good ){
        $not_saved.html( '' ).hide().css( 'visibility', 'hidden' ).attr( 'aria-hidden', true );
        $saved.html( message ).show().css( 'visibility', 'visible' ).attr( 'aria-hidden', false );

    }else{
        $not_saved.html( message ).show().css( 'visibility', 'visible' ).attr( 'aria-hidden', false );
        $saved.html( '' ).hide().css( 'visibility', 'hidden' ).attr( 'aria-hidden', true );
    }
}
