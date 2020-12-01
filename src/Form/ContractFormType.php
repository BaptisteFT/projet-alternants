<?php


namespace App\Form;



use App\Entity\Contract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('socialReason' ,TextType::class)
            ->add('siretNumber',TextType::class)
            ->add('activity',TextType::class)

            ->add('locationStreet',TextType::class)
            ->add('locationCity',TextType::class)
            ->add('postalCode',NumberType::class)

            ->add('phoneNumber',NumberType::class)
            ->add('contractEmail',EmailType::class)

            ->add('representativeCivility',ChoiceType::class, [
                'choices'  => [
                    'Madame' => true,
                    'Monsieur' => false,

                ],
                'required' => true,
            ])
            ->add('representativeFirstName',TextType::class)
            ->add('representativeLastName',TextType::class)
            ->add('representativeRole',TextType::class)
            ->add('representativeEmail',EmailType::class)

            ->add('otherSocialReason',TextType::class)
            ->add('otherLocationStreet',TextType::class)
            ->add('otherLocationCity',TextType::class)
            ->add('otherPostalCode',NumberType::class)
            ->add('otherPhoneNumber',NumberType::class)

            ->add('workerRole',TextType::class)
            ->add('contractType',TextType::class)
            ->add('contractStartDate',DateTimeType::class)
            ->add('contractEndDate',DateTimeType::class)

            ->add('tutorCivility',ChoiceType::class, [
                'choices'  => [
                    'Madame' => true,
                    'Monsieur' => false,

                ],
                'required' => true,
            ])
            ->add('tutorFirstName',TextType::class)
            ->add('tutorLastName',TextType::class)
            ->add('tutorRole',TextType::class)
            ->add('tutorPhoneNumber',NumberType::class)
            ->add('tutorEmail',EmailType::class)
            ;



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contract::class,
        ]);

    }
}