<?php
    $contact = getContent('contact_us.content', true);
    $elemenets = getContent('contact_us.element');
?>
<?php $__env->startSection('content'); ?>
    <section class="py-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="feature-overlay-section mb-60">
                        <div class="features bg-white">
                            <div class="container">
                                <div class="row gy-4 justify-content-center">
                                    <div class="col-md-4">
                                        <div class="feature-card flex-center flex-column">
                                            <div class="feature-card__overlay"></div>
                                            <div class="feature-card__icon">
                                                <img src="<?php echo e(asset($activeTemplateTrue . 'images/shapes/hexagon-shap.png')); ?>" alt="<?php echo app('translator')->get('image'); ?>" />
                                                <span class="icon"><i class="fas fa-map-marker-alt"></i></span>
                                            </div>
                                            <div class="feature-card__content text-center">
                                                <h4 class="feature-card__title"><?php echo app('translator')->get('Office Address'); ?></h4>
                                                <p class="feature-card__desc"><?php echo e(__(@$contact->data_values->contact_address)); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="feature-card flex-center flex-column">
                                            <div class="feature-card__overlay"></div>
                                            <div class="feature-card__icon">
                                                <img src="<?php echo e(asset($activeTemplateTrue . 'images/shapes/hexagon-shap.png')); ?>" alt="<?php echo app('translator')->get('image'); ?>" />
                                                <span class="icon"><i class="fas fa-phone"></i></span>
                                            </div>
                                            <div class="feature-card__content text-center">
                                                <h4 class="feature-card__title"><?php echo app('translator')->get('Phone Number'); ?></h4>
                                                <p class="feature-card__desc">
                                                    <a class="link" href="tel:<?php echo e(@$contact->data_values->contact_number); ?>"><?php echo e(@$contact->data_values->contact_number); ?></a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="feature-card flex-center flex-column">
                                            <div class="feature-card__overlay"></div>
                                            <div class="feature-card__icon">
                                                <img src="<?php echo e(asset($activeTemplateTrue . 'images/shapes/hexagon-shap.png')); ?>" alt="<?php echo app('translator')->get('image'); ?>" />
                                                <span class="icon"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <div class="feature-card__content text-center">
                                                <h4 class="feature-card__title"><?php echo app('translator')->get('Email Address'); ?></h4>
                                                <p class="feature-card__desc">
                                                    <a class="link" href="mailto:<?php echo e(@$contact->data_values->email_address); ?>"><?php echo e(@$contact->data_values->email_address); ?></a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="contact-form">
                        <div class="row">
                            <div class="col-12">
                                <div class="section-heading">
                                    <h6 class="section-heading__subtitle"><?php echo e(__(@$contact->data_values->heading)); ?></h6>
                                    <h2 class="section-heading__title"><?php echo e(__(@$contact->data_values->subheading)); ?></h2>
                                </div>
                            </div>
                            <form action="" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form--group">
                                            <label class="form--label"><?php echo app('translator')->get('Name'); ?></label>
                                            <input name="name" type="text" class="form-control form--control" value="<?php echo e(old('name',@$user->fullname)); ?>" <?php if($user && $user->profile_complete): ?> readonly <?php endif; ?> required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form--group">
                                            <label class="form--label"><?php echo app('translator')->get('Email'); ?></label>
                                            <input type="email" class="form--control" name="email" value="<?php echo e(old('email', @$user->email)); ?>" <?php if($user): ?> readonly <?php endif; ?> required />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form--group">
                                            <label class="form--label"><?php echo app('translator')->get('Subject'); ?></label>
                                            <input type="text" name="subject" class="form--control" value="<?php echo e(old('subject')); ?>" required />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form--group">
                                            <label class="form--label"><?php echo app('translator')->get('Message'); ?></label>
                                            <textarea name="message" class="form--control" rows="4" cols="50"><?php echo e(old('message')); ?></textarea>
                                        </div>
                                    </div>

                                    <?php if (isset($component)) { $__componentOriginalff0a9fdc5428085522b49c68070c11d6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalff0a9fdc5428085522b49c68070c11d6 = $attributes; } ?>
<?php $component = App\View\Components\Captcha::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('captcha'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Captcha::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalff0a9fdc5428085522b49c68070c11d6)): ?>
<?php $attributes = $__attributesOriginalff0a9fdc5428085522b49c68070c11d6; ?>
<?php unset($__attributesOriginalff0a9fdc5428085522b49c68070c11d6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalff0a9fdc5428085522b49c68070c11d6)): ?>
<?php $component = $__componentOriginalff0a9fdc5428085522b49c68070c11d6; ?>
<?php unset($__componentOriginalff0a9fdc5428085522b49c68070c11d6); ?>
<?php endif; ?>

                                    <div class="col-12">
                                        <div class="form--group">
                                            <button class="btn btn--base" id="recaptcha" type="submit"><?php echo app('translator')->get('Send Message'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="map">
        <iframe src="<?php echo e(@$contact->data_values->map_source); ?>" class="google-map" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <?php if($sections->secs != null): ?>
        <?php $__currentLoopData = json_decode($sections->secs); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make($activeTemplate . 'sections.' . $sec, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/contact.blade.php ENDPATH**/ ?>