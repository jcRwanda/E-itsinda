<?php
    $serviceContent = getContent('service.content', true);
    $services = getContent('service.element', false, 3, true);
?>

<?php if($serviceContent): ?>
    <section class="services-section py-120">
        <div class="services-section-overlay overlay">
            <div class="img-full d-flex">
                <div class="img-full__left">
                    <img src="<?php echo e(asset($activeTemplateTrue . 'images/thumbs/service-bg-left.png')); ?>" alt="<?php echo app('translator')->get('image'); ?>">
                </div>
                <div class="img-full__right">
                    <img src="<?php echo e(getImage('assets/images/frontend/service/' . @$serviceContent->data_values->image, '665x760')); ?>" alt="<?php echo app('translator')->get('image'); ?>">
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="section-heading style-left">
                        <h6 class="section-heading__subtitle"><?php echo e(__(@$serviceContent->data_values->heading)); ?></h6>
                        <h2 class="section-heading__title"><?php echo e(__(@$serviceContent->data_values->subheading)); ?></h2>
                    </div>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-sm-6 col-xsm-6">
                        <div class="service-card text-center rounded">
                            <span class="service-card__icon">
                                <?php echo @$service->data_values->icon ?>
                            </span>
                            <div class="service-card__content">
                                <h5 class="service-card__heading"><?php echo e(__(@$service->data_values->heading)); ?></h5>
                                <p class="service-card__desc"><?php echo e(__(@$service->data_values->description)); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/sections/service.blade.php ENDPATH**/ ?>