<div class="row g-4 justify-content-center">
    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-xxl-4 col-sm-6">
            <div class="pricing-card text-center rounded">
                <div class="pricing-card__header">
                    <div class="pricing-card__overlay"></div>
                    <p class="pricing-card__title"><?php echo e(__(@$plan->name)); ?></p>
                    <h2 class="pricing-card__price">
                        <?php echo e(getAmount(@$plan->per_installment)); ?>

                        <span class="text-small">&nbsp;/ <?php echo e($plan->installment_interval); ?> <?php echo e(__(Str::plural('Day', $plan->installment_interval))); ?></span>
                    </h2>
                </div>
                <div class="pricing-card__content">
                    <ul>

                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name"><?php echo app('translator')->get('Interest Rate'); ?> </p>
                            <p class="pricing-card_value fs-18 ms-auto"><?php echo e(getAmount($plan->interest_rate)); ?>%</p>
                        </li>

                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name"><?php echo app('translator')->get('Per Installment'); ?></p>
                            <p class="pricing-card_value fs-18 ms-auto"><?php echo e(showAmount($plan->per_installment)); ?></p>
                        </li>

                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name"><?php echo app('translator')->get('Installment Interval'); ?></p>
                            <p class="pricing-card_value fs-18 ms-auto"><?php echo e($plan->installment_interval); ?> <?php echo e(__(Str::plural('Day', $plan->installment_interval))); ?></p>
                        </li>
                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name"><?php echo app('translator')->get('Total Installment'); ?></p>
                            <p class="pricing-card_value fs-18 ms-auto"><?php echo e(@$plan->total_installment); ?></p>
                        </li>
                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name"><?php echo app('translator')->get('Deposit'); ?></p>
                            <p class="pricing-card_value fs-18 ms-auto"><?php echo e(showAmount(@$plan->total_installment * @$plan->per_installment)); ?></p>
                        </li>
                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name"><?php echo app('translator')->get('You Will Get'); ?></p>
                            <p class="pricing-card_value fs-18 ms-auto"><?php echo e(showAmount($plan->final_amount)); ?></p>
                        </li>
                    </ul>
                    <button type="button" data-id="<?php echo e($plan->id); ?>" class="btn btn--base dpsBtn"><?php echo app('translator')->get('Apply Now'); ?></button>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php $__env->startPush('script'); ?>
    <script>
        "use strict";
        (function($) {
            $('.dpsBtn').on('click', (e) => {
                let modal = $('#dpsModal');
                let data = e.currentTarget.dataset;
                let form = modal.find('form')[0];
                form.action = `<?php echo e(route('user.dps.apply', '')); ?>/${data.id}`;
                modal.modal('show');
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('modal'); ?>
    <div class="modal fade custom--modal" id="dpsModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="post">
                    <?php if(auth()->guard()->check()): ?>
                        <div class="modal-header">
                            <h5 class="modal-title method-name"><?php echo app('translator')->get('Apply to Open a DPS'); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <?php if(checkIsOtpEnable()): ?>
                                <?php echo $__env->make($activeTemplate . 'partials.otp_field', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <button type="submit" class="btn btn--base w-100"><?php echo app('translator')->get('Submit'); ?></button>
                            <?php else: ?>
                                <?php echo app('translator')->get('Are you sure to apply for this plan?'); ?>
                            <?php endif; ?>
                        </div>
                        <?php if(!checkIsOtpEnable()): ?>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn--dark" data-bs-dismiss="modal" aria-label="Close"><?php echo app('translator')->get('No'); ?></button>
                                <button type="submit" class="btn btn-sm btn--base h-auto"><?php echo app('translator')->get('Yes'); ?></button>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="modal-body">
                            <div class="text-center"><i class="la la-times-circle text--danger la-6x" aria-hidden="true"></i></div>
                            <h3 class="text-center mt-3"><?php echo app('translator')->get('You are not logged in!'); ?></h3>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn--sm btn-dark" data-bs-dismiss="modal" aria-label="Close"><?php echo app('translator')->get('Close'); ?></button>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/partials/dps_plans.blade.php ENDPATH**/ ?>