<?php
    $socialLinks = getContent('social_link.element', orderById: true);
?>
<?php $__currentLoopData = $socialLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li class="follow-social-list__item">
        <a target="_blank" href="<?php echo e($social->data_values->social_link); ?>" class="follow-social-list__link">
            <?php echo $social->data_values->social_icon; ?>
        </a>
    </li>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/partials/social_link.blade.php ENDPATH**/ ?>