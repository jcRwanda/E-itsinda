<?php $__env->startSection('fdr-content'); ?>
    <div class="card custom--card overflow-hidden">
        <div class="card-header">
            <div class="header-nav mb-0">
                <?php if (isset($component)) { $__componentOriginale48b4598ffc2f41a085f001458a956d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale48b4598ffc2f41a085f001458a956d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.search-form','data' => ['placeholder' => 'FDR No.','dateSearch' => 'yes','btn' => 'btn--base']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('search-form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'FDR No.','dateSearch' => 'yes','btn' => 'btn--base']); ?>
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
                <table class="table  table--responsive--md">
                    <thead>
                        <tr>
                            <th><?php echo app('translator')->get('FDR No.'); ?></th>
                            <th><?php echo app('translator')->get('Rate'); ?></th>
                            <th><?php echo app('translator')->get('Amount'); ?></th>
                            <th><?php echo app('translator')->get('Installment'); ?></th>
                            <th><?php echo app('translator')->get('Next Installment'); ?></th>
                            <th><?php echo app('translator')->get('Lock In Period'); ?></th>
                            <th><?php echo app('translator')->get('Status'); ?></th>
                            <th><?php echo app('translator')->get('Opened At'); ?></th>
                            <th><?php echo app('translator')->get('Action'); ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $allFdr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fdr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>

                                <td>
                                    #<?php echo e($fdr->fdr_number); ?>

                                </td>

                                <td><?php echo e(getAmount($fdr->interest_rate)); ?>%</td>

                                <td>
                                    <span class="fw-semibold"><?php echo e(showAmount($fdr->amount)); ?></span>
                                </td>

                                <td><?php echo e(showAmount($fdr->per_installment)); ?> /<?php echo e($fdr->installment_interval); ?> <?php echo e(__(Str::plural('Day', $fdr->installment_interval))); ?></td>

                                <td>
                                    <?php if($fdr->status != 2): ?>
                                        <?php echo e(showDateTime($fdr->next_installment_date, 'd M, Y')); ?>

                                    <?php else: ?>
                                        <?php echo app('translator')->get('N/A'); ?>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php echo e(showDateTime($fdr->locked_date->endOfDay(), 'd M, Y')); ?>

                                </td>

                                <td><?php echo $fdr->statusBadge; ?></td>

                                <td>
                                    <?php echo e(showDateTime($fdr->created_at->endOfDay(), 'd M, Y h:i A')); ?>

                                </td>

                                <td>
                                    <div class="dropdown">
                                        <button aria-expanded="false" class="btn btn--sm btn--base" data-bs-toggle="dropdown" type="button">
                                            <i class="las la-ellipsis-v m-0"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="<?php echo e(route('user.fdr.details', $fdr->fdr_number)); ?>" class="dropdown-item">
                                                <i class="las la-list"></i> <?php echo app('translator')->get('Details'); ?>
                                            </a>

                                            <a href="<?php echo e(route('user.fdr.instalment.logs', $fdr->fdr_number)); ?>" class="dropdown-item">
                                                <i class="las la-wallet"></i> <?php echo app('translator')->get('Installments'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="100%" class="text-center"><?php echo e(__($emptyMessage)); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($allFdr->hasPages()): ?>
            <div class="card-footer">
                <?php echo e(paginateLinks($allFdr)); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('modal'); ?>
    <div class="modal fade" id="closeFdr" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo app('translator')->get('Close FDR'); ?></h5>
                    <button type="button" class="bg-transparent" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>

                <form action="" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="user_token" required>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="id" class="transferId" required>
                        </div>
                        <div class="content">
                            <p><?php echo app('translator')->get('Are you sure to close this FDR?'); ?></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn--danger text-white" data-bs-dismiss="modal"><?php echo app('translator')->get('No'); ?></button>
                        <button type="submit" class="btn btn-md btn--base text-white"><?php echo app('translator')->get('Yes'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $('.closeBtn').on('click', function() {
                let modal = $('#closeFdr');
                let form = modal.find('form')[0];
                form.action = `<?php echo e(route('user.fdr.close', '')); ?>/${$(this).data('id')}`
                modal.modal('show');
            });

        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'user.fdr.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/user/fdr/list.blade.php ENDPATH**/ ?>