<?php

class Besa_Merlin_Data_Version_Auctions
{

	public function import_files_woo_simple_auction(){
		$data_url 	= "http://demosamples.thembay.com/besa2/woo-auctions/data.xml";
        $widget_url = "http://demosamples.thembay.com/besa2/woo-auctions/widgets.wie";

		return array(
			array(
				'import_file_name'           => 'Home',
				'home'                       => 'home',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/woo-auctions/home/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/woo-auctions/home/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_wooauction/',
				'group_label_start'          => 'yes',
				'group_label_name'           => 'Besa Woo Auctions',
				'group_label_end'          	 => 'yes',
			),
		);
	}

	public function import_files_yith_auction(){
		$data_url 	= "http://demosamples.thembay.com/besa2/yith-auctions/data.xml";
        $widget_url = "http://demosamples.thembay.com/besa2/yith-auctions/widgets.wie";

		return array(
			array(
				'import_file_name'           => 'Home',
				'home'                       => 'home',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/yith-auctions/home/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/yith-auctions/home/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_yithauction/',
				'group_label_start'          => 'yes',
				'group_label_name'           => 'Besa YITH Auctions',
				'group_label_end'          	 => 'yes',
			),
		);
	}
}
