<?php
if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_5f3c4b6387167',
        'title' => 'اطلاعات محصول',
        'fields' => array(
            array(
                'key' => 'field_5f41969d60475',
                'label' => 'اطلاعات',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
            ),
            array(
                'key' => 'field_5f3c4b850c474',
                'label' => 'نوع محصول',
                'name' => 'product-type',
                'type' => 'text',
                'instructions' => '( مثال : گیفت کارت )',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '33',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5f4e73d8f19b2',
                'label' => 'تصویر پس‌زمینه',
                'name' => 'product-bg',
                'type' => 'image',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '33',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'url',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
            ),
            array(
                'key' => 'field_5f4e76d429904',
                'label' => 'نمایش به عنوان محصول پیشنهادی',
                'name' => 'product-special',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 0,
                'ui_on_text' => '',
                'ui_off_text' => '',
            ),
            array(
                'key' => 'field_5f5f6898c02e6',
                'label' => 'لینک نقد و بررسی محصول',
                'name' => 'naghd',
                'type' => 'url',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
            ),
            array(
                'key' => 'field_5f41968960474',
                'label' => 'ویدیو',
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
            ),
            array(
                'key' => 'field_5f4196d560477',
                'label' => 'لینک ویدیو',
                'name' => 'product-video-link',
                'type' => 'url',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
            ),
            array(
                'key' => 'field_5f4196aa60476',
                'label' => 'تامبنیل ویدیو (تصویر ویدیو)',
                'name' => 'product-video-thumb',
                'type' => 'image',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_5f4196d560477',
                            'operator' => '!=empty',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'url',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'product',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
    
    acf_add_local_field_group(array(
        'key' => 'group_5f3d2cbf277b3',
        'title' => 'دسته بندی محصولات',
        'fields' => array(
            array(
                'key' => 'field_5f3d2cc8d5c51',
                'label' => 'نام دسته بندی (انگلیسی)',
                'name' => 'product-category-name',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'product_cat',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
    
    acf_add_local_field_group(array(
        'key' => 'group_5f4a71afe1759',
        'title' => 'سی‌دی‌کی ها و اکانت های مشتری',
        'fields' => array(
            array(
                'key' => 'field_5f4a71c268687',
                'label' => 'اکانت های خریداری شده',
                'name' => 'user-accounts',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => '',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5f4a71e368688',
                        'label' => 'محصول',
                        'name' => 'user-product',
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
                            0 => 'product',
                        ),
                        'taxonomy' => '',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'return_format' => 'id',
                        'ui' => 1,
                    ),
                    array(
                        'key' => 'field_5f4a748468689',
                        'label' => 'نام کاربری اکانت یا سی‌دی‌کی',
                        'name' => 'user-product-username',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_5f4a74a16868a',
                        'label' => 'رمز اکانت یا سی‌دی‌کی',
                        'name' => 'user-product-pass',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                ),
            ),
            array(
                'key' => 'field_5f568ca4fdc48',
                'label' => 'سی‌دی‌کی های خریداری شده',
                'name' => 'user-cdkeys',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => '',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5f568ca4fdc49',
                        'label' => 'محصول',
                        'name' => 'user-product-c',
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
                            0 => 'product',
                        ),
                        'taxonomy' => '',
                        'allow_null' => 0,
                        'multiple' => 0,
                        'return_format' => 'id',
                        'ui' => 1,
                    ),
                    array(
                        'key' => 'field_5f568ca4fdc4a',
                        'label' => 'کد',
                        'name' => 'cdkey-code',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'user_form',
                    'operator' => '==',
                    'value' => 'all',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
    
    acf_add_local_field_group(array(
        'key' => 'group_5f3fbf4094dc3',
        'title' => 'کمپانی ها',
        'fields' => array(
            array(
                'key' => 'field_5f3fbf451f4f8',
                'label' => 'لوگو کمپانی',
                'name' => 'company-logo',
                'type' => 'image',
                'instructions' => '( ترجیحا به صورت PNG و بدون پس‌زمینه [Transparrent] )',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'url',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'companies',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
    
    acf_add_local_field_group(array(
        'key' => 'group_5f4fe0f00dbd5',
        'title' => 'گیم‌پلاس',
        'fields' => array(
            array(
                'key' => 'field_5f4fe0f82d3fd',
                'label' => '',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '<h1>گیم‌پلاس</h1>
    <p>Mangaer,Graphist and Adviser: Sina Mohammadi</p>
    <p>Developer: Ahmad Karimzade</p>
    <p>Artark.ir</p>
    <p>تمامی حقوق قالب متعلق به آرت‌آرک میباشد و هرگونه کپی برداری, ایجاد تغییرات بدون کسب اجازه, و انتشار رایگان غیر مجاز بوده و پیگرد
            قانونی دارد.</p>',
                'new_lines' => 'wpautop',
                'esc_html' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'gameplus-settings',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
    
    endif;
?>