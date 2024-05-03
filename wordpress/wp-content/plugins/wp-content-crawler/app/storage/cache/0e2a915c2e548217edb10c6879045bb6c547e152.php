

<?php

use WPCCrawler\Services\UserPrefsService;

$prefs = (new UserPrefsService())->getUserPreferences($pageType ?? null, $siteId ?? null) ?? '{}';

?>

<div id="user-prefs" class="hidden" data-prefs='<?php echo $prefs; ?>'></div><?php /**PATH /www/wwwroot/jeannechic.com/wp-content/plugins/wp-content-crawler/app/views/partials/user-preferences.blade.php ENDPATH**/ ?>