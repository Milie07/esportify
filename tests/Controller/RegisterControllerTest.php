<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    public function testRegistrationPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/signup');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Inscription');
    }

    public function testSuccessfulRegistration(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/signup');

        $this->assertResponseIsSuccessful();

        // Remplir le formulaire
        $form = $crawler->selectButton('Valider l\'inscription')->form([
            'registration_form_type[firstName]' => 'Test',
            'registration_form_type[lastName]' => 'User',
            'registration_form_type[pseudo]' => 'testuser' . time(),
            'registration_form_type[email]' => 'test' . time() . '@example.com',
            'registration_form_type[plainPassword][first]' => 'Password123',
            'registration_form_type[plainPassword][second]' => 'Password123',
            'registration_form_type[avatar]' => '1',
            'registration_form_type[conditions]' => '1',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/login');
        $client->followRedirect();
        $this->assertSelectorExists('.alert-success');
    }

    public function testRegistrationWithInvalidData(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/signup');

        $form = $crawler->selectButton('Valider l\'inscription')->form([
            'registration_form_type[firstName]' => 'T', // Trop court
            'registration_form_type[lastName]' => 'U',
            'registration_form_type[pseudo]' => 'ab', // Trop court
            'registration_form_type[email]' => 'invalid-email',
            'registration_form_type[plainPassword][first]' => 'weak',
            'registration_form_type[plainPassword][second]' => 'weak',
            'registration_form_type[conditions]' => '1',
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
    }

    public function testRegistrationWithMismatchedPasswords(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/signup');

        $form = $crawler->selectButton('Valider l\'inscription')->form([
            'registration_form_type[firstName]' => 'Test',
            'registration_form_type[lastName]' => 'User',
            'registration_form_type[pseudo]' => 'testuser',
            'registration_form_type[email]' => 'test@example.com',
            'registration_form_type[plainPassword][first]' => 'Password123',
            'registration_form_type[plainPassword][second]' => 'DifferentPassword123',
            'registration_form_type[conditions]' => '1',
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
    }

    public function testRegistrationWithoutAcceptingConditions(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/signup');

        $form = $crawler->selectButton('Valider l\'inscription')->form([
            'registration_form_type[firstName]' => 'Test',
            'registration_form_type[lastName]' => 'User',
            'registration_form_type[pseudo]' => 'testuser',
            'registration_form_type[email]' => 'test@example.com',
            'registration_form_type[plainPassword][first]' => 'Password123',
            'registration_form_type[plainPassword][second]' => 'Password123',
            'registration_form_type[conditions]' => '0', // Non accepté
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
    }
}