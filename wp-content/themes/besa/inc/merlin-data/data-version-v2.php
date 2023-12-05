<?php

class Besa_Merlin_Data_Version_V2
{
	private $verison = 'Besa Style 2 ';

    public function import_files_rtl(){
		
		$prefix_name = 'RTL ';

		$rev_sliders = array(
			"http://demosamples.thembay.com/besa2/rtl/revslider/slider-1.zip",
			"http://demosamples.thembay.com/besa2/rtl/revslider/slider-2.zip",
			"http://demosamples.thembay.com/besa2/rtl/revslider/slider-3.zip",
			"http://demosamples.thembay.com/besa2/rtl/revslider/slider-4.zip",
        );

		$data_url 	= "http://demosamples.thembay.com/besa2/rtl/data.xml";
        $widget_url = "http://demosamples.thembay.com/besa2/rtl/widgets.wie";

		return array(
			array(
				'import_file_name'           => 'Home 1',
				'home'                       => 'home-1',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/rtl/home-1/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/rtl/home-1/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_rtl/',
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
						'file_url'   => "http://demosamples.thembay.com/besa2/rtl/home-2/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/rtl/home-2/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_rtl/home-2',
			), 
			array(
				'import_file_name'           => 'Home 3',
				'home'                       => 'home-3',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/rtl/home-3/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/rtl/home-3/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_rtl/home-3/',
			),
			array(
				'import_file_name'           => 'Home 4',
				'home'                       => 'home-4',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/rtl/home-4/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/rtl/home-4/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_rtl/home-4/',
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
			"http://demosamples.thembay.com/besa2/dokan/revslider/slider-1.zip",
			"http://demosamples.thembay.com/besa2/dokan/revslider/slider-2.zip",
			"http://demosamples.thembay.com/besa2/dokan/revslider/slider-3.zip",
			"http://demosamples.thembay.com/besa2/dokan/revslider/slider-4.zip",
        );

		$data_url 	= "http://demosamples.thembay.com/besa2/dokan/data.xml";
        $widget_url = "http://demosamples.thembay.com/besa2/dokan/widgets.wie";

		return array(
			array(
				'import_file_name'           => 'Home 1',
				'home'                       => 'home-1',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/dokan/home-1/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/dokan/home-1/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2/',
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
						'file_url'   => "http://demosamples.thembay.com/besa2/dokan/home-2/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/dokan/home-2/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2/home-2',
			), 
			array(
				'import_file_name'           => 'Home 3',
				'home'                       => 'home-3',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/dokan/home-3/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/dokan/home-3/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2/home-3/',
			),
			array(
				'import_file_name'           => 'Home 4',
				'home'                       => 'home-4',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/dokan/home-4/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/dokan/home-4/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2/home-4/',
				'group_label_end'          	 => 'yes',
			),
		);
	}

	public function import_files_wcfm(){
		
		$prefix_name = 'WCFM ';

		$rev_sliders = array(
			"http://demosamples.thembay.com/besa2/wcfm/revslider/slider-1.zip",
			"http://demosamples.thembay.com/besa2/wcfm/revslider/slider-2.zip",
			"http://demosamples.thembay.com/besa2/wcfm/revslider/slider-3.zip",
			"http://demosamples.thembay.com/besa2/wcfm/revslider/slider-4.zip",
        );

		$data_url 	= "http://demosamples.thembay.com/besa2/wcfm/data.xml";
        $widget_url = "http://demosamples.thembay.com/besa2/wcfm/widgets.wie";

		return array(
			array(
				'import_file_name'           => 'Home 1',
				'home'                       => 'home-1',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/wcfm/home-1/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/wcfm/home-1/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_wcfm/',
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
						'file_url'   => "http://demosamples.thembay.com/besa2/wcfm/home-2/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/wcfm/home-2/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_wcfm/home-2',
			), 
			array(
				'import_file_name'           => 'Home 3',
				'home'                       => 'home-3',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/wcfm/home-3/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/wcfm/home-3/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_wcfm/home-3/',
			),
			array(
				'import_file_name'           => 'Home 4',
				'home'                       => 'home-4',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/wcfm/home-4/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/wcfm/home-4/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_wcfm/home-4/',
				'group_label_end'          	 => 'yes',
			),
		);
	}

	public function import_files_mvx(){
		
		$prefix_name = 'mvx ';

		$rev_sliders = array(
			"http://demosamples.thembay.com/besa2/wcmp/revslider/slider-1.zip",
			"http://demosamples.thembay.com/besa2/wcmp/revslider/slider-2.zip",
			"http://demosamples.thembay.com/besa2/wcmp/revslider/slider-3.zip",
			"http://demosamples.thembay.com/besa2/wcmp/revslider/slider-4.zip",
        );

		$data_url 	= "http://demosamples.thembay.com/besa2/wcmp/data.xml";
        $widget_url = "http://demosamples.thembay.com/besa2/wcmp/widgets.wie";

		return array(
			array(
				'import_file_name'           => 'Home 1',
				'home'                       => 'home-1',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/wcmp/home-1/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/wcmp/home-1/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_wcmp/',
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
						'file_url'   => "http://demosamples.thembay.com/besa2/wcmp/home-2/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/wcmp/home-2/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_wcmp/home-2',
			), 
			array(
				'import_file_name'           => 'Home 3',
				'home'                       => 'home-3',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/wcmp/home-3/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/wcmp/home-3/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_wcmp/home-3/',
			),
			array(
				'import_file_name'           => 'Home 4',
				'home'                       => 'home-4',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/wcmp/home-4/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/wcmp/home-4/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_wcmp/home-4/',
				'group_label_end'          	 => 'yes',
			),
		);
	}

	
	public function import_files_wcvendors(){
		
		$prefix_name = 'wcvendors ';

		$rev_sliders = array(
			"http://demosamples.thembay.com/besa2/wcvendors/revslider/slider-1.zip",
			"http://demosamples.thembay.com/besa2/wcvendors/revslider/slider-2.zip",
			"http://demosamples.thembay.com/besa2/wcvendors/revslider/slider-3.zip",
			"http://demosamples.thembay.com/besa2/wcvendors/revslider/slider-4.zip",
        );

		$data_url 	= "http://demosamples.thembay.com/besa2/wcvendors/data.xml";
        $widget_url = "http://demosamples.thembay.com/besa2/wcvendors/widgets.wie";


		return array(
			array(
				'import_file_name'           => 'Home 1',
				'home'                       => 'home-1',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/wcvendors/home-1/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/wcvendors/home-1/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_wcvendors/',
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
						'file_url'   => "http://demosamples.thembay.com/besa2/wcvendors/home-2/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/wcvendors/home-2/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_wcvendors/home-2',
			), 
			array(
				'import_file_name'           => 'Home 3',
				'home'                       => 'home-3',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/wcvendors/home-3/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/wcvendors/home-3/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_wcvendors/home-3/',
			),
			array(
				'import_file_name'           => 'Home 4',
				'home'                       => 'home-4',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/wcvendors/home-4/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/wcvendors/home-4/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_wcvendors/home-4/',
				'group_label_end'          	 => 'yes',
			),
		);
	}

	public function import_files_dark_mode(){
		
		$prefix_name = 'dark mode ';

		$rev_sliders = array(
			"http://demosamples.thembay.com/besa2/dark/revslider/slider-1.zip",
			"http://demosamples.thembay.com/besa2/dark/revslider/slider-2.zip",
			"http://demosamples.thembay.com/besa2/dark/revslider/slider-3.zip",
			"http://demosamples.thembay.com/besa2/dark/revslider/slider-4.zip",
        );

		$data_url 	= "http://demosamples.thembay.com/besa2/dark/data.xml";
        $widget_url = "http://demosamples.thembay.com/besa2/dark/widgets.wie";


		return array(
			array(
				'import_file_name'           => 'Home 1',
				'home'                       => 'home-1',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/dark/home-1/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/dark/home-1/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_dark/',
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
						'file_url'   => "http://demosamples.thembay.com/besa2/dark/home-2/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/dark/home-2/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_dark/home-2',
			), 
			array(
				'import_file_name'           => 'Home 3',
				'home'                       => 'home-3',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/dark/home-3/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/dark/home-3/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_dark/home-3/',
			),
			array(
				'import_file_name'           => 'Home 4',
				'home'                       => 'home-4',
                'import_file_url'          	 => $data_url,
                'import_widget_file_url'     => $widget_url,
				'import_redux'         => array(
					array(
						'file_url'   => "http://demosamples.thembay.com/besa2/dark/home-4/redux_options.json",
						'option_name' => 'besa_tbay_theme_options',
					),
				),
				'rev_sliders'                => $rev_sliders,
				'import_preview_image_url'   => "http://demosamples.thembay.com/besa2/dark/home-4/screenshot.jpg",
				'import_notice'              => esc_html__( 'After you import this demo, you will have to setup the slider separately.', 'besa' ),
				'preview_url'                => 'https://el3.thembaydev.com/besa2_dark/home-4/',
				'group_label_end'          	 => 'yes',
			),
		);
	}
}
