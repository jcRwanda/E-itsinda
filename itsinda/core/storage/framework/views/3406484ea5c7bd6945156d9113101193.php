<?php $__env->startSection('content'); ?>
    <div class="row gy-4 justify-content-center ">
        <div class="col-xxl-3 col-xl-4 col-lg-5 col-md-5 d-none d-md-block">
            <div class="section-bg">
                <span class="text-center d-block profile-image-preview">
                    <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $user->image, null, true)); ?>" alt="<?php echo app('translator')->get('image'); ?>" class="man-thumb">
                </span>
                <ul class="user-info-card ">
                    <li class="user-info-card__list flex-align">
                        <p class="user-info-card__name"><?php echo app('translator')->get('Account No.'); ?></p>
                        <p class="user-info-card__value fs-16 ms-auto"><?php echo e($user->account_number); ?></p>
                    </li>
                    <?php if($user->branch): ?>
                        <li class="user-info-card__list flex-align">
                            <p class="user-info-card__name"><?php echo app('translator')->get('Branch'); ?></p>
                            <p class="user-info-card__value fs-16 ms-auto"><?php echo e(__(@$user->branch->name)); ?></p>
                        </li>
                    <?php endif; ?>
                    <li class="user-info-card__list flex-align">
                        <p class="user-info-card__name"><?php echo app('translator')->get('Username'); ?></p>
                        <p class="user-info-card__value fs-16 ms-auto"><?php echo e($user->username); ?></p>
                    </li>
                    <li class="user-info-card__list flex-align">
                        <p class="user-info-card__name"><?php echo app('translator')->get('Email'); ?></p>
                        <p class="user-info-card__value fs-16 ms-auto"><?php echo e($user->email); ?></p>
                    </li>
                    <li class="user-info-card__list flex-align">
                        <p class="user-info-card__name"><?php echo app('translator')->get('Mobile'); ?></p>
                        <p class="user-info-card__value fs-16 ms-auto"><?php echo e($user->mobile); ?></p>
                    </li>
                    <li class="user-info-card__list flex-align">
                        <p class="user-info-card__name"><?php echo app('translator')->get('Country'); ?></p>
                        <p class="user-info-card__value fs-16 ms-auto"><?php echo e(__($user->country_name)); ?></p>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xxl-9 col-xl-8 col-lg-7 col-md-7 ">
            <form class="section-bg" action="" method="post" enctype="multipart/form-data">

                <?php echo csrf_field(); ?>

                <div class="row gx-4">

                    <div class="col-xl-6 col-lg-12 col-md-6 col-xsm-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo app('translator')->get('First Name'); ?></label>
                            <input type="text" class="form--control" name="firstname" value="<?php echo e($user->firstname); ?>" required>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-12 col-md-6 col-xsm-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo app('translator')->get('Last Name'); ?></label>
                            <input type="text" class="form--control" name="lastname" value="<?php echo e($user->lastname); ?>" required>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-12 col-md-6 col-xsm-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo app('translator')->get('State'); ?></label>
                            <input type="text" class="form--control" name="state" value="<?php echo e($user->state); ?>">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-12 col-md-6 col-xsm-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo app('translator')->get('City'); ?></label>
                            <input type="text" class="form--control" name="city" value="<?php echo e($user->city); ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label"><?php echo app('translator')->get('Zip Code'); ?></label>
                            <input type="text" class="form--control" name="zip" value="<?php echo e($user->zip); ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label"><?php echo app('translator')->get('Address'); ?></label>
                            <textarea type="text" class="form--control" name="address"><?php echo e($user->address); ?></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label"><?php echo app('translator')->get('Profile Picture'); ?></label>
                            <input type="file" class="form--control" id="imageUpload" name="image" type='file' accept=".png, .jpg, .jpeg">

                            <?php echo app('translator')->get('For optimal results, please upload an image with a 3.5:3 aspect ratio, which will be resized to'); ?> <?php echo e(getFileSize('userProfile')); ?> <?php echo app('translator')->get('pixels'); ?>
                        </div>
                    </div>

                    <div class="col-sm-4 col-8 d-md-none d-block">
                        <div class="mb-3">
                            <div class="text-center d-block profile-image-preview">
                                <img src="<?php echo e(getImage(getFilePath('userProfile') . '/' . $user->image, null, true)); ?>" alt="<?php echo app('translator')->get('image'); ?>" class="man-thumb">
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn--base w-100" type="submit"><?php echo app('translator')->get('Submit'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        $("#imageUpload").on('change', function() {
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('.profile-image-preview img').attr('src', e.target.result)
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('bottom-menu'); ?>
    <div class="col-12 order-lg-3 order-4">
        <div class="d-flex nav-buttons flex-align gap-md-3 gap-2">
            <a href="<?php echo e(route('user.profile.setting')); ?>" class="btn btn--base active"><?php echo app('translator')->get('Profile Setting'); ?></a>
            <a href="<?php echo e(route('user.change.password')); ?>" class="btn btn-outline--base"><?php echo app('translator')->get('Change Password'); ?></a>
            <a href="<?php echo e(route('user.twofactor')); ?>" class="btn btn-outline--base"><?php echo app('translator')->get('2FA Security'); ?></a>
        </div>
    </div>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/user/profile_setting.blade.php ENDPATH**/ ?>