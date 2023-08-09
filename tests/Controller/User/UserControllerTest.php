<?php

namespace App\Tests\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class UserControllerTest extends WebTestCase
{
    private ?KernelBrowser $client = null;
    private User $registredUser;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin@gmail.com');
        $this->registredUser = $userRepository->findOneByUsername('helloUser');
        $userAddedForTest = $userRepository->findOneByUsername('Pedro');

        if (!empty($userAddedForTest)) {
            $userRepository->deleteUser($userAddedForTest->getEmail());
        }

        $this->urlGenerator = $this->client->getContainer()->get('router.default');

        $this->client->loginUser($testUser);

        $this->client->followRedirects();

    }

    public function testAdminListPageIsUp()
    {

        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_list'));

        $this->assertResponseIsSuccessful();

    }

    public function testCreateNewUniqUser()
    {
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_create'));
        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'Pedro';
        $form['user[roles]'] = 'ROLE_USER';
        $form['user[password][first]'] = '123456';
        $form['user[password][second]'] = '123456';
        $form['user[email]'] = 'pedro@gmail.com';
        $this->client->submit($form);
        $this->assertSelectorTextContains(
            'div.alert.alert-success',
            "Superbe ! L'utilisateur Pedro a bien été ajouté."
        );

        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_create'));
        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'Pedro';
        $form['user[roles]'] = 'ROLE_USER';
        $form['user[password][first]'] = '123456';
        $form['user[password][second]'] = '123456';
        $form['user[email]'] = 'pedro09@gmail.com';
        $this->client->submit($form);
        $this->assertSelectorTextContains(
            'div.alert.alert-danger',
            "Oops ! l'utilisateur Pedro existe déjà."
        );

        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_create'));
        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'Pedro';
        $form['user[roles]'] = 'ROLE_USER';
        $form['user[password][first]'] = '123456';
        $form['user[password][second]'] = '123456';
        $form['user[email]'] = 'pedro@gmail.com';
        $this->client->submit($form);
       echo $this->client->getResponse()->getContent();
        $this->assertSelectorTextContains(
            'html li',
            "Cette valeur est déjà utilisée."
        );
    }

    public function testWrongPasswordInputUser()
    {
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_create'));
        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'Pedro';
        $form['user[roles]'] = 'ROLE_USER';
        $form['user[password][first]'] = '123456';
        $form['user[password][second]'] = '12456';
        $form['user[email]'] = 'pedro@gmail.com';
        $this->client->submit($form);
        $this->assertSelectorTextContains(
            'html li',
            "Les deux mots de passes doivent correspondre."
        );
    }

    public function testEditExistingUser()
    {
        $random = rand(1544,45546);
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => $this->registredUser->getId()] ));
        $form = $crawler->selectButton('Modifier')->form();
        $form['user[roles]'] = 'ROLE_USER';
        $form['user[password][first]'] = $random;
        $form['user[password][second]'] = $random;
        $form['user[email]'] = "$random@hotmail.com";
        $this->client->submit($form);
        $this->assertSelectorTextContains(
            'div.alert.alert-success',
            "Superbe ! L'utilisateur helloUser a bien été modifié"
        );

    }


}
