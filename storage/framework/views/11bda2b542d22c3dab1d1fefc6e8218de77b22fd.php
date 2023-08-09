<?php if(count(\Laravel\Nova\Nova::resourcesForNavigation(request()))): ?>
    <h3 class="flex items-center font-normal text-white mb-6 text-base no-underline">
        <svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path fill="var(--sidebar-icon)" d="M3 1h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3h-4zM3 11h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4h-4z"
            />
        </svg>
        <span class="sidebar-label"><?php echo e(__('Resources')); ?></span>
    </h3>

    <?php $__currentLoopData = $navigation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $resources): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(count($groups) > 1): ?>
            <h4 class="ml-8 mb-4 text-xs text-white-50% uppercase tracking-wide"><?php echo e($group); ?></h4>
        <?php endif; ?>

        <ul class="list-reset mb-8">
            <?php $__currentLoopData = $resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="leading-tight mb-4 ml-8 text-sm">
                    <router-link :to="{
                        name: 'index',
                        params: {
                            resourceName: '<?php echo e($resource::uriKey()); ?>'
                        }
                    }" class="text-white text-justify no-underline dim" dusk="<?php echo e($resource::uriKey()); ?>-resource-link">
                        <?php echo e($resource::label()); ?>

                    </router-link>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH D:\KJR\Main\0_Work\09_intra\1_videosuite-app\vendor\laravel\nova\src/../resources/views/resources/navigation.blade.php ENDPATH**/ ?>