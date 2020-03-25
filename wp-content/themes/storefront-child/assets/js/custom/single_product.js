jQuery(document).ready(function() {
    jQuery('table.variations td.value select#pa_format').change(function () {
        let selected_option = jQuery(this).find("option:selected");
        if (selected_option.val() === '')
            return;
        let price_el = jQuery(this).parents('table.variations').siblings('p.price').find(".woocommerce-Price-amount.amount");

        let amount = jQuery(this).parents('table.variations').siblings('div.quantity').find('input[name="quantity"]').val();
        price_el.text(selected_option.attr("data-price") * amount);
        price_el.append("<span class='woocommerce-Price-currencySymbol'>₽</span>");
    });
//Замена количества
    jQuery("form.variations_form div.quantity input[name='quantity']").change(function () {

        let price_el = jQuery(this).parents('div.quantity').siblings('p.price').find(".woocommerce-Price-amount.amount");
        //let current_price = price_el.clone().children().remove().end().text();
        let selected_option = jQuery(this).parents('div.quantity')
            .siblings('table.variations')
            .find('td.value select#pa_format')
            .find('option:selected');
        if (selected_option.val() === '')
            return;
        //console.log(selected_option);

        if (selected_option.length > 0 && typeof selected_option !== 'undefined')
            var new_price = selected_option.attr('data-price') * jQuery(this).val();
        else
            var new_price = jQuery('select#pa_format option:nth-child(2)').attr('data-price') * jQuery(this).val();
        price_el.text(new_price);
        price_el.append("<span class='woocommerce-Price-currencySymbol'>₽</span>");
    });
});