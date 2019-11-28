<?
if( function_exists('acf_add_local_field_group') ):
	acf_add_local_field_group(array(
		'key' => 'group_5c99f882357c0',
		'title' => 'Основные настройки',
		'fields' => array(
			array(
				'key' => 'field_5c99f8f845922',
				'label' => 'Телефон',
				'name' => 'phone',
				'type' => 'text'
			),
			array(
				'key' => 'field_5c99f90c45923',
				'label' => 'Почта',
				'name' => 'mail',
				'type' => 'text'
			),
			array(
				'key' => 'field_5c99f95c4590150',
				'label' => 'Время работы',
				'name' => 'time',
				'type' => 'text'
			),
			array(
				'key' => 'field_5c87a86bdb0fb',
				'label' => 'Баннеры',
				'name' => 'banners',
				'type' => 'repeater',
				'layout' => 'row',
				'button_label' => '',
				'sub_fields' => array(
					array(
						'key' => 'field_5c87a889db0fc',
						'label' => 'Изображение',
						'name' => 'image',
						'type' => 'image',
						'return_format' => 'url',
						'preview_size' => 'thumbnail',
						'library' => 'all',
					),
					array(
						'key' => 'field_3c47a6cedb0ff12',
						'label' => 'Заголовок',
						'name' => 'title',
						'type' => 'text',
					),
					array(
						'key' => 'field_3c47a6cedb0ff123',
						'label' => 'Текст',
						'name' => 'desc',
						'type' => 'text',
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'options',
				),
			),
		)
	));
	
endif;