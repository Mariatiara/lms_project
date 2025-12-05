<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <!-- WAJIB agar CSRF tidak error -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnFlux LMS - <?php echo $__env->yieldContent('title'); ?></title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- WAJIB untuk menjaga session csrf -->
    <script>
        window.Laravel = { csrfToken: "<?php echo e(csrf_token()); ?>" };
    </script>

    <main class="min-h-screen">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\lms_project\resources\views/layouts/app.blade.php ENDPATH**/ ?>