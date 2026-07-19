<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactRecipientTest extends TestCase
{
    use RefreshDatabase;

    public function test_le_destinataire_par_defaut_est_l_adresse_de_production(): void
    {
        $this->assertSame('contact@bcj37.fr', config('mail.contact.to'));
        $this->assertSame('no-reply@bcj37.fr', config('mail.contact.from'));
    }

    public function test_le_formulaire_envoie_vers_l_adresse_configuree(): void
    {
        // Simule un environnement (ex. test) qui redirige les messages.
        config([
            'mail.default' => 'array',
            'mail.contact.to' => 'boite-de-test@exemple.fr',
        ]);

        $this->postJson('/api/v1/contact', [
            'name' => 'Jean Dupont',
            'email' => 'visiteur@exemple.fr',
            'message' => 'Bonjour, ceci est un test.',
        ])->assertStatus(200);

        $messages = Mail::mailer()->getSymfonyTransport()->messages();
        $this->assertCount(1, $messages);

        $sent = $messages[0]->getOriginalMessage();
        $this->assertSame('boite-de-test@exemple.fr', $sent->getTo()[0]->getAddress());
        // L'email du visiteur reste en reply-to (pas en destinataire).
        $this->assertSame('visiteur@exemple.fr', $sent->getReplyTo()[0]->getAddress());
    }
}
