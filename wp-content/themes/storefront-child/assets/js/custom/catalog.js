function generate_attribute_url(el)
{
    let variation_id = el.val();
    let product_id = el.attr('id');
    let selected_option = el.find('option:selected').length > 0 ? el.find('option:selected') : el.find('option')[0];
    let attribute_name = selected_option.prop('class');
    let attribute_val = selected_option.text();
    let amount = el.parents('.custom-select').siblings('.main__item__cont').find('.quantity input[name="quantity"]').val();
    let url = `/?add-to-cart=${product_id}&variation_id=${variation_id}&${attribute_name}=${attribute_val}&quantity=${amount}`;
    return url;
}
function get_product_price(el)
{
    let select = el.parents('.main__item__cont').siblings('.custom-select').find('select.attribute_select');
    let selected_option = select.find('option:selected').length > 0 ? select.find('option:selected') : select.find('option')[0];
    let price = selected_option.data('price');
    return price;
}

jQuery(document).ready(function(){
    //Открытие и закрытие мобильного меню
    var i = 0;
    jQuery('#header .header__menu__item-img.bkg-img.menu').click(function(){
        if( i==0 )
        {
            jQuery('#header .mobile-menu, #header .mobile-menu-bg').show("slow");
            i++;
        }
        else
        {
            jQuery('#header .mobile-menu, #header .mobile-menu-bg').hide("slow");
            i = 0;
        }

    });

    if (jQuery('.storefront-breadcrumb').length > 0)
        jQuery('#content').css('padding-top', '0');

    //Устанавливаем правильную цену на все продукты при загрузке страницы
    jQuery('ul.products .product .main__item__price .woocommerce-Price-amount.amount').each(function(){
        jQuery(this).text(get_product_price(jQuery(this) ) );
        jQuery(this).append("<span class='woocommerce-Price-currencySymbol'>₽</span>");
    });

    jQuery('.attribute_select').each(function(index){
        jQuery(this).parents('.custom-select').siblings('a.add_to_cart_button').attr('href', generate_attribute_url(jQuery(this) ) );
    });
    //http://mysite.com/cart/?add-to-cart=PRODUCT_ID&variation_id=VARIATION_ID&attribute_pa_colour=ATTRIBUTE_SLUG
    jQuery('.attribute_select').change(function(){
        let selected_option = jQuery(this).find("option:selected");
        let price_el = jQuery(this).parents('.custom-select').siblings('.main__item__cont').find(".woocommerce-Price-amount.amount");
        //Меняем ссылку на корзину
        jQuery(this).parents('.custom-select').siblings('a.add_to_cart_button').attr('href', generate_attribute_url(jQuery(this) ) );
        let amount = jQuery(this).parents('.custom-select').siblings('.main__item__cont').find('.quantity input[name="quantity"]').val();
        price_el.text(selected_option.data("price") * amount);
        price_el.append("<span class='woocommerce-Price-currencySymbol'>₽</span>");
    });
    //Замена количества
    jQuery('.main__item__cont input[name="quantity"]').change(function(){
        let cart_btn = jQuery(this).parents('.main__item__cont').siblings('a.add_to_cart_button');
        let cart_url = cart_btn.attr('href');
        let contains_quantity = cart_url.indexOf("quantity") >= 0;

        let price_el = jQuery(this).parents('.main__item__cont').find(".woocommerce-Price-amount.amount");
        //let current_price = price_el.clone().children().remove().end().text();
        let selected_option = jQuery(this).parents('.main__item__cont').siblings('.custom-select').find('.attribute_select option:selected');

        if (selected_option.length > 0 && typeof selected_option !== 'undefined')
            var new_price = selected_option.data('price') * jQuery(this).val();
        else
            var new_price = cart_btn.data('base_price') * jQuery(this).val();
        price_el.text(new_price);
        price_el.append("<span class='woocommerce-Price-currencySymbol'>₽</span>");

        if (!contains_quantity)
            cart_btn.attr('href', cart_url + '&quantity=' + jQuery(this).val() );
        else
            cart_btn.attr('href', cart_url.slice(0, (cart_url.lastIndexOf("=") - cart_url.length) + 1 ) + jQuery(this).val());
    });
});