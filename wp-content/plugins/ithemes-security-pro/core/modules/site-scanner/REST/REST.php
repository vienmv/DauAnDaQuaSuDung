<?php

namespace iThemesSecurity\Site_Scanner\REST;

use iThemesSecurity\Contracts\Runnable;

class REST implements Runnable {
	/** @var \WP_REST_Controller[] */
	private $controllers;

	public function __construct( \WP_REST_Controller ...$controllers ) {
		$this->controllers = $controllers;
	}

	public function run() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	public function register_routes() {
		foreach ( $this->controllers as $controller ) {
			$controller->register_routes();
		}

		register_rest_route( 'ithemes-security/v1', 'site-scanner/verify-scan', [
			'methods'             => 'POST',
			'callback'            => [ $this, 'handle_verification_request' ],
			'permission_callback' => '__return_true',
			'args'                => [
				'secret' => [
					'type'      => 'string',
					'required'  => true,
					'minLength' => 64,
					'maxLength' => 64,
				],
			],
		] );
	}

	public function handle_verification_request( \WP_REST_Request $request ) {
		$key_pair = \ITSEC_Site_Scanner_API::get_key_pair();

		if ( ! $key_pair['secret'] || ! hash_equals( $key_pair['secret'], $request['secret'] ) ) {
			\ITSEC_Log::add_debug( 'site-scanner', 'verify-scan::invalid_secret', [
				'params' => $request->get_params(),
			] );

			return new \WP_Error( 'invalid_secret', __( 'Secret did not match', 'it-l10n-ithemes-security-pro' ), [ 'status' => \WP_Http::OK ] );
		}

		\ITSEC_Log::add_debug( 'site-scanner', 'verify-scan::valid_secret', [
			'params' => $request->get_params(),
		] );

		return new \WP_REST_Response( [ 'public' => $key_pair['public'] ] );
	}

}
