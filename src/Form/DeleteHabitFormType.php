<?php
/**
 * Delete Habit type.
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class HabitType.
 */
class DeleteHabitFormType extends AbstractType
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('habitId', HiddenType::class,[
            'data' => $options['habitId'],
            ]
        )
        ->add(
            'delete',
            SubmitType::class,
            [
            'label' => 'Delete Habit',
            'attr' => ['class' => 'btn btn-danger'],
             ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('habitId'); // Mark 'habitId' as a required option for the form type
    }
}
