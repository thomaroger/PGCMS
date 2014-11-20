<?php
return array(
	'assetic_configuration' => array(
		'modules' => array(
			'admin_default_base' => array(
				'root_path' => array(
					__DIR__ . '/../../../../design/admin/default/pgcms/assets',
				),
				'collections' => array(
				    
                    'admin_images' => array(
                        'assets' => array(
                            'images/*.ico',
                            'images/**/*.gif',
                            'images/**/*.jpg',
                            'images/**/*.png',
                        ),
                        'options' => array(
                            'move_raw' => true,
                            'output' => 'zfcadmin/',
                        ),
                    ),
                    
                    'admin_fonts' => array(
                        'assets' => array(
                            'fonts/*.eot',
                            'fonts/*.svg',
                            'fonts/*.ttf',
                            'fonts/*.woff',
                        ),
                        'options' => array(
                            'move_raw' => true,
                            'output' => 'zfcadmin/',
                        ),
                    ),
                    
                    /**
                     * MAIN CSS FILES
                     */
                    'playground_css' => array(
                        'assets' => array(
                            'bootstrap.min.css'              => 'css/lib/bootstrap.min.css',
                            'light-blue.css'                 => 'css/lib/light-blue.css',
                            'bootstrap-wysihtml5.css'        => 'css/lib/bootstrap-wysihtml5.css',
                            'flags.css'                      => 'css/lib/flags.css',
                            'style.css'                      => 'css/style.css',
                        ),
                        'options' => array(
                            'output' => 'zfcadmin/css/base'
                        ),
                    ),
                    
                    /**
                     * MAIN JS FILES
                     */
                    'playground_js' => array(
                        'assets' => array(
                            'jquery-1.10.2.min.js'          => 'js/lib/jquery-1.10.2.min.js',
                            'jquery-ui.1.10.4.min.js'       => 'js/lib/jquery-ui.1.10.4.min.js',
                            'jquery-maskedinput.min.js'     => 'js/lib/jquery-maskedinput.min.js',
                            'jquery.nestable.js'            => 'js/lib/jquery.nestable.js',
                            'jquery.highlight.js'           => 'js/lib/jquery.highlight.js',
                            'bootstrap.min.js'              => 'js/lib/bootstrap.min.js',
                            'bootstrap-select.js'           => 'js/lib/bootstrap-select.js',
                            'bootstrap-datepicker.js'       => 'js/lib/bootstrap-datepicker.js',
                            'icheck.min.js'                 => 'js/lib/icheck.min.js',
                            'wysihtml5-0.3.0_rc2.js'        => 'js/lib/wysihtml5-0.3.0_rc2.js',
                            'bootstrap-wysihtml5.js'        => 'js/lib/bootstrap-wysihtml5.js',
                            'main.js'                       => 'js/main.js',
                        ),
                        'filters' => array(),
                        'options' => array(
                            'output' => 'zfcadmin/js/script_playground'
                        ),
                    ),
				),
			),
		),
        
        'routes' => array(
            'admin.*' => array(
                '@playground_css',
                '@playground_js',
            ),           
        ),
	),
);