<?php $__env->startSection('loan-content'); ?>
    <div class="card custom--card overflow-hidden">
        <div class="card-header">
            <div class="header-nav mb-0">
                <?php if (isset($component)) { $__componentOriginale48b4598ffc2f41a085f001458a956d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale48b4598ffc2f41a085f001458a956d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['placeholder' => 'Loan No.','dateSearch' => 'yes','btn' => 'btn--base']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Loan No.','dateSearch' => 'yes','btn' => 'btn--base']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale48b4598ffc2f41a085f001458a956d1)): ?>
<?php $attributes = $__attributesOriginale48b4598ffc2f41a085f001458a956d1; ?>
<?php unset($__attributesOriginale48b4598ffc2f41a085f001458a956d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale48b4598ffc2f41a085f001458a956d1)): ?>
<?php $component = $__componentOriginale48b4598ffc2f41a085f001458a956d1; ?>
<?php unset($__componentOriginale48b4598ffc2f41a085f001458a956d1); ?>
<?php endif; ?>
                <?php if(request()->date || request()->search): ?>
                    <a class="btn btn-outline--info" href="<?php echo e(request()->fullUrlWithQuery(['download' => 'pdf'])); ?>"><i class="la la-download"></i> <?php echo app('translator')->get('Download PDF'); ?></a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table--responsive--md">
                    <thead>
                        <tr>
                            <th><?php echo app('translator')->get('Loan No.'); ?></th>
                            <th><?php echo app('translator')->get('Rate'); ?></th>
                            <th><?php echo app('translator')->get('Amount'); ?></th>
                            <th><?php echo app('translator')->get('Installment'); ?></th>
                            <th><?php echo app('translator')->get('Given'); ?></th>
                            <th><?php echo app('translator')->get('Total'); ?></th>
                            <th><?php echo app('translator')->get('Next Installment'); ?></th>
                            <th><?php echo app('translator')->get('Total Payable'); ?></th>
                            <th><?php echo app('translator')->get('Status'); ?></th>
                            <th><?php echo app('translator')->get('Action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $__empty_1 = true; $__currentLoopData = $loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>

                                <td>
                                    #<?php echo e($loan->loan_number); ?>

                                </td>

                                <td><?php echo e(getAmount($loan->interestRate())); ?>%</td>

                                <td>
                                    <?php echo e(showAmount($loan->amount)); ?>


                                </td>

                                <td>
                                    <?php echo e(showAmount($loan->per_installment)); ?>

                                </td>

                                <td><?php echo e($loan->given_installment); ?></td>

                                <td><?php echo e($loan->total_installment); ?></td>

                                <td>
                                    <?php if($loan->nextInstallment): ?>
                                        <?php echo e(showDateTime($loan->nextInstallment->installment_date, 'd M, Y')); ?>

                                    <?php else: ?>
                                        <?php echo app('translator')->get('N/A'); ?>
                                    <?php endif; ?>
                                </td>

                                <td><?php echo e(showAmount($loan->payable_amount)); ?></td>

                                <td>
                                    <?php echo $loan->statusBadge; ?>
                                    <?php if($loan->status == 3): ?>
                                        <span class="admin-feedback" data-feedback="<?php echo e(__($loan->admin_feedback)); ?>">
                                            <i class="la la-info-circle"></i>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <button aria-expanded="false" class="btn btn--sm btn--base" data-bs-toggle="dropdown" type="button">
                                            <i class="las la-ellipsis-v m-0"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="<?php echo e(route('user.loan.details', $loan->loan_number)); ?>" class="dropdown-item">
                                                <i class="las la-list"></i> <?php echo app('translator')->get('Details'); ?>
                                            </a>

                                            <a class="dropdown-item <?php if(!$loan->nextInstallment): echo 'disabled'; endif; ?>" href="<?php echo e(route('user.loan.instalment.logs', $loan->loan_number)); ?>">
                                                <i class="las la-wallet"></i> <?php echo app('translator')->get('Installments'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td class="text-center" colspan="100%"><?php echo e(__($emptyMessage)); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($loans->hasPages()): ?>
            <div class="card-footer">
                <?php echo e(paginateLinks($loans)); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";

            $('.admin-feedback').on('click', function() {
                var modal = $('#adminFeedback');
                modal.find('.modal-body').text($(this).data('feedback'));
                modal.modal('show');
            });

        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('modal'); ?>
    <!-- Modal -->
    <div class="modal fade" id="adminFeedback">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo app('translator')->get('Reason of Rejection'); ?>!</h5>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn--dark" data-bs-dismiss="modal" type="button"><?php echo app('translator')->get('Close'); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopPush(); ?>

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

<?php echo $__env->make($activeTemplate . 'user.loan.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/user/loan/list.blade.php ENDPATH**/ ?>