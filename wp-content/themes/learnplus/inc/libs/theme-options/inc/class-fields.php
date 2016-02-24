<?php
/**
 * Fields generator
 *
 * @package Theme Alien Options Framework
 */

/**
 * Class to generate option fields
 *
 * @author Theme Alien
 * @version 1.0
 */
class LearnPlus_Theme_Options_Fields {
	/**
	 * Generate html markup for a list of items
	 *
	 * @since  1.0
	 *
	 * @param  array $fields List of items to be generated
	 *
	 * @return string
	 */
	function generate( $fields ) {
		$html = '';
		foreach ( $fields as $field ) {
			if ( is_string( $field ) ) {
				$html .= $field;
			} elseif ( 'divider' == $field['type'] ) {
				$html .= $this->divider( $field );
			} elseif ( method_exists( $this, $field['type'] ) ) {
				$html .= $this->element( $field );
			}
		}

		return $html;
	}

	/**
	 * Generate label, description, sub description for the field
	 *
	 * @since  1.0
	 *
	 * @param  array  $field Field options
	 * @param  string $value Field value
	 *
	 * @return string
	 */
	function element( $field, $value = '' ) {
		if ( ! $value ) {
			$value = LearnPlus_Theme_Options::get_option( $field['name'] );
		}

		$attr = array(
			'class' => "field field-{$field['type']} clearfix ",
		);

		if ( isset( $field['id'] ) ) {
			$attr['id'] = $field['id'];
		}

		if ( isset( $field['class'] ) ) {
			$attr['class'] .= $field['class'];
		}

		return $this->tag( 'div', $attr,
			$this->label( $field ),
			$this->tag( 'div', array( 'class' => 'input' ),
				$this->{$field['type']}( $field, $value ),
				$this->suffix( $field ),
				$this->subdesc( $field )
			)
		);
	}

	/**
	 * Generate label element for the field
	 *
	 * @since  1.0
	 *
	 * @param  array $field Field options to be generated
	 *
	 * @return string
	 */
	function label( $field ) {
		$attr = array();

		if ( empty( $field['label'] ) ) {
			return '';
		}

		if ( isset( $field['name'] ) ) {
			$attr['for'] = $field['name'];
		}

		return $this->tag( 'div', array( 'class' => 'label' ),
			$this->tag( 'label', $attr, $field['label'] ),
			$this->desc( $field )
		);
	}

	/**
	 * Generate description text for the field
	 *
	 * @since  1.0
	 *
	 * @param  array $field Field options to be generated
	 *
	 * @return string
	 */
	function desc( $field ) {
		return empty( $field['desc'] ) ? '' : $this->tag( 'div', array( 'class' => 'desc' ), $field['desc'] );
	}

	/**
	 * Generate sub description text for the field
	 *
	 * @since 1.0
	 *
	 * @param  array $field Field options to be generated
	 *
	 * @return string
	 */
	function subdesc( $field ) {
		return empty( $field['subdesc'] ) ? '' : $this->tag( 'div', array( 'class' => 'subdesc' ), $field['subdesc'] );
	}

	/**
	 * Generate text suffix for the field
	 *
	 * @since  1.0
	 *
	 * @param  array $field Field options to be generated
	 *
	 * @return string
	 */
	function suffix( $field ) {
		return empty( $field['suffix'] ) ? '' : $this->tag( 'span', array( 'class' => 'suffix' ), $field['suffix'] );
	}

	/**
	 * Generate divider line
	 *
	 * @since  1.0
	 *
	 * @param  array $field Field options to be generated
	 *
	 * @return string
	 */
	function divider( $field ) {
		if ( isset( $field['label'] ) ) {
			return sprintf( '<div class="field divider">%s</div>', $field['label'] );
		} else {
			return '<hr class="field divider">';
		}
	}

	/**
	 * Generate Text field type
	 *
	 * @since  1.0
	 *
	 * @param  array  $field Field options to be generated
	 * @param  string $value
	 *
	 * @return string
	 */
	function text( $field, $value ) {
		$size = isset( $field['size'] ) ? $field['size'] : '';
		$atts = array(
			'id'    => $field['name'],
			'class' => $size ? "input-$size" : '',
			'name'  => $field['name'],
			'type'  => 'text',
			'value' => $value,
		);

		if ( isset( $field['placeholder'] ) ) {
			$atts['placeholder'] = $field['placeholder'];
		}

		return $this->tag( 'input', $atts );
	}

	/**
	 * Generate number
	 *
	 * @since  1.0
	 *
	 * @param  array $field Field options to be generated
	 * @param  int   $value
	 *
	 * @return string
	 */
	function number( $field, $value ) {
		$size = isset( $field['size'] ) ? $field['size'] : 'mini';
		$atts = array(
			'id'    => $field['name'],
			'class' => "input-$size",
			'name'  => $field['name'],
			'type'  => 'number',
			'value' => $value,
		);

		return $this->tag( 'input', $atts );
	}

	/**
	 * Generate email control
	 *
	 * @since  1.0
	 *
	 * @param  array  $field Field options to be generated
	 * @param  string $value
	 *
	 * @return string
	 */
	function email( $field, $value ) {
		$size = isset( $field['size'] ) ? $field['size'] : '';
		$atts = array(
			'id'    => $field['name'],
			'class' => $size ? "input-$size" : '',
			'name'  => $field['name'],
			'type'  => 'email',
			'value' => $value,
		);

		return $this->tag( 'input', $atts );
	}

	/**
	 * Generate Textarea field type
	 *
	 * @since  1.0
	 *
	 * @param  array  $field Field
	 * @param  string $value
	 *
	 * @return string
	 */
	function textarea( $field, $value ) {
		$size = isset( $field['size'] ) ? $field['size'] : 'xxlarge';
		$atts = array(
			'class' => "input-$size",
			'id'    => $field['name'],
			'name'  => $field['name'],
			'rows'  => isset( $field['rows'] ) ? intval( $field['rows'] ) : 5
		);

		return $this->tag( 'textarea', $atts, $value );
	}

	/**
	 * Generate Select field type
	 *
	 * @since  1.0
	 *
	 * @param  array  $field Field
	 * @param  string $value
	 *
	 * @return string
	 */
	function select( $field, $value ) {
		$size        = isset( $field['size'] ) ? $field['size'] : '';
		$select_atts = array(
			'class' => $size ? "input-{$size}" : '',
			'id'    => $field['name'],
			'name'  => $field['name'],
		);

		$items = array();
		foreach ( $field['options'] as $k => $text ) {
			if ( is_array( $text ) ) {
				$children = array();
				foreach ( $text['options'] as $child_value => $child_text ) {
					$atts = array( 'value' => $child_value );
					if ( $child_value == $value ) {
						$atts['selected'] = 'selected';
					}
					$children[] = $this->tag( 'option', $atts, $child_text );
				}
				$items[] = $this->tag( 'optgroup', array( 'label' => $text['label'] ), implode( '', $children ) );
			} else {
				$atts = array( 'value' => $k );
				if ( $k == $value ) {
					$atts['selected'] = 'selected';
				}
				$items[] = $this->tag( 'option', $atts, $text );
			}
		}

		return $this->tag( 'select', $select_atts, implode( '', $items ) );
	}

	/**
	 * Generate radio field
	 *
	 * @since 1.0
	 *
	 * @param array  $field Field
	 * @param string $value
	 * @param string $sep   Separator, default is '<br>'. Used in "toggle" field
	 *
	 * @return string
	 */
	function radio( $field, $value, $sep = '<br>' ) {
		$items = array();
		foreach ( $field['options'] as $k => $v ) {
			$atts = array(
				'value' => $k,
				'type'  => 'radio',
				'name'  => $field['name'],
			);
			if ( $k == $value ) {
				$atts['checked'] = 'checked';
			}

			$items[] = $this->tag( 'label', '', $this->tag( 'input', $atts ), ' ' . $v );
		}

		return implode( $sep, $items );
	}

	/**
	 * Generate checkbox field
	 *
	 * @since  1.0
	 *
	 * @param  array  $field Field
	 * @param  string $value
	 *
	 * @return string
	 */
	function checkbox( $field, $value ) {
		$atts = array(
			'value' => 1,
			'type'  => 'checkbox',
			'name'  => $field['name'],
		);
		if ( 1 == $value ) {
			$atts['checked'] = 'checked';
		}

		return $this->tag( 'input', $atts );
	}

	/**
	 * Generate checkbox list field
	 *
	 * @since  1.0
	 *
	 * @param  array  $field Field
	 * @param  string $value
	 *
	 * @return string
	 */
	function checkbox_list( $field, $value ) {
		$value = (array) $value;
		$items = array();
		foreach ( $field['options'] as $k => $v ) {
			$atts = array(
				'value' => $k,
				'type'  => 'checkbox',
				'name'  => $field['name'] . '[]',
			);
			if ( in_array( $k, $value ) ) {
				$atts['checked'] = 'checked';
			}

			$items[] = $this->tag( 'label', '', $this->tag( 'input', $atts ), ' ' . $v );
		}

		return implode( '<br>', $items );
	}

	/**
	 * Generate color picker control
	 *
	 * @since 1.0
	 *
	 * @param array $field Field
	 * @param array $value
	 *
	 * @return string
	 */
	function color( $field, $value ) {
		$atts = array(
			'id'    => $field['name'],
			'class' => 'color',
			'name'  => $field['name'],
			'type'  => 'text',
			'value' => $value,
		);

		return $this->tag( 'input', $atts );
	}

	/**
	 * Generate image control
	 *
	 * @since  1.0
	 *
	 * @param  array  $field Field
	 * @param  string $value
	 *
	 * @return string
	 */
	function image( $field, $value ) {
		$field['size'] = 'large';
		$multiple = false;

		if ( isset( $field['multiple'] ) && $field['multiple'] ) {
			$multiple = true;
		}

		$attr = array(
			'class' => 'images-holder',
		);

		if ( $multiple ) {
			$attr['class'] .= ' multiple';
			$attr['data-name'] = $field['name'];
		}

		if ( ! empty( $value ) ) {
			$attr['class'] .= ' has-image';
		}

		// If it is single image
		if ( ! $multiple ) {
			return $this->tag( 'div', $attr,
				$this->tag( 'i', array( 'class' => 'entypo-image' ) ),
				$this->tag( 'img', array( 'src' => $value ) ),
				$this->tag( 'button', array( 'type' => 'button', 'class' => 'button button-primary button-select' ), __( 'Upload Image', 'learnplus' ) ),
				$this->tag( 'i', array( 'class' => 'entypo-squared-minus button-clear', 'title' => __( 'Remove Image', 'learnplus' ) ) ),
				$this->tag( 'input', array( 'type' => 'hidden', 'name' => $field['name'], 'value' => $value ) )
			);
		}

		// Multiple images
		$images = array();
		if ( ! empty( $value ) ) {
			foreach( (array) $value as $image ) {
				$src = wp_get_attachment_image_src( $image, 'thumbnail' );

				$images[] = $this->tag( 'div', array( 'class' => 'single-image' ),
					$this->tag( 'img', array( 'src' => $src[0] ) ),
					$this->tag( 'i', array( 'class' => 'entypo-squared-minus button-clear' ) ),
					$this->tag( 'input', array( 'type' => 'hidden', 'name' => $field['name'] . '[]', 'value' => $image ) )
				);
			}
		}

		$images = implode( '', $images );

		return $this->tag( 'div', $attr,
			$this->tag( 'i', array( 'class' => 'entypo-images' ) ),
			$this->tag( 'div', array( 'class' => 'images-sort' ), $images ),
			$this->tag( 'button', array( 'type' => 'button', 'class' => 'button button-primary button-select' ), __( 'Upload Images', 'learnplus' ) )
		);
	}

	/**
	 * Generate switcher control
	 *
	 * @since 1.0
	 *
	 * @param array $field Field
	 * @param int   $value
	 *
	 * @return string
	 */
	function switcher( $field, $value ) {
		$class = '';
		$atts  = array(
			'value' => 1,
			'type'  => 'checkbox',
			'name'  => $field['name'],
		);
		if ( 1 == $value ) {
			$atts['checked'] = 'checked';
			$class           = 'on';
		}

		return $this->tag( 'label', array( 'class' => "switcher $class" ),
			$this->tag( 'input', $atts )
		);
	}

	/**
	 * Generate buttons toggle control
	 *
	 * @since 1.0
	 *
	 * @param array  $field Field options
	 * @param string $value
	 *
	 * @return string
	 */
	function toggle( $field, $value ) {
		$html     = '';
		$multiple = isset( $field['multiple'] ) && $field['multiple'] == true;
		$value    = $multiple ? (array) $value : $value;

		foreach ( $field['options'] as $k => $v ) {
			$class = '';
			$atts  = array(
				'value' => $k,
				'type'  => $multiple ? 'checkbox' : 'radio',
				'name'  => $multiple ? $field['name'] . '[]' : $field['name']
			);

			if ( ( ! $multiple && $k == $value ) || ( $multiple && in_array( $k, $value ) ) ) {
				$atts['checked'] = 'checked';
				$class           = 'active';
			}

			$html .= $this->tag( 'label', array( 'class' => "button $class" ), $this->tag( 'input', $atts ), $v );
		}

		$class = $multiple ? 'multiple' : '';

		return $this->tag( 'div', array( 'class' => "button-group $class" ), $html );
	}

	/**
	 * Generate image toggles control
	 *
	 * @since 1.0
	 *
	 * @param array  $field Field
	 * @param string $value
	 *
	 * @return string
	 */
	function image_toggle( $field, $value ) {
		$html = '';
		foreach ( $field['options'] as $k => $v ) {
			$atts  = array(
				'type'  => 'radio',
				'name'  => $field['name'],
				'value' => $k
			);
			$class = '';
			if ( $k == $value ) {
				$atts['checked'] = 'checked';
				$class           = 'active';
			}
			$html .= $this->tag( 'label', array( 'class' => $class ),
				$this->tag( 'input', $atts ),
				$this->tag( 'img', array( 'src' => $v ) )
			);
		}
		$layout = isset( $field['layout'] ) ? $field['layout'] : 'horizontal';

		return $this->tag( 'div', array( 'class' => $layout ), $html );
	}

	/**
	 * Generate size control
	 *
	 * @since 1.0
	 *
	 * @param array  $field Field
	 * @param string $value
	 *
	 * @return string
	 */
	function size( $field, $value ) {
		$name  = $field['name'];
		$value = array_merge( array( 'number' => '', 'unit' => 'px' ), (array) $value );

		$input_field = array_merge( $field, array( 'name' => "{$name}[number]", 'size' => 'mini' ) );
		$input       = $this->number( $input_field, $value['number'] );

		$select_field = array_merge( $field, array( 'name' => "{$name}[unit]", 'size' => 'mini', 'options' => array( 'px' => 'px', '%' => '%' ) ) );
		$select       = $this->select( $select_field, $value['unit'] );

		return $input . $select;
	}

	/**
	 * Generate group control
	 *
	 * @since 1.0
	 *
	 * @param array  $field Field
	 * @param string $value
	 *
	 * @return  string
	 */
	function group( $field, $value ) {
		$layout = isset( $field['layout'] ) ? $field['layout'] : 'horizontal';

		return $this->tag( 'div', array( 'class' => $layout ), $this->generate( $field['children'] ) );
	}

	/**
	 * Generate font control
	 *
	 * @since  1.0
	 *
	 * @param  array $field Field
	 * @param  array $value
	 *
	 * @return string
	 */
	function font( $field, $value ) {
		$value = array_merge( array(
			'color'  => '',
			'font'   => '',
			'size'   => '',
			'styles' => array(),
		), (array) $value );

		$fonts = array( '' => __( 'Select', 'learnplus' ) );
		if ( isset( $field['standard'] ) ) {
			$fonts[] = array(
				'label'   => __( 'Standard Fonts', 'learnplus' ),
				'options' => $field['standard']
			);
		}
		if ( isset( $field['google'] ) ) {
			$fonts[] = array(
				'label'   => __( 'Google Fonts', 'learnplus' ),
				'options' => $field['google']
			);
		}

		return $this->tag( 'div', array( 'class' => 'horizontal' ),
			$this->element( array(
				'desc'    => __( 'Size', 'learnplus' ),
				'type'    => 'number',
				'name'    => $field['name'] . '[size]',
				'suffix'  => 'px',
				'subdesc' => __( 'Size', 'learnplus' ),
			), $value['size'] ),
			$this->element( array(
				'desc'    => __( 'Font Family', 'learnplus' ),
				'type'    => 'select',
				'name'    => $field['name'] . '[font]',
				'subdesc' => __( 'Family', 'learnplus' ),
				'options' => $fonts,
			), $value['font'] ),
			$this->element( array(
				'desc'     => __( 'Style', 'learnplus' ),
				'type'     => 'toggle',
				'multiple' => true,
				'name'     => $field['name'] . '[styles]',
				'subdesc'  => __( 'Style', 'learnplus' ),
				'options'  => array( 'bold' => '<strong>B</strong>', 'italic' => '<i>I</i>', 'underline' => '<u>U</u>' ),
			), $value['styles'] ),
			$this->element( array(
				'desc'    => __( 'Color', 'learnplus' ),
				'type'    => 'color',
				'name'    => $field['name'] . '[color]',
				'subdesc' => __( 'Color', 'learnplus' ),
			), $value['color'] )
		);
	}

	/**
	 * Generate background control
	 *
	 * @since 1.0
	 *
	 * @param array $field Field
	 * @param array $value
	 *
	 * @return string
	 */
	function background( $field, $value ) {
		$name  = $field['name'];
		$value = array_merge( array(
			'image'      => '',
			'color'      => '',
			'position_x' => '',
			'position_y' => '',
			'repeat'     => '',
			'attachment' => '',
			'size'       => '',
		), (array) $value );

		// Preview style
		$style = array();
		$class = '';
		if ( ! empty( $value['image'] ) || ! empty( $value['color'] ) ) {
			$class = ! empty( $value['image'] ) ? 'has-image' : '';

			$style[] = 'background: ';
			$style[] = empty( $value['image'] ) ? '' : "url({$value['image']})";
			$style[] = $value['repeat'];
			$style[] = $value['attachment'];
			$style[] = $value['position_x'];
			$style[] = $value['position_y'];
			$style[] = $value['color'];
			$style[] = empty( $value['size'] ) ? '' : '; background-size: ' . $value['size'];
		}

		$ui = $this->tag( 'div', array( 'class' => 'horizontal' ),
			$this->tag(
				'div',
				array(
					'class' => 'background-preview ' . $class,
					'style' => implode( ' ', $style ) . '; background-size'
				),
				$this->tag( 'button', array( 'type' => 'button', 'class' => 'button button-primary button-select' ), __( 'Upload Image', 'learnplus' ) ),
				$this->tag( 'button', array( 'type' => 'button', 'class' => 'button button-clear' ), __( 'Remove Image', 'learnplus' ) ),
				$this->tag( 'input', array( 'type' => 'hidden', 'name' => "{$name}[image]", 'value' => $value['image'] ) )
			),
			$this->tag( 'div', array( 'class' => 'vertical' ),
				$this->tag( 'div', array( 'class' => 'horizontal background-position' ),
					$this->element( array(
						'type'    => 'select',
						'name'    => "{$name}[position_x]",
						'class'   => 'background-position-x',
						'subdesc' => __( 'Horizontal Position', 'learnplus' ),
						'options' => array(
							'left'   => __( 'Left', 'learnplus' ),
							'center' => __( 'Center', 'learnplus' ),
							'right'  => __( 'Right', 'learnplus' )
						)
					), $value['position_x'] ),
					$this->element( array(
						'type'    => 'select',
						'name'    => "{$name}[position_y]",
						'class'   => 'background-position-y',
						'subdesc' => __( 'Vertical Position', 'learnplus' ),
						'options' => array(
							'top'    => __( 'Top', 'learnplus' ),
							'center' => __( 'Center', 'learnplus' ),
							'bottom' => __( 'Bottom', 'learnplus' )
						)
					), $value['position_y'] )
				),
				$this->tag( 'div', array( 'class' => 'horizontal' ),
					$this->element( array(
						'type'    => 'select',
						'name'    => "{$name}[repeat]",
						'class'   => 'background-repeat',
						'subdesc' => __( 'Repeat', 'learnplus' ),
						'options' => array(
							'repeat'    => __( 'Repeat', 'learnplus' ),
							'repeat-x'  => __( 'Repeat Horizontally', 'learnplus' ),
							'repeat-y'  => __( 'Repeat Vertically', 'learnplus' ),
							'no-repeat' => __( 'No Repeat', 'learnplus' ),
						)
					), $value['repeat'] ),
					$this->element( array(
						'type'    => 'select',
						'name'    => "{$name}[attachment]",
						'class'   => 'background-attachment',
						'subdesc' => __( 'Attachment', 'learnplus' ),
						'options' => array(
							'scroll' => __( 'Scroll', 'learnplus' ),
							'fixed'  => __( 'Fixed', 'learnplus' ),
						)
					), $value['attachment'] )
				),
				$this->tag( 'div', array( 'class' => 'horizontal' ),
					$this->element(
						array(
							'type'    => 'color',
							'name'    => "{$name}[color]",
							'subdesc' => __( 'Color', 'learnplus' ),
						),
						$value['color']
					),
					$this->element( array(
						'type'    => 'select',
						'name'    => "{$name}[size]",
						'class'   => 'background-size',
						'subdesc' => __( 'Size', 'learnplus' ),
						'options' => array(
							''        => __( 'Normal', 'learnplus' ),
							'contain' => __( 'Contain', 'learnplus' ),
							'cover'   => __( 'Cover', 'learnplus' ),
						)
					), $value['attachment'] )
				)
			)
		);

		// Pattern
		if ( isset( $field['patterns'] ) ) {
			$patterns = array();
			foreach ( $field['patterns'] as $img ) {
				$attr = array(
					'style' => "background-image: url($img)",
					'class' => 'pattern '
				);
				if ( $img == $value['image'] ) {
					$attr['class'] .= 'active';
				}
				$patterns[] = $this->tag( 'span', $attr, $this->tag( 'img', array( 'src' => $img ) ) );
			}

			$ui .= $this->tag( 'div', array( 'class' => 'horizontal field-image_toggle background-patterns' ), implode( "\n", $patterns ) );
		}

		return $ui;
	}

	/**
	 * Generate Social icons control
	 *
	 * @since 1.0
	 *
	 * @param array $field Field
	 * @param array $value
	 *
	 * @return  string
	 */
	function social( $field, $value ) {
		$socials = array(
			'facebook'   => __( 'Facebook', 'learnplus' ),
			'twitter'    => __( 'Twitter', 'learnplus' ),
			'google'     => __( 'Google', 'learnplus' ),
			'tumblr'     => __( 'Tumblr', 'learnplus' ),
			'flickr'     => __( 'Flickr', 'learnplus' ),
			'vimeo'      => __( 'Vimeo', 'learnplus' ),
			'youtube'    => __( 'Youtube', 'learnplus' ),
			'linkedin'   => __( 'LinkedIn', 'learnplus' ),
			'pinterest'  => __( 'Pinterest', 'learnplus' ),
			'dribbble'   => __( 'Dribbble', 'learnplus' ),
			'behance'    => __( 'Behance', 'learnplus' ),
			'soundcloud' => __( 'SoundCloud', 'learnplus' ),
			'spotify'    => __( 'Spotify', 'learnplus' ),
			'github'     => __( 'GitHub', 'learnplus' ),
			'instagram'  => __( 'Instagram', 'learnplus' ),
			'picasa'     => __( 'Picasa', 'learnplus' ),
			'spotify'    => __( 'Sportify', 'learnplus' ),
			'foursquare' => __( 'Foursquare', 'learnplus' ),
			'lastfm'     => __( 'Lastfm', 'learnplus' ),
		);

		$value    = (array) $value;
		$links    = array_filter( $value );
		$active   = array_keys( $links );
		$settings = $icons = array();

		foreach ( $socials as $name => $label ) {
			// Settings
			$settings[] = $this->tag(
				'div',
				array( 'id' => $name, 'class' => 'clearfix social-link-input ' . ( in_array( $name, $active ) ? 'active' : 'hidden' ) ),
				$this->tag( 'label', array( 'for' => "{$field['name']}[$name]", 'class' => 'entypo-' . $name ) ),
				$this->tag( 'i', array( 'class' => 'deactive-social-link entypo-cross', 'title' => __( 'Remove', 'learnplus' ) ) ),
				$this->element(
					array(
						'name'        => "{$field['name']}[$name]",
						'placeholder' => $label,
						'type'        => 'text',
					),
					isset( $value[$name] ) ? $value[$name] : ''
				)
			);

			// Icons
			$icons[] = $this->tag(
				'i',
				array(
					'title'       => $label,
					'class'       => 'active-social-link entypo-' . $name . ' ' . ( in_array( $name, $active ) ? 'hidden' : '' ),
					'data-social' => $name
				)
			);
		}

		return $this->tag(
			'div',
			array(
				'id'    => 'socials-actived',
				'class' => 'socials-actived',
			),
			implode( "\n", $settings )
		)
		. $this->tag(
			'div',
			array(
				'id'    => 'socials-inactived',
				'class' => 'socials-inactived',
			),
			implode( "\n", $icons )
		);
	}

	/**
	 * Generate icon selector control
	 *
	 * @since  1.0
	 *
	 * @param  array  $field Field
	 * @param  string $value
	 *
	 * @return string
	 */
	function icon( $field, $value ) {
		return $this->tag( 'div', array( 'class' => 'icon-picker' . ( $value ? ' has-icon' : '' ) ),
			$this->tag( 'img', array( 'src' => $value ) ),
			$this->tag( 'a', array( 'class' => 'select' ), '+' ),
			$this->tag( 'a', array( 'class' => 'remove' ), 'x' ),
			$this->tag( 'input', array( 'type' => 'hidden', 'name' => $field['name'], 'value' => $value ) )
		);
	}

	/**
	 * Generate code editor control
	 *
	 * @since  1.0
	 *
	 * @param  array  $field Field
	 * @param  string $value Value
	 *
	 * @return string
	 */
	function code_editor( $field, $value ) {
		$field = array_merge( array( 'language' => 'javascript' ), $field );
		$value = esc_html( $value );

		return $this->tag(
			'textarea',
			array(
				'name'  => $field['name'],
				'class' => 'hidden'
			),
			$value
		)
		. $this->tag(
			'div',
			array(
				'id'            => 'ace-' . $field['name'],
				'class'         => 'code-editor',
				'data-language' => $field['language']
			),
			$value
		);
	}

	/**
	 * Generate richtext editor
	 *
	 * @since  1.0
	 *
	 * @param  array  $field Field
	 * @param  string $value Value
	 *
	 * @return string
	 */
	function editor( $field, $value ) {
		$settings = array(
			'media_buttons' => false,
			'teeny'         => true,
			'quicktags'     => false,
		);

		if ( isset( $field['settings'] ) ) {
			$settings = wp_parse_args( (array) $field['settings'], $settings );
		}

		ob_start();
		wp_editor( $value, $field['name'], $settings );
		$output = ob_get_clean();

		return $output;
	}

	/**
	 * Generate date field control
	 *
	 * @since  1.0
	 *
	 * @param  array  $field Field
	 * @param  string $value Value
	 *
	 * @return string
	 */
	function date( $field, $value ) {
		return $this->tag(
			'input',
			array(
				'type'  => 'text',
				'name'  => $field['name'],
				'class' => 'datepicker',
				'value' => $value,
			)
		);
	}

	/**
	 * Generate color scheme field control
	 *
	 * @since  1.0.1
	 *
	 * @param  array  $field Field
	 * @param  string $value Value
	 *
	 * @return string
	 */
	function color_scheme( $field, $value ) {
		$html = '';
		foreach ( $field['options'] as $color => $code ) {
			$atts  = array(
				'type'  => 'radio',
				'name'  => $field['name'],
				'value' => $color
			);

			$style = '';
			if( strrpos( $code, '|') ) {
				$code = explode( '|', $code );
				$style = array(
					'style' => 'background-color: ' . $code[0] . ';border-right: 25px solid ' . $code[1]
				);
			} else {
				$style = array(
					'style' => 'background-color: ' . $code
				);
			}
			$class = $color;
			if ( $color == $value ) {
				$atts['checked'] = 'checked';
				$class           .= ' active';
			}
			$html .= $this->tag( 'label', array( 'class' => $class ),
				$this->tag( 'input', $atts ),
				$this->tag( 'span', $style )
			);
		}
		$layout = isset( $field['layout'] ) ? $field['layout'] : 'horizontal';

		return $this->tag( 'div', array( 'class' => $layout ), $html );
	}

	/**
	 * Generate backup control
	 *
	 * @since  1.0
	 *
	 * @param  array  $field Field
	 * @param  string $value
	 *
	 * @return string
	 */
	function backup( $field, $value ) {
		$value = get_theme_mods();
		unset( $value['nav_menu_locations'] );
		$value = base64_encode( maybe_serialize( $value ) );

		return $this->tag( 'textarea', array( 'class' => 'input-xxlarge', 'rows' => 5 ), $value ) .
		$this->tag( 'button', array( 'class' => 'button import-options' ), __( 'Import Opitons', 'learnplus' ) );
	}

	/**
	 * Helper functions to generate html tag
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function tag() {
		$arguments = func_get_args();
		if ( ! isset( $arguments[0] ) ) {
			return '';
		}

		$tag = array_shift( $arguments );

		$atts = '';
		if ( ! empty( $arguments[0] ) ) {
			foreach ( $arguments[0] as $name => $value ) {
				$atts .= sprintf( ' %s="%s"', $name, esc_attr( $value ) );
			}
			array_shift( $arguments );
		}

		$content = empty( $arguments ) ? '' : implode( '', $arguments );

		return in_array( $tag, array( 'hr', 'img', 'br' ) )
			? sprintf( '<%s%s>', $tag, $atts )
			: sprintf( '<%1$s%2$s>%3$s</%1$s>', $tag, $atts, $content );
	}
}
