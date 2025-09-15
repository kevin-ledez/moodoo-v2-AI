<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        // Create categories
        $categories = [];
        $categoryNames = ['Technologie', 'Marketing', 'Design', 'Business', 'Tendances', 'Innovation'];
        
        foreach ($categoryNames as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $category->setSlug(strtolower(str_replace(' ', '-', $categoryName)));
            $category->setDescription($faker->sentence(10));
            
            $manager->persist($category);
            $categories[] = $category;
        }
        
        // Create test post with image
        $testPost = new Post();
        $testPost->setTitle('Test Article');
        $testPost->setSlug('test-article');
        $testPost->setContent('This is a test article content.');
        $testPost->setExcerpt('This is a test excerpt.');
        $testPost->setFeaturedImage('test-image.jpg');
        $testPost->setStatus('published');
        $testPost->setPublishedAt(new \DateTime());
        $testPost->setCreatedAt(new \DateTime());
        $testPost->setUpdatedAt(new \DateTime());
        
        // Add one category to test post
        $testPost->addCategory($categories[0]);
        
        $manager->persist($testPost);
        
        // Create additional posts
        for ($i = 0; $i < 18; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence(6, true));
            $post->setSlug(strtolower(str_replace(' ', '-', $faker->sentence(4, true))));
            $post->setContent($faker->paragraphs(5, true));
            $post->setExcerpt($faker->sentence(15, true));
            $post->setFeaturedImage('https://picsum.photos/800/600?random=' . $i);
            $post->setStatus('published');
            
            // Set published date to a random date in the past year
            $publishedDate = $faker->dateTimeBetween('-1 year', 'now');
            $post->setPublishedAt($publishedDate);
            $post->setCreatedAt($publishedDate);
            $post->setUpdatedAt(new \DateTime());
            
            // Add random categories to post (1-3 categories)
            $numCategories = rand(1, 3);
            $selectedCategories = array_rand($categories, $numCategories);
            
            if (!is_array($selectedCategories)) {
                $selectedCategories = [$selectedCategories];
            }
            
            foreach ($selectedCategories as $categoryIndex) {
                $post->addCategory($categories[$categoryIndex]);
            }
            
            $manager->persist($post);
        }
        
        $manager->flush();
    }
}