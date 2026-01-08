<?php if(gs()->modules->fdr): ?>
    <?php
        $content = getContent('fdr_plans.content', true);
        $plans = App\Models\FdrPlan::active()
            ->latest()
            ->limit(3)
            ->get();
    ?>

    <?php if($content && $plans->count()): ?>
        <section class="py-120 pricing  bg-img" data-background-image="<?php echo e(asset($activeTemplateTrue . 'images/thumbs/pricing-bg.png')); ?>">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-heading">
                            <h6 class="section-heading__subtitle"><?php echo e(__(@$content->data_values->heading)); ?></h6>
                            <h2 class="section-heading__title"><?php echo e(__(@$content->data_values->subheading)); ?></h2>
                        </div>
                    </div>
                </div>
                <?php echo $__env->make($activeTemplate . 'partials.fdr_plans', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="text-center mt-4">
                    <a href="<?php echo e(route('user.fdr.plans')); ?>" class="btn btn--base"><?php echo app('translator')->get('View All'); ?></a>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/sections/fdr_plans.blade.php ENDPATH**/ ?>