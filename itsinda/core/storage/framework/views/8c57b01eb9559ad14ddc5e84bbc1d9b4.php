<?php
    $workContent = getContent('how_it_work.content', true);
    $workElement = getContent('how_it_work.element', orderById: true);
?>
<section class="py-120 work-us">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <h6 class="section-heading__subtitle"><?php echo e(__(@$workContent->data_values->title)); ?></h6>
                    <h2 class="section-heading__title"><?php echo e(__(@$workContent->data_values->heading)); ?></h2>
                </div>
            </div>
        </div>
        <div class="row gx-5 gy-3">
            <?php $__currentLoopData = $workElement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-3 col-sm-6 col-xsm-6">
                    <div class="work-card text-center">
                        <?php if(!$loop->last): ?>
                            <img class="work-card__line" src="<?php echo e(asset($activeTemplateTrue . 'images/shapes/line.png')); ?>" alt="<?php echo app('translator')->get('image'); ?>" />
                        <?php endif; ?>
                        <span class="work-card__number rounded-circle mx-auto"><?php echo e($loop->iteration); ?></span>
                        <div class="work-card__content">
                            <h4 class="work-card__title"><?php echo e(__(@$element->data_values->heading)); ?></h4>
                            <p class="work-card__desc"><?php echo e(__(@$element->data_values->subheading)); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/sections/how_it_work.blade.php ENDPATH**/ ?>