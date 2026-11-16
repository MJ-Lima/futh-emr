<?php if (isset($component)) { $__componentOriginal1ea8f370d8126b074ba5abe21fb80060 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1ea8f370d8126b074ba5abe21fb80060 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.filament-branding','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-branding'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1ea8f370d8126b074ba5abe21fb80060)): ?>
<?php $attributes = $__attributesOriginal1ea8f370d8126b074ba5abe21fb80060; ?>
<?php unset($__attributesOriginal1ea8f370d8126b074ba5abe21fb80060); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1ea8f370d8126b074ba5abe21fb80060)): ?>
<?php $component = $__componentOriginal1ea8f370d8126b074ba5abe21fb80060; ?>
<?php unset($__componentOriginal1ea8f370d8126b074ba5abe21fb80060); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\futh-lafia-emr\storage\framework\views/2b928d07bbc52bf603e7c57c123757ef.blade.php ENDPATH**/ ?>