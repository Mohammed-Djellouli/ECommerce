<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword',PasswordType::class,[
                'label' => 'Actual Password',
                'attr' => [
                    'placeholder' => '******',
                ],
                'mapped' => false,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 30,
                    ]),
                ],
                'first_options'  => ['label' => 'Nouveau mot de Pass', 'hash_property_path' => 'password','attr' => ['placeholder' => '************']],
                'second_options' => ['label' => 'Confirmez Votre Nouveau Mot de Pass','attr' => ['placeholder' => '************']],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-success',
                ]
            ])
            //pour verifier si le mot de pass saisie correspande au mot de pass dans la base de données
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                //recupérer le mdp saisie dans le formulaire de modification mdp
                    //récupération de données de formulaire
                    $form = $event->getForm();
                    $actualPwd = $form->get('actualPassword')->getData();

                //Récupération de Mdp actual dans la base de données
                $user = $form->getConfig()->getOptions()['data'];

                //password hasher
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];

                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $actualPwd
                );

                //si le mdp ne correspande pas afficher un message d'erreur

                if(!$isValid) {
                    $form->get('actualPassword')->addError(new FormError('Mot de Passe invalide'));
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' =>null,
        ]);
    }
}
