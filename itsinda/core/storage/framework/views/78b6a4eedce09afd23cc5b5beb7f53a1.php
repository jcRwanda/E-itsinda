<header class="header" id="header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand logo" href="<?php echo e(route('home')); ?>">
                <img src="<?php echo e(siteLogo('dark')); ?>" alt="<?php echo app('translator')->get('image'); ?>" />
            </a>
            <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu ms-auto align-items-lg-center">
                    <li class="nav-item d-block d-lg-none">
                        <div class="top-button d-flex flex-wrap justify-content-between align-items-center"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(menuActive('home')); ?>" aria-current="page" href="<?php echo e(route('home')); ?>"><?php echo app('translator')->get('Home'); ?></a>
                    </li>
                    <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="nav-item">
                            <a class="nav-link <?php if($data->slug == Request::segment(1)): ?> active <?php endif; ?>" aria-current="page" href="<?php echo e(route('pages', [$data->slug])); ?>"><?php echo e(__($data->name)); ?></a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(menuActive('contact')); ?>" href="<?php echo e(route('contact')); ?>"><?php echo app('translator')->get('Contact'); ?></a>
                    </li>

                </ul>
                <div class="nav-right">
                    <?php if(gs('multi_language')): ?>
                        <?php
                            $language = App\Models\Language::all();
                            $selectLang = $language->where('code', config('app.locale'))->first();
                            $currentLang = session('lang') ? $language->where('code', session('lang'))->first() : $language->where('is_default', Status::YES)->first();
                        ?>

                        <?php if($language->count()): ?>
                            <div class="language_switcher">
                                <div class="language_switcher__caption">
                                    <span class="icon">
                                        <img src="<?php echo e(getImage(getFilePath('language') . '/' . @$currentLang->image, getFileSize('language'))); ?>" alt="<?php echo app('translator')->get('image'); ?>">
                                    </span>
                                    <span class="text"> <?php echo e(__(@$selectLang->name)); ?> </span>
                                </div>
                                <div class="language_switcher__list">
                                    <?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="language_switcher__item    <?php if(session('lang') == $item->code): ?> selected <?php endif; ?>" data-value="<?php echo e($item->code); ?>">
                                            <a href="<?php echo e(route('lang', $item->code)); ?>" class="thumb">
                                                <span class="icon">
                                                    <img src="<?php echo e(getImage(getFilePath('language') . '/' . $item->image, getFileSize('language'))); ?>" alt="<?php echo app('translator')->get('image'); ?>">
                                                </span>
                                                <span class="text"> <?php echo e(__($item->name)); ?></span>
                                            </a>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="signin-btn">
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('user.home')); ?>" class="btn btn--base">
                                <?php echo app('translator')->get('Dashboard'); ?>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('user.login')); ?>" class="btn btn--base">
                                <i class="las la-sign-in-alt"></i>
                                <?php echo app('translator')->get('Sign In'); ?>
                            </a>
                            <a href="<?php echo e(route('user.logout')); ?>" class="logout-btn v-hidden"></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/partials/header.blade.php ENDPATH**/ ?>