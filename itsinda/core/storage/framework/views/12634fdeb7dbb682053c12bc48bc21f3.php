<?php
    $banner = getContent('banner.content', true);
?>
<section class="banner-section section-overlay">
    <div class="banner-bg-masks-group">
        <span class="bg-mask bg-circle"></span>
        <span class="bg-mask bg-polygon"></span>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-6">
                <div class="banner-content">
                    <h1 class="banner-content__title"><?php echo e(__(@$banner->data_values->heading)); ?></h1>
                    <div class="banner-content__bottom flex-align gap-4">
                        <span class="banner-animation">
                            <i class="icon-Asset-1"></i>
                        </span>

                        <a href="<?php echo e(@$banner->data_values->button_link); ?>" class="btn btn--base"><?php echo e(__(@$banner->data_values->button_text)); ?></a>
                        <div class="video-preview-content flex-center">
                            <div class="video-preview__img">
                                <img src="<?php echo e(getImage('assets/images/frontend/banner/' . @$banner->data_values->video_thumbnail, '105x65')); ?>"
                                    alt="<?php echo app('translator')->get('image'); ?>" />
                                <a href="<?php echo e(@$banner->data_values->video_link); ?>" class="video-preview" data-rel="lightcase:myCollection"
                                    class="video-icon wow fadeInRight">
                                    <span class="video-preview__icon">
                                        <i class="fas fa-play"></i>
                                    </span>
                                </a>

                            </div>

                            <h5 class="video-preview__text"><?php echo app('translator')->get('Watch Video'); ?></h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-md-6">
                <div class="banner-thumb">
                    <div class="banner-img">
                        <img src="<?php echo e(getImage('assets/images/frontend/banner/' . @$banner->data_values->image, '495x560')); ?>"
                            alt="<?php echo app('translator')->get('image'); ?>" />
                        <div class="happy-user d-flex justify-content-between">
                            <img class="happy-user__img"
                                src="<?php echo e(getImage('assets/images/frontend/banner/' . @$banner->data_values->user_images, '210x70')); ?>"
                                alt="<?php echo app('translator')->get('image'); ?>" />
                            <div class="happy-user__content">
                                <h4 class="happy-user__title"><?php echo e(__(@$banner->data_values->total_user)); ?></h4>
                                <p class="happy-user__desc fs-15"><?php echo e(__(@$banner->data_values->title)); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->startPush('style'); ?>
    <style>
        .lightcase-icon-spin:before {
            font-family: 'Line Awesome Free';
            font-weight: 900;
            content: "\f1ce";
        }

        .lightcase-icon-close:before {
            content: "\f00d";
            font-family: 'Line Awesome Free';
            font-weight: 900;
        }

        [class*='lightcase-icon-']:before {}
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('style-lib'); ?>
    <link href="<?php echo e(asset('assets/global/css/lightcase.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset('assets/global/js/lightcase.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $('a[data-rel^=lightcase]').lightcase();
        })(jQuery)
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/sections/banner.blade.php ENDPATH**/ ?>