<?php /* Template Name: Контакты */ ?>
<?php    get_header(); ?>

<h1 class="content__title" style="font-weight: bold">Контакты</h1>

<?php
$whatsapp_num = get_field('whatsapp_num');
$viber_num = get_field('viber_num');
$mobile_num = get_field('mobile_num');
$email = get_field('email');
$schedule = get_field('schedule');
$requisites = get_field('requisites');
?>

<div class="contacts__cont">
    <div class="contacts__form">
        <h3>Напишите нам</h3>
        <form>
            <div class="contacts__form__cont">
                <div>
                    <label class="input__label" for="input-name">Ваше имя</label>
                    <input type="text" id="input-name" class="input">
                </div>
                <div>
                    <label class="input__label" for="input-phone">Телефон для связи</label>
                    <input type="text" id="input-phone" class="input"/>
                </div>
            </div>

            <label class="input__label" for="input-message">Сообщение</label>
            <textarea id="input-message" class="input"></textarea>

            <input type="submit" value="ОТПРАВИТЬ" class="input"/>
        </form>
    </div>
    <div class="contacts__info">
        <div class="contacts__info__socials">
            <?php if (isset($whatsapp_num) && !empty($whatsapp_num)): ?>
            <a class="whatsapp" href="https://api.whatsapp.com/send?phone=<?=$whatsapp_num?>"><img src="/wp-content/themes/storefront-child/assets/images/custom/wa-icon.svg"/></a>
            <?php endif; ?>

            <?php if (isset($viber_num) && !empty($viber_num)): ?>
            <a href="viber://chat/?number=%2B<?=$viber_num?>"><img src="/wp-content/themes/storefront-child/assets/images/custom/vb-icon.svg"/></a>
            <?php endif; ?>
        </div>
        <div class="contacts__info__cont">
            <div class="contacts__info__phone bkg-img">
                <?php if (isset($mobile_num) && !empty($mobile_num)): ?>
                    <a href="tel:<?=$mobile_num?>"><?php echo $mobile_num; ?></a>
                <?php endif; ?>
            </div>
            <div class="contacts__info__email bkg-img">
                <?php if (isset($email) && !empty($email)): ?>
                    <a href="mailto:<?=$email?>"><?php echo $email; ?></a>
                <?php endif; ?>
            </div>
            <div class="contacts__info__time bkg-img">
                <?php if (isset($schedule) && !empty($schedule)): ?>
                    <?php echo $schedule; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="contacts__info__reg">
            <?php if (isset($requisites) && !empty($requisites)): ?>
                <?php echo $requisites; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="contacts__map">
    <!-- map -->
</div>

<?php get_footer(); ?>
