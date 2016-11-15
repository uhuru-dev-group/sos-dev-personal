<?php

return array(
    'sosConf' => [
        'creation_key' => 'YMlMd2TsjWZLTY3w9MHL0JydaWCSAKxXVW00SFg3WW1raz0H',
        'manufacture_key' => '23354f6dffee134ba486e16f61324bc5af57aef491847e8a989d1d373b0b391d',
        'assoc_private_key' => '23354f6dffee134ba486e16f61324bc5af57aef491847e8a989d1d373b0b391d',
        'endpoint_base' => [
            'jp' => 'https://sato-backend-jp.herokuapp.com',
            'qa' => 'https://sato-backend-qa.herokuapp.com',
        ],
        'db_url' => [
            'jp' => 'postgres://u7vgl9r10qfntl:p4hfsr35juk47aah400n7p5dbp8@ec2-52-204-14-54.compute-1.amazonaws.com:5432/d8rgg27101kp2c',
            'qa' => 'postgres://uevp4jld7h6gj5:pdoup8kavf1jn5fu0c1t76h6j3h@ec2-54-163-243-181.compute-1.amazonaws.com:5442/d8lfgqe8omi0tt',
        ],
    ],

	/**
	 * Localization & internationalization settings
	 */
	// 'language'           => 'en', // Default language
	// 'language_fallback'  => 'en', // Fallback language when file isn't available for default language
	// 'locale'             => 'en_US', // PHP set_locale() setting, null to not set

	/**
	 * Internal string encoding charset
	 */
	// 'encoding'  => 'UTF-8',

	/**
	 * DateTime settings
	 *
	 * server_gmt_offset	in seconds the server offset from gmt timestamp when time() is used
	 * default_timezone		optional, if you want to change the server's default timezone
	 */
	// 'server_gmt_offset'  => 0,
	// 'default_timezone'   => null,

	/**
	 * Logging Threshold.  Can be set to any of the following:
	 *
	 * Fuel::L_NONE
	 * Fuel::L_ERROR
	 * Fuel::L_WARNING
	 * Fuel::L_DEBUG
	 * Fuel::L_INFO
	 * Fuel::L_ALL
	 */
	// 'log_threshold'    => Fuel::L_WARNING,
	// 'log_path'         => APPPATH.'logs/',
	//'log_date_format'  => 'Y-m-d H:i:s',

	/**
	 * Security settings
	 */
	'security' => array(
		// 'csrf_autoload'            => false,
		// 'csrf_autoload_methods'    => array('post', 'put', 'delete'),
		// 'csrf_bad_request_on_fail' => false,
		// 'csrf_auto_token'          => false,
		// 'csrf_token_key'           => 'fuel_csrf_token',
		// 'csrf_expiration'          => 0,

		/**
		 * A salt to make sure the generated security tokens are not predictable
		 */
		// 'token_salt'            => 'put your salt value here to make the token more secure',

		/**
		 * Allow the Input class to use X headers when present
		 *
		 * Examples of these are HTTP_X_FORWARDED_FOR and HTTP_X_FORWARDED_PROTO, which
		 * can be faked which could have security implications
		 */
		// 'allow_x_headers'       => false,

		/**
		 * This input filter can be any normal PHP function as well as 'xss_clean'
		 *
		 * WARNING: Using xss_clean will cause a performance hit.
		 * How much is dependant on how much input data there is.
		 */
		'uri_filter'       => array('htmlentities'),

		/**
		 * This input filter can be any normal PHP function as well as 'xss_clean'
		 *
		 * WARNING: Using xss_clean will cause a performance hit.
		 * How much is dependant on how much input data there is.
		 */
		// 'input_filter'  => array(),

		/**
		 * This output filter can be any normal PHP function as well as 'xss_clean'
		 *
		 * WARNING: Using xss_clean will cause a performance hit.
		 * How much is dependant on how much input data there is.
		 */
		'output_filter'  => array('Security::htmlentities'),

		/**
		 * Encoding mechanism to use on htmlentities()
		 */
		// 'htmlentities_flags' => ENT_QUOTES,

		/**
		 * Whether to encode HTML entities as well
		 */
		// 'htmlentities_double_encode' => false,

		/**
		 * Whether to automatically filter view data
		 */
		// 'auto_filter_output'  => true,

		/**
		 * With output encoding switched on all objects passed will be converted to strings or
		 * throw exceptions unless they are instances of the classes in this array.
		 */
		'whitelisted_classes' => array(
			'Fuel\\Core\\Presenter',
			'Fuel\\Core\\Response',
			'Fuel\\Core\\View',
			'Fuel\\Core\\ViewModel',
			'Closure',
		),
	),

	/**
	 * Cookie settings
	 */
	// 'cookie' => array(
		// Number of seconds before the cookie expires
		// 'expiration'  => 0,
		// Restrict the path that the cookie is available to
		// 'path'        => '/',
		// Restrict the domain that the cookie is available to
		// 'domain'      => null,
		// Only transmit cookies over secure connections
		// 'secure'      => false,
		// Only transmit cookies over HTTP, disabling Javascript access
		// 'http_only'   => false,
	// ),

	/**
	 * Validation settings
	 */
	// 'validation' => array(
		/**
		 * Whether to fallback to global when a value is not found in the input array.
		 */
		// 'global_input_fallback' => true,
	// ),

	/**
	 * Controller class prefix
	 */
	 // 'controller_prefix' => 'Controller_',

	/**
	 * Routing settings
	 */
	// 'routing' => array(
		/**
		 * Whether URI routing is case sensitive or not
		 */
		// 'case_sensitive' => true,

		/**
		 *  Whether to strip the extension
		 */
		// 'strip_extension' => true,
	// ),

	/**
	 * To enable you to split up your application into modules which can be
	 * routed by the first uri segment you have to define their basepaths
	 * here. By default empty, but to use them you can add something
	 * like this:
	 *      array(APPPATH.'modules'.DS)
	 *
	 * Paths MUST end with a directory separator (the DS constant)!
	 */
	// 'module_paths' => array(
	// 	//APPPATH.'modules'.DS
	// ),

	/**
	 * To enable you to split up your additions to the framework, packages are
	 * used. You can define the basepaths for your packages here. By default
	 * empty, but to use them you can add something like this:
	 *      array(APPPATH.'modules'.DS)
	 *
	 * Paths MUST end with a directory separator (the DS constant)!
	 */
	'package_paths' => array(
		PKGPATH,
	),
);
