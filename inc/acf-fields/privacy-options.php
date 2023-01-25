<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_63d13ae0b726a',
	'title' => 'Privacy Options',
	'fields' => array(
		array(
			'key' => 'field_63d13ae0db409',
			'label' => 'Cookie Policy Page',
			'name' => 'cookie_policy_page',
			'aria-label' => '',
			'type' => 'post_object',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'post_type' => array(
				0 => 'page',
			),
			'taxonomy' => '',
			'return_format' => 'object',
			'multiple' => 0,
			'allow_null' => 0,
			'ui' => 1,
		),
		array(
			'key' => 'field_63d15c3c1352d',
			'label' => 'Cookie Consent Type',
			'name' => 'cookie_consent_type',
			'aria-label' => '',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'uc' => 'Usercentrics / Termageddon',
				'basic' => 'Basic Banner',
			),
			'default_value' => false,
			'return_format' => 'value',
			'multiple' => 0,
			'allow_null' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_63d13d16bdbfb',
			'label' => 'Usercentrics Code',
			'name' => 'cc_code',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => 'Just the code within "data-settings-id" of the Usermetrics JS code.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_63d15c3c1352d',
						'operator' => '==',
						'value' => 'uc',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_63d1489b76d13',
			'label' => 'Privacy Settings Link',
			'name' => 'privacy_settings_link',
			'aria-label' => '',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_63d15c3c1352d',
						'operator' => '==',
						'value' => 'uc',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Add link to privacy settings next to cookie policy.',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-cookie-policy-consent',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
));

endif;		