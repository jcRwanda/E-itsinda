<?php
    $contact = getContent('contact_us.content', true);
?>
<div class="header-top d-lg-block d-none">
    <div class="container">
        <div class="top-header-wrapper flex-between">
            <ul class="top-contact contact-list">
                <li class="contact-list__item">
                    <span class="contact-list__item-icon"><i class="fas fa-map-marker-alt"></i></span>
                    <span class="contact-list__link">
                        <?php echo e(@$contact->data_values->contact_address); ?>

                    </span>
                </li>
                <li class="contact-list__item">
                    <span class="contact-list__item-icon">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <a href="mailto:<?php echo e(@$contact->data_values->email_address); ?>" class="contact-list__link">
                        <?php echo e(@$contact->data_values->email_address); ?>

                    </a>
                </li>
            </ul>
            <ul class="top-button follow-social-list d-flex flex-wrap justify-content-between align-items-center">
                <span class="follow-social-list__text"><?php echo app('translator')->get('Follow US'); ?></span>
                <?php echo $__env->make($activeTemplate . 'partials.social_link', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </ul>
        </div>
    </div>
</div>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/partials/header_top.blade.php ENDPATH**/ ?>