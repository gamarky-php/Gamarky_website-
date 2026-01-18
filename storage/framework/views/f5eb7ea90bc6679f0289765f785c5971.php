<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e($currentDir ?? 'rtl'); ?>">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title><?php echo $__env->yieldContent('title', __('nav.brand')); ?></title>

  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <?php if(app()->getLocale() === 'ar'): ?>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
  <?php elseif(app()->getLocale() === 'zh'): ?>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
  <?php else: ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <?php endif; ?>

  
  <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css','resources/js/app.js']); ?>
  
  
  <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>


  <style>
    [x-cloak] { display: none !important; }
    body {
      font-family: <?php echo e(app()->getLocale() === 'ar' ? "'Cairo', sans-serif" : (app()->getLocale() === 'zh' ? "'Noto Sans SC', sans-serif" : "'Inter', sans-serif")); ?>;
    }
  </style>
</head>
<body class="min-h-screen bg-white text-gray-900 antialiased">

  
  <header class="sticky top-0 z-50">
    <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  </header>

  <main class="min-h-[60vh]">
    <?php echo $__env->yieldContent('content'); ?>
  </main>

  
  <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  
  <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>





<?php /**PATH C:\xampp\htdocs\gamarky\resources\views/layouts/front.blade.php ENDPATH**/ ?>