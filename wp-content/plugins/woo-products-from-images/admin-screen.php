<?php $groups = woo_get_all_subcategories(); ?>
<div class="woocommerce" style="margin: 10px 20px 0 2px;">
    <div class="prod_generation_main">
        <div class="image_gallery">
            <h2 class="header">Изображения</h2>
            <div class='image-preview-wrapper'></div>

            <input id="upload_image_button" type="button" class="button" value="Загрузить изображения" />
        </div>

        <form id="product_generation_form" method="post">
            <div class="product_categories">
                <h2 class="header">Категории</h2>
                <div class="product_cat_wrapper">
                    <div class="product_cat_item">
                        <label for="product_category_1">Категория 1</label>
                        <select id="product_category_1" class="product_category" name="product_category[0]">
                            <?php foreach ($groups as $group): ?>
                                <option value="<?=$group['cat_id']?>"><?=$group['cat_name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="product_variations">
                <h2 class="header">Вариации</h2>
                <div class="product_variation_wrapper">
                    <div class="product_variation_item">
                        <h3>Вариация 1</h3>
                        <label for="product_variation_price_1">Цена вариации 1</label>
                        <input id="product_variation_price_1" type="text" name="product_variation[0][price]">
                        <label for="product_variation_attribute_1">Атрибут вариации 1</label>
                        <select id="product_variation_attribute_1" name="product_variation[0][attribute]"></select>
                    </div>
                </div>
            </div>

            <div class="generate_products_submit">
                <input id="generate_products_button" type="submit" name="submit" value="Сгенерировать товары" class="button-primary">
            </div>
        </form>

    </div>
</div>