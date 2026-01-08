<?php
    $subscribe = getContent('subscribe.content', true);
?>

<?php if($subscribe): ?>
    <div class="py-60 newsletter">
        <div class="container">
            <div class="row gy-3">
                <div class="col-md-12 col-lg-7 d-flex flex-wrap align-center">
                    <h4 class="newsletter__title m-0 ">
                        <?php echo e(__(@$subscribe->data_values->heading)); ?>

                    </h4>
                </div>
                <div class="col-md-12 col-lg-5">
                    <form class="newsletter-from d-flex flex-wrap items-center" id="subscribeForm">
                        <?php echo csrf_field(); ?>
                        <input required type="email" class="form--control flex-grow-1" name="email" id="leadEmail" placeholder="<?php echo app('translator')->get('Your email address'); ?>" />
                        <button class="btn btn--base" type="submit"><?php echo app('translator')->get('Subscribe'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $__env->startPush('script'); ?>
    <script>
        "use strict";
        (function($) {
            var form = $("#subscribeForm");
            form.on('submit', function(e) {
                e.preventDefault();
                var data = form.serialize();
                $.ajax({
                    url: `<?php echo e(route('subscribe')); ?>`,
                    method: 'post',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            form.find('input[name=email]').val('');
                            notify('success', response.message);
                        } else {
                            notify('error', response.error);
                            form.find('button[type=submit]').removeAttr('disabled');
                        }
                    }
                });
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/sections/subscribe.blade.php ENDPATH**/ ?>