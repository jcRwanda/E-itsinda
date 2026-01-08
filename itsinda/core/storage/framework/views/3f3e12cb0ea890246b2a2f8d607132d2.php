<div class="sidebar-menu flex-between">
    <div class="sidebar-menu__inner">
        <span class="sidebar-menu__close d-lg-none d-block flex-between"><i class="fas fa-times"></i></span>
        <div class="sidebar-logo">
            <a href="<?php echo e(route('user.home')); ?>" class="sidebar-logo__link"><img src="<?php echo e(siteLogo('dark')); ?>" alt="<?php echo app('translator')->get('image'); ?>" /></a>
        </div>
        <ul class="sidebar-menu-list">
            <li class="sidebar-menu-list__item <?php echo e(menuActive('user.home')); ?>">
                <a href="<?php echo e(route('user.home')); ?>" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-landmark"></i></span>
                    <span class="text"><?php echo app('translator')->get('Dashboard'); ?></span>
                </a>
            </li>

            <?php if(gs()->modules->deposit): ?>
                <li class="sidebar-menu-list__item <?php echo e(menuActive('user.deposit.*')); ?>">
                    <a href="<?php echo e(route('user.deposit.history')); ?>" class="sidebar-menu-list__link">
                        <span class="icon"><i class="las la-wallet"></i></span>
                        <span class="text"><?php echo app('translator')->get('Deposit'); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(gs()->modules->withdraw): ?>
                <li class="sidebar-menu-list__item <?php echo e(menuActive('user.withdraw*')); ?>">
                    <a href="<?php echo e(route('user.withdraw.history')); ?>" class="sidebar-menu-list__link">
                        <span class="icon"><i class="las la-money-bill"></i></span>
                        <span class="text"><?php echo app('translator')->get('Withdraw'); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(gs()->modules->fdr): ?>
                <li class="sidebar-menu-list__item <?php echo e(menuActive('user.fdr.*')); ?>">
                    <a href="<?php echo e(route('user.fdr.list')); ?>" class="sidebar-menu-list__link">
                        <span class="icon"><i class="las la-file-invoice-dollar"></i></span>
                        <span class="text"><?php echo app('translator')->get('FDR'); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(gs()->modules->dps): ?>
                <li class="sidebar-menu-list__item <?php echo e(menuActive('user.dps.*')); ?>">
                    <a href="<?php echo e(route('user.dps.list')); ?>" class="sidebar-menu-list__link">
                        <span class="icon"><i class="las la-piggy-bank"></i></span>
                        <span class="text"><?php echo app('translator')->get('DPS'); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(gs()->modules->loan): ?>
                <li class="sidebar-menu-list__item <?php echo e(menuActive('user.loan.*')); ?>">
                    <a href="<?php echo e(route('user.loan.list')); ?>" class="sidebar-menu-list__link">
                        <span class="icon"><i class="las la-hand-holding-usd"></i></span>
                        <span class="text"><?php echo app('translator')->get('Loan'); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(@gs()->modules->airtime): ?>
                <li class="sidebar-menu-list__item <?php echo e(menuActive('user.airtime.*')); ?>">
                    <a href="<?php echo e(route('user.airtime.form')); ?>" class="sidebar-menu-list__link">
                        <span class="icon"><i class="las la-mobile-alt"></i></span>
                        <span class="text"><?php echo app('translator')->get('Mobile Top Up'); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if(gs()->modules->own_bank || gs()->modules->other_bank || gs()->modules->wire_transfer): ?>
                <li class="sidebar-menu-list__item <?php echo e(menuActive(['user.transfer*', 'user.beneficiary.*'])); ?>">

                    <a href="<?php echo e(route('user.transfer.history')); ?>" class="sidebar-menu-list__link">
                        <span class="icon"><i class="las la-exchange-alt"></i></span>
                        <span class="text"><?php echo app('translator')->get('Transfer'); ?></span>
                    </a>

                </li>
            <?php endif; ?>

            <li class="sidebar-menu-list__item <?php echo e(menuActive('user.transaction.history')); ?>">
                <a href="<?php echo e(route('user.transaction.history')); ?>" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-sync"></i></span>
                    <span class="text"><?php echo app('translator')->get('Transactions'); ?></span>
                </a>
            </li>

            <?php if(gs()->modules->referral_system): ?>
                <li class="sidebar-menu-list__item <?php echo e(menuActive('user.referral.users')); ?>">
                    <a href="<?php echo e(route('user.referral.users')); ?>" class="sidebar-menu-list__link">
                        <span class="icon"><i class="las la-user-friends"></i></span>
                        <span class="text"><?php echo app('translator')->get('Referral'); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="sidebar-menu-list__item <?php echo e(menuActive('ticket.*')); ?>">
                <a href="<?php echo e(route('ticket.index')); ?>" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-ticket-alt"></i></span>
                    <span class="text"><?php echo app('translator')->get('Support Ticket'); ?></span>
                </a>
            </li>

            <li class="sidebar-menu-list__item <?php echo e(menuActive(['user.profile.setting', 'user.change.password', 'user.twofactor'])); ?>">
                <a href="<?php echo e(route('user.profile.setting')); ?>" class="sidebar-menu-list__link">
                    <span class="icon"><i class="las la-cog"></i></span>
                    <span class="text"><?php echo app('translator')->get('Setting'); ?></span>
                </a>
            </li>
        </ul>
    </div>
    <div class="user-logout">
        <div class="sidebar-menu-list__item w-100">
            <a href="<?php echo e(route('user.logout')); ?>" class="sidebar-menu-list__link logout logout-btn">
                <span class="icon"><i class="las la-sign-out-alt"></i></span>
                <span class="text"><?php echo app('translator')->get('Log Out'); ?></span>
            </a>
        </div>
    </div>
</div>
<?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/partials/sidenav.blade.php ENDPATH**/ ?>