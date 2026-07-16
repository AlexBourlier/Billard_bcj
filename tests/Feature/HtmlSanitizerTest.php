<?php

namespace Tests\Feature;

use App\Support\HtmlSanitizer;
use Tests\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_it_strips_dangerous_html_and_keeps_allowed_tags(): void
    {
        $dirty = '<p>Bonjour <strong>club</strong></p>'
            . '<script>alert(1)</script>'
            . '<img src=x onerror="alert(2)">'
            . '<a href="javascript:alert(3)">piege</a>'
            . '<a href="https://bcj37.fr">site</a>'
            . '<p style="color:red">rouge</p>'
            . '<h2>Sous-titre</h2>';

        $clean = HtmlSanitizer::post($dirty);

        // Dangereux retire
        $this->assertStringNotContainsString('<script', $clean);
        $this->assertStringNotContainsStringIgnoringCase('onerror', $clean);
        $this->assertStringNotContainsStringIgnoringCase('javascript:', $clean);
        $this->assertStringNotContainsString('style=', $clean);
        $this->assertStringNotContainsString('<img', $clean);

        // Legitime conserve
        $this->assertStringContainsString('<strong>club</strong>', $clean);
        $this->assertStringContainsString('<h2>Sous-titre</h2>', $clean);
        $this->assertStringContainsString('bcj37.fr', $clean);
    }

    public function test_it_preserves_plain_and_empty_values(): void
    {
        $this->assertNull(HtmlSanitizer::post(null));
        $this->assertSame('', HtmlSanitizer::post(''));
        $this->assertStringContainsString('Texte simple', (string) HtmlSanitizer::post('Texte simple'));
    }
}
