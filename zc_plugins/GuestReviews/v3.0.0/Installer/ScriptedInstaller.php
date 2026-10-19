<?php
use Zencart\PluginSupport\ScriptedInstaller as ScriptedInstallBase;

class ScriptedInstaller extends ScriptedInstallBase
{
    /**
     * @return bool
     */
    protected function executeInstall()
    {
        if (!$this->purgeOldFiles()) {
            return false;
        }

        // -----
        // If the plugin's configuration settings aren't present, add them now.
        //
        $this->executeInstallerSql(
            "INSERT IGNORE INTO " . TABLE_CONFIGURATION . "
                (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function)
             VALUES
                ('Enable product reviews by guests?', 'REVIEWS_BY_GUESTS', '0', 'Identifies whether (1) or not (0, the default) your store allows guests to write reviews.', 18, 63, now(), NULL, 'zen_cfg_select_option([\'1\', \'0\'],'),

                ('Product Review Write - Guest Reviewer Name', 'REVIEW_NAME_MIN_LENGTH', '5', 'Minimum length of a guest reviewer\'s name', 2, 14, now(), NULL, NULL)"
        );

        return parent::executeInstall();
    }

    /**
     * @return bool
     */
    protected function executeUninstall()
    {
        $this->executeInstallerSql(
            "DELETE FROM " . TABLE_CONFIGURATION . "
              WHERE configuration_key IN ('REVIEWS_BY_GUEST', 'REVIEW_NAME_MIN_LENGTH')"
        );

        return parent::executeUninstall();
    }

    protected function purgeOldFiles(): bool
    {
        $files_to_remove = [
            DIR_FS_ADMIN . 'includes/auto_loaders/config.SuperGlobalsAdmin.php',
            DIR_FS_ADMIN . 'includes/init_includes/init_reviews_updated_admin.php',
            DIR_FS_CATALOG . 'includes/auto_loaders/config.SuperGlobals.php',
            DIR_FS_CATALOG . 'includes/classes/observers/auto.reviews_updated.php',
        ];

        $error = false;
        foreach ($files_to_remove as $key => $next_file) {
            if (file_exists($next_file)) {
                $result = unlink($next_file);
                if (!$result && file_exists($next_file)) {
                    $error = true;
                    $this->errorContainer->addError(
                        0,
                        sprintf(ERROR_UNABLE_TO_DELETE_FILE, $next_file),
                        false,
                        // this str_replace has to do DIR_FS_ADMIN before CATALOG because catalog is contained within admin, so results are wrong.
                        // also, '[admin_directory]' is used to obfuscate the admin dir name, in case the user copy/pastes output to a public forum for help.
                        sprintf(ERROR_UNABLE_TO_DELETE_FILE, str_replace([DIR_FS_ADMIN, DIR_FS_CATALOG], ['[admin_directory]/', ''], $next_file))
                    );
                }
            }
        }
        return !$error;
    }
}
