<?php $__env->startSection('content'); ?>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div class="d-flex nav-buttons flex-align gap-md-3 gap-2">
            <?php if(@gs()->modules->own_bank || @$gs()->modules->other_bank || @gs()->modules->wire_transfer): ?>
                <a href="<?php echo e(route('user.transfer.history')); ?>" class="btn btn-outline--base <?php echo e(menuActive('user.transfer.history')); ?>">
                    <?php echo app('translator')->get('Transfer History'); ?>
                </a>

                <?php if(@gs()->modules->own_bank): ?>
                    <a href="<?php echo e(route('user.transfer.own.bank.beneficiaries')); ?>" class="btn btn-outline--base <?php echo e(menuActive('user.transfer.own.bank.beneficiaries')); ?>">
                        <?php echo app('translator')->get('Transfer Within'); ?> <?php echo app('translator')->get(gs()->site_name); ?></a>
                <?php endif; ?>

                <?php if(@gs()->modules->other_bank): ?>
                    <a href="<?php echo e(route('user.transfer.other.bank.beneficiaries')); ?>" class="<?php if(request()->routeIs('user.transfer.other.bank.beneficiaries')): ?> btn btn--base active <?php else: ?> btn btn-outline--base <?php endif; ?>">
                        <?php echo app('translator')->get('Transfer to Other Bank'); ?>
                    </a>
                <?php endif; ?>
                <?php if(@gs()->modules->wire_transfer): ?>
                    <a href="<?php echo e(route('user.transfer.wire.index')); ?>" class="<?php if(request()->routeIs('user.transfer.wire.index')): ?> btn btn--base active <?php else: ?> btn btn-outline--base <?php endif; ?>">
                        <?php echo app('translator')->get('Wire Transfer'); ?>
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="header-nav mb-0 flex-grow-1">
            <?php echo $__env->yieldPushContent('header-nav'); ?>
        </div>
    </div>

    <?php echo $__env->yieldContent('transfer-content'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .btn[type=submit] {
            height: unset !important;
        }

       .nav-buttons .btn {
            padding: 12px 16px;
        }

        @media (max-width: 650px) {
            .nav-buttons .btn {
                padding: 8px 10px;
            }
        }
        @media (max-width: 550px) {
            .nav-buttons .btn {
               flex-grow: 1;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/user/transfer/layout.blade.php ENDPATH**/ ?>