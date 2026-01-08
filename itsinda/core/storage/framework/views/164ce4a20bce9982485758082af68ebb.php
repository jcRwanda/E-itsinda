<?php $__env->startSection('dps-content'); ?>
    <div class="card custom--card overflow-hidden">
        <div class="card-header">
            <div class="header-nav mb-0">
                <?php if (isset($component)) { $__componentOriginale48b4598ffc2f41a085f001458a956d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale48b4598ffc2f41a085f001458a956d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['placeholder' => 'DPS No.','dateSearch' => 'yes','btn' => 'btn--base']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'DPS No.','dateSearch' => 'yes','btn' => 'btn--base']); ?>
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
                            <th><?php echo app('translator')->get('DPS No.'); ?></th>
                            <th><?php echo app('translator')->get('Rate'); ?></th>
                            <th><?php echo app('translator')->get('Per Installment'); ?></th>
                            <th><?php echo app('translator')->get('Total'); ?></th>
                            <th><?php echo app('translator')->get('Given'); ?></th>
                            <th><?php echo app('translator')->get('Next Installment'); ?></th>
                            <th><?php echo app('translator')->get('DPS Amount'); ?></th>
                            <th><?php echo app('translator')->get('Maturity Amount'); ?></th>
                            <th><?php echo app('translator')->get('Status'); ?></th>
                            <th><?php echo app('translator')->get('Action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $allDps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    #<?php echo e($dps->dps_number); ?>

                                </td>

                                <td><?php echo e(getAmount($dps->interest_rate)); ?>%</td>

                                <td><?php echo e(showAmount($dps->per_installment)); ?> /<?php echo e($dps->installment_interval); ?> <?php echo e(__(Str::plural('Day', $dps->installment_interval))); ?></td>

                                <td><?php echo e($dps->total_installment); ?></td>
                                <td><?php echo e($dps->given_installment); ?></td>
                                <td><?php echo e(showDateTime(@$dps->nextInstallment->installment_date, 'd M, Y')); ?></td>
                                <td><?php echo e(showAmount($dps->depositedAmount())); ?></td>
                                <td><?php echo e(showAmount($dps->depositedAmount() + $dps->profitAmount())); ?></td>
                                <td><?php echo $dps->statusBadge; ?></td>

                                <td>
                                    <div class="dropdown">
                                        <button aria-expanded="false" class="btn btn--sm btn--base" data-bs-toggle="dropdown" type="button">
                                            <i class="las la-ellipsis-v m-0"></i>
                                        </button>
                                        <div class="dropdown-menu">

                                            <a href="<?php echo e(route('user.dps.details', $dps->dps_number)); ?>" class="dropdown-item">
                                                <i class="las la-list"></i> <?php echo app('translator')->get('Details'); ?>
                                            </a>

                                            <a class="dropdown-item" href="<?php echo e(route('user.dps.instalment.logs', $dps->dps_number)); ?>">
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

        <?php if($allDps->hasPages()): ?>
            <div class="card-footer">
                <?php echo e(paginateLinks($allDps)); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";

            $('.withdrawBtn').on('click', function() {
                let modal = $('#wihtdrawModal');
                let data = $(this).data();
                $.each(data, function(i, value) {
                    $(`.${i}`).text(value);
                });
                let form = modal.find('form')[0];
                form.action = `<?php echo e(route('user.dps.withdraw', '')); ?>/${$(this).data('id')}`
                modal.modal('show');
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('modal'); ?>
    <div class="modal fade custom--modal" id="wihtdrawModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title"><?php echo app('translator')->get('Withdrawal Preview'); ?></h6>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close"><i class="las la-times"></i></span>
                </div>
                <form action="" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <?php echo app('translator')->get('DPS Number'); ?>
                                <span class="dps_number"></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <?php echo app('translator')->get('Per Installment'); ?>
                                <span class="per_installment">14</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <?php echo app('translator')->get('Total Installment'); ?>
                                <span class="total_installment">14</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <?php echo app('translator')->get('Total Deposited'); ?>
                                <span class="total_deposited">2</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <?php echo app('translator')->get('Interest Rate'); ?>
                                <span class="interest_rate">2</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <?php echo app('translator')->get('Profit Amount'); ?>
                                <span class="profit_amount">2</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <?php echo app('translator')->get('Installment Delay Charge'); ?>
                                <span class="delay_charge">2</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                <?php echo app('translator')->get('Withdrawable Amount'); ?>
                                <span class="withdrawable_amount">1</span>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn--dark" data-bs-dismiss="modal" type="button"><?php echo app('translator')->get('Cancel'); ?></button>
                        <button class="btn btn-sm btn--base" type="submit"><?php echo app('translator')->get('Withdraw'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'user.dps.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/user/dps/list.blade.php ENDPATH**/ ?>