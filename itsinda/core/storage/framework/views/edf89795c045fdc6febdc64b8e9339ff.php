<?php
    $counterContent = getContent('counter.content', true);
    $counters = getContent('counter.element', orderById: true);
?>
<section class="py-120 counter section-overlay bg-img"
    data-background-image="<?php echo e(getImage('assets/images/frontend/counter/' . @$counterContent->data_values->image, '1920x400')); ?>">
    <span class="counter-overlay"></span>
    <div class="container">
        <div class="row g-4 ">
            <?php $__currentLoopData = $counters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $counter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-6 col-md-3">
                    <div class="counter-card counterup-item">
                        <h5 class="counter-card__title">
                            <span class="odometer" data-odometer-final="<?php echo e(@$counter->data_values->digit); ?>"></span>
                            <span class="extra"><?php echo e(@$counter->data_values->symbol); ?></span>
                        </h5>
                        <p class="counter-card__desc">
                            <?php echo e(__(@$counter->data_values->title)); ?>

                        </p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<?php $__env->startPush('style-lib'); ?>
    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'css/odometer.css')); ?>" />
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/viewport.jquery.js')); ?>"></script>
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/odometer.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/sections/counter.blade.php ENDPATH**/ ?>