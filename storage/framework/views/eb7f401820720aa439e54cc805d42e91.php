<?php $__env->startSection('navbar_home'); ?>
  <?php if(auth()->guard()->guest()): ?>
    <?php if(Route::has('login')): ?>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('login')); ?>"><?php echo e(__('Login')); ?></a>
      </li>
    <?php endif; ?>

    <?php if(Route::has('register')): ?>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('register')); ?>"><?php echo e(__('Register')); ?></a>
      </li>
    <?php endif; ?>
  <?php else: ?>

    <?php if($user->customClaims['admin']): ?>
        <li class="nav-item">
        <a class="nav-link text-dark" href="/home/admin"><?php echo e(__('Admin')); ?></a>
        </li>
    <?php endif; ?>

    <li class="nav-item">
      <a class="nav-link text-dark" href="/home/profile"><?php echo e(__('Profile')); ?></a>
    </li>

    <li class="nav-item">
      <a class="nav-link text-dark" href="<?php echo e(route('logout')); ?>"
      onclick="event.preventDefault();
      document.getElementById('logout-form').submit();">
      <?php echo e(__('Logout')); ?>

    </a>
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
      <?php echo csrf_field(); ?>
    </form>
  </li>
</div>
</li>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container">

    <?php if(Session::has('message')): ?>
      <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?php echo e(Session::get('message')); ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php endif; ?>

    <?php if(Session::has('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(Session::get('error')); ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><?php echo e(__('Dashboard')); ?></div>

                <div class="card-body">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if($totalUsers == 1): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Admin Panel is now available! Become an admin by visiting  <mark class="bg-ligth">/home/iamadmin</mark> or simply <a href="/home/iamadmin">click here</a>.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                    <?php endif; ?>

                    <?php echo e(__('You are logged in!')); ?>

                    <h1><?php echo e($user->customClaims["admin"]); ?></h1>

                </div>
            </div>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sydne\Desktop\FINAL FOR DEFENSE EDITING\Editing\resources\views/home.blade.php ENDPATH**/ ?>