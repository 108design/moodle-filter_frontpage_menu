<?php
// This file is part of Moodle - https://moodle.org/
/**
 * This file is part of a 108design source-available software product.
 *
 * Use and modification are permitted only under the Software License included
 * with this distribution. See LICENSE.md for the full terms.
 *
 * File documentation for classes/text_filter.php.
 *
 * @package   filter_frontpage_menu
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license   108design source-available software license; see LICENSE.md
 */
namespace filter_frontpage_menu;

/** Safe shortcode renderer used by Moodle 5.0+ filter discovery. */
class text_filter extends \moodle_text_filter {
    public function filter($text, array $options = []): string {
        if (!is_string($text) || !str_contains($text, '[[frontpage-menu:')) {
            return $text;
        }
        $renderer = $this->renderer();
        if ($renderer === '') {
            return $text;
        }
        $contextlevel = $this->context?->contextlevel ?? CONTEXT_SYSTEM;
        if (!in_array($contextlevel, [CONTEXT_SYSTEM, CONTEXT_COURSECAT, CONTEXT_COURSE,
                CONTEXT_MODULE, CONTEXT_BLOCK], true)) {
            return $text;
        }
        return preg_replace_callback(
            '/\[\[frontpage-menu:([a-zA-Z0-9][a-zA-Z0-9_-]{7,99})\]\]/',
            static fn(array $match): string => $renderer::render($match[1], [
                'style' => 'minimal', 'padding' => 'compact', 'label' => get_string('navigation'),
            ]),
            $text
        ) ?? $text;
    }

    private function renderer(): string {
        if (class_exists('\local_menubuilder\local\authority')
                && \local_menubuilder\local\authority::active()) {
            return '\local_menubuilder\menu';
        }
        if (class_exists('\local_frontpage\menu')) {
            return '\local_frontpage\menu';
        }
        return class_exists('\local_menubuilder\menu') ? '\local_menubuilder\menu' : '';
    }
}
