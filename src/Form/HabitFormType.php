<?php
/**
 * Hbait type.
 */

namespace App\Form;

use App\Entity\Habit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class HabitType.
 */
class HabitFormType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => ' ',
                'label_attr' => ['class' => 'image-label'],
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );
        $builder->add(
            'time',
            TimeType::class,
            [
                'input' => 'string',
                'widget' => 'choice',
                'required' => true,
            ]
        );
        $builder->add(
            'place',
            TextType::class,
            [
                'label' => ' ',
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );
        $builder->add(
            'monday',
            CheckboxType::class,
            [
            'label' => 'monday',
            'required' => false,
             ]
        );
        $builder->add(
            'tusday',
            CheckboxType::class,
            [
                'label' => 'tuesday',
                'required' => false,
            ]
        );
        $builder->add(
            'wednesday',
            CheckboxType::class,
            [
                'label' => 'wednesday',
                'required' => false,
            ]
        );
        $builder->add(
            'thursday',
            CheckboxType::class,
            [
                'label' => 'thursday',
                'required' => false,
            ]
        );
        $builder->add(
            'friday',
            CheckboxType::class,
            [
                'label' => 'friday',
                'required' => false,
            ]
        );
        $builder->add(
            'sathurday',
            CheckboxType::class,
            [
                'label' => 'saturday',
                'required' => false,
            ]
        );
        $builder->add(
            'sunday',
            CheckboxType::class,
            [
                'label' => 'sunday',
                'required' => false,
            ]
        );
        $builder->add(
            'requisites',
            TextType::class,
            [
                'label' => ' ',
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );
        $builder->add(
            'notes',
            TextType::class,
            [
                'label' => ' ',
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );

        $builder->add(
            'image',
            ChoiceType::class,
            [
            'choices' => [
                'Option 1' => '0',
                'Option 2' => '1',
                'Option 3' => '2',
                'Option 4' => '3',
                'Option 5' => '4',
                'Option 6' => '5',
                'Option 7' => '6',
                'Option 8' => '7',
                'Option 9' => '8',
                'Option 10' => '9',
                'Option 11' => '10',
                'Option 12' => '11',
                'Option 13' => '12',
                'Option 14' => '13',
                'Option 15' => '14',
                'Option 16' => '15',
                'Option 17' => '16',
                'Option 18' => '17',
                'Option 19' => '18',
                'Option 20' => '19',
                'Option 21' => '20',
                'Option 22' => '21',
                'Option 23' => '22',
                'Option 24' => '23',
                'Option 25' => '24',
                'Option 26' => '25',
                'Option 27' => '26',
            ],
            'expanded' => true,
            'multiple' => false,
        ]
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Habit::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the templat e block name
     */
    public function getBlockPrefix(): string
    {
        return 'habit';
    }
}
