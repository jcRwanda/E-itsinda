<?php
    $testimonialContent = getContent('testimonial.content', true);
    $testimonials = getContent('testimonial.element', orderById: true);
?>

<?php if($testimonials->count()): ?>
    <section class="testimonials py-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h6 class="section-heading__subtitle"><?php echo e(__(@$testimonialContent->data_values->heading)); ?></h6>
                        <h2 class="section-heading__title"><?php echo e(__(@$testimonialContent->data_values->subheading)); ?></h2>
                    </div>
                </div>
            </div>
            <div class="testimonial-slider">
                <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="testimonails-card">
                        <div class="testimonial-item">
                            <div class="testimonial-item__content">
                                <div class="testimonial-item__info">
                                    <img class="testimonial-item__thumb"
                                        src="<?php echo e(getImage('assets/images/frontend/testimonial/' . @$testimonial->data_values->image, '75x75')); ?>"
                                        alt="<?php echo app('translator')->get('image'); ?>" />
                                    <div class="testimonial-item__details">
                                        <h5 class="testimonial-item__name"><?php echo e(__(@$testimonial->data_values->name)); ?></h5>
                                        <span class="testimonial-item__designation"> <?php echo e(__(@$testimonial->data_values->designation)); ?></span>
                                        <div class="testimonial-item__testimonials">
                                            <?php echo displayRating(floatval(@$testimonial->data_values->rating)) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="testimonial-item__quote-icon">
                                    <img src="<?php echo e(asset($activeTemplateTrue . 'images/icons/quote-icon.png')); ?>" alt="<?php echo app('translator')->get('image'); ?>" />
                                </div>
                            </div>
                            <p class="testimonial-item__desc">
                                <?php echo e(__(@$testimonial->data_values->quote)); ?>

                            </p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $(".testimonial-slider").slick({
                slidesToShow: 2,
                slidesToScroll: 1,
                autoplay: false, //TODO: need auto paly true
                autoplaySpeed: 2000,
                speed: 1500,
                dots: true,
                pauseOnHover: true,
                arrows: false,
                prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-long-arrow-alt-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fas fa-long-arrow-alt-right"></i></button>',
                responsive: [{
                        breakpoint: 1199,
                        settings: {
                            arrows: false,
                            slidesToShow: 2,
                            dots: true,
                        },
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            arrows: false,
                            slidesToShow: 2,
                        },
                    },
                    {
                        breakpoint: 490,
                        settings: {
                            arrows: false,
                            slidesToShow: 1,
                        },
                    },
                ],
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/sections/testimonial.blade.php ENDPATH**/ ?>