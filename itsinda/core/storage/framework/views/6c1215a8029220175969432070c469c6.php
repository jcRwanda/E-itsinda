<?php $__env->startSection('transfer-content'); ?>

<div class="card custom--card overflow-hidden">
    <div class="card-header">
        <div class="header-nav mb-0">
            <?php if (isset($component)) { $__componentOriginale48b4598ffc2f41a085f001458a956d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale48b4598ffc2f41a085f001458a956d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['placeholder' => 'TRX No.','dateSearch' => 'yes','btn' => 'btn--base']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'TRX No.','dateSearch' => 'yes','btn' => 'btn--base']); ?>
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
                        <th><?php echo app('translator')->get('TRX No.'); ?></th>
                        <th><?php echo app('translator')->get('Time'); ?></th>
                        <th><?php echo app('translator')->get('Recipient'); ?></th>
                        <th><?php echo app('translator')->get('Account No.'); ?></th>
                        <th><?php echo app('translator')->get('Bank'); ?></th>
                        <th><?php echo app('translator')->get('Amount'); ?></th>
                        <th><?php echo app('translator')->get('Charge'); ?></th>
                        <th><?php echo app('translator')->get('Paid Amount'); ?></th>
                        <th><?php echo app('translator')->get('Status'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $transfers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transfer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <span class="text--dark fw-bold">#<?php echo e($transfer->trx); ?></span>
                            </td>

                            <td>
                                <em><?php echo e(showDateTime($transfer->created_at, 'd M, Y h:i A')); ?></em>
                            </td>

                            <td>
                                <?php if($transfer->beneficiary): ?>
                                    <span class="text--base fw-bold"><?php echo e($transfer->beneficiary->short_name); ?></span>
                                <?php else: ?>
                                    <span class="text--base fw-bold"><?php echo e($transfer->wireTransferAccountName()); ?></span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if($transfer->beneficiary): ?>
                                    <?php echo e(@$transfer->beneficiary->account_number); ?>

                                <?php else: ?>
                                    <?php echo e($transfer->wireTransferAccountNumber()); ?>

                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if($transfer->beneficiary): ?>
                                    <?php echo e($transfer->beneficiary->beneficiaryOf->name ?? gs()->site_name); ?>

                                <?php else: ?>
                                    <span class="text--warning fw-bold"><?php echo app('translator')->get('Wire Transfer'); ?></span>
                                    <br>
                                    <button class="badge badge--info wire-transfer" data-id="<?php echo e($transfer->id); ?>" type="button"> <i class="la la-eye"></i> <?php echo app('translator')->get('Recipient Info'); ?></button>
                                <?php endif; ?>
                            </td>

                            <td><?php echo e(showAmount($transfer->amount)); ?></td>

                            <td><?php echo e(showAmount($transfer->charge)); ?></td>

                            <td><?php echo e(showAmount($transfer->final_amount)); ?></td>

                            <td>
                                <?php if($transfer->status == 1): ?>
                                    <span class="badge badge--success"><?php echo app('translator')->get('Completed'); ?></span>
                                <?php elseif($transfer->status == 0): ?>
                                    <span class="badge badge--warning"><?php echo app('translator')->get('Pending'); ?></span>
                                <?php elseif($transfer->status == 2): ?>
                                    <span class="badge badge--danger"><?php echo app('translator')->get('Rejected'); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td class="text-center" colspan="100%"><?php echo app('translator')->get($emptyMessage); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($transfers->hasPages()): ?>
        <div class="card-footer">
            <?php echo e(paginateLinks($transfers)); ?>

        </div>
    <?php endif; ?>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        "use strict";
        (function($) {
            $('.wire-transfer').on('click', function(e) {
                let id = $(this).data('id');
                let modal = $('#detailsModal');
                modal.find('.loading').removeClass('d-none');
                let action = `<?php echo e(route('user.transfer.wire.details', ':id')); ?>`;

                $.ajax({
                    url: action.replace(':id', id),
                    type: "GET",
                    dataType: 'json',
                    cache: false,
                    success: function(response) {
                        if (response.success) {
                            modal.find('.loading').addClass('d-none');
                            modal.find('.modal-body').html(response.html);
                            modal.modal('show');
                        } else {
                            notify('error', response.message || `<?php echo app('translator')->get('Something went the wrong'); ?>`)
                        }
                    },
                    error: function(e) {
                        notify(`<?php echo app('translator')->get('Something went the wrong'); ?>`)
                    }
                });

            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('modal'); ?>
    <div class="modal fade custom--modal" id="detailsModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo app('translator')->get('Wire Transfer Details'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (isset($component)) { $__componentOriginal8c1c796af10563291be3a19176d03808 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c1c796af10563291be3a19176d03808 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ajax-loader','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ajax-loader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c1c796af10563291be3a19176d03808)): ?>
<?php $attributes = $__attributesOriginal8c1c796af10563291be3a19176d03808; ?>
<?php unset($__attributesOriginal8c1c796af10563291be3a19176d03808); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c1c796af10563291be3a19176d03808)): ?>
<?php $component = $__componentOriginal8c1c796af10563291be3a19176d03808; ?>
<?php unset($__componentOriginal8c1c796af10563291be3a19176d03808); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'user.transfer.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/user/transfer/history.blade.php ENDPATH**/ ?>