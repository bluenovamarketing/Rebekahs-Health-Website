<?php
/** Dynamic single-post template used by every existing and future blog post. */
get_header();
while ( have_posts() ) : the_post();
	$categories = get_the_category();
	$category = $categories ? $categories[0]->name : 'Wellness Source';
	$initials = '';
	foreach ( preg_split( '/\s+/', get_the_author() ) as $part ) { $initials .= strtoupper( substr( $part, 0, 1 ) ); }
?>
<main>
	<header class="article-hero"><div class="wrap"><nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Wellness Source</a><span aria-hidden="true">/</span><span><?php echo esc_html( $category ); ?></span></nav><div class="hero-grid"><div><span class="category"><?php echo esc_html( $category ); ?></span><h1><?php the_title(); ?></h1><p class="dek"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 38 ) ); ?></p><div class="byline"><div class="avatar" aria-hidden="true"><?php echo esc_html( substr( $initials, 0, 2 ) ); ?></div><p><strong><?php the_author(); ?></strong><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time> &middot; <?php echo esc_html( rhn_reading_time() ); ?> minute read</p></div></div><div class="hero-media"><?php echo wp_kses_post( rhn_post_image( get_the_ID(), 'large' ) ); ?><div class="stamp">Questions<br>welcome<br>here</div></div></div></div></header>
	<div class="wrap article-layout"><aside class="share" aria-label="Share this article"><span>Share</span><div class="share-links"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( get_permalink() ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook">f</a><a href="mailto:?subject=<?php echo rawurlencode( get_the_title() ); ?>&amp;body=<?php echo rawurlencode( get_permalink() ); ?>" aria-label="Share by email">@</a><button class="rhn-copy-link" type="button" data-url="<?php echo esc_url( get_permalink() ); ?>" aria-label="Copy article link">&nearr;</button></div></aside><article class="article-body" id="article"><?php the_content(); ?><section class="expert" aria-labelledby="about-author"><div class="avatar" aria-hidden="true"><?php echo esc_html( substr( $initials, 0, 2 ) ); ?></div><div><h2 id="about-author">About <?php the_author(); ?></h2><p>Rebekah's Health &amp; Nutrition shares practical education to help Michigan neighbors ask better questions and make more informed wellness choices. Educational content is not medical advice.</p></div></section><div class="tags" aria-label="Article topics"><?php the_category( ' ' ); ?><?php the_tags( '', ' ', '' ); ?></div></article></div>
	<section class="next-read"><div class="wrap"><div class="next-head"><div><span class="eyebrow">Keep learning</span><h2>Your next thoughtful read</h2></div><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Explore all articles &rarr;</a></div><div class="cards">
	<?php $related = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 3, 'post__not_in' => array( get_the_ID() ), 'category__in' => wp_get_post_categories( get_the_ID() ) ) ); while ( $related->have_posts() ) : $related->the_post(); ?>
		<?php $related_categories = wp_get_post_categories( get_the_ID(), array( 'fields' => 'names' ) ); ?>
		<a class="card" href="<?php the_permalink(); ?>"><div class="card-art"><?php echo wp_kses_post( rhn_post_image( get_the_ID(), 'medium' ) ); ?></div><div class="card-body"><span><?php echo esc_html( $related_categories[0] ?? 'Wellness' ); ?></span><h3><?php the_title(); ?></h3><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 16 ) ); ?></p></div></a>
	<?php endwhile; wp_reset_postdata(); ?>
	</div></div></section>
</main>
<?php endwhile; get_footer(); ?>
