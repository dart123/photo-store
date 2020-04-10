<?php $groups = woo_get_all_subcategories();
      $attributes = woo_get_all_attributes();
?>
<div class="woocommerce" style="margin: 10px 20px 0 2px;">
    <div class="prod_generation_main">
        <div class="image_gallery">
            <h2 class="header">Изображения</h2>
            <div class='image-preview-wrapper'></div>

            <input id="upload_image_button" type="button" class="button" value="Загрузить изображения" />
        </div>

        <form id="product_generation_form" method="post">
            <div class="product_categories">
                <h2 class="header">Группа</h2>
                <div class="product_cat_wrapper">
                    <div class="product_cat_item">
                        <label for="product_category">Группа</label>
                        <select id="product_category" required name="product_category">
                            <?php foreach ($groups as $group): ?>
                                <option value="<?=$group['cat_id']?>"><?=$group['cat_name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="product_variations">
                <h2 class="header">Вариации товаров</h2>
                <div class="product_variation_wrapper">
                    <div class="product_variation_item">
                        <h3>Вариация 1</h3>
                        <label for="product_variation_price_1">Цена вариации 1</label>
                        <input id="product_variation_price_1" required type="text" name="product_variation[0][price]">
                        <label for="product_variation_attribute_1">Атрибут вариации 1</label>
                        <select id="product_variation_attribute_1" required name="product_variation[0][attribute]">
                            <?php foreach ($attributes as $attribute): ?>
                                <option value="<?=$attribute->slug ?>"><?=$attribute->name?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <input id="add_variation_btn" type="button" value="Добавить вариацию" class="button-primary">
            </div>

            <div class="generate_products_submit">
                <input id="generate_products_button" type="submit" name="submit" value="Сгенерировать товары" class="button-primary">
            </div>
        </form>

    </div>
</div>