<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\I18n;
use App\Core\Theme;
use App\Core\Visitor;

use function App\Core\redirect;

final class PreferenceController
{
    public function setLanguage(): void
    {
        $lang = (string)($_POST['lang'] ?? '');
        $lang = I18n::normalize($lang);
        Visitor::setLanguage($lang);

        $back = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect($back);
    }

    public function setTheme(): void
    {
        $theme = (string)($_POST['theme'] ?? '');
        $theme = Theme::normalize($theme);
        Visitor::setTheme($theme);

        $back = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect($back);
    }
}
