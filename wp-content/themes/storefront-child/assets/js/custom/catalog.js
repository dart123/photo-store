jQuery(document).ready(function(){
    console.log('aaaa');
    http://mysite.com/cart/?add-to-cart=PRODUCT_ID&variation_id=VARIATION_ID&attribute_pa_colour=ATTRIBUTE_SLUG
    jQuery('.attribute_select').change(function(){
        let variation_id = jQuery(this).val();
        let product_id = jQuery(this).attr('id');
        let attribute_name = jQuery(this).find('option:selected').prop('class');
        let attribute_val = jQuery(this).find('option:selected').text();
        let url = `/?add-to-cart=${product_id}&variation_id=${variation_id}&${attribute_name}=${attribute_val}`;
        jQuery(this).parents('.custom-select').siblings('a.add_to_cart_button').attr('href', url);
    });
});