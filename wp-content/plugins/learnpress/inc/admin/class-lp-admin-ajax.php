<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( !class_exists( 'LP_Admin_Ajax' ) ) {

	/**
	 * Class LP_Admin_Ajax
	 */
	class LP_Admin_Ajax {
		/**
		 * Add action ajax
		 */
		public static function init() {
			$ajaxEvents = array(
				'create_page'                     => false,
				'add_quiz_question'               => false,
				'convert_question_type'           => false,
				'update_quiz_question_state'      => false,
				'update_editor_hidden'            => false,
				'update_curriculum_section_state' => false,
				'quick_add_item'                  => false,
				'add_new_item'                    => false,
				'toggle_lesson_preview'           => false,
				'remove_course_items'             => false,
				'search_courses'                  => false,
				'add_item_to_order'               => false,
				'remove_order_item'               => false,
				'plugin_action'                   => false,
				'search_questions'                => false,
				'remove_quiz_question'            => false,
				'modal_search_items'              => false,
				'add_item_to_section'             => false,
				'remove_course_section'           => false,
				/////////////
				'quick_add_lesson'                => false,
				'quick_add_quiz'                  => false,
				'be_teacher'                      => false,
				'custom_stats'                    => false,
				'ignore_setting_up'               => false,
				'get_page_permalink'              => false,
				'dummy_image'                     => false,
				'update_add_on_status'            => false,
				'plugin_install'                  => false,
				'bundle_activate_add_ons'         => false,
				'install_sample_data'             => false
			);
			foreach ( $ajaxEvents as $ajaxEvent => $nopriv ) {
				add_action( 'wp_ajax_learnpress_' . $ajaxEvent, array( __CLASS__, $ajaxEvent ) );
				// enable for non-logged in users
				if ( $nopriv ) {
					add_action( 'wp_ajax_nopriv_learnpress_' . $ajaxEvent, array( __CLASS__, $ajaxEvent ) );
				}
			}
			add_filter( 'learn_press_modal_search_items_exclude', array( __CLASS__, '_modal_search_items_exclude' ), 10, 4 );
			add_filter( 'learn_press_modal_search_items_not_found', array( __CLASS__, '_modal_search_items_not_found' ), 10, 2 );
			do_action( 'learn_press_admin_ajax_load', __CLASS__ );
		}

		static function _modal_search_items_not_found( $message, $type ) {
			switch ( $type ) {
				case 'lp_lesson':
					$message = __( 'No lessons found', 'learn_press' );
					break;
				case 'lp_quiz':
					$message = __( 'No quizzes found', 'learn_press' );
					break;
				case 'lp_question':
					$message = __( 'No questions found', 'learn_press' );
					break;
			}
			return $message;
		}

		static function _modal_search_items_exclude( $exclude, $type, $context = '', $context_id = null ) {
			global $wpdb;
			$exclude2 = array();
			$user     = learn_press_get_current_user();
			switch ( $type ) {
				case 'lp_lesson':
				case 'lp_quiz':
					$query    = $wpdb->prepare( "
						SELECT item_id
						FROM {$wpdb->prefix}learnpress_section_items
						WHERE %d
					", 1 );
					$exclude2 = $wpdb->get_col( $query );
					if ( ( $context == 'course' ) && ( get_post_type( $context_id ) == 'lp_course' ) ) {
						$course_author = get_post_field( 'post_author', $context_id );

					}
					break;
				case 'lp_question':

			}
			if ( $exclude2 && $exclude ) {
				$exclude = array_merge( $exclude, $exclude2 );
			} else if ( $exclude2 ) {
				$exclude = $exclude2;
			}
			return $exclude;
		}

		static function add_item_to_section() {
			global $wpdb;
			$section = learn_press_get_request( 'section' );
			if ( !$section ) {
				wp_die( __( 'Error', 'learn_press' ) );
			}
			$items = (array) learn_press_get_request( 'item' );
			if ( !$items ) {
				$max_order = $wpdb->get_var( $wpdb->prepare( "SELECT max() FROM {$wpdb}learnpress_section_items WHERE section_id = %d", $section ) );
				foreach ( $items as $item ) {

				}
			}
		}

		static function modal_search_items() {
			global $wpdb;

			$user       = learn_press_get_current_user();
			$term       = (string) ( stripslashes( learn_press_get_request( 'term' ) ) );
			$type       = (string) ( stripslashes( learn_press_get_request( 'type' ) ) );
			$context    = (string) ( stripslashes( learn_press_get_request( 'context' ) ) );
			$context_id = (string) ( stripslashes( learn_press_get_request( 'context_id' ) ) );
			$exclude    = array();

			if ( !empty( $_GET['exclude'] ) ) {
				$exclude = array_map( 'intval', $_GET['exclude'] );
			}
			$exclude = array_unique( (array) apply_filters( 'learn_press_modal_search_items_exclude', $exclude, $type, $context, $context_id ) );
			$exclude = array_map( 'intval', $exclude );
			$args    = array(
				'post_type'      => array( $type ),
				'posts_per_page' => - 1,
				'post_status'    => 'publish',
				'order'          => 'ASC',
				'orderby'        => 'parent title',
				'exclude'        => $exclude
			);
			if ( !$user->is_admin() ) {
				$args['author'] = $user->id;
			}

			if ( $context && $context_id ) {
				switch ( $context ) {
					/**
					 * If is search lesson/quiz for course only search the items of course's author
					 */
					case 'course-items':
						if ( get_post_type( $context_id ) == 'lp_course' ) {
							$args['author'] = get_post_field( 'post_author', $context_id );
						}
						break;
					/**
					 * If is search question for quiz only search the items of course's author
					 */
					case 'quiz-items':
						if ( get_post_type( $context_id ) == 'lp_quiz' ) {
							$args['author'] = get_post_field( 'post_author', $context_id );
						}
						break;
				}
			}
			if ( $term ) {
				$args['s'] = $term;
			}
			$posts       = get_posts( $args );
			$found_items = array();

			if ( !empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$found_items[$post->ID]             = $post;
					$found_items[$post->ID]->post_title = !empty( $post->post_title ) ? $post->post_title : sprintf( '(%s)', __( 'Untitled', 'learn_press' ) );
				}
			}

			ob_start();
			if ( $found_items ) {
				foreach ( $found_items as $id => $item ) {
					printf( '
						<li class="" data-id="%1$d" data-type="%3$s" data-text="%2$s">
						<label>
							<input type="checkbox" value="%1$d">
							<span class="lp-item-text">%2$s</span>
						</label>
					</li>
					', $id, $item->post_title, $item->post_type );
				}
			} else {
				echo '<li>' . apply_filters( 'learn_press_modal_search_items_not_found', __( 'No item found', 'learn_press' ), $type ) . '</li>';
			}

			$response = array(
				'html' => ob_get_clean(),
				'data' => $found_items,
				'args' => $args
			);
			learn_press_send_json( $response );
		}

		static function remove_quiz_question() {
			global $wpdb;
			$quiz_id     = learn_press_get_request( 'quiz_id' );
			$question_id = learn_press_get_request( 'question_id' );
			if ( !wp_verify_nonce( learn_press_get_request( 'remove-nonce' ), 'remove_quiz_question' ) ) {
				wp_die( __( 'Error', 'learn_press' ) );
			}
			$query = $wpdb->prepare( "
				DELETE FROM {$wpdb->prefix}learnpress_quiz_questions
				WHERE quiz_id = %d
				AND question_id = %d
			", $quiz_id, $question_id );
			$wpdb->query( $query );
			die();
		}

		static function search_questions() {
			global $wpdb;

			$quiz_id = learn_press_get_request( 'quiz_id' );
			$user    = learn_press_get_current_user();
			if ( !$user->is_admin() && get_post_field( 'post_author', $quiz_id ) != get_current_user_id() ) {
				wp_die( __( 'You have not permission to access this section', 'learn_press' ) );
			}
			$term    = (string) ( stripslashes( learn_press_get_request( 'term' ) ) );
			$exclude = array();

			if ( !empty( $_GET['exclude'] ) ) {
				$exclude = array_map( 'intval', $_GET['exclude'] );
			}

			$added = $wpdb->get_col(
				$wpdb->prepare( "
					SELECT question_id
					FROM {$wpdb->prefix}learnpress_quiz_questions
					WHERE %d
				", 1 )
			);
			if ( $added ) {
				$exclude = array_merge( $exclude, $added );
				$exclude = array_unique( $exclude );
			}

			$args = array(
				'post_type'      => array( 'lp_question' ),
				'posts_per_page' => - 1,
				'post_status'    => 'publish',
				'order'          => 'ASC',
				'orderby'        => 'parent title',
				'exclude'        => $exclude
			);
			if ( !$user->is_admin() ) {
				$args['author'] = $user->id;
			}
			if ( $term ) {
				$args['s'] = $term;
			}
			$posts           = get_posts( $args );
			$found_questions = array();

			if ( !empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$found_questions[$post->ID] = !empty( $post->post_title ) ? $post->post_title : sprintf( '(%s)', __( 'Untitled', 'learn_press' ) );
				}
			}

			ob_start();
			if ( $found_questions ) {
				foreach ( $found_questions as $id => $question ) {
					printf( '
						<li class="" data-id="%1$d" data-type="" data-text="%2$s">
						<label>
							<input type="checkbox" value="%1$d">
							<span class="lp-item-text">%2$s</span>
						</label>
					</li>
					', $id, $question );
				}
			} else {
				echo '<li>' . __( 'No question found', 'learn_press' ) . '</li>';
			}

			$response = array(
				'html' => ob_get_clean(),
				'data' => $found_questions,
				'args' => $args
			);
			learn_press_send_json( $response );
		}

		static function plugin_action() {
			$url = learn_press_get_request( 'url' );
			ob_start();
			wp_remote_get( $url );
			ob_get_clean();
			echo wp_remote_get( admin_url( 'admin.php?page=learn_press_add_ons&tab=installed' ) );
			die();
		}

		/**
		 * Remove an item from order
		 */
		static function remove_order_item() {
			// ensure that user has permission
			if ( !current_user_can( 'edit_lp_orders' ) ) {
				die( __( 'Permission denied', 'learn_press' ) );
			}

			// verify nonce
			$nonce = learn_press_get_request( 'remove_nonce' );
			if ( !wp_verify_nonce( $nonce, 'remove_order_item' ) ) {
				die( __( 'Check nonce failed', 'learn_press' ) );
			}

			// validate order
			$order_id = learn_press_get_request( 'order_id' );
			if ( !is_numeric( $order_id ) || get_post_type( $order_id ) != 'lp_order' ) {
				die( __( 'Order invalid', 'learn_press' ) );
			}

			// validate item
			$item_id = learn_press_get_request( 'item_id' );
			$post    = get_post( learn_press_get_order_item_meta( $item_id, '_course_id' ) );
			if ( !$post || ( 'lp_course' !== $post->post_type ) ) {
				die( __( 'Course invalid', 'learn_press' ) );
			}

			learn_press_remove_order_item( $item_id );

			$order_data                  = learn_press_update_order_items( $order_id );
			$currency_symbol             = learn_press_get_currency_symbol( $order_data['currency'] );
			$order_data['subtotal_html'] = learn_press_format_price( $order_data['subtotal'], $currency_symbol );
			$order_data['total_html']    = learn_press_format_price( $order_data['total'], $currency_symbol );


			learn_press_send_json(
				array(
					'result'     => 'success',
					'order_data' => $order_data
				)
			);
		}

		/**
		 * Add new course to order
		 */
		static function add_item_to_order() {

			// ensure that user has permission
			if ( !current_user_can( 'edit_lp_orders' ) ) {
				die( __( 'Permission denied', 'learn_press' ) );
			}

			// verify nonce
			$nonce = learn_press_get_request( 'nonce' );
			if ( !wp_verify_nonce( $nonce, 'add_item_to_order' ) ) {
				die( __( 'Check nonce failed', 'learn_press' ) );
			}

			// validate order
			$order_id = learn_press_get_request( 'order_id' );
			if ( !is_numeric( $order_id ) || get_post_type( $order_id ) != 'lp_order' ) {
				die( __( 'Order invalid', 'learn_press' ) );
			}

			// validate item
			$item_id = learn_press_get_request( 'item_id' );
			$post    = get_post( $item_id );
			if ( !$post || ( 'lp_course' !== $post->post_type ) ) {
				die( __( 'Course invalid', 'learn_press' ) );
			}


			$course = learn_press_get_course( $post->ID );
			$order  = learn_press_get_order( $order_id );

			$item = array(
				'course_id' => $course->id,
				'name'      => $course->get_title(),
				'quantity'  => 1,
				'subtotal'  => $course->get_price(),
				'total'     => $course->get_price()
			);

			// Add item
			$item_id = learn_press_add_order_item( $order_id, array(
				'order_item_name' => $item['name']
			) );

			$item['id'] = $item_id;

			// Add item meta
			if ( $item_id ) {
				$item = apply_filters( 'learn_press_ajax_order_item', $item );

				learn_press_add_order_item_meta( $item_id, '_course_id', $item['course_id'] );
				learn_press_add_order_item_meta( $item_id, '_quantity', $item['quantity'] );
				learn_press_add_order_item_meta( $item_id, '_subtotal', $item['subtotal'] );
				learn_press_add_order_item_meta( $item_id, '_total', $item['total'] );

				do_action( 'learn_press_ajax_add_order_item_meta', $item );
			}

			$order_data                  = learn_press_update_order_items( $order_id );
			$currency_symbol             = learn_press_get_currency_symbol( $order_data['currency'] );
			$order_data['subtotal_html'] = learn_press_format_price( $order_data['subtotal'], $currency_symbol );
			$order_data['total_html']    = learn_press_format_price( $order_data['total'], $currency_symbol );

			ob_start();
			include learn_press_get_admin_view( 'meta-boxes/order/order-item.php' );
			$item_html = ob_get_clean();

			learn_press_send_json(
				array(
					'result'     => 'success',
					'item_html'  => $item_html,
					'order_data' => $order_data
				)
			);
		}

		static function search_courses() {
			$nonce = learn_press_get_request( 'nonce' );
			if ( !wp_verify_nonce( $nonce, 'search_item_term' ) ) {
				LP_Debug::exception( __( 'Verify nonce failed', 'learn_press' ) );
			}

			$term    = learn_press_get_request( 'term' );
			$exclude = learn_press_get_request( 'exclude' );

			$posts         = learn_press_get_all_courses(
				array(
					'term'    => $term,
					'exclude' => $exclude
				)
			);
			$found_courses = array();
			if ( !empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$found_courses[$post] = array(
						'title'     => get_the_title( $post ),
						'permalink' => get_the_permalink( $post )
					);
				}
			}

			$found_courses = apply_filters( 'learn_press_json_search_found_courses', $found_courses );

			learn_press_send_json( $found_courses );
		}

		static function remove_course_section() {
			$id = learn_press_get_request( 'id' );
			if ( $id ) {
				global $wpdb;
				$query = $wpdb->prepare( "
					DELETE FROM {$wpdb->prefix}learnpress_section_items
					WHERE section_id = %d
				", $id );
				$wpdb->query( $query );
				learn_press_reset_auto_increment( 'learnpress_section_items' );
				$query = $wpdb->prepare( "
					DELETE FROM {$wpdb->prefix}learnpress_sections
					WHERE section_id = %d
				", $id );
				$wpdb->query( $query );
				learn_press_reset_auto_increment( 'learnpress_sections' );
			}
			die();
		}

		static function toggle_lesson_preview() {
			$id = learn_press_get_request( 'lesson_id' );
			if ( get_post_type( $id ) == 'lp_lesson' && wp_verify_nonce( learn_press_get_request( 'nonce' ), 'learn-press-toggle-lesson-preview' ) ) {
				update_post_meta( $id, '_lp_is_previewable', learn_press_get_request( 'previewable' ) );
			}
			die();
		}

		static function add_new_item() {
			$post_type  = learn_press_get_request( 'type' );
			$post_title = learn_press_get_request( 'name' );
			$response   = array();
			if ( $post_type && $post_title ) {
				$args                = compact( 'post_title', 'post_type' );
				$args['post_status'] = 'publish';
				$item_id             = wp_insert_post( $args );
				if ( $item_id ) {
					LP_Lesson_Post_Type::create_default_meta( $item_id );
					$item                        = get_post( $item_id );
					$response['post']            = $item;
					$response['post']->edit_link = get_edit_post_link( $item_id );
				}
			}
			learn_press_send_json( $response );
		}

		static function quick_add_item() {
			$post_type  = learn_press_get_request( 'type' );
			$post_title = learn_press_get_request( 'name' );
			$response   = array();
			if ( $post_type && $post_title ) {
				$args                = compact( 'post_title', 'post_type' );
				$args['post_status'] = 'publish';
				$item_id             = wp_insert_post( $args );
				if ( $item_id ) {
					$item             = get_post( $item_id );
					$response['post'] = $item;
					$response['html'] = sprintf( '<li class="" data-id="%1$d" data-type="%2$s" data-text="%3$s">
						<label>
							<input type="checkbox" value="%1$d">
							<span class="lp-item-text">%3$s</span>
						</label>
					</li>', $item->ID, $item->post_type, $item->post_title );
				}
			}
			learn_press_send_json( $response );
		}

		static function update_editor_hidden() {
			if ( $id = learn_press_get_request( 'course_id' ) ) {
				if ( learn_press_get_request( 'is_hidden' ) ) {
					update_post_meta( $id, '_lp_editor_hidden', 'yes' );
				} else {
					delete_post_meta( $id, '_lp_editor_hidden' );
				}
			}
			learn_press_send_json( $_POST );
		}

		static function update_quiz_question_state() {
			$hidden = learn_press_get_request( 'hidden' );
			$post   = learn_press_get_request( 'quiz_id' );
			update_post_meta( $post, '_admin_hidden_questions', $hidden );
			die();
		}

		static function update_curriculum_section_state() {
			$hidden = learn_press_get_request( 'hidden' );
			$post   = learn_press_get_request( 'course_id' );
			update_post_meta( $post, '_admin_hidden_sections', $hidden );
			die();
		}

		/**
		 * Create a new page with the title passed via $_REQUEST
		 */
		public static function create_page() {
			$page_name = !empty( $_REQUEST['page_name'] ) ? $_REQUEST['page_name'] : '';
			$response  = array();
			if ( $page_name ) {
				$args    = array(
					'post_type'   => 'page',
					'post_title'  => $page_name,
					'post_status' => 'publish'
				);
				$page_id = wp_insert_post( $args );

				if ( $page_id ) {
					$response['page'] = get_page( $page_id );
					$html             = learn_press_pages_dropdown( '', '', array( 'echo' => false ) );
					preg_match_all( '!value=\"([0-9]+)\"!', $html, $matches );
					$response['positions'] = $matches[1];
					$response['html']      = '<a href="' . get_edit_post_link( $page_id ) . '" target="_blank">' . __( 'Edit Page', 'learn_press' ) . '</a>&nbsp;';
					$response['html'] .= '<a href="' . get_permalink( $page_id ) . '" target="_blank">' . __( 'View Page', 'learn_press' ) . '</a>';
				} else {
					$response['error'] = __( 'Error! Can not create page. Please try again!', 'learn_press' );
				}
			} else {
				$response['error'] = __( 'Page name is empty!', 'learn_press' );
			}
			learn_press_send_json( $response );
			die();
		}

		public static function add_quiz_question() {
			$id       = learn_press_get_request( 'id' );
			$quiz_id  = learn_press_get_request( 'quiz_id' );
			$type     = learn_press_get_request( 'type' );
			$name     = learn_press_get_request( 'name' );
			$response = array(
				'id' => $id
			);
			if ( !$id ) {
				$id = wp_insert_post(
					array(
						'post_title'  => $name,
						'post_type'   => LP()->question_post_type,
						'post_status' => 'publish'
					)
				);
				if ( $id ) {
					add_post_meta( $id, '_lp_type', $type );
				}
				$response['id'] = $id;
			}
			if ( $id && $quiz_id ) {
				global $wpdb;
				$max_order = $wpdb->get_var( $wpdb->prepare( "SELECT max(question_order) FROM {$wpdb->prefix}learnpress_quiz_questions WHERE quiz_id = %d", $quiz_id ) );
				$wpdb->insert(
					$wpdb->prefix . 'learnpress_quiz_questions',
					array(
						'quiz_id'        => $quiz_id,
						'question_id'    => $id,
						'question_order' => $max_order + 1
					),
					array( '%d', '%d', '%d' )
				);
				ob_start();
				$question = LP_Question_Factory::get_question( $id );
				learn_press_admin_view( 'meta-boxes/quiz/question.php', array( 'question' => $question ) );
				$response['html'] = ob_get_clean();
			}
			learn_press_send_json( $response );
			die();
		}

		public static function convert_question_type() {
			if ( ( $from = learn_press_get_request( 'from' ) ) && ( $to = learn_press_get_request( 'to' ) ) && $question_id = learn_press_get_request( 'question_id' ) ) {
				$data = array();
				parse_str( $_POST['data'], $data );

				do_action( 'learn_press_convert_question_type', $question_id, $from, $to, $data );
				$question = LP_Question_Factory::get_question( $question_id, array( 'type' => $to ) );
				learn_press_send_json(
					array(
						'html' => $question->admin_interface( array( 'echo' => false ) ),
						'icon' => $question->get_icon()
					)
				);
			} else {
				throw new Exception( __( 'Convert question type must be specify the id, source and destination type', 'learn_press' ) );
			}
			die();
		}

		/*******************************************************************************************************/

		/**
		 * Install sample data or dismiss the notice depending on user's option
		 */
		static function install_sample_data() {
			$yes      = !empty( $_REQUEST['yes'] ) ? $_REQUEST['yes'] : '';
			$response = array();
			if ( 'false' == $yes ) {
				set_transient( 'learn_press_install_sample_data', 'off', 12 * HOUR_IN_SECONDS );
				$response['hide_notice'] = true;
			} else {
				$result = learn_press_install_and_active_add_on( 'learnpress-import-export' );
				if ( 'activate' == $result['status'] ) {
					if ( !class_exists( 'LPR_Import' ) ) {
						$import_file_lib = WP_PLUGIN_DIR . "/learnpress-import-export/incs/lpr-import.php";
						if ( file_exists( $import_file_lib ) ) {
							include_once WP_PLUGIN_DIR . "/learnpress-import-export/incs/lpr-import.php";
						}
					}
					if ( !class_exists( 'LPR_Import' ) ) {
						$response['error'] = __( 'Import/Export addon not found', 'learn_press' );
					} else {
						$importer      = new LPR_Import();
						$import_source = LP_PLUGIN_PATH . '/dummy-data/learnpress-how-to-use-learnpress.xml';

						$upload_dir = wp_upload_dir();

						$copy = $upload_dir['path'] . '/learnpress-how-to-use-learnpress-copy.xml';
						@copy( $import_source, $copy );
						if ( file_exists( $copy ) ) {
							$result = $importer->import( $copy );
							if ( $result == 1 ) {
								$response['success']  = __( 'Import sample data successfully. The page will reload now!', 'learn_press' );
								$response['redirect'] = admin_url( 'edit.php?post_type=lpr_course' );
							} else {
								$response['error'] = __( 'Unknown error when importing sample data. Please try again!', 'learn_press' );
							}
						} else {
							$response['error'] = __( 'Dummy sample data not found. Please try again!', 'learn_press' );
						}
					}
				} else {
					$response['error'] = __( 'Unknown error when installing/activating Import/Export addon. Please try again!', 'learn_press' );
				}
			}
			learn_press_send_json( $response );
			die();
		}

		/**
		 * Activate a bundle of add-ons, if an add-on is not installed then install it first
		 */
		static function bundle_activate_add_ons() {
			global $learn_press_add_ons;
			include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' ); //for plugins_api..
			$response = array( 'addons' => array() );

			if ( !current_user_can( 'activate_plugins' ) ) {
				$response['error'] = __( 'You do not have sufficient permissions to deactivate plugins for this site.', 'learn_press' );
			} else {

				$add_ons = $learn_press_add_ons['bundle_activate'];

				if ( $add_ons ) {
					foreach ( $add_ons as $slug ) {
						$response['addons'][$slug] = learn_press_install_and_active_add_on( $slug );
					}
				}
			}
			learn_press_send_json( $response );
		}

		/**
		 * Activate a bundle of add-ons, if an add-on is not installed then install it first
		 */
		static function bundle_activate_add_on() {
			$response = array();
			include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' ); //for plugins_api..
			if ( !current_user_can( 'activate_plugins' ) ) {
				$response['error'] = __( 'You do not have sufficient permissions to deactivate plugins for this site.', 'learn_press' );
			} else {
				$slug            = !empty( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : null;
				$response[$slug] = learn_press_install_and_active_add_on( $slug );
			}
			learn_press_send_json( $response );
		}

		static function plugin_install() {
			$plugin_name = !empty( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
			$response    = learn_press_install_add_on( $plugin_name );
			learn_press_send_json( $response );
			die();
		}

		static function update_add_on_status() {
			$plugin   = !empty( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
			$t        = !empty( $_REQUEST['t'] ) ? $_REQUEST['t'] : '';
			$response = array();
			if ( !current_user_can( 'activate_plugins' ) ) {
				$response['error'] = __( 'You do not have sufficient permissions to deactivate plugins for this site.', 'learn_press' );
			}
			if ( $plugin && $t ) {
				if ( $t == 'activate' ) {
					activate_plugin( $plugin, false, is_network_admin() );
				} else {
					deactivate_plugins( $plugin, false, is_network_admin() );
				}
				$is_activate        = is_plugin_active( $plugin );
				$response['status'] = $is_activate ? 'activate' : 'deactivate';

			}
			wp_send_json( $response );
			die();
		}

		/**
		 * Output the image to browser with text and params passed via $_GET
		 */
		public static function dummy_image() {
			$text = !empty( $_REQUEST['text'] ) ? $_REQUEST['text'] : '';
			learn_press_text_image( $text, $_GET );
			die();
		}

		/**
		 * Get edit|view link of a page
		 */
		public static function get_page_permalink() {
			$page_id = !empty( $_REQUEST['page_id'] ) ? $_REQUEST['page_id'] : '';
			?>
			<a href="<?php echo get_edit_post_link( $page_id ); ?>" target="_blank"><?php _e( 'Edit Page', 'learn_press' ); ?></a>
			<a href="<?php echo get_permalink( $page_id ); ?>" target="_blank"><?php _e( 'View Page', 'learn_press' ); ?></a>
			<?php
			die();
		}


		/**
		 *
		 */
		public function custom_stats() {
			$from      = !empty( $_REQUEST['from'] ) ? $_REQUEST['from'] : 0;
			$to        = !empty( $_REQUEST['to'] ) ? $_REQUEST['to'] : 0;
			$date_diff = strtotime( $to ) - strtotime( $from );
			if ( $date_diff <= 0 || $from == 0 || $to == 0 ) {
				die();
			}
			learn_press_process_chart( learn_press_get_chart_students( $to, 'days', floor( $date_diff / ( 60 * 60 * 24 ) ) + 1 ) );
			die();
		}

		/**
		 * Quick add lesson with only title
		 */
		public static function quick_add_lesson() {

			$lesson_title = $_POST['lesson_title'];

			$new_lesson = array(
				'post_title'  => wp_strip_all_tags( $lesson_title ),
				'post_type'   => LP()->lesson_post_type,
				'post_status' => 'publish'
			);

			wp_insert_post( $new_lesson );

			$args      = array(
				'numberposts' => 1,
				'post_type'   => LP()->lesson_post_type,
				'post_status' => 'publish'
			);
			$lesson    = wp_get_recent_posts( $args );
			$lesson_id = $lesson[0]['ID'];
			$data      = array(
				'id'    => $lesson_id,
				'title' => $lesson_title
			);
			wp_send_json( $data );
			die;
		}

		/**
		 * Add a new quiz with the title only
		 */
		public static function quick_add_quiz() {
			$quiz_title = $_POST['quiz_title'];

			$new_quiz = array(
				'post_title'  => wp_strip_all_tags( $quiz_title ),
				'post_type'   => LP()->quiz_post_type,
				'post_status' => 'publish'
			);

			wp_insert_post( $new_quiz );

			$args    = array(
				'numberposts' => 1,
				'post_type'   => LP()->quiz_post_type,
				'post_status' => 'publish'
			);
			$quiz    = wp_get_recent_posts( $args );
			$quiz_id = $quiz[0]['ID'];
			$data    = array(
				'id'    => $quiz_id,
				'title' => $quiz_title
			);
			wp_send_json( $data );
			die;
		}

		public static function be_teacher() {
			$user_id    = get_current_user_id();
			$be_teacher = new WP_User( $user_id );
			$be_teacher->set_role( LP()->teacher_role );
			die;
		}

		public static function ignore_setting_up() {
			update_option( '_lpr_ignore_setting_up', 1, true );
			die;
		}
	}
}
LP_Admin_Ajax::init();