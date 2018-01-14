<?php

/*
 * This file is part of the Kimai package.
 *
 * (c) Kevin Papst <kevin@kevinpapst.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type;

use App\Entity\UserPreference;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Custom form field type to edit a user preference.
 *
 * @author Kevin Papst <kevin@kevinpapst.de>
 */
class UserPreferenceType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var UserPreference $preference */
                $preference = $event->getData();

                if ($preference instanceof UserPreference) {
                    // prevents unconfigured values from showing up in the form
                    if ($preference->getType() === null) {
                        return;
                    }

                    $event->getForm()->add('value', $preference->getType(), [
                        'label' => 'label.' . $preference->getName(),
                        'constraints' => $preference->getConstraints()
                    ]);
                }
            }
        );
        $builder->add('name', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserPreference::class,
        ));
    }
}
