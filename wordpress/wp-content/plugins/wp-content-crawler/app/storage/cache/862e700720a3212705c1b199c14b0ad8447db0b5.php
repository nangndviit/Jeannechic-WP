<?php if(isset($message)): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div><span class="inner-title"><?php echo e($k); ?></span></div>
    <div class="raw-html-find-replace-results">
        <?php $__currentLoopData = $result; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="result-item">
                <div><span class="result-item-title"><?php echo $key; ?> (<?php echo e(_wpcc('Length')); ?>: <?php echo e(mb_strlen($value)); ?>):</span></div>
                <?php if($value): ?>
                    <textarea class="large"><?php echo e($value); ?></textarea>
                <?php else: ?>
                    <span><?php echo e(_wpcc('No HTML. If you expect HTML, make sure the HTML is valid or there is network connection.')); ?></span>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if(empty($results)): ?>
    <span class="no-result"><?php echo e(_wpcc('No result')); ?></span>
<?php endif; ?>


<?php echo $__env->make('.partials.notification-for-url-cache', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php echo $__env->make('partials.info-list', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /www/wwwroot/jeannechic.com/wp-content/plugins/wp-content-crawler/app/views/partials/test-result-find-replace-raw-html.blade.php ENDPATH**/ ?>