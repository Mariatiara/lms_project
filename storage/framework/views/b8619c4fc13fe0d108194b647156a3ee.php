<div class="w-64 bg-white shadow-lg h-screen fixed left-0 top-0 p-6">
    <h1 class="text-xl font-bold mb-8">LearnFlux</h1>

    <?php $role = Auth::user()->role; ?>

    <ul class="space-y-4">

        
        <li>
            <a href="<?php echo e($role == 'kepala_sekolah' ? route('dashboard.kepsek') :
                ($role == 'guru' ? route('dashboard.guru') :
                ($role == 'siswa' ? route('dashboard.siswa') :
                ($role == 'orang_tua' ? route('dashboard.orangtua') :
                route('dashboard.dinas'))))); ?>"
            class="flex items-center space-x-3 text-gray-700 hover:text-blue-600">
                <?php echo $__env->make('components.icons.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <span>Dashboard</span>
            </a>
        </li>


        
        <?php if($role == 'dinas'): ?>
            <li>
                <a href="<?php echo e(route('dashboard.dinas')); ?>" class="flex items-center gap-3">
                    <?php echo $__env->make('components.icons.dashboard-dinas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <span>Dashboard Dinas</span>
                </a>
            </li>

            <li>
                <a href="<?php echo e(route('superadmin.dashboard')); ?>" class="sidebar-item">
                    <?php echo $__env->make('components.icons.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <span>Superadmin</span>
                </a>
            </li>
        <?php endif; ?>


        
        <?php if($role == 'kepala_sekolah'): ?>
            <li>
                <a href="<?php echo e(route('dashboard.kepsek')); ?>" class="sidebar-item">
                    <?php echo $__env->make('components.icons.laporan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <span>Laporan Nilai</span>
                </a>
            </li>
        <?php endif; ?>


        
        <?php if($role == 'guru'): ?>
            <li>
                <a href="<?php echo e(route('nilai.index')); ?>" class="sidebar-item">
                    <?php echo $__env->make('components.icons.nilai', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <span>Input Nilai</span>
                </a>
            </li>

            <li>
                <a href="<?php echo e(route('jadwal.index')); ?>" class="sidebar-item">
                    <?php echo $__env->make('components.icons.jadwal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <span>Kelola Jadwal</span>
                </a>
            </li>

            <li>
                <a href="<?php echo e(route('tugas.index')); ?>" class="sidebar-item">
                    <?php echo $__env->make('components.icons.file-upload', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <span>Tugas</span>
                </a>
            </li>
        <?php endif; ?>


        
        <?php if($role == 'siswa'): ?>
            <li>
                <a href="<?php echo e(route('nilai.siswa')); ?>" class="sidebar-item">
                    <?php echo $__env->make('components.icons.nilai', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <span>Nilai Saya</span>
                </a>
            </li>

            <li>
                <a href="<?php echo e(route('jadwal.siswa')); ?>" class="sidebar-item">
                    <?php echo $__env->make('components.icons.jadwal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <span>Jadwal Pelajaran</span>
                </a>
            </li>

            <li>
                <a href="<?php echo e(route('tugas.index')); ?>" class="sidebar-item">
                    <?php echo $__env->make('components.icons.file-upload', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <span>Tugas</span>
                </a>
            </li>
        <?php endif; ?>


        
        <?php if($role == 'orang_tua'): ?>
            <li>
                <a href="<?php echo e(route('parent.dashboard')); ?>" class="sidebar-item">
                    <?php echo $__env->make('components.icons.profil', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <span>Monitoring Anak</span>
                </a>
            </li>
        <?php endif; ?>


        
        <li>
            <a href="<?php echo e(route('chat.index')); ?>" class="sidebar-item">
                <?php echo $__env->make('components.icons.pesan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <span>Pesan</span>
            </a>
        </li>


        
        <li class="pt-10">
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit"
                    class="flex items-center space-x-3 text-red-600 hover:text-red-800 w-full text-left">
                    <?php echo $__env->make('components.icons.logout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <span>Logout</span>
                </button>
            </form>
        </li>

    </ul>
</div>
<?php /**PATH C:\xampp\htdocs\lms_project\resources\views/components/sidebar.blade.php ENDPATH**/ ?>