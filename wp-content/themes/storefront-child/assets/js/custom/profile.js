jQuery(document).ready(function(){
    jQuery('.tab-register').hide();
    jQuery(".login__form__switch__item").click(function(){
        if (jQuery(this).hasClass('active'))
            return false;
        else
            if (jQuery(this).siblings('a').hasClass('active'))
            {
                jQuery(this).siblings('a').removeClass('active');
                jQuery(this).addClass('active');

                if (jQuery(this).hasClass('login-link'))
                {
                    jQuery('.tab-register').hide();
                    jQuery('.tab-login').show();
                }
                else
                    if (jQuery(this).hasClass('register-link'))
                    {
                        jQuery('.tab-login').hide();
                        jQuery('.tab-register').show();
                    }

            }
    });
});