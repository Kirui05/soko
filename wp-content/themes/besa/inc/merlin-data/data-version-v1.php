<?php

class Besa_Merlin_Data_Version_V1
{
	private $verison = 'Besa Style 1 ';

    public function import_files_rtl(){
		
		$prefix_name = 'RTL ';

		
		$rev_sliders = array(
			"http://demosamples.thembay.com/besa/rtl/revslider/home-1.zip",
			"http://demosamples.thembay.com/besa/rtl/revslider/home-2.zip",
			"http://demosamples.thembay.com/besa/rtl/revslider/home-3.zip",
			"http://demosamples.thembay.com/besa/rtl/revslider/slider-1.zip",
			"http://demosamples.thembay.com/besa/rtl/revslider/slider-flash-sale.zip",
        );

		$data_url 	= "http://demosamples.thembay.com/besa/rtl/data.xml";
        $widget_url = "http://demosamples.thembay.com/besa/rtl/widgets.wie";

		return array(
			array(
				'import_file_name'           => 'Home 1',
				'home'                       => 'home-1',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa/rtl/home-1/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa/rtl/home-1/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://elementor4.thembay.com/besa_rtl/',
				'group_label_start'          => 'yes',
				'group_label_name'           => $this->verison.$prefix_name,
			),
			array(
				'import_file_name'           => 'Home 2',
				'home'                       => 'home-2',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa/rtl/home-2/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa/rtl/home-2/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://elementor4.thembay.com/besa_rtl/home-2',
			), 
			array(
				'import_file_name'           => 'Home 3',
				'home'                       => 'home-3',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa/rtl/home-3/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa/rtl/home-3/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://elementor4.thembay.com/besa_rtl/home-3/',
				'group_label_end'          	 => 'yes',
			),
		);
	}

	public function import_files_dokan(){
		
		$prefix_name = '';

		if( class_exists('WeDevs_Dokan') ) {
			$prefix_name = 'Dokan ';
		}

		$rev_sliders = array(
			"http://demosamples.thembay.com/besa/dokan/revslider/home-1.zip",
			"http://demosamples.thembay.com/besa/dokan/revslider/home-2.zip",
			"http://demosamples.thembay.com/besa/dokan/revslider/home-3.zip",
			"http://demosamples.thembay.com/besa/dokan/revslider/slider-1.zip",
			"http://demosamples.thembay.com/besa/dokan/revslider/slider-flash-sale.zip",
        );

		$data_url 	= "http://demosamples.thembay.com/besa/dokan/data.xml";
        $widget_url = "http://demosamples.thembay.com/besa/dokan/widgets.wie";

		return array(
			array(
				'import_file_name'           => 'Home 1',
				'home'                       => 'home-1',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa/dokan/home-1/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa/dokan/home-1/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://elementor4.thembay.com/besa/',
                'group_label_start'          => 'yes',
				'group_label_name'           => $this->verison.$prefix_name,
			),
			array(
				'import_file_name'           => 'Home 2',
				'home'                       => 'home-2',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa/dokan/home-2/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa/dokan/home-2/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://elementor4.thembay.com/besa/home-2',
			), 
			array(
				'import_file_name'           => 'Home 3',
				'home'                       => 'home-3',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa/dokan/home-3/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa/dokan/home-3/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://elementor4.thembay.com/besa/home-3/',
				'group_label_end'          	 => 'yes',
			),
		);
	}

	public function import_files_wcfm(){
		
		$prefix_name = 'WCFM ';

		$rev_sliders = array(
			"http://demosamples.thembay.com/besa/wcfm/revslider/home-1.zip",
			"http://demosamples.thembay.com/besa/wcfm/revslider/home-2.zip",
			"http://demosamples.thembay.com/besa/wcfm/revslider/home-3.zip",
			"http://demosamples.thembay.com/besa/wcfm/revslider/slider-1.zip",
			"http://demosamples.thembay.com/besa/wcfm/revslider/slider-flash-sale.zip",
        );

		$data_url 	= "http://demosamples.thembay.com/besa/wcfm/data.xml";
        $widget_url = "http://demosamples.thembay.com/besa/wcfm/widgets.wie";

		return array(
			array(
				'import_file_name'           => 'Home 1',
				'home'                       => 'home-1',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa/wcfm/home-1/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa/wcfm/home-1/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://elementor4.thembay.com/besa_wcfm/',
				'group_label_start'          => 'yes',
				'group_label_name'           => $this->verison.$prefix_name,
			),
			array(
				'import_file_name'           => 'Home 2',
				'home'                       => 'home-2',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa/wcfm/home-2/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa/wcfm/home-2/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://elementor4.thembay.com/besa_wcfm/home-2',
			), 
			array(
				'import_file_name'           => 'Home 3',
				'home'                       => 'home-3',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa/wcfm/home-3/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa/wcfm/home-3/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://elementor4.thembay.com/besa_wcfm/home-3/',
				'group_label_end'          	 => 'yes',
			),
		);
	}

	public function import_files_mvx(){
		
		$prefix_name = 'mvx ';

		$rev_sliders = array(
			"http://demosamples.thembay.com/besa/wcmp/revslider/home-1.zip",
			"http://demosamples.thembay.com/besa/wcmp/revslider/home-2.zip",
			"http://demosamples.thembay.com/besa/wcmp/revslider/home-3.zip",
			"http://demosamples.thembay.com/besa/wcmp/revslider/slider-1.zip",
			"http://demosamples.thembay.com/besa/wcmp/revslider/slider-flash-sale.zip",
        );

		$data_url 	= "http://demosamples.thembay.com/besa/wcmp/data.xml";
        $widget_url = "http://demosamples.thembay.com/besa/wcmp/widgets.wie";

		return array(
			array(
				'import_file_name'           => 'Home 1',
				'home'                       => 'home-1',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa/wcmp/home-1/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa/wcmp/home-1/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://elementor4.thembay.com/besa_wcmp/',
				'group_label_start'          => 'yes',
				'group_label_name'           => $this->verison.$prefix_name,
			),
			array(
				'import_file_name'           => 'Home 2',
				'home'                       => 'home-2',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa/wcmp/home-2/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa/wcmp/home-2/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://elementor4.thembay.com/besa_wcmp/home-2',
			), 
			array(
				'import_file_name'           => 'Home 3',
				'home'                       => 'home-3',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa/wcmp/home-3/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa/wcmp/home-3/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://elementor4.thembay.com/besa_wcmp/home-3/',
				'group_label_end'          	 => 'yes',
			),
		);
	}

	
	public function import_files_wcvendors(){
		
		$prefix_name = 'wcvendors ';

		$rev_sliders = array(
			"http://demosamples.thembay.com/besa/wcvendors/revslider/home-1.zip",
			"http://demosamples.thembay.com/besa/wcvendors/revslider/home-2.zip",
			"http://demosamples.thembay.com/besa/wcvendors/revslider/home-3.zip",
			"http://demosamples.thembay.com/besa/wcvendors/revslider/slider-1.zip",
			"http://demosamples.thembay.com/besa/wcvendors/revslider/slider-flash-sale.zip",
        );

		$data_url 	= "http://demosamples.thembay.com/besa/wcvendors/data.xml";
        $widget_url = "http://demosamples.thembay.com/besa/wcvendors/widgets.wie";

		return array(
			array(
				'import_file_name'           => 'Home 1',
				'home'                       => 'home-1',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa/wcvendors/home-1/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa/wcvendors/home-1/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://elementor4.thembay.com/besa_wcvendors/',
				'group_label_start'          => 'yes',
				'group_label_name'           => $this->verison.$prefix_name,
			),
			array(
				'import_file_name'           => 'Home 2',
				'home'                       => 'home-2',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa/wcvendors/home-2/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa/wcvendors/home-2/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://elementor4.thembay.com/besa_wcvendors/home-2',
			), 
			array(
				'import_file_name'           => 'Home 3',
				'home'                       => 'home-3',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa/wcvendors/home-3/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa/wcvendors/home-3/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://elementor4.thembay.com/besa_wcvendors/home-3/',
				'group_label_end'          	 => 'yes',
			),
		);
	}
}
