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
    <li class="nav-item">
      <a class="nav-link text-dark" href="/home/profile"><?php echo e(__('Profile')); ?></a>
    </li>

    <li class="nav-item">
      <a class="nav-link text-dark" href="<?php echo e(url('/home')); ?>">Home</a>
    </li>

    <li class="nav-item">
      <a class="nav-link text-dark active" href="<?php echo e(route('logout')); ?>"
        onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
        <?php echo e(__('Logout')); ?>

      </a>
      <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
        <?php echo csrf_field(); ?>
      </form>
    </li>
  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Your styles here */
    body {
        -ms-overflow-style: none;
        scrollbar-width: none;
        overflow-y: scroll;
    }

    body::-webkit-scrollbar {
        display: none;
    }

    .list-group-item.active {
        background-color: rgba(232,236,238,255) !important;
        color: black;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script>

    $(document).ready(function () {
        $('#usersTable').DataTable({
            "order": [[1, "desc"]],
            "searching": false,
            "lengthChange": false,
            "pageLength": 10,
            "paging": false,
            "info": false,
        });

        showDashboard();

    });

    function showDashboard() {
      document.getElementById('dashboardContent').style.display = 'block';
      document.getElementById('userContent').style.display = 'none';
      document.getElementById('dummyContent').style.display = 'none';

      document.getElementById('dashboardLink').classList.add('active');
      document.getElementById('userLink').classList.remove('active');
      document.getElementById('dummyLink').classList.remove('active');
    }

    function showUser() {
      document.getElementById('dashboardContent').style.display = 'none';
      document.getElementById('userContent').style.display = 'block';
      document.getElementById('dummyContent').style.display = 'none';

      document.getElementById('dashboardLink').classList.remove('active');
      document.getElementById('userLink').classList.add('active');
      document.getElementById('dummyLink').classList.remove('active');
    }

    function showDummy() {
      document.getElementById('dashboardContent').style.display = 'none';
      document.getElementById('userContent').style.display = 'none';
      document.getElementById('dummyContent').style.display = 'block';

      document.getElementById('dashboardLink').classList.remove('active');
      document.getElementById('userLink').classList.remove('active');
      document.getElementById('dummyLink').classList.add('active');
    };
</script>

<div class="container">

    <?php if(Session::has('message')): ?>
        <p class=" pb-3 alert <?php echo e(Session::get('alert-class', 'alert-info')); ?> alert-dismissible fade show"><?php echo e(Session::get('message')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </p>
    <?php endif; ?>

    <?php if(Session::has('delete')): ?>
        <p class=" pb-3 alert <?php echo e(Session::get('alert-class', 'alert-danger')); ?> alert-dismissible fade show"><?php echo e(Session::get('delete')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </p>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><?php echo e($error); ?></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-3 col-sm-12 p-3">
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action mb-3 border active" id="dashboardLink" onclick="showDashboard()">Dashboard</a>


                <div class="dropdown">
                    <a href="#" class="list-group-item list-group-item-action mb-3 border dropdown-toggle d-flex justify-content-between align-items-center" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Users
                    </a>
                    <div class="dropdown-menu w-100 p-2" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#" onclick="showUser()">View Users</a>
                        <a class="dropdown-item" href="#" onclick="showDummy()">Add New User</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9 col-sm-12 p-3">
            <!-- Dashboard Content -->
            <div class="card rounded content-section" id="dashboardContent">
                <h5 class="card-header">Dashboard Content</h5>
                <div class="card-body row">
                    <!-- Example: Total Users Count -->
                    <div class="col-3">
                        <div class="card rounded text-center h-100">
                            <div class="card-header bg-warning">Total Users</div>
                            <div class="card-body display-4"><?php echo e(count($users)); ?></div>
                        </div>
                    </div>

                    <!-- Example: System Information -->
                    <div class="col-9">
                        <div class="card rounded h-100">
                            <div class="card-header bg-success text-center">System Information</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4 text-left"><strong>Laravel Version</strong></div>
                                    <div class="col-8">: &ThinSpace; <?php echo e(Illuminate\Foundation\Application::VERSION); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-4 text-left"><strong>PHP Version</strong></div>
                                    <div class="col-8">: &ThinSpace; <?php echo e(PHP_VERSION); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-4 text-left"><strong>Database</strong></div>
                                    <div class="col-8">: &ThinSpace; Firebase</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Example: Recent Active Users Table -->
                    <div class="col-12 pt-3">
                        <div class="card rounded ">
                            <div class="card-header bg-info text-white">Recent Active Users</div>
                            <table id="usersTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Logged-In</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($user->displayName); ?></td>
                                            <td><?php echo e(Carbon\Carbon::parse($user->metadata->lastLoginAt)->diffForHumans()); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- User Content -->
            <div class="card rounded" id="userContent">
                <h5 class="card-header">User Content</h5>
                <!-- Example: Recent Active Users Table with Additional Information -->
                <div class="col-12 pt-3">
                    <div class="card rounded ">
                        
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Last Signed In</th>
                                    <th scope="col">Email Verified</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($user->displayName); ?></td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td><?php echo e(Carbon\Carbon::parse($user->metadata->lastLoginAt)->diffForHumans()); ?></td>
                                        <td style="text-align: center;"><?php echo e($user->emailVerified ? 'Yes' : 'No'); ?></td>
                                        <td class="text-center"><?php echo e($user->disabled ? 'Disabled' : 'Active'); ?></td>
                                        <td>
                                            <?php if($currentUser != $user->uid): ?>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo e($user->uid); ?>">
                                                    Edit
                                                </button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="<?php echo e($user->uid); ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo e($user->uid); ?>" aria-hidden="true">
                                                    <!-- Modal content here -->
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Edit User: <?php echo e($user->displayName); ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Example: Form fields for editing user details -->

                                                                <form method="POST" action="<?php echo e(action('App\Http\Controllers\Auth\AdminController@update', $user->uid)); ?>">
                                                                    <?php echo method_field('PATCH'); ?>
                                                                    <?php echo csrf_field(); ?>

                                                                    <div class="form-group">
                                                                        <label for="displayName">First Name:</label>
                                                                        <input type="text" name="displayName" id="displayName" class="form-control" value="<?php echo e($user->displayName); ?>">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="email">Email:</label>
                                                                        <input type="email" name="email" id="email" class="form-control" value="<?php echo e($user->email); ?>">
                                                                    </div>

                                                                    <div class="modal-footer border-0">
                                                                        <button type="submit" class="btn btn-success">Save changes</button>

                                                                </form>


                                                               <?php if($user->disabled): ?>
                                                                    <form method="POST" action="<?php echo e(action('App\Http\Controllers\Auth\AdminController@destroy', $user->uid)); ?>">
                                                                        <?php echo method_field('DELETE'); ?>
                                                                        <?php echo csrf_field(); ?>
                                                                        <button type="submit" class="btn btn-success">Enable Account</button>
                                                                    </form>
                                                                <?php else: ?>
                                                                    <form method="POST" action="<?php echo e(action('App\Http\Controllers\Auth\AdminController@destroy', $user->uid)); ?>">
                                                                        <?php echo method_field('DELETE'); ?>
                                                                        <?php echo csrf_field(); ?>
                                                                        <button type="submit" class="btn btn-danger">Disable Account</button>
                                                                    </form>
                                                                <?php endif; ?>


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Dummy Content -->
            <div class="card rounded" id="dummyContent">
                <h5 class="card-header">Add New User</h5>
                <div class="card-body">
                    <!-- New User Form -->
                   <form method="POST" action="<?php echo e(action('App\Http\Controllers\Auth\AdminController@store')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="form-group">
                            <label for="displayName">Display Name:</label>
                            <input type="text" name="displayName" id="displayName" class="form-control" value="<?php echo e(old('displayName')); ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?php echo e(old('email')); ?>">
                        </div>

                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create User</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sydne\Desktop\FINAL FOR DEFENSE EDITING\Editing\resources\views/auth/admin.blade.php ENDPATH**/ ?>