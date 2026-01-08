<?php
    $footer = getContent('footer.content', true);
    $socialLinks = getContent('social_link.element', orderById: true);
    $policyPages = getContent('policy_pages.element', orderById: true);
    $contact = getContent('contact_us.content', true);
    $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
?>

<?php if($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie')): ?>
    <div class="cookies-card hide text-center">
        <div class="cookies-card__icon bg--base">
            <i class="las la-cookie-bite"></i>
        </div>
        <p class="cookies-card__content mt-4"><?php echo e(@$cookie->data_values->short_desc); ?> <a href="<?php echo e(route('cookie.policy')); ?>" target="_blank"
                class="text--base"><?php echo app('translator')->get('Learn more'); ?></a></p>
        <div class="cookies-card__btn mt-4">
            <a class="btn btn--base w-100 policy" href="javascript:void(0)"><?php echo app('translator')->get('Allow'); ?></a>
        </div>
    </div>
<?php endif; ?>

<footer class="footer-area">
    <?php echo $__env->make($activeTemplate . 'sections.subscribe', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="py-60">
        <div class="container">
            <div class="row justify-content-center gy-5">
                <div class="col-xl-3 col-sm-6">
                    <div class="footer-item">
                        <h5 class="footer-item__title"><?php echo e(__(@$footer->data_values->title)); ?></h5>
                        <p class="footer-item__desc"><?php echo e(__(@$footer->data_values->description)); ?></p>
                        <ul class="social-list">
                            <?php $__currentLoopData = $socialLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="social-list__item">
                                    <a href="<?php echo e($social->data_values->social_link); ?>" target="_blank" class="social-list__link flex-center">
                                        <?php echo $social->data_values->social_icon; ?>
                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-1 d-xl-block d-none"></div>
                <div class="col-xl-2 col-sm-6">
                    <div class="footer-item">
                        <h5 class="footer-item__title"><?php echo app('translator')->get('Pages'); ?></h5>
                        <ul class="footer-menu">
                            <?php if(auth()->guard()->check()): ?>
                                <li class="footer-menu__item">
                                    <a href="<?php echo e(route('user.home')); ?>" class="footer-menu__link"><?php echo app('translator')->get('Dashboard'); ?></a>
                                </li>
                            <?php else: ?>
                                <li class="footer-menu__item">
                                    <a href="<?php echo e(route('user.register')); ?>" class="footer-menu__link"><?php echo app('translator')->get('Register'); ?></a>
                                </li>
                            <?php endif; ?>
                            <li class="footer-menu__item">
                                <a href="<?php echo e(route('branches')); ?>" class="footer-menu__link"><?php echo app('translator')->get('Our Branches'); ?></a>
                            </li>
                            <li class="footer-menu__item">
                                <a href="<?php echo e(route('contact')); ?>" class="footer-menu__link"><?php echo app('translator')->get('Contact'); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6">
                    <div class="footer-item">
                        <h5 class="footer-item__title"><?php echo app('translator')->get('Useful Link'); ?></h5>
                        <ul class="footer-menu">
                            <?php $__currentLoopData = $policyPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $policy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="footer-menu__item">
                                    <a href="<?php echo e(route('policy.pages', $policy->slug)); ?>" target="_blank"
                                        class="footer-menu__link"><?php echo e(__($policy->data_values->title)); ?></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-1 d-xl-block d-none"></div>
                <div class="col-xl-3 col-sm-6">
                    <div class="footer-item">
                        <h5 class="footer-item__title"><?php echo e(__(@$footer->data_values->contact_title)); ?></h5>
                        <ul class="footer-contact-menu">

                            <li class="footer-contact-menu__item">
                                <div class="footer-contact-menu__item-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="footer-contact-menu__item-content">
                                    <p><?php echo e(__(@$contact->data_values->contact_address)); ?></p>
                                </div>
                            </li>
                            <li class="footer-contact-menu__item">
                                <div class="footer-contact-menu__item-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="footer-contact-menu__item-content">
                                    <a class="footer-menu__link"
                                        href="mailto:<?php echo e(__(@$contact->data_values->email_address)); ?>"><?php echo e(__(@$contact->data_values->email_address)); ?></a>
                                </div>
                            </li>
                            <li class="footer-contact-menu__item">
                                <div class="footer-contact-menu__item-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="footer-contact-menu__item-content">
                                    <a class="footer-menu__link"
                                        href="tel:<?php echo e(__(@$contact->data_values->contact_number)); ?>"><?php echo e(__(@$contact->data_values->contact_number)); ?></a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-footer section-bg">
        <div class="container">
            <div class="row gy-3 align-items-center">
                <div class="col-md-6">
                    <div class="footer-item__logo">
                        <a href="<?php echo e(route('home')); ?>"> <img src="<?php echo e(siteLogo()); ?>" alt="<?php echo app('translator')->get('image'); ?>"></a>
                    </div>
                </div>
                <div class="col-md-6  m-0">
                    <p class="bottom-footer-text text-white"> &copy; <?php echo app('translator')->get('Copyright'); ?> © <?php echo date('Y') ?> <?php echo e(__(gs()->site_name)); ?>

                        <?php echo app('translator')->get('All Right Reserved'); ?>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/partials/footer.blade.php ENDPATH**/ ?>