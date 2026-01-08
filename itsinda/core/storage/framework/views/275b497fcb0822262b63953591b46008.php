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
    <link href="<?php echo e(asset('assets/global/css/slick.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset($activeTemplateTrue . 'css/custom-fonts.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset($activeTemplateTrue . 'css/main.css')); ?>"rel="stylesheet" />
    <link href="<?php echo e(asset($activeTemplateTrue . 'css/custom.css')); ?>" rel="stylesheet">

    <?php echo $__env->yieldPushContent('style-lib'); ?>
    <?php echo $__env->yieldPushContent('style'); ?>

    <link href="<?php echo e(asset($activeTemplateTrue . 'css/color.php?color=' . gs()->base_color . '&secondColor=' . gs()->secondary_color)); ?>" rel="stylesheet">
</head>
<?php echo loadExtension('google-analytics') ?>

<body>

    <div class="preloader">
        <div class="middle">
            <div class="bar bar1"></div>
            <div class="bar bar2"></div>
            <div class="bar bar3"></div>
            <div class="bar bar4"></div>
            <div class="bar bar5"></div>
            <div class="bar bar6"></div>
            <div class="bar bar7"></div>
            <div class="bar bar8"></div>
        </div>
    </div>

    <div class="body-overlay"></div>
    <div class="sidebar-overlay"></div>
    <a class="scroll-top flex-center"><span class="arrow"></span></a>

    <?php echo $__env->yieldContent('app'); ?>

    <?php echo $__env->yieldPushContent('modal'); ?>
    <script src="<?php echo e(asset('assets/global/js/jquery-3.7.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/jquery.validate.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/global/js/slick.min.js')); ?>"></script>
    <script src="<?php echo e(asset($activeTemplateTrue . 'js/main.js')); ?>"></script>

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

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);

            $('.policy').on('click', function() {
                $.get(`<?php echo e(route('cookie.accept')); ?>`, function(response) {
                    $('.cookies-card').addClass('d-none');
                });
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
                    Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                        (column.colSpan == 100) || column.setAttribute('data-label', heading[i].innerText)
                    });
                });
            });

        })(jQuery);
    </script>

</body>

</html>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/layouts/app.blade.php ENDPATH**/ ?>