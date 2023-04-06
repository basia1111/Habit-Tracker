<?php
/**
 * Habit fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Habit;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class UserFixtures.
 */
class HabitFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }

    /**
     * Load data.
     */
    protected function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(10, 'habits', function (int $i) {
            $habit = new Habit();
            $habit->setName($this->faker->word);
            $habit->setTime('08:00:00');
            $habit->setPlace($this->faker->city);
            $user = $this->getRandomReference('users');
            $habit->setUser($user);
            $habit->setMonday(false);
            $habit->setTuesday(true);
            $habit->setWednesday(true);
            $habit->setThursday(true);
            $habit->setFriday(true);
            $habit->setSathurday(true);
            $habit->setSunday(false);
            $habit->setStreak('3');
            $habit->setBestStreak('15');

            return $habit;
        });

        $this->manager->flush();
    }
}
