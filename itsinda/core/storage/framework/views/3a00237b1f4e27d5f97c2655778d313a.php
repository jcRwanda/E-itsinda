<?php
    $featureContent = getContent('feature.content', true);
    $features = getContent('feature.element', false, 3, true);
?>
<?php if(!blank($features)): ?>
    <div class="py-120 features section-bg-light">
        <div class="container">

            <div class="section-heading ">
                <h6 class="section-heading__subtitle"><?php echo e(__(@$featureContent->data_values->heading)); ?></h6>
                <h2 class="section-heading__title">
                    <?php echo e(__(@$featureContent->data_values->subheading)); ?>

                </h2>
            </div>

            <div class="row g-4 justify-content-center">
                <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-sm-6 col-xsm-6">
                        <div class="feature-card flex-center">
                            <div class="feature-card__overlay"></div>
                            <div class="feature-card__icon">
                                <img src="<?php echo e(asset($activeTemplateTrue . 'images/shapes/hexagon-shap.png')); ?>" alt="<?php echo app('translator')->get('image'); ?>" />
                                <span class="icon">
                                    <?php echo @$feature->data_values->icon ?>
                                </span>
                            </div>
                            <div class="feature-card__content text-center">
                                <h4 class="feature-card__title"><?php echo e(__(@$feature->data_values->heading)); ?></h4>
                                <p class="feature-card__desc">
                                    <?php echo e(__(@$feature->data_values->subheading)); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/sections/feature.blade.php ENDPATH**/ ?>