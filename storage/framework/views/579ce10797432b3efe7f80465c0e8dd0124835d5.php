<dropdown-trigger class="h-9 flex items-center">
    <?php if(isset($user->email)): ?>
        <img
            src="https://secure.gravatar.com/avatar/<?php echo e(md5(\Illuminate\Support\Str::lower($user->email))); ?>?size=512"
            class="rounded-full w-8 h-8 mr-3"
        />
    <?php endif; ?>

    <span class="text-90">
        <?php echo e($user->name ?? $user->email ?? __('Nova User')); ?>

    </span>
</dropdown-trigger>

<dropdown-menu slot="menu" width="200" direction="rtl">
    <ul class="list-reset">
        <li>
            <a href="<?php echo e(route('nova.logout')); ?>" class="block no-underline text-90 hover:bg-30 p-3">
                <?php echo e(__('Logout')); ?>

            </a>
        </li>
    </ul>
</dropdown-menu>
<?php /**PATH D:\KJR\Main\0_Work\09_intra\1_videosuite-app\resources\views/vendor/nova/partials/user.blade.php ENDPATH**/ ?>