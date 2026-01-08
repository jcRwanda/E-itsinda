<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="show-filter mb-3 text-end">
                <button class="btn btn--base showFilterBtn btn-sm" type="button"><i class="las la-filter"></i> <?php echo app('translator')->get('Filter'); ?></button>
            </div>
            <div class="card custom--card responsive-filter-card mb-4">
                <div class="card-body">
                    <form action="">
                        <div class="d-flex flex-wrap gap-4">

                            <div class="flex-grow-1">
                                <label class="form-label"><?php echo app('translator')->get('Date'); ?></label>
                                <?php if (isset($component)) { $__componentOriginal37e12294b28f0bd91a733acab9bb06c5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal37e12294b28f0bd91a733acab9bb06c5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.date-picker','data' => ['class' => 'form--control']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('date-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'form--control']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal37e12294b28f0bd91a733acab9bb06c5)): ?>
<?php $attributes = $__attributesOriginal37e12294b28f0bd91a733acab9bb06c5; ?>
<?php unset($__attributesOriginal37e12294b28f0bd91a733acab9bb06c5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal37e12294b28f0bd91a733acab9bb06c5)): ?>
<?php $component = $__componentOriginal37e12294b28f0bd91a733acab9bb06c5; ?>
<?php unset($__componentOriginal37e12294b28f0bd91a733acab9bb06c5); ?>
<?php endif; ?>
                            </div>

                            <div class="flex-grow-1">
                                <label class="form-label"><?php echo app('translator')->get('Type'); ?></label>
                                <select class="form-select form--control" name="trx_type">
                                    <option value=""><?php echo app('translator')->get('All'); ?></option>
                                    <option value="+" <?php if(request()->trx_type == '+'): echo 'selected'; endif; ?>><?php echo app('translator')->get('Plus'); ?></option>
                                    <option value="-" <?php if(request()->trx_type == '-'): echo 'selected'; endif; ?>><?php echo app('translator')->get('Minus'); ?></option>
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label class="form-label"><?php echo app('translator')->get('Remark'); ?></label>
                                <select class="form-select form--control" name="remark">
                                    <option value=""><?php echo app('translator')->get('Any'); ?></option>
                                    <?php $__currentLoopData = $remarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $remark): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($remark->remark); ?>" <?php if(request()->remark == $remark->remark): echo 'selected'; endif; ?>><?php echo e(__(keyToTitle($remark->remark))); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="align-self-end">
                                <button class="btn btn--base w-100"><i class="las la-filter"></i> <?php echo app('translator')->get('Apply Filter'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card custom--card">
                <div class="card-header d-flex justify-content-end">

                    <form method="GET">
                        <div class="input-group">
                            <input class="form-control form--control" placeholder="<?php echo app('translator')->get('TRX No.'); ?>" name="search" type="text" value="<?php echo e(request()->search); ?>">
                            <button type="submit" class="input-group-text"><i class="la la-search"></i></button>
                        </div>

                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table--responsive--md has-search-form">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('TRX No.'); ?></th>
                                    <th><?php echo app('translator')->get('Time'); ?></th>
                                    <th><?php echo app('translator')->get('Amount'); ?></th>
                                    <th><?php echo app('translator')->get('Post Balance'); ?></th>
                                    <th><?php echo app('translator')->get('Details'); ?></th>
                                    <th><?php echo app('translator')->get('Blockchain'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            #<?php echo e($trx->trx); ?>

                                        </td>
                                        <td>
                                            <?php echo e(showDateTime($trx->created_at)); ?>

                                        </td>
                                        <td>
                                            <span class="<?php if($trx->trx_type == '+'): ?> text--success <?php else: ?> text--danger <?php endif; ?>">
                                                <?php echo e($trx->trx_type); ?> <?php echo e(showAmount($trx->amount)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php echo e(showAmount($trx->post_balance)); ?>

                                        </td>
                                        <td><?php echo e($trx->details); ?></td>
                                        <td>
                                            <?php
                                                $blockchainTx = null;
                                                if (strpos($trx->details, 'Blockchain TX:') !== false) {
                                                    preg_match('/Blockchain TX: ([a-f0-9]{64})/', $trx->details, $matches);
                                                    $blockchainTx = $matches[1] ?? null;
                                                }
                                            ?>
                                            <?php if($blockchainTx): ?>
                                                <button class="btn btn-sm btn--base copy-blockchain-tx" 
                                                        data-tx="<?php echo e($blockchainTx); ?>"
                                                        data-bs-toggle="tooltip" 
                                                        title="Click to copy Cardano TX">
                                                    <i class="las la-copy"></i> <?php echo e(substr($blockchainTx, 0, 8)); ?>...
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted">--</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%"><?php echo e(__($emptyMessage)); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($transactions->hasPages()): ?>
                    <div class="card-footer">
                        <?php echo e($transactions->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script>
    (function($) {
        "use strict";
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Copy blockchain transaction
        $('.copy-blockchain-tx').on('click', function(e) {
            e.preventDefault();
            var tx = $(this).data('tx');
            var $button = $(this);
            
            // Copy to clipboard
            navigator.clipboard.writeText(tx).then(function() {
                // Show success message
                iziToast.success({
                    message: "Blockchain TX copied! You can verify it on Cardanoscan",
                    position: "topRight"
                });
                
                // Change button temporarily
                var originalHtml = $button.html();
                $button.html('<i class="las la-check"></i> Copied!');
                
                setTimeout(function() {
                    $button.html(originalHtml);
                }, 2000);
                
                // Open Cardanoscan in new tab
                var cardanoscanUrl = 'https://preview.cardanoscan.io/transaction/' + tx;
                console.log('View transaction at:', cardanoscanUrl);
                
            }).catch(function(err) {
                iziToast.error({
                    message: "Failed to copy",
                    position: "topRight"
                });
            });
        });
    })(jQuery);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/user/transactions.blade.php ENDPATH**/ ?>