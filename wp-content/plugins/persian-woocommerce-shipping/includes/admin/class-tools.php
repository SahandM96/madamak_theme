<?php
/**
 * Developer : MahdiY
 * Web Site  : MahdiY.IR
 * E-Mail    : M@hdiY.IR
 */

class PWS_Settings_Tools extends PWS_Settings {

	protected static $_instance = null;

	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function get_sections() {
		return [
			[
				'id'    => 'pws_tools',
				'title' => 'ابزارهای کاربردی'
			],
			[
				'id'    => 'pws_debug',
				'title' => 'عیب یابی'
			]
		];
	}

	public function get_fields() {
		return [
			'pws_tools' => [
				[
					'name' => 'html',
					'desc' => '<b>آموزش:</b> برای پیکربندی حمل و نقل می توانید از <a href="https://yun.ir/pwsvideo" target="_blank">آپارات</a> فیلم های آموزشی افزونه را مشاهده کنید.',
					'type' => 'html'
				],
				[
					'label'   => 'وضعیت سفارشات کمکی',
					'name'    => 'status_enable',
					'default' => '0',
					'type'    => 'checkbox',
					'css'     => 'width: 350px;',
					'desc'    => 'جهت مدیریت بهتر سفارشات فروشگاه، وضعیت های زیر به پنل اضافه خواهد شد.
					<ol>
						<li>ارسال شده به انبار</li>
						<li>بسته بندی شده</li>
						<li>تحویل پیک</li>
					</ol>
					',
				],
				[
					'label'   => 'فقط روش ارسال رایگان',
					'name'    => 'hide_when_free',
					'default' => '0',
					'type'    => 'checkbox',
					'css'     => 'width: 350px;',
					'desc'    => 'در صورتی که یک روش ارسال رایگان در دسترس باشد، بقیه روش های ارسال مخفی می شوند.'
				],
				[
					'label'   => 'فقط روش ارسال پیک موتوری',
					'name'    => 'hide_when_courier',
					'default' => '0',
					'type'    => 'checkbox',
					'css'     => 'width: 350px;',
					'desc'    => 'در صورتی که پیک موتوری برای کاربر در دسترس باشد، بقیه روش های ارسال مخفی می شوند.'
				],
			],
			'pws_debug' => [
				[
					'name' => 'html',
					'desc' => 'بزودی!',
					'type' => 'html'
				],
			]
		];
	}

	public static function output() {

		$instance = self::instance();

		echo '<div class="wrap">';

		$instance->show_navigation();
		$instance->show_forms();

		echo '</div>';
	}
}