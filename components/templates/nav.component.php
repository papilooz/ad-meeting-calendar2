<?php
declare(strict_types=1);

function navHeader(array $navList, ?array $user = null): void
{
    ?>
    <header>
<nav class="bg-[#2a003f] border-b border-purple-800 text-white shadow-md">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">

                    <!-- Logo / Brand -->
                    <a href="/index.php" class="flex items-center gap-3 text-purple-700 dark:text-purple-400 font-bold text-xl">
                        <img src="/assets/img/theelite-logo.png" alt="The Elite Logo" class="h-8 w-8 rounded-full">
                        <span>The Elite</span>
                    </a>

                    <!-- Main Nav -->
                    <div class="hidden md:flex items-center space-x-8 text-sm font-medium">
                       <?php foreach ($navList as $nav):
    // Skip "Home"
    if (strtolower($nav['label']) === 'home') {
        continue;
    }

    if ($nav["for"] === "all" || ($user && ($user['role'] ?? '') === "team lead")):
?>
    <a href="<?= htmlspecialchars($nav['link']) ?>"
       class="text-gray-100 hover:text-purple-300 transition">
        <?= htmlspecialchars($nav['label']) ?>
    </a>
<?php
    endif;
endforeach; ?>

                    </div>

                    <!-- User Actions -->
                    <div class="flex items-center space-x-2">
                        <?php if ($user): ?>
                            <?php
                            $name = htmlspecialchars($user['first_name']);
                            $role = htmlspecialchars($user['role'] ?? '');
                            ?>
                            <span class="hidden sm:inline text-gray-600 dark:text-gray-300 text-sm">
                                <?= "Hi, <strong>{$name}</strong> ({$role})" ?>
                            </span>
                            <a href="/pages/account/index.php"
                               class="bg-purple-700 hover:bg-purple-800 text-white px-4 py-2 rounded text-sm transition">
                                Settings
                            </a>
                            <a href="/pages/logout/index.php"
                               class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded text-sm transition">
                                Log out
                            </a>
                        <?php else: ?>
                            <a href="/index.php"
                               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm transition">
                                Log in
                            </a>
                            <a href="/pages/signup/index.php"
                               class="bg-purple-100 hover:bg-purple-200 text-purple-900 px-4 py-2 rounded text-sm transition dark:bg-purple-800 dark:hover:bg-purple-700 dark:text-white">
                                Sign Up
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <?php
}
