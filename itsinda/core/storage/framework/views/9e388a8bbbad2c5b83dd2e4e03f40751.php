<div class="row g-4 justify-content-center">
    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-xxl-4 col-sm-6">
            <div class="pricing-card text-center rounded">
                <div class="pricing-card__header">
                    <div class="pricing-card__overlay"></div>
                    <p class="pricing-card__title"><?php echo e(__(@$plan->name)); ?></p>
                    <h2 class="pricing-card__price">
                        <?php echo e(getAmount($plan->per_installment)); ?>%
                        <span class="text-small">&nbsp;/ <?php echo e($plan->installment_interval); ?> <?php echo e(__(Str::plural('Day', $plan->installment_interval))); ?></span>
                    </h2>
                </div>
                <div class="pricing-card__content">
                    <ul>
                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name"><?php echo app('translator')->get('Take Minimum'); ?></p>
                            <p class="pricing-card_value fs-18 ms-auto"><?php echo e(showAmount($plan->minimum_amount)); ?></p>
                        </li>

                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name"><?php echo app('translator')->get('Take Maximum'); ?></p>
                            <p class="pricing-card_value fs-18 ms-auto"><?php echo e(showAmount($plan->maximum_amount)); ?></p>
                        </li>

                        <li class="pricing-card__list flex-align">
                            <span class="pricing-card__icon text-stat">
                                <i class="la la-check"></i>
                            </span>
                            <p class="pricing-card__name"><?php echo app('translator')->get('Per Installment'); ?></p>
                            <p class="pricing-card_value fs-18 ms-auto"><?php echo e(getAmount($plan->per_installment)); ?>%</p>
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
                            <p class="pricing-card__name"> <?php echo app('translator')->get('Total Installment'); ?></p>
                            <p class="pricing-card_value fs-18 ms-auto"><?php echo e(__($plan->total_installment)); ?></p>
                        </li>
                    </ul>
                    <button type="button" data-id="<?php echo e($plan->id); ?>" data-minimum="<?php echo e(showAmount($plan->minimum_amount)); ?>" data-maximum="<?php echo e(showAmount($plan->maximum_amount)); ?>" class="btn btn--base loanBtn"><?php echo app('translator')->get('Apply Now'); ?></button>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $('.loanBtn').on('click', (e) => {
                var modal = $('#loanModal');
                let data = e.currentTarget.dataset;
                modal.find('.min-limit').text(`Minimum Amount ${data.minimum}`);
                modal.find('.max-limit').text(`Maximum Amount ${data.maximum}`);
                let form = modal.find('form')[0];
                form.action = `<?php echo e(route('user.loan.apply', '')); ?>/${data.id}`;
                modal.modal('show');
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('modal'); ?>
    <div class="modal fade custom--modal" id="loanModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="" method="post">
                    <?php if(auth()->guard()->check()): ?>
                        <div class="modal-header">
                            <h5 class="modal-title method-name" id="exampleModalLabel"><?php echo app('translator')->get('Apply for Loan'); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="required"><?php echo app('translator')->get('Amount'); ?></label>
                                <div class="input-group custom-input-group">
                                    <input type="number" step="any" name="amount" class="form-control form--control" placeholder="<?php echo app('translator')->get('Enter An Amount'); ?>" required>
                                    <span class="input-group-text"> <?php echo e(gs()->cur_text); ?> </span>
                                </div>
                                <p><small class="text--danger min-limit"></small></p>
                                <p><small class="text--danger max-limit"></small></p>
                            </div>
                            <button type="submit" class="btn btn--base w-100"><?php echo app('translator')->get('Confirm'); ?></button>
                        </div>
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
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/partials/loan_plans.blade.php ENDPATH**/ ?>