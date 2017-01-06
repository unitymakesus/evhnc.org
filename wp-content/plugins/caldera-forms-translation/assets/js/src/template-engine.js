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

