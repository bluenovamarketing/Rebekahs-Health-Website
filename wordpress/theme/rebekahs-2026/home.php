<?php
/** Dynamic Wellness Source archive. Existing and future posts use this automatically. */
get_header();

$paged = max( 1, get_query_var( 'paged' ) );
$query = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 9, 'paged' => $paged ) );
$featured = null;
if ( $query->have_posts() ) {
	$featured = $query->posts[0];
}
?>
<main id="main">
	<section class="hero"><div class="wrap hero-grid"><div><span class="eyebrow">The Wellness Source</span><h1>Good questions.<br><em>Grounded guidance.</em></h1><p class="hero-copy">Explore practical ideas, thoughtful product education and conversations with people who make wellness feel more understandable—one article at a time.</p></div><aside class="hero-aside"><span class="eyebrow">Start where you are</span><strong>Wellness is personal. Learning can be shared.</strong><p>Use the topics below to find an approachable next read, then bring your questions to any of our four Michigan stores.</p></aside></div></section>
	<?php if ( $featured ) : setup_postdata( $featured ); ?>
	<?php $featured_categories = wp_get_post_categories( get_the_ID(), array( 'fields' => 'names' ) ); ?>
	<section class="featured"><div class="wrap"><article class="feature-card"><a class="feature-media" href="<?php the_permalink(); ?>"><?php echo wp_kses_post( rhn_post_image( get_the_ID(), 'large' ) ); ?></a><div class="feature-copy"><div class="meta"><span><?php echo esc_html( $featured_categories[0] ?? 'Featured article' ); ?></span><i></i><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></div><h2><?php the_title(); ?></h2><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p><a class="feature-link" href="<?php the_permalink(); ?>">Read the article &rarr;</a></div></article></div></section>
	<?php wp_reset_postdata(); endif; ?>
	<section class="archive" aria-labelledby="latest"><div class="wrap"><div class="archive-head"><div><span class="eyebrow">Explore the archive</span><h2 id="latest">Latest from Rebekah's</h2></div><p>Product know-how, expert perspectives and practical ideas for everyday wellness.</p></div><div class="tools"><div class="filters" role="group" aria-label="Filter articles by topic"><button class="filter active" data-filter="all">All articles</button><button class="filter" data-filter="healthy">Healthy living</button><button class="filter" data-filter="products">Know your products</button><button class="filter" data-filter="experts">Access to experts</button></div><label class="search"><span aria-hidden="true">&#9906;</span><input id="search" type="search" placeholder="Search articles" aria-label="Search articles"></label></div>
		<div class="grid" id="article-grid">
		<?php if ( $query->have_posts() ) : $index = 0; while ( $query->have_posts() ) : $query->the_post(); if ( 0 === $index++ && 1 === $paged ) { continue; } ?>
			<a class="card" data-category="<?php echo esc_attr( rhn_post_topic_key() ); ?>" href="<?php the_permalink(); ?>"><div class="card-media"><?php echo wp_kses_post( rhn_post_image( get_the_ID(), 'medium_large' ) ); ?></div><div class="card-body"><div class="meta"><span><?php echo esc_html( wp_get_post_categories( get_the_ID(), array( 'fields' => 'names' ) )[0] ?? 'Wellness' ); ?></span><i></i><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'M j' ) ); ?></time></div><h3><?php the_title(); ?></h3><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p><span class="read">Read article &rarr;</span></div></a>
		<?php endwhile; wp_reset_postdata(); endif; ?>
		</div><div class="empty" id="empty">No articles match that search yet. Try another word or browse all articles.</div>
		<nav class="pagination" aria-label="Blog pages"><?php echo wp_kses_post( paginate_links( array( 'total' => $query->max_num_pages, 'current' => $paged, 'type' => 'plain', 'prev_text' => '&larr; Newer', 'next_text' => 'Older articles &rarr;' ) ) ); ?></nav>
	</div></section>
</main>
<?php get_footer(); ?>
