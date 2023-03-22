<?php
/**
 * Habit fixtures.
 */

namespace App\DataFixtures;


use App\Entity\Habit;
use DateTimeImmutable;
use DateTime;

/**
 * Class UserFixtures.
 */
class HabitFixtures extends AbstractBaseFixtures
{

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
            $habit->setLastExecuted(
                DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            $habit->setStreak('0');

            return $habit;
        });


        $this->manager->flush();
    }
}
