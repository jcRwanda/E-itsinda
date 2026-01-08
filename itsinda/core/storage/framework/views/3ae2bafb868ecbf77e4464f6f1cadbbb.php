<?php $__env->startSection('app'); ?>
    <?php
        $loginBg = getContent('login_bg.content', true);
    ?>
    <section class="account">
        <div class="account__left flex-align bg-img" data-background-image="<?php echo e(asset($activeTemplateTrue . 'images/thumbs/account-bg.png')); ?>">
            <div class="account__thumb">
                <img class="" src="<?php echo e(getImage('assets/images/frontend/login_bg/' . @$loginBg->data_values->image, '820x730')); ?>" alt="<?php echo app('translator')->get('image'); ?>">
            </div>
        </div>
        <div class="d-flex flex-wrap account__right flex-align">
            <div class="account__form">
                <div class="account-form">
                    <div class="site-logo">
                        <a href="<?php echo e(route('home')); ?>"> <img src="<?php echo e(siteLogo('dark')); ?>" alt="<?php echo app('translator')->get('logo'); ?>"></a>
                    </div>
                    <div class="section-heading style-left">
                        <h6 class="section-heading__subtitle"><?php echo e(__(@$loginBg->data_values->heading)); ?></h6>
                        <h3 class="section-heading__title"><?php echo e(__(@$loginBg->data_values->subheading)); ?></h3>
                    </div>

                    <?php if(@gs('socialite_credentials')->google->status == Status::ENABLE || @gs('socialite_credentials')->facebook->status == Status::ENABLE || @gs('socialite_credentials')->linkedin->status == Status::ENABLE): ?>
                        <?php echo $__env->make($activeTemplate . 'partials.social_login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('user.login')); ?>" class="verify-gcaptcha">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="username" class="form-label"><?php echo app('translator')->get('Username or Email'); ?></label>
                                    <input type="text" name="username" class="form--control" id="username" value="<?php echo e(old('username')); ?>" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="your-password" class="form-label"><?php echo app('translator')->get('Password'); ?></label>
                                    <input id="your-password" type="password" class="form--control" name="password" required>
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
                                <div class="d-flex form-group flex-wrap justify-content-between">
                                    <div class="form--check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="remember"><?php echo app('translator')->get('Remember me'); ?> </label>
                                    </div>
                                    <a href="<?php echo e(route('user.password.request')); ?>" class="forgot-password text--base"><?php echo app('translator')->get('Forgot Password?'); ?></a>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" id="recaptcha" class="btn btn--base w-100"><?php echo app('translator')->get('Sign In'); ?></button>
                                </div>
                            </div>

                            <?php if(gs('registration')): ?>
                                <div class="col-12">
                                    <div class="have-account text-center">
                                        <p class="have-account__text"><?php echo app('translator')->get('Don\'t Have An Account?'); ?>
                                            <a href="<?php echo e(route('user.register')); ?>" class="have-account__link text--base"><?php echo app('translator')->get('Create an Account'); ?></a>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/user/auth/login.blade.php ENDPATH**/ ?>