<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $products = [
            ['name' => 'iPhone 13 Pro', 'description' => 'll ne s\'agit pas d\'une révolution en soi, l\'iPhone 13 Pro se situant dans la lignée de son prédécesseur. Mais Apple nous sert un smartphone complet à qui il est difficile de reprocher quelque chose. Dommage que son prix soit plus élevé que celui du Google Pixel 6 Pro qui performe dans les mêmes domaines. Malgré tout, les améliorations apportées du côté de l\'écran, de la partie photo/vidéo et de l\'autonomie suffisent pour faire de lui un must have pour tous les habitués d\'iOS.', 'reference' => 'Modèle A2638 128 Go', 'constructor' => 'Apple Inc.', 'priceExcludingTaxes' => 951, 'VAT' => 208, 'stock' => 10],
            ['name' => 'Pixel 6 Pro', 'description' => 'Le Google Pixel 6 Pro est le modèle le plus abouti de la firme américaine en 2021. Bien plus qu\'un photophone premium avec ses performances de haute volée, son superbe écran et ses finitions très soignées, le flagship livre une expérience complète sur l\'OS de son constructeur.', 'reference' => 'Modèle 7015267 128 Go', 'constructor' => 'Google', 'priceExcludingTaxes' => 720, 'VAT' => 179, 'stock' => 8],
            ['name' => 'Galaxy S22 Plus', 'description' => 'Pour le Galaxy S22 et le Galaxy S22+, Samsung a repris la même philosophie que pour la génération précédente. En résulte deux terminaux assez similaires sur la forme, avec de menues variations sur la fiche technique. Côté design pour commencer, les S22 et S22+ reprennent assez largement les lignes de leur prédécesseurs : bords arrondis, écran plat doté de bordures fines et module photo vertical. Un design d’une grande sobriété, qui à très largement fait ses preuves sur le S21 et le S21+.', 'reference' => 'Modèle 5903108454872 128 Go', 'constructor' => 'Samsung', 'priceExcludingTaxes' => 687, 'VAT' => 172, 'stock' => 4],
            ['name' => 'iPhone 13', 'description' => 'Doté d\un écran OLED 6.1" ou 5.4", adapté à la 5G, ce modèle est la première gamme de prix des modèles les plus récents d\'iPhone. Puissant, d\'une fluidité sans pareille, il est équipé d\'un double appareil photo avancé, grand-angle et ultra grand-angle, qui le place parmi les plus beaux produits du marché à ce prix, sans hésitation.', 'reference' => 'Modèle A2634 128 Go', 'constructor' => 'Apple Inc.', 'priceExcludingTaxes' => 743, 'VAT' => 166, 'stock' => 5]
        ];

        for ($i = 1; $i < 5; $i++) {
            $customer = new Customer();
            $customer->setFirstName($faker->firstName());
            $customer->setLastName($faker->lastName());
            $customer->setPassword($this->userPasswordHasher->hashPassword($customer, 'password'));
            $customer->setEmail($faker->email());
            $customer->setPhoneNumber($faker->phoneNumber());
            $customer->setAddress($faker->address());
            $customer->setPostalCode($faker->postcode());
            $customer->setCity($faker->city());
            $customer->setCountry($faker->country());
            $customer->setRole(['customer']);
            $manager->persist($customer);

            for ($j = 1; $j < 3; $j++) {
                $user = new User();
                $user->setUsername($faker->userName());
                $user->setFirstName($faker->firstName());
                $user->setLastName($faker->lastName());
                $user->setPassword($this->userPasswordHasher->hashPassword($user, 'password'));
                $user->setEmail($faker->email());
                $user->setCustomer($customer);

                $manager->persist($user);
            }
        }

        foreach ($products as $productArray) {
            $product = new Product();
            $product->setName($productArray['name']);
            $product->setDescription($productArray['description']);
            $product->setReference($productArray['reference']);
            $product->setConstructor($productArray['constructor']);
            $product->setPriceExcludingTaxes($productArray['priceExcludingTaxes']);
            $product->setVAT($productArray['VAT']);
            $product->setStock($productArray['stock']);

            $manager->persist($product);
        }


        $manager->flush();
    }
}
