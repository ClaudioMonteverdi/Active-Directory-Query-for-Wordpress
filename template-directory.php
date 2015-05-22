<?php
/*
Template Name: Directory Page
*/
?>
<?php get_header(); ?>
<?php global $woo_options; ?>
<div id="title-container">
<h1 class="title col-full"><?php the_title(); ?></h1>
</div>

    <div id="content" class="page col-full">

                <div id="main" class="col-left">

                <?php if ( $woo_options[ 'woo_breadcrumbs_show' ] == 'true' ) woo_breadcrumbs(); ?>

        <?php if (have_posts()) : $count = 0; ?>
        <?php while (have_posts()) : the_post(); $count++; ?>

            <?php if ( woo_active_sidebar( 'secondary' ) ) { ?>
                        <div id="sub_nav">
                                <?php woo_sidebar( 'secondary' ); ?>
                        </div><!-- /sub_nav -->
                        <?php } ?>

            <div <?php post_class(); ?>>

                <div class="entry">
                        <div id="horizontal_list">  //the list below was how we navigated, but you can do anything you want here.
                        <ul>
                                <li><a class="neg-margin" href="#Employee-Directory">Employees |</a></li>
                                <li><a href="#Conference-Room-Directory">Conference Rooms |</a></li>
                                <li><a href="#Organizational-Chart">Organizational Chart </a></li>
                        </ul></div><hr></hr>
                        <a name="Employee-Directory" id="Employee-Directory"></a>
                        <h3>Employee Directory</h3>
                                <?php include(get_template_directory() . "/directory.html"); ?>
                                <?php the_content(); ?>

                                        <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) ); ?>
                </div><!-- /.entry -->

                                <?php edit_post_link( __( '{ Edit }', 'woothemes' ), '<span class="small">', '</span>' ); ?>

            </div><!-- /.post -->

            <?php $comm = $woo_options[ 'woo_comments' ]; if ( ($comm == "page" || $comm == "both") ) : ?>
                <?php comments_template(); ?>
            <?php endif; ?>

                <?php endwhile; else: ?>
                        <div <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ) ?></p>
            </div><!-- /.post -->
        <?php endif; ?>

                </div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->

    <?php get_footer(); ?>