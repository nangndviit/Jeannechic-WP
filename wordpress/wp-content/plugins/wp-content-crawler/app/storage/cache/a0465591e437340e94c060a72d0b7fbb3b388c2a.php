

<tr <?php if(isset($id)): ?> id="<?php echo e($id); ?>" <?php endif; ?>
<?php if(isset($class)): ?> class="<?php echo e($class); ?>" <?php endif; ?>
>
    <td>
        <?php echo $__env->make('form-items/label', [
            'for'   => $name,
            'title' => $title,
            'info'  => $info,
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </td>
    <td>
        <?php echo $__env->make('form-items.partials.button-openai-gpt', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </td>
</tr><?php /**PATH /www/wwwroot/jeannechic.com/wp-content/plugins/wp-content-crawler/app/views/form-items/combined/button-openai-gpt-with-label.blade.php ENDPATH**/ ?>