<?php
/**
 * The feedback model class for themetechs SDK
 *
 * @package     themetechsSDK
 * @subpackage  Feedback
 * @copyright   Copyright (c) 2018, Badhon
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License
 * @since       1.0.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'themetechs_SDK_Feedback' ) ) :
	/**
	 * Feedback model for themetechs SDK.
	 */
	abstract class themetechs_SDK_Feedback {
		/**
		 * @var themetechs_SDK_Product $product themetechs Product.
		 */
		protected $product;

		/**
		 * @var string $feedback_url Url where to send the feedback
		 */
		private $feedback_url = 'http://feedback.themetechs.com/wordpress/wp-json/__pirate_feedback_/v1/feedback';

		/**
		 * themetechs_SDK_Feedback constructor.
		 *
		 * @param themetechs_SDK_Product $product_object Product Object.
		 */
		public function __construct( $product_object ) {
			if ( $product_object instanceof themetechs_SDK_Product ) {
				$this->product = $product_object;
			}
			$this->setup_hooks();
		}

		/**
		 * Registers the hooks and then delegates to the child
		 */
		public function setup_hooks() {
			$this->setup_hooks_child();
		}

		/**
		 * Calls the API
		 *
		 * @param string $attributes The attributes of the post body.
		 */
		protected function call_api( $attributes ) {
			$slug                  = $this->product->get_slug();
			$version               = $this->product->get_version();
			$attributes['slug']    = $slug;
			$attributes['version'] = $version;

			$response = wp_remote_post(
				$this->feedback_url, array(
					'body' => $attributes,
				)
			);
		}

		/**
		 * Randomizes the options array
		 *
		 * @param array $options The options array.
		 */
		function randomize_options( $options ) {
			$new  = array();
			$keys = array_keys( $options );
			shuffle( $keys );

			foreach ( $keys as $key ) {
				$new[ $key ] = $options[ $key ];
			}

			return $new;
		}

		/**
		 * Abstract function for delegating to the child
		 */
		protected abstract function setup_hooks_child();

	}
endif;
