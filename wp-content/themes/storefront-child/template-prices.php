<?php /* Template Name: Цены и условия */ ?>
<?php    get_header(); ?>

<h1 class="content__title" style="font-weight: bold">Цены и условия</h1>

<?php
$prices_text = get_field('prices_text');
$product_names = get_field('product_names');
$product_prices = get_field('product_prices');
?>

<div class="prices_text">
    <?php if (isset($prices_text) && !empty($prices_text)) echo $prices_text; ?>
</div>

<?php
if( $product_names && $product_prices && count($product_names)==count($product_prices) ): ?>
    <table class="prices__list">
        <tbody>
            <?php foreach (array_combine(array_values($product_names), array_values($product_prices)) as $name => $price): ?>
               <?php if (!empty($name) && !empty($price)): ?>
                    <tr>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $price; ?> ₽</td>
                    </tr>
                <?php endif ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php get_footer(); ?>
