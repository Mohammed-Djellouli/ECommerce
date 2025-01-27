<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegsiterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        /**
         * 1. Créer un faux client (navigateur) de pointer vers une URL)
         * 2. Remplir les champs de mon formulaire d'inscription
         * 3. Est-ce que tu peux regarder si dans ma page j'ai le message (alerte)  suivante :
         */

        //1.
        $client = static::createClient();
        $client->request('GET', '/inscription');


        //2. (firsname,lastname,email,password,confirmation password)
        $client->submitForm('Valider', [
            'register_user[firstname]' => 'test5',
            'register_user[lastname]' => 'test5',
            'register_user[email]' =>   'test5@gmail.com',
            'register_user[plainPassword][first]' => 'test5test5',
            'register_user[plainPassword][second]' =>   'test5test5'
        ]);

        //Follow
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        //3.
        $this->assertSelectorExists('div:contains("Votre compte est correctement créer")');
    }
}
