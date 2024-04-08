<?php
 //   $profile=asset(Storage::url('uploads/avatar/'));
$profile=\App\Models\Utility::get_file('uploads/avatar');
?>
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage User')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Client')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <?php if(\Auth::user()->type == 'company' || \Auth::user()->type == 'HR'): ?>
            <a href="<?php echo e(route('user.userlog')); ?>" class="btn btn-primary btn-sm <?php echo e(Request::segment(1) == 'user'); ?>"
               data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('User Logs History')); ?>"><i class="ti ti-user-check"></i>
            </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create user')): ?>
            <a href="#" data-size="lg" data-url="<?php echo e(route('users.create')); ?>" data-ajax-popup="true"  data-bs-toggle="tooltip" title="<?php echo e(__('Create User')); ?>"  class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xxl-12">
            <div class="row">
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center card-2">
                            <div class="card-header border-0 pb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <div class=" badge bg-primary p-2 px-3 rounded">
                                            <?php echo e(ucfirst($user->type)); ?>

                                        </div>
                                    </h6>

                                </div>

                                <div class="card-header-right">
                                    <div class="btn-group card-option">
                                        <button type="button" class="btn dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-end">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit user')): ?>
                                                <a href="#!" data-size="lg" data-url="<?php echo e(route('users.edit',$user->id)); ?>" data-ajax-popup="true" class="dropdown-item" data-bs-original-title="<?php echo e(__('Edit User')); ?>">
                                                    <i class="ti ti-pencil"></i>
                                                    <span><?php echo e(__('Edit')); ?></span>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete user')): ?>
                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user['id']],'id'=>'delete-form-'.$user['id']]); ?>

                                                <a href="#!"  class="dropdown-item bs-pass-para">
                                                    <i class="ti ti-archive"></i>
                                                    <span> <?php if($user->delete_status!=0): ?><?php echo e(__('Delete')); ?> <?php else: ?> <?php echo e(__('Restore')); ?><?php endif; ?></span>
                                                </a>

                                                <?php echo Form::close(); ?>

                                            <?php endif; ?>
                                            <a href="#!" data-url="<?php echo e(route('users.reset',\Crypt::encrypt($user->id))); ?>" data-ajax-popup="true" class="dropdown-item" data-bs-original-title="<?php echo e(__('Reset Password')); ?>">
                                                <i class="ti ti-adjustments"></i>
                                                <span>  <?php echo e(__('Reset Password')); ?></span>
                                            </a>

                                            <?php if($user->is_enable_login == 1): ?>
                                            <a href="<?php echo e(route('users.login', \Crypt::encrypt($user->id))); ?>"
                                                class="dropdown-item">
                                                <i class="ti ti-road-sign"></i>
                                                <span class="text-danger"> <?php echo e(__('Login Disable')); ?></span>
                                            </a>
                                        <?php elseif($user->is_enable_login == 0 && $user->password == null): ?>
                                            <a href="#" data-url="<?php echo e(route('users.reset', \Crypt::encrypt($user->id))); ?>"
                                                data-ajax-popup="true" data-size="md" class="dropdown-item login_enable"
                                                data-title="<?php echo e(__('New Password')); ?>" class="dropdown-item">
                                                <i class="ti ti-road-sign"></i>
                                                <span class="text-success"> <?php echo e(__('Login Enable')); ?></span>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo e(route('users.login', \Crypt::encrypt($user->id))); ?>"
                                                class="dropdown-item">
                                                <i class="ti ti-road-sign"></i>
                                                <span class="text-success"> <?php echo e(__('Login Enable')); ?></span>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body full-card">
                                <div class="img-fluid rounded-circle card-avatar">
                                    <img src="<?php echo e((!empty($user->avatar))? asset(Storage::url("uploads/avatar/".$user->avatar)): asset(Storage::url("uploads/avatar/avatar.png"))); ?>"  class="img-user wid-80 rounded-circle">
                                </div>
                                <h4 class=" mt-2 text-primary"><?php echo e($user->name); ?></h4>
                                <small class="text-primary"><?php echo e($user->email); ?></small>
                                <p></p>


                                <div class="col text-center d-block h6 mb-0" data-bs-toggle="tooltip" title="<?php echo e(__('Last Login')); ?>">
                                    <?php echo e((!empty($user->last_login_at)) ? $user->last_login_at : ''); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script>
        $(document).on('change', '#password_switch', function() {
            if ($(this).is(':checked')) {
                $('.ps_div').removeClass('d-none');
                $('#password').attr("required", true);

            } else {
                $('.ps_div').addClass('d-none');
                $('#password').val(null);
                $('#password').removeAttr("required");
            }
        });
        $(document).on('click', '.login_enable', function() {
            setTimeout(function() {
                $('.modal-body').append($('<input>', {
                    type: 'hidden',
                    val: 'true',
                    name: 'login_enable'
                }));
            }, 2000);
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/tesla/Desktop/ERP/Test2/main-file/resources/views/user/index.blade.php ENDPATH**/ ?>