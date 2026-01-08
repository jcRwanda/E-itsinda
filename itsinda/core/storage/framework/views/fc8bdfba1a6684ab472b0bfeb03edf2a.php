<?php $__env->startSection('content'); ?>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div class="d-flex nav-buttons flex-align gap-md-3 gap-2">
            <a href="<?php echo e(route('user.fdr.list')); ?>" class="btn btn-outline--base <?php echo e(menuActive('user.fdr.list')); ?>"><?php echo app('translator')->get('My FDR List'); ?></a>
            <a href="<?php echo e(route('user.fdr.plans')); ?>" class="btn btn-outline--base <?php echo e(menuActive('user.fdr.plans')); ?>"><?php echo app('translator')->get('FDR Plans'); ?></a>
        </div>
    </div>

    <?php echo $__env->yieldContent('fdr-content'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .btn[type=submit] {
            height: unset !important;
        }

        .btn {
            padding: 12px 1.875rem;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/user/fdr/layout.blade.php ENDPATH**/ ?>