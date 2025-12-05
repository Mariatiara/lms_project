<div class="fixed top-0 left-64 right-0 bg-white shadow h-16 flex items-center justify-between px-6 z-40">

    
    <h2 class="text-xl font-semibold capitalize">
        Dashboard <?php echo e(str_replace('_',' ', Auth::user()->role)); ?>

    </h2>

    
    <div class="flex items-center gap-6">

        
        <a href="<?php echo e(route('notif.index')); ?>" class="relative">
            <?php echo $__env->make('components.icons.notifikasi', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php
            $notifCount = \App\Models\Message::where('penerima_id', Auth::id())
            ->where('is_read', false)
            ->count();

            ?>

            <?php if($notifCount > 0): ?>
                <span id="notif-badge"
                    class="absolute -top-1 -right-1 bg-red-600 text-white text-xs 
                        rounded-full w-5 h-5 flex items-center justify-center">
                    <?php echo e($notifCount); ?>

                </span>
            <?php endif; ?>
        </a>

        
        <a href="<?php echo e(route('chat.index')); ?>" class="relative">
            <?php echo $__env->make('components.icons.pesan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php
                $msgCount = \App\Models\Message::where('penerima_id', Auth::id())
                            ->where('is_read', false)
                            ->count();
            ?>

            <?php if($msgCount > 0): ?>
                <span id="chat-badge"
                    class="absolute -top-1 -right-1 bg-blue-600 text-white text-xs 
                        rounded-full w-5 h-5 flex items-center justify-center">
                    <?php echo e($msgCount); ?>

                </span>
            <?php endif; ?>
        </a>

        
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-2">
                <?php echo $__env->make('components.icons.profil', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <span><?php echo e(Auth::user()->name); ?></span>
            </button>

            
            <div x-show="open"
                 @click.outside="open = false"
                 class="absolute right-0 mt-2 w-40 bg-white shadow rounded py-2 border z-50">

                <a href="<?php echo e(route('settings.index')); ?>" 
                   class="block px-4 py-2 hover:bg-gray-100 text-sm">
                    Pengaturan
                </a>

                <a href="<?php echo e(route('logout')); ?>" 
                   class="block px-4 py-2 hover:bg-gray-100 text-sm text-red-600">
                    Logout
                </a>
            </div>
        </div>

    </div>
</div>



<script>
setInterval(() => {
    // Notifikasi
    fetch("<?php echo e(route('notif.count')); ?>")
        .then(r => r.json())
        .then(d => {
            const badge = document.getElementById('notif-badge');
            if (badge) {
                badge.innerText = d.count;
                badge.style.display = d.count > 0 ? 'flex' : 'none';
            }
        });

    // Chat
    fetch("/chat/count")
        .then(r => r.json())
        .then(d => {
            const badge = document.getElementById('chat-badge');
            if (badge) {
                badge.innerText = d.count;
                badge.style.display = d.count > 0 ? 'flex' : 'none';
            }
        });
}, 3000);
</script>
<?php /**PATH C:\xampp\htdocs\lms_project\resources\views/components/topbar.blade.php ENDPATH**/ ?>