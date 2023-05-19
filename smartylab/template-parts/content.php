<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package smartylab_test
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				smartylab_posted_on();
				smartylab_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php smartylab_post_thumbnail(); ?>

	<div class="smartylab_test">
	<?php
// Отримати значення полів для поточного об'єкта нерухомості
$house_name = get_field('house_name');
$location_coordinates = get_field('location_coordinates');
$number_of_floors = get_field('number_of_floors');
$type_of_building = get_field('type_of_building');
?>


<div class="building-details">
    <h2>Деталі будівлі</h2>
    <ul>
        <li><strong>Назва будинку:</strong> <?php echo $house_name; ?></li>
        <li><strong>Координати місцезнаходження:</strong> <?php echo $location_coordinates; ?></li>
        <li><strong>Кількість поверхів:</strong> <?php echo $number_of_floors; ?></li>
        <li><strong>Тип будівлі:</strong> <?php echo $type_of_building; ?></li>
    </ul>
</div>

	</div>




	

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'smartylab' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'smartylab' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php smartylab_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
