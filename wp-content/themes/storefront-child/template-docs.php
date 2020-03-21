<?php /* Template Name: Документы */ ?>
<?php    get_header(); ?>

<h1 class="content__title">Документы</h1>

<?php
$images = get_field('doc_images');
if( isset($images) && !empty($images) ): ?>
<div class="items-grid">
    <?php foreach( $images as $image ): ?>
        <?php if (!empty($image)): ?>
            <div class="items-grid__item docs__item">
                <a href="<?php echo esc_url($image); ?>" target="_blank">
                    <img src="<?php echo esc_url($image); ?>"/>
                </a>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<!--<div class="pages">-->
<!--    <a class="pages__item active" href="##">1</a>-->
<!--    <a class="pages__item" href="##">2</a>-->
<!--    <a class="pages__item" href="##">3</a>-->
<!--    <div class="pages__item">...</div>-->
<!--    <a class="pages__item" href="##">10</a>-->
<!--    <a class="pages__item pages__item__next bkg-img" href="##"></a>-->
<!--</div>-->

<?php get_footer(); ?>
