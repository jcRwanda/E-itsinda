<?php
    $aboutContent = getContent('about.content', true);
    $aboutElement = getContent('about.element', orderById: true);
?>
<?php if($aboutContent): ?>
    <section class="py-120 about-section">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6 order-lg-1 order-2">
                    <div class="about-thumb text-center">
                        <div class="thumb-card flex-align gap-3 text-start">
                            <span class="thumb-card__icon flex-center">
                                <?php echo @$aboutContent->data_values->image_popup_icon ?>
                            </span>
                            <div class="thumb-card__content">
                                <h5 class="thumb-card__title"><?php echo e(__(@$aboutContent->data_values->image_popup_digit)); ?></h5>
                                <p class="thumb-card__desc"><?php echo e(__(@$aboutContent->data_values->image_popup_title)); ?></p>
                            </div>
                        </div>
                        <img class="thumbnail" src="<?php echo e(getImage('assets/images/frontend/about/' . @$aboutContent->data_values->image, '385x460')); ?>"
                            alt="<?php echo app('translator')->get('image'); ?>" />
                        <span class="shape">
                            <i class="icon-element-55-1"></i>
                        </span>
                    </div>
                </div>
                <div class="col-lg-5 order-lg-2 order-1">
                    <div class="section-heading style-left">
                        <h6 class="section-heading__subtitle"><?php echo e(__(@$aboutContent->data_values->heading)); ?></h6>
                        <h2 class="section-heading__title">
                            <?php echo e(__(@$aboutContent->data_values->subheading)); ?>

                        </h2>
                    </div>
                    <div class="about-section__content">
                        <ul class="nav custom--tab nav-pills mb-3" id="about-tab" role="tablist">
                            <?php $__currentLoopData = $aboutElement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $about): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?php if($loop->first): ?> active <?php endif; ?>" id="pills-<?php echo e($loop->iteration); ?>-tab"
                                        data-bs-toggle="pill" data-bs-target="#pills-<?php echo e($loop->iteration); ?>" type="button" role="tab"
                                        aria-controls="pills-<?php echo e($loop->iteration); ?>" aria-selected="true">
                                        <?php echo e(__(@$about->data_values->heading)); ?>

                                    </button>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <div class="tab-content" id="about-tabContent">
                            <?php $__currentLoopData = $aboutElement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $about): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="tab-pane fade <?php if($loop->first): ?> show active <?php endif; ?>" id="pills-<?php echo e($loop->iteration); ?>"
                                    role="tabpanel" aria-labelledby="pills-<?php echo e($loop->iteration); ?>-tab" tabindex="0">
                                    <p class="tab-pane__text">
                                        <?php echo e(__(@$about->data_values->description)); ?>

                                    </p>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/sections/about.blade.php ENDPATH**/ ?>