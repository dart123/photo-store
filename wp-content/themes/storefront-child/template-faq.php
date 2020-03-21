<?php /* Template Name: Помощь и FAQ */ ?>
<?php    get_header(); ?>

<h1 class="content__title">Помощь и FAQ</h1>

<?php
$questions = get_field('faq_questions');
$answers = get_field('faq_answers');

if( $questions && $answers && count($questions)==count($answers) ): ?>
    <div class="faq__list">
        <?php foreach (array_combine(array_values($questions), array_values($answers)) as $question => $answer): ?>
           <?php if (!empty($question) && !empty($answer)): ?>
                <div class="faq__list__item">
                    <div class="faq__list__item__cont" onclick="this.parentNode.classList.toggle('opened')">
                        <h3 class="faq__list__item__title"><?php echo $question ?></h3>
                        <div class="faq__list__item__more bkg-img"></div>
                    </div>
                    <div class="faq__list__item__text">
                        <?php echo $answer; ?>
                    </div>
                </div>
            <?php endif ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
