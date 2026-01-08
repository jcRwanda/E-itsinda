<?php
    $text = isset($register) ? 'Register' : 'Login';
?>
<div class="social_login__wrapper">
    <?php if(@gs('socialite_credentials')->google->status == Status::ENABLE): ?>
        <a href="<?php echo e(route('user.social.login', 'google')); ?>" class="social_login__link">
            <span class="google-icon">
                <img src="<?php echo e(asset('assets/images/google.svg')); ?>" alt="Google">
            </span> <?php echo app('translator')->get("$text with Google"); ?>
        </a>
    <?php endif; ?>
    <?php if(@gs('socialite_credentials')->facebook->status == Status::ENABLE): ?>
        <a href="<?php echo e(route('user.social.login', 'facebook')); ?>" class="social_login__link">
            <span class="facebook-icon">
                <img src="<?php echo e(asset('assets/images/facebook.svg')); ?>" alt="Facebook">
            </span> <?php echo app('translator')->get("$text with Facebook"); ?>
        </a>
    <?php endif; ?>
    <?php if(@gs('socialite_credentials')->linkedin->status == Status::ENABLE): ?>
        <a href="<?php echo e(route('user.social.login', 'linkedin')); ?>" class="social_login__link">
            <span class="linkedin-icon">
                <img src="<?php echo e(asset('assets/images/linkedin.svg')); ?>" alt="Linkedin">
            </span> <?php echo app('translator')->get("$text with Linkedin"); ?>
        </a>
    <?php endif; ?>
</div>
<div class="text-center auth-divide">
    <span><?php echo app('translator')->get('OR'); ?></span>
</div>

<?php $__env->startPush('style'); ?>
    <style>
          .social_login__wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        .social_login__link {
            background: hsl(var(--white));
            border: 1px solid hsl(var(--black) / .1);
            padding: 12px;
            border-radius: 8px;
            transition: 0.3s linear;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            line-height: 1;
            gap: 10px;
        }

        .social_login__link span {
            margin-right: 10px;
        }

        .social_login__link:hover {
            color: #fff;
            background: hsl(var(--base));
        }

        .auth-divide {
            position: relative;
            z-index: 1;
            margin: 24px 0px;
        }

        .auth-divide::after{
            content: "";
            position: absolute;
            height: 1px;
            width: 100%;
            top: 50%;
            left: 0px;
            background-color: hsl(var(--black) / .1);
            z-index: -1;
        }

        .auth-divide span{
            background-color: #f6f7f9;
            padding-inline: 6px;
        }

    </style>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/partials/social_login.blade.php ENDPATH**/ ?>