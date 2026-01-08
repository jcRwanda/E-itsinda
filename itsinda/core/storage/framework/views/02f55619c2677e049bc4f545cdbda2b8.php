<!DOCTYPE html>
<html lang="<?php echo e(config('app.locale')); ?>" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo e(gs()->siteName(__($pageTitle))); ?></title>
    <?php echo $__env->make('partials.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <link href="<?php echo e(asset('assets/global/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/global/css/all.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/global/css/line-awesome.min.css')); ?>" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'user/css/icon-moon.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset($activeTemplateTrue . 'user/css/main.css')); ?>" />
    <?php echo $__env->yieldPushContent('style-lib'); ?>
    <link href="<?php echo e(asset($activeTemplateTrue . 'css/custom.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldPushContent('style'); ?>
    <link href="<?php echo e(asset($activeTemplateTrue . 'user/css/color.php?color=' . gs()->base_color . '&secondColor=' . gs()->secondary_color)); ?>" rel="stylesheet">
</head>

<body>
    <div class="preloader">
        <div class="loader-p"></div>
    </div>
    <div class="body-overlay"></div>
    <div class="sidebar-overlay"></div>

    <div class="dashboard position-relative">
        <div class="dashboard__inner flex-wrap">
            <?php echo $__env->make($activeTemplate . 'partials.sidenav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="dashboard__right">
                <?php echo $__env->make($activeTemplate . 'partials.dashboard_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="dashboard-body">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->yieldPushContent('modal'); ?>

    <script src="<?php echo e(asset('assets/global/js/jquery-3.7.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/jquery.validate.js')); ?>"></script>
    <script src="<?php echo e(asset($activeTemplateTrue . 'user/js/main.js')); ?>"></script>

    <?php echo $__env->yieldPushContent('script-lib'); ?>
    <?php echo loadExtension('tawk-chat') ?>
    <?php echo $__env->make('partials.notify', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php if(gs('pn')): ?>
        <?php echo $__env->make('partials.push_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php echo $__env->yieldPushContent('script'); ?>
    <?php echo $__env->make('partials.user_activity', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script>
        "use strict";
        (function($) {

            $(".langSel").on("change", function() {
                window.location.href = "<?php echo e(route('home')); ?>/change/" + $(this).val();
            });

            $('.language_switcher > .language_switcher__caption').on('click', function() {
                $(this).parent().toggleClass('open');
            });
            $(document).on('keyup', function(evt) {
                if ((evt.keyCode || evt.which) === 27) {
                    $('.language_switcher').removeClass('open');
                }
            });

            $(document).on('click', function(evt) {
                if ($(evt.target).closest(".language_switcher > .language_switcher__caption").length === 0) {
                    $('.language_switcher').removeClass('open');
                }
            });

            $('form').on('submit', function() {
                if ($(this).valid()) {
                    $(':submit', this).attr('disabled', 'disabled');
                }
            });

            var inputElements = $('[type=text],[type=password],select,textarea');

            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox' && element.hasAttribute('required')) {
                    $(element).closest('.form-group').find('label').addClass('required');
                }
            });

            $('.showFilterBtn').on('click', function() {
                $('.responsive-filter-card').slideToggle();
            });

            Array.from(document.querySelectorAll('table')).forEach(table => {
                let heading = table.querySelectorAll('thead tr th');
                Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                    Array.from(row.querySelectorAll('td')).forEach((column, i) => {
                        (column.colSpan == 100) || column.setAttribute('data-label', heading[i].innerText)
                    });
                });
            });

            let disableSubmission = false;
            $('.disableSubmission').on('submit', function(e) {
                if (disableSubmission) {
                    e.preventDefault()
                } else {
                    disableSubmission = true;
                }
            });

        })(jQuery);
    </script>
</body>

</html>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/layouts/master.blade.php ENDPATH**/ ?>