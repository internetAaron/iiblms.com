<?php
/**
 * Template Name: Instructors
 *
 * The template file for displaying one page.
 *
 * @package LearnPlus
 */

get_header(); ?>

<?php
$number   = intval( learnplus_get_meta( 'number_teacher' ) );
$paged    = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$offset   = ( $paged - 1 ) * $number;
$teachers = get_users( 'role=instructor&offset=' . $offset . '&number=' . $number );
$output   = array();

foreach ( $teachers as $teacher ) {
	$output[] = sprintf( '<div class="course-list">' );

	$output[] = sprintf(
		'
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="shop-item-list entry">
				%s
				<div class="magnifier"></div>
			</div>
		</div>',
		get_avatar( $teacher->user_email, '263' )
	);

	$profession = get_the_author_meta( 'profession', $teacher->ID );

	if ( $profession ) {
		$profession = sprintf(
			'
			<div class="shopmeta">
				<span class="pull-left"><strong>%s</strong>%s </span>
			</div>
			<hr class="invis clearfix">',
			esc_html__( 'Profession : ', 'learnplus' ),
			$profession
		);
	}

	$output[] = sprintf(
		'
		<div class="col-md-6 col-sm-6 col-xs-12">
			<div class="shop-list-desc">
				<h4><a href="%s">%s</a></h4>
				%s
				<p>%s</p>
				<a href="%s" class="btn btn-default"><i class="fa fa-search"></i>%s</a>
				<a href="%s" id="btn-contact-teacher" class="btn btn-primary"><i class="fa fa-envelope-o"></i>%s</a>
			</div>
		</div>',
		esc_url( home_url( '/author/' . $teacher->user_login ) ),
		$teacher->display_name,
		$profession,
		get_the_author_meta( 'description', $teacher->ID ),
		esc_url( home_url( '/author/' . $teacher->user_login ) ),
		esc_html__( ' View My Courses', 'learnplus' ),
		'#',
		esc_html__( ' Contact With Me', 'learnplus' )
	);

	$skills = str_replace( "\n", ',', get_the_author_meta( 'skills', $teacher->ID ) );

	$output[] = sprintf(
		'
		<div class="col-md-3 col-sm-12 skills">
            <div class="teacher-skills">
            %s
            </div>
        </div>',
		do_shortcode( '[vc_progress_bar values="' . $skills . '" units="%"]' )
	);

	$output[] = sprintf( '</div>' );
}

printf(
	'<div class="teacher-list">%s</div>',
	implode( ' ', $output )
);
?>

<?php
$users       = get_users( 'role=instructor' );
$total_users = count( $users );
$total_query = count( $teachers );
$total_pages = ceil( $total_users / $number );
if ( $total_users > $total_query ) {
	echo '<nav class="text-center instructors-navigation navigation paging-navigation numeric-navigation">';
	$current_page = max( 1, get_query_var( 'paged' ) );
	$big          = 999999999;
	$args         = array(
		'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'total'     => $total_pages,
		'current'   => $current_page,
		'prev_text' => '&laquo;',
		'next_text' => '&raquo;',
		'type'      => 'plain',
	);

	echo paginate_links( $args );
	echo '</nav>';
}
?>

<?php get_footer(); ?>

<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="item-detail">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<i class="fa fa-times"></i><span class="sr-only"><?php esc_html_e( 'Close', 'learnplus' ) ?></span>
					</button>
				</div>
				<div class="modal-body">
					<?php
					$form = learnplus_get_meta( 'contact_form_7' );
					if ( $form ) {
						$form = '[contact-form-7 id="' . $form . '"]';
						echo do_shortcode( $form );
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
