<?php
if( ! defined( 'ABSPATH' )){
	exit;
}
?>
<script id="tpml--cf-translate-field" type="text/x-handlebars-template">
	<div class="caldera-config-group cf-translate-field-label-wrap cf-translate-field-group">
		<label for="cf-translate-field-label-{{ID}}-{{language}}" class="label-{{language}}">
			<?php esc_html_e('Field Label', 'caldera-forms-translation'); ?>
		</label>
		<div class="caldera-config-field">
			<input id="cf-translate-field-label-{{ID}}-{{language}}" type="text" value="{{label}}" data-language="{{language}}" name="label" data-field-id="{{ID}}" />
		</div>
	</div>

	<div class="caldera-config-group cf-translate-field-caption-wrap cf-translate-field-group">
		<label for="cf-translate-field-caption-{{ID}}-{{language}}" class="label-{{language}}">
			<?php esc_html_e('Field Description', 'caldera-forms-translation'); ?>
		</label>
		<div class="caldera-config-field">
			<input id="cf-translate-field-caption-{{ID}}-{{language}}" type="text" value="{{caption}}" data-language="{{language}}" name="caption" data-field-id="{{ID}}" />
		</div>
	</div>

	<div class="caldera-config-group cf-translate-field-default-wrap cf-translate-field-group">
		<label for="cf-translate-field-default-{{ID}}-{{language}}" class="label-{{language}}">
			<?php esc_html_e('Default', 'caldera-forms-translation'); ?>
		</label>
		<div class="caldera-config-field">
			<input id="cf-translate-field-default-{{ID}}-{{language}}" type="text" value="{{default}}" data-language="{{language}}" name="default" data-field-id="{{ID}}" />
		</div>
	</div>
</script>