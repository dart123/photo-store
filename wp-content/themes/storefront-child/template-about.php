<?php /* Template Name: О фирме */ ?>
<?php    get_header(); ?>

<h1 class="content__title" style="font-weight: bold">О фирме</h1>

<?php
    $about_img = get_field('about_img');
    $about_text = get_field('about_text');
    $about_photos = get_field('about_photos');
    $about_certificates = get_field('about_certificates');

    $about_facts = array();
    $i = 0;
    while (!empty(get_field('about_fact_'.($i+1))) && count(get_field('about_fact_'.($i+1))) > 0)
    {
        $about_facts[] = get_field('about_fact_'.($i+1));
        $i++;
    }

    $about_reviews = array();
    $i = 0;
    while (!empty(get_field('about_review_'.($i+1))) && count(get_field('about_review_'.($i+1))) > 0)
    {
        $about_reviews[] = get_field('about_review_'.($i+1));
        $i++;
    }
//    echo 'facts:';
//    print_r($about_facts);
//    echo 'reviews:';
//    print_r($about_reviews);
//if (!empty($about_facts[$i]) && count($about_facts[$i]) > 0)
//{
//
//}
?>

    <div class="about__info">
        <?php if (isset($about_img) && !empty($about_img)): ?>
            <img class="about__info__img" src="<?=$about_img?>"/>
        <?php endif; ?>
        <div class="about__info__cont">
            <?php if (isset($about_text) && !empty($about_text)): ?>
            <div>
                <?php echo $about_text; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="about__facts">
        <?php if (isset($about_facts) && !empty($about_facts)): ?>
            <?php foreach($about_facts as $fact): ?>
                <?php if (!empty($fact) && count($fact) > 0 && !empty($fact['img_fact']) && !empty($fact['header_fact']) && !empty($fact['text_fact'])): ?>
                    <div class="about__facts__item">
                        <img src="<?=$fact['img_fact']?>"/>
                        <h4><?=$fact['header_fact']?></h4>
                        <div class="about_fact_description"><?=$fact['text_fact']?></div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="about__photos">
        <?php if (isset($about_photos) && !empty($about_photos)): ?>
            <?php foreach($about_photos as $photo): ?>
                <?php if (!empty($photo)): ?>
                    <img src="<?=$photo?>"/>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="about__reviews">
        <h2>Отзывы о компании</h2>

        <div class="about__reviews__items">
            <?php if (isset($about_reviews) && !empty($about_reviews)): ?>
                <?php foreach($about_reviews as $review): ?>
                    <?php if (!empty($review['review_username']) && !empty($review['review_text'])): ?>
                        <div class="about__reviews__item">
                            <div class="about__reviews__item__cont">
                                <div class="about__reviews__item__user">
                                    <?php if (!empty($review['review_userphoto'])): ?>
                                        <img src="<?=$review['review_userphoto']?>"/>
                                    <?php endif; ?>
                                    <div class="user_info">
                                        <h3><?=$review['review_username']?></h3>
                                        <?php if (!empty($review['review_date'])): ?>
                                            <div><?=$review['review_date']?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if (!empty($review['review_rate'])): ?>
                                    <div class="about__reviews__item__rate">
                                        <?php
                                        $rate = (int)$review['review_rate'];
                                        for ($i=1; $i<=5; $i++)
                                        {
                                            if ($i <= $rate)
                                                echo '<img src="/wp-content/themes/storefront-child/assets/images/custom/rate-star.svg"/>';
                                            else
                                                echo '<img src="/wp-content/themes/storefront-child/assets/images/custom/star.svg"/>';
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="about__reviews__item__text"><?=$review['review_text']?></div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="about__certs">
        <h2>Наши сертификаты</h2>

        <div class="about__certs__items">
            <?php if (isset($about_certificates) && !empty($about_certificates)): ?>
                <?php foreach($about_certificates as $certificate): ?>
                    <?php if (!empty($certificate)): ?>
                        <div class="about__certs__item">
                            <a href="<?=$certificate?>">
                                <img src="<?=$certificate?>"/>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        var ph = jQuery('.about__photos'), reviews = jQuery('.about__reviews__items'), certs = jQuery('.about__certs__items');
        ph.slick({
            infinity: true,
            slidesToShow: 1,
            initialSlide: 0,
            //variableWidth: true,
            draggable: false,
            prevArrow: '<div class="about__prev"><div class="bkg-img"></div></div>',
            nextArrow: '<div class="about__next"><div class="bkg-img"></div></div>'
        });
        reviews.slick({
            infinity: true,
            slidesToShow: 2,
            initialSlide: 0,
            //variableWidth: true,
            draggable: false,
            prevArrow: '<div class="about__prev"><div class="bkg-img"></div></div>',
            nextArrow: '<div class="about__next"><div class="bkg-img"></div></div>',
            responsive: [{
                breakpoint: 1000,
                settings: {
                    slidesToShow: 1
                }
            }]
        });
        certs.slick({
            infinity: true,
            slidesToShow: 3,
            initialSlide: 0,
            //variableWidth: true,
            draggable: false,
            prevArrow: '<div class="about__prev bkg-img"><div class="bkg-img"></div></div>',
            nextArrow: '<div class="about__next bkg-img"><div class="bkg-img"></div></div>',
            responsive: [{
                breakpoint: 1000,
                settings: {
                    slidesToShow: 1
                }
            }]
        });
    </script>

<?php get_footer(); ?>