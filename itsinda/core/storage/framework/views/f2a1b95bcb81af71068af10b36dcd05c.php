<div class="dashboard-header">
    <div class="row gy-3 gy-lg-4 align-items-center">

        <div class="col-7 d-flex align-items-center gap-3">
            <div class="d-lg-none d-inline">
                <span class="dashboard-body__bar-icon"><i class="fas fa-bars"></i></span>
            </div>
            <div class="dashboard-header__details">
                <h4 class="dashboard-header__title mb-0"><?php echo e(__(@$pageTitle)); ?></h4>
            </div>
        </div>

        <div class="col-5 text-end">
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
                                <img src="<?php echo e(getImage(getFilePath('language') . '/' . $currentLang->image, getFileSize('language'))); ?>" alt="<?php echo app('translator')->get('image'); ?>">
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
        </div>

        <?php echo $__env->yieldPushContent('bottom-menu'); ?>
    </div>
</div>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/partials/dashboard_header.blade.php ENDPATH**/ ?>