<?php $__env->startSection('content'); ?>
    <section class="py-120">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <?php
                    echo @$cookie->data_values->description;
                ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/cookie.blade.php ENDPATH**/ ?>