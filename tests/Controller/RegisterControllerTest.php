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

        $form = $crawler->selectButton("Valider l'inscription")->form([
            'firstName'        => 'Test',
            'lastName'         => 'User',
            'pseudo'           => 'testuser' . time(),
            'email'            => 'test' . time() . '@example.com',
            'password'         => 'Password123',
            'confirm_password' => 'Password123',
            'avatar'           => '1',
            'conditions'       => '1',
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

        $form = $crawler->selectButton("Valider l'inscription")->form([
            'firstName'        => 'T',
            'lastName'         => 'U',
            'pseudo'           => 'ab',
            'email'            => 'invalid-email',
            'password'         => 'weak',
            'confirm_password' => 'weak',
            'conditions'       => '1',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/signup');
    }

    public function testRegistrationWithMismatchedPasswords(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/signup');

        $form = $crawler->selectButton("Valider l'inscription")->form([
            'firstName'        => 'Test',
            'lastName'         => 'User',
            'pseudo'           => 'testuser',
            'email'            => 'test@example.com',
            'password'         => 'Password123',
            'confirm_password' => 'DifferentPassword123',
            'conditions'       => '1',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/signup');
    }

    public function testRegistrationWithoutAcceptingConditions(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/signup');

        $form = $crawler->selectButton("Valider l'inscription")->form([
            'firstName'        => 'Test',
            'lastName'         => 'User',
            'pseudo'           => 'testuser',
            'email'            => 'test@example.com',
            'password'         => 'Password123',
            'confirm_password' => 'Password123',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/signup');
    }
}
