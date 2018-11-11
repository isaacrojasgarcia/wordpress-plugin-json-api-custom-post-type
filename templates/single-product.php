<?php
/*

Template Name: Single Product Template

*/

get_header();

if (have_posts()) :

    while (have_posts()) : the_post();

        $page_title = get_the_title();
        ?>

        <div class="container">
            <div  id="main" class="entry-content" style="width: 80%; margin: auto;">


                <h1 class="product-title" style="font-size: 40px; margin-top: 10px; font-weight: 400;text-align: center;"> <?= $page_title ?></h1>
                <h2 style="text-align: center;"><?php echo get_post_meta(get_the_ID(), 'currency')[0]; ?><?php echo get_post_meta(get_the_ID(), 'price')[0]; ?></h2>
                <div style="width: 40%; float: left;">
                    <a href="<?php echo get_post_meta(get_the_ID(), 'URL')[0]; ?>" rel="nofollow"
                       target="_blank">  <?php echo the_post_thumbnail("large"); ?> </a>
                </div>

                <div style="width: 40%; float: right;">
                    <ul>
                        <li>Brand: <?php echo get_post_meta(get_the_ID(), 'brand')[0]; ?></li>
                        <li>Delivery Cost: <?php echo get_post_meta(get_the_ID(), 'deliveryCosts')[0]; ?></li>
                        <li>Delivery Time: <?php echo get_post_meta(get_the_ID(), 'deliveryTime')[0]; ?></li>
                        <?php $gender = get_post_meta(get_the_ID(), 'gender ');
                        if (count($gender) > 0) {
                            ?>
                            <li>Gender: <?php echo $gender[0]; ?></li>
                            <?php
                        }
                        ?>

                        <?php $size = get_post_meta(get_the_ID(), 'size ');
                        if (count($size) > 0) {
                            ?>
                            <li>Size: <?php echo $size[0]; ?></li>
                            <?php
                        }
                        ?>

                        <li style="list-style: none;" ><a href="<?php echo get_post_meta(get_the_ID(), 'URL')[0]; ?>" rel="nofollow" class="btn btn-sm animated-button thar-two"
                               target="_blank" >BUY NOW!!! </a></li>
                    </ul>
                </div>


            </div>
        </div>

    <?php endwhile;

endif;

get_footer();
?>
