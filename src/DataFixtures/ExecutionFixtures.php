<?php
/**
 * User fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Execution;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class executionsFixtures.
 */
class ExecutionFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     */
    protected function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(10, 'executions', function (int $i) {
            $executions = new Execution();
            $executions->setDate(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days')));
            $habit = $this->getRandomReference('habits');
            $executions->setHabit($habit);

            return $executions;
        });

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [HabitFixtures::class];
    }
}
