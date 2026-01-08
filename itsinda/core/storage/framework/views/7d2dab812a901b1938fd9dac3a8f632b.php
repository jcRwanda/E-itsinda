<?php $__env->startSection('content'); ?>
    <div class="container py-120">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card custom--card">
                    <div class="card-body">
                        <div class="alert alert-warning mb-3" role="alert">
                            <strong> <i class="la la-info-circle"></i> <?php echo app('translator')->get('You need to complete your profile to get access to your dashboard'); ?></strong>
                        </div>
                        <form method="POST" action="<?php echo e(route('user.data.submit')); ?>" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="form-label"><?php echo app('translator')->get('Username'); ?></label>
                                    <input type="text" class="form--control checkUser" name="username" value="<?php echo e(old('username')); ?>" required>
                                    <small class="text--danger usernameExist"></small>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><?php echo app('translator')->get('Country'); ?></label>
                                        <select name="country" class="form-control form--control select2" required>
                                            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option data-mobile_code="<?php echo e($country->dial_code); ?>" value="<?php echo e($country->country); ?>" data-code="<?php echo e($key); ?>">
                                                    <?php echo e(__($country->country)); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><?php echo app('translator')->get('Mobile'); ?></label>
                                        <div class="input-group ">
                                            <span class="input-group-text mobile-code">

                                            </span>
                                            <input type="hidden" name="mobile_code">
                                            <input type="hidden" name="country_code">
                                            <input type="number" name="mobile" value="<?php echo e(old('mobile')); ?>" class="form-control form--control checkUser ps-0" required>
                                        </div>
                                        <small class="text--danger mobileExist"></small>
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <label class="form-label required"><?php echo app('translator')->get('Image'); ?></label>
                                    <input type="file" class="form--control" name="image" id="imageUpload" value="<?php echo e(old('firstname')); ?>" accept=".png, .jpg, .jpeg" required>

                                    <small class="mb-1 text-muted"><?php echo app('translator')->get('Please upload an image with a 3.5:3 aspect ratio, which will be resized to'); ?> <?php echo e(getFileSize('userProfile')); ?> <?php echo app('translator')->get('pixels'); ?></small>


                                    <div class="proifle-image-preview d-none"><img src="" alt="profile-image"></div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="form-label"><?php echo app('translator')->get('Address'); ?></label>
                                    <input type="text" class="form--control" name="address" value="<?php echo e(old('address')); ?>">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label"><?php echo app('translator')->get('State'); ?></label>
                                    <input type="text" class="form--control" name="state" value="<?php echo e(old('state')); ?>">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label"><?php echo app('translator')->get('Zip Code'); ?></label>
                                    <input type="text" class="form--control" name="zip" value="<?php echo e(old('zip')); ?>">
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="form-label"><?php echo app('translator')->get('City'); ?></label>
                                    <input type="text" class="form--control" name="city" value="<?php echo e(old('city')); ?>">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-md btn--base w-100">
                                <?php echo app('translator')->get('Submit'); ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .proifle-image-preview {
            margin-top: 15px;
        }

        .proifle-image-preview img {
            width: 200px;
            height: 160px;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style-lib'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/global/css/select2.min.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-lib'); ?>
    <script src="<?php echo e(asset('assets/global/js/select2.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        "use strict";
        (function($) {

            $("#imageUpload").on('change', function() {
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('.proifle-image-preview').removeClass('d-none');
                        $('.proifle-image-preview img').attr('src', e.target.result)
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            <?php if($mobileCode): ?>
                $(`option[data-code=<?php echo e($mobileCode); ?>]`).attr('selected', '');
            <?php endif; ?>

            $('.select2').select2();

            $('select[name=country]').on('change', function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
                var value = $('[name=mobile]').val();
                var name = 'mobile';
                checkUser(value, name);
            });

            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));


            $('.checkUser').on('focusout', function(e) {
                var value = $(this).val();
                var name = $(this).attr('name')
                checkUser(value, name);
            });

            function checkUser(value, name) {
                var url = '<?php echo e(route('user.checkUser')); ?>';
                var token = '<?php echo e(csrf_token()); ?>';

                if (name == 'mobile') {
                    var mobile = `${value}`;
                    var data = {
                        mobile: mobile,
                        mobile_code: $('.mobile-code').text().substr(1),
                        _token: token
                    }
                }
                if (name == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.field} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            }
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/user/user_data.blade.php ENDPATH**/ ?>