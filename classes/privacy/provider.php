<?php
namespace filter_frontpage_menu\privacy;

use core_privacy\local\metadata\null_provider;

/** This stateless renderer stores no personal data. */
final class provider implements null_provider {
    public static function get_reason(): string {
        return 'privacy:metadata';
    }
}
