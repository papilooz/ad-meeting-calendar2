<?php
function footerComponent()
{
    include_once STATICDATAS_PATH . "/footer.staticData.php";
    ?>
    <footer class="bg-[#1a1a2e] text-gray-300 py-6 mt-10">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-start gap-8">
            
            <!-- Logo and Brand -->
            <div class="flex flex-col items-start">
                <img src="/assets/img/theelite-logo.png" alt="The Elite Logo" class="h-10 w-10 mb-2">
                <span class="font-bold text-lg text-white">The Elite</span>
                <p class="text-sm text-gray-400 mt-1">Empowering collaboration one meeting at a time.</p>
            </div>

            <!-- Footer Links -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php foreach ($footerLinks as $link): ?>
                    <div>
                        <h3 class="text-white font-semibold mb-2"><?= htmlspecialchars($link["title"]) ?></h3>
                        <ul class="space-y-1">
                            <?php foreach ($link['subs'] as $subLink): ?>
                                <li>
                                    <a 
                                        href="<?= htmlspecialchars($subLink["link"]) ?>"
                                        class="text-gray-400 hover:text-white text-sm transition"
                                        <?= array_key_exists('download', $subLink) && $subLink['download'] ? 'download' : '' ?>>
                                        <?= htmlspecialchars($subLink["tag"]) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mt-6 border-t border-gray-700 pt-4 text-center text-xs text-gray-500">
            &copy; <?= date('Y') ?> The Elite. All rights reserved.
        </div>
    </footer>
    <?php
}
