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
                'label' => 'label.title',
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
                'label' => 'tusday',
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
                'label' => 'sathurday',
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
                'label' => 'label.title',
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );
        $builder->add(
            'notes',
            TextType::class,
            [
                'label' => 'label.title',
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );

        $builder->add(
            'image',
            ChoiceType::class, [
            'choices' => [
                'Option 1' => '1',
                'Option 2' => '2',
                'Option 3' => '3',
                'Option 4' => '4',
                'Option 5' => '5',
                'Option 6' => '6',
                'Option 7' => '7',
                'Option 8' => '8',
                'Option 9' => '9',
                'Option 10' => '10',
                'Option 11' => '12',
                'Option 13' => '14',
                'Option 15' => '15',
                'Option 16' => '16',
                'Option 17' => '17',
                'Option 18' => '18',
                'Option 19' => '19',
                'Option 20' => '20',
                'Option 21' => '21',
                'Option 22' => '22',
                'Option 23' => '23',



            ],
            'expanded' => true,
            'multiple' => false,


        ]);
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
