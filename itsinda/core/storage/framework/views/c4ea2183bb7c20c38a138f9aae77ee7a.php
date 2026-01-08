<?php $__env->startSection("meta"); ?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php if($user->kv != Status::KYC_VERIFIED): ?>
        <?php
            $kyc = getContent('kyc.content', true);
        ?>

        <?php if(auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason): ?>
            <div class="alert mb-4 alert-danger" role="alert">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text--danger"><?php echo app('translator')->get('KYC Documents Rejected'); ?></h4>
                    <button class="btn btn--dark btn--sm" data-bs-toggle="modal" data-bs-target="#kycRejectionReason"><?php echo app('translator')->get('Show Reason'); ?></button>
                </div>
                <hr>
                <p class="mb-2"><?php echo e(__(@$kyc->data_values->reject)); ?> <a href="<?php echo e(route('user.kyc.form')); ?>"><?php echo app('translator')->get('Click Here to Re-submit Documents'); ?>.</a></p>
                <a href="<?php echo e(route('user.kyc.data')); ?>"><?php echo app('translator')->get('See KYC Data'); ?></a>
            </div>
        <?php elseif(auth()->user()->kv == Status::KYC_UNVERIFIED): ?>
            <div class="alert mb-4 alert--danger" role="alert">
                <h4 class="text--primary mb-0"><?php echo app('translator')->get('KYC Verification required'); ?></h4>
                <hr>
                <p><?php echo e(__(@$kyc->data_values->required)); ?> <a href="<?php echo e(route('user.kyc.form')); ?>"><?php echo app('translator')->get('Click Here to Submit Documents'); ?></a></p>
            </div>
        <?php elseif(auth()->user()->kv == Status::KYC_PENDING): ?>
            <div class="alert mb-4 alert--warning" role="alert">
                <h4 class="text--warning mb-0"><?php echo app('translator')->get('KYC Verification pending'); ?></h4>
                <hr>
                <p><?php echo e(__(@$kyc->data_values->pending)); ?> <a href="<?php echo e(route('user.kyc.data')); ?>"><?php echo app('translator')->get('See KYC Data'); ?></a></p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="row gy-lg-4 gy-md-3 gy-3 align-items-center">
        <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6">
            <a href="<?php echo e(route('user.transaction.history')); ?>" class="d-block">
                <div class="dashboard-widget user-account-card">
                    <div class="card-body">
                        <h5 class="user-account-card__name text--info text-uppercase"><?php echo e($user->username); ?></h5>
                        <h6 class="user-account-card__number text--black"><?php echo e($user->account_number); ?></h6>
                        <div class="user-account-card__balance text-center pt-2">
                            <span class="user-account-card__text"><?php echo app('translator')->get('Available Balance'); ?></span>
                            <h3 class="user-account-card__amount"><?php echo e(showAmount($user->balance)); ?></h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <?php if(gs()->modules->referral_system): ?>
            <div class="col-xl-8 col-lg-12 col-md-8 order-xl-0 order-lg-first order-md-0 order-sm-first">
                <div class="dashboard-widget refer">
                    <div class="custom-border flex-align flex-between">
                        <div class="refer__content">
                            <h5 class="refer__title"><?php echo app('translator')->get('My Referral Link'); ?>:</h5>
                            <h5 class="refer__link" id="ref"><?php echo e(route('home') . '?reference=' . $user->username); ?>

                            </h5>
                        </div>
                        <span class="refer__icon dashboard-widget__icon flex-center copy-icon copyBtn">
                            <i class="icon-copy"></i>
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if(@gs()->modules->deposit): ?>
            <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                <a href="<?php echo e(route('user.deposit.history')); ?>?status=<?php echo e(Status::PAYMENT_PENDING); ?>" class="d-block">
                    <div class="dashboard-widget">
                        <div class="dashboard-widget__content flex-align">
                            <span class="dashboard-widget__icon flex-center">
                                <i class="las la-wallet"></i>
                            </span>
                            <span class="dashboard-widget__text"><?php echo app('translator')->get('Pending Deposits'); ?></span>
                        </div>
                        <h4 class="dashboard-widget__number">
                            <?php echo e(showAmount(@$widget['total_deposit'])); ?></h4>
                    </div>
                </a>
            </div>
        <?php endif; ?>

        

<!-- WIDGET DEBUG: ussd_amount=<?php echo e(@$widget['ussd_amount']); ?> | ussd_contributions=<?php echo e(@$widget['ussd_contributions']); ?> | ussd_pending=<?php echo e(@$widget['ussd_pending']); ?> | ussd_pending_amount=<?php echo e(@$widget['ussd_pending_amount']); ?> | Time=<?php echo e(now()); ?> -->
        <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
            <div class="dashboard-widget">
                <div class="dashboard-widget__content flex-align">
                    <span class="dashboard-widget__icon flex-center">
                        <i class="las la-mobile"></i>
                    </span>
                    <span class="dashboard-widget__text"><?php echo app('translator')->get('USSD Contributions'); ?></span>
                </div>
                <h4 class="dashboard-widget__number">
                    <?php echo e(showAmount(@$widget['ussd_amount'])); ?>

                </h4>
                <div style="font-size: 12px; color: #666; margin-top: 5px;">
                    <span><?php echo e(@$widget['ussd_contributions']); ?> <?php echo app('translator')->get('completed'); ?></span>
                    <?php if(@$widget['ussd_pending'] > 0): ?>
                        <span class="badge badge--warning ms-2">
                            <?php echo e(@$widget['ussd_pending']); ?> <?php echo app('translator')->get('Pending'); ?> 
                            (<?php echo e(showAmount(@$widget['ussd_pending_amount'])); ?>)
                        </span>
                    <?php else: ?>
                        <span class="badge badge--success ms-2"><?php echo e(@$widget['ussd_status']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if(@gs()->modules->withdraw): ?>
            <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                <a href="<?php echo e(route('user.withdraw.history')); ?>?status=<?php echo e(Status::PAYMENT_PENDING); ?>" class="d-block">
                    <div class="dashboard-widget">
                        <div class="dashboard-widget__content flex-align">
                            <span class="dashboard-widget__icon flex-center">
                                <i class="las la-money-check"></i>
                            </span>
                            <span class="dashboard-widget__text"><?php echo app('translator')->get('Pending Withdrawals'); ?></span>
                        </div>
                        <h4 class="dashboard-widget__number">
                            <?php echo e(showAmount(@$widget['total_withdraw'])); ?></h4>
                    </div>
                </a>
            </div>
        <?php endif; ?>

        <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
            <a href="<?php echo e(route('user.transaction.history')); ?>?today=1" class="d-block">
                <div class="dashboard-widget">
                    <div class="dashboard-widget__content flex-align">
                        <span class="dashboard-widget__icon flex-center">
                            <i class="las la-exchange-alt"></i>
                        </span>
                        <span class="dashboard-widget__text"><?php echo app('translator')->get('Today Transactions'); ?></span>
                    </div>
                    <h4 class="dashboard-widget__number"><?php echo e(@$widget['total_trx']); ?></h4>
                </div>
            </a>
        </div>

        <?php if(gs()->modules->fdr): ?>
            <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                <a href="<?php echo e(route('user.fdr.list')); ?>?status=<?php echo e(Status::FDR_RUNNING); ?>" class="d-block">
                    <div class="dashboard-widget">
                        <div class="dashboard-widget__content flex-align">
                            <span class="dashboard-widget__icon flex-center">
                                <i class="las la-money-bill"></i>
                            </span>
                            <span class="dashboard-widget__text"><?php echo app('translator')->get('Running FDR'); ?></span>
                        </div>
                        <h4 class="dashboard-widget__number"><?php echo e(@$widget['total_fdr']); ?></h4>
                    </div>
                </a>
            </div>
        <?php endif; ?>
        <?php if(gs()->modules->dps): ?>
            <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                <a href="<?php echo e(route('user.dps.list')); ?>?status=<?php echo e(Status::FDR_RUNNING); ?>" class="d-block">
                    <div class="dashboard-widget">
                        <div class="dashboard-widget__content flex-align">
                            <span class="dashboard-widget__icon flex-center">
                                <i class="las la-box-open"></i>
                            </span>
                            <span class="dashboard-widget__text"><?php echo app('translator')->get('Running DPS'); ?></span>
                        </div>
                        <h4 class="dashboard-widget__number"><?php echo e(@$widget['total_dps']); ?></h4>
                    </div>
                </a>
            </div>
        <?php endif; ?>

        <?php if(gs()->modules->loan): ?>
            <div class="col-xl-4 col-lg-12 col-md-4 col-sm-12 col-xsm-6">
                <a href="<?php echo e(route('user.loan.list')); ?>?status=<?php echo e(Status::LOAN_RUNNING); ?>" class="d-block">
                    <div class="dashboard-widget">
                        <div class="dashboard-widget__content flex-align">
                            <span class="dashboard-widget__icon flex-center">
                                <i class="las la-hand-holding-usd"></i>
                            </span>
                            <span class="dashboard-widget__text"><?php echo app('translator')->get('Running Loan'); ?></span>
                        </div>
                        <h4 class="dashboard-widget__number"><?php echo e(@$widget['total_loan']); ?></h4>
                    </div>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="pt-60">
        <div class="row gy-4 justify-content-center">
            <div class="col-xxl-6">
                <div class="dashboard-table">
                    <h5 class="dashboard-table__title card-header__title text-dark">
                        <?php echo app('translator')->get('Latest Credits'); ?>
                    </h5>
                    <table class="table table--responsive--md">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('TRX No.'); ?></th>
                                <th><?php echo app('translator')->get('Date'); ?></th>
                                <th><?php echo app('translator')->get('Amount'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $credits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $credit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($credit->trx); ?></td>
                                    <td>
                                        <?php echo e(showDateTime($credit->created_at, 'd M, Y h:i A')); ?>

                                    </td>
                                    <td class="fw-bold">
                                        <?php echo e(showAmount($credit->amount)); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="100%" class="text-center"><?php echo e(__($emptyMessage)); ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-xxl-6">
                <div class="dashboard-table">
                    <h5 class="dashboard-table__title card-header__title text-dark">
                        <?php echo app('translator')->get('Latest Debits'); ?>
                    </h5>
                    <table class="table table--responsive--md">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('TRX No.'); ?></th>
                                <th><?php echo app('translator')->get('Date'); ?></th>
                                <th><?php echo app('translator')->get('Amount'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $debits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $debit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($debit->trx); ?></td>
                                    <td><?php echo e(showDateTime($debit->created_at, 'd M, Y h:i A')); ?></td>
                                    <td class="fw-bold">
                                        <?php echo e(showAmount($debit->amount)); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="100%" class="text-center"><?php echo e(__($emptyMessage)); ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php if(auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason): ?>
    <?php $__env->startPush('modal'); ?>
        <div class="modal fade" id="kycRejectionReason">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo app('translator')->get('KYC Document Rejection Reason'); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo e(auth()->user()->kyc_rejection_reason); ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php $__env->stopPush(); ?>
<?php endif; ?>

<?php $__env->startPush('script'); ?>
    <script>
        "use strict";
        (function($) {
            $('.copyBtn').click(function() {
                const urlText = $('#ref').text();
                const tempTextArea = $('<textarea>');
                tempTextArea.val(urlText);
                $('body').append(tempTextArea);
                tempTextArea.select();
                document.execCommand('copy');
                tempTextArea.remove();
                notify('success', `Copied - ${urlText}`)
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .user-account-card {
            padding: 15px 16px !important;
            transition: 0.3s;
        }

        .user-account-card__name {
            font-size: 0.875rem;
            font-weight: 400;
            margin-bottom: 5px;
        }

        .user-account-card__number {
            font-size: 0.875rem;
            color: #a0aec0;
            margin-bottom: 0px;
            font-weight: 400;
        }

        .user-account-card__text {
            color: #a0aec0;
            font-size: 0.875rem;
        }

        .user-account-card__amount {
            margin-bottom: 0px;
            /* font-size: 1.25rem; */
        }

        .user-account-card:hover .user-account-card__name,
        .user-account-card:hover .user-account-card__number,
        .user-account-card:hover .user-account-card__text,
        .user-account-card:hover .user-account-card__amount {
            color: #fff !important;
        }

        .dashboard .refer__link {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/garatech.rw/itsinda/core/resources/views/templates/crystal_sky/user/dashboard.blade.php ENDPATH**/ ?>