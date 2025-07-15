<?php
declare(strict_types=1);

function navHeader(array $navList, ?array $user = null): void
{
    ?>
    <header>
        <nav class="bg-yellow-200 dark:bg-gray-900 shadow px-4 lg:px-6 py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">

                <!-- Logo and Brand -->
                <a href="/index.php" class="flex items-center space-x-3">
                    <img src="/assets/img/buttonconeFav.png" class="h-8 w-8 rounded" alt="Buttoncone Logo" />
                    <span class="text-xl font-extrabold text-gray-800 dark:text-white">
                        Buttoncone
                        <span class="text-red-400 dark:text-red-400">Meeting Calendar</span>
                    </span>
                </a>

                <!-- User Actions -->
                <div class="flex items-center space-x-2 lg:order-2">
                    <?php if ($user): ?>
                        <?php
                        $name = htmlspecialchars($user['first_name']);
                        $role = htmlspecialchars($user['role'] ?? '');
                        ?>
                        <span class="hidden sm:inline-block px-3 text-gray-700 dark:text-gray-300">
                            Welcome, <span class="font-semibold"><?= "{$name}:{$role}" ?></span>
                        </span>
                        <a href="/pages/account/index.php"
                           class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm transition">
                            Settings
                        </a>
                        <a href="/pages/logout/index.php"
                           class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-md text-sm transition">
                            Log out
                        </a>
                    <?php else: ?>
                        <a href="/pages/login/index.php"
                           class="bg-red-400 hover:bg-red-400 text-white px-4 py-2 rounded-md text-sm transition">
                            Log in
                        </a>
                        <a href="/pages/signup/index.php"
                           class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md text-sm transition dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white">
                            Sign Up
                        </a>
                    <?php endif; ?>

                    <!-- Mobile Menu Toggle -->
                    <button data-collapse-toggle="mobile-menu-2" type="button"
                        class="lg:hidden inline-flex items-center p-2 rounded-md text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        aria-controls="mobile-menu-2" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5h14M3 10h14M3 15h14" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <div class="hidden lg:flex justify-between items-center lg:order-1 w-full lg:w-auto" id="mobile-menu-2">
                    <ul class="flex flex-col lg:flex-row lg:space-x-6 mt-4 lg:mt-0 text-sm font-medium">
                        <?php foreach ($navList as $nav):
                            if ($nav["for"] === "all" || ($user && htmlspecialchars($user['role']) === "team lead")):
                                ?>
                                <li>
                                    <a href="<?= htmlspecialchars($nav['link']) ?>"
                                       class="block py-2 px-3 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                        <?= htmlspecialchars($nav['label']) ?>
                                    </a>
                                </li>
                                <?php
                            endif;
                        endforeach; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <?php
}
?>