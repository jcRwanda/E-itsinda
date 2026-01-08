<?php $__env->startSection('content'); ?>
    <section class="py-120">
        <div class="container">
            <div class="row gy-4 justify-content-center pb-50">
                <div class="table-responsive">
                    <table class="table table--responsive--md">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('S.N.'); ?></th>
                                <th><?php echo app('translator')->get('Branch Name'); ?></th>
                                <th><?php echo app('translator')->get('Address'); ?></th>
                                <th><?php echo app('translator')->get('Email'); ?></th>
                                <th><?php echo app('translator')->get('Contact'); ?></th>
                                <th><?php echo app('translator')->get('Routing No.'); ?></th>
                                <th><?php echo app('translator')->get('Map'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($loop->index + $branches->firstItem()); ?></td>
                                    <td><?php echo e(__($branch->name)); ?></td>
                                    <td><?php echo e(__($branch->address)); ?></td>
                                    <td><?php echo e($branch->email); ?></td>
                                    <td><?php echo e($branch->mobile); ?></td>
                                    <td><?php echo e($branch->routing_number); ?></td>
                                    <td>
                                        <button class="btn btn-outline--base btn--sm show-map-btn" data-name="<?php echo e($branch->name); ?>" data-map_location="<?php echo e($branch->map_location); ?>">
                                            <i class="las la-map-marked-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="100%"><?php echo e(__($emptyMessage)); ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if($branches->hasPages()): ?>
                    <div class="mt-3">
                        <?php echo e($branches->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('modal'); ?>
    <div class="modal fade" id="mapModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <span class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <p class="text-center"><?php echo app('translator')->get('Map not available for this branch'); ?></p>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        (function($) {
            "use strict";
            $('.show-map-btn').on('click', function() {
                var modal = $('#mapModal');
                modal.find('.modal-title').text(`${$(this).data('name')} Branch`);
                if ($(this).data('map_location')) {
                    modal.find('.modal-body').html($(this).data('map_location'));
                }
                modal.find('iframe').css('width', '100%')
                modal.modal('show')
            });
        })(jQuery)
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/branches.blade.php ENDPATH**/ ?>