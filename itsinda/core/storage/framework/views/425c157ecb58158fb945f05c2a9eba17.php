<?php
    $faq = getContent('faq.content', true);
    $faqs = getContent('faq.element', orderById: true);
?>
<?php if($faq): ?>
    <section class="py-120 faq section-bg-light bg-img" data-background-image="<?php echo e(asset($activeTemplateTrue . 'images/thumbs/faq-bg.png')); ?>">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="section-heading style-left">
                        <h6 class="section-heading__subtitle"><?php echo e(__(@$faq->data_values->heading)); ?></h6>
                        <h2 class="section-heading__title">
                            <?php echo e(__(@$faq->data_values->subheading)); ?>

                        </h2>
                    </div>
                    <div class="faq-content">
                        <p class="faq-content__desc"><?php echo e(__(@$faq->data_values->description)); ?></p>
                        <a href="<?php echo e(@$faq->data_values->button_link); ?>" class="btn btn--base"><?php echo e(__(@$faq->data_values->button_text)); ?></a>
                    </div>
                </div>

                <div class="col-lg-8 col-md-12 right-section">
                    <div class="accordion accordion-flush custom--accordion" id="viserBank-faq">
                        <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button  <?php if(!$loop->first): ?> collapsed <?php endif; ?>" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#id-<?php echo e($loop->iteration); ?>-faq"
                                        <?php if($loop->first): ?> aria-expanded="true" <?php else: ?> aria-expanded="false" <?php endif; ?>
                                        aria-controls="id-<?php echo e($loop->iteration); ?>-faq">
                                        <?php echo e(__(@$faq->data_values->question)); ?>

                                    </button>
                                </h2>
                                <div id="id-<?php echo e($loop->iteration); ?>-faq"
                                    class="accordion-collapse collapse <?php if($loop->first): ?> show <?php endif; ?>" data-bs-parent="#viserBank-faq">
                                    <p class="accordion-body">
                                        <?php echo e(__(@$faq->data_values->answer)); ?>

                                    </p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/sections/faq.blade.php ENDPATH**/ ?>