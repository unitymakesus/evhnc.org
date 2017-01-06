function CF_Translate_Field( field_data, language ){
    return  {
        language: language,
        ID: field_data.ID,
        caption: field_data.caption,
        label: field_data.label,
        default: field_data.default
    };

}