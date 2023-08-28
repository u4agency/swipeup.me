<?php

namespace App\Form;

use App\Entity\Swipe;
use App\Entity\SwipeImage;
use App\Entity\Widget;
use App\Entity\WidgetData;
use App\Entity\WidgetSwipe;
use App\Form\Widgets\ButtonWidgetType;
use App\Form\Widgets\TextWidgetType;
use App\Repository\WidgetRepository;
use MarcW\Heroicons\Heroicons;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichFileType;

class SwipeSectionType extends AbstractType
{
    public function __construct(
        private readonly WidgetRepository $widgetRepository,
        private readonly Security         $security,
        private readonly array            $widgetFields = ['widgetBody', 'widgetFooter'],
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Swipe|null $swipe */
        $swipe = $options['data'] ?? null;
        $isEdit = $swipe && $swipe->getId();

        $acceptedMimeTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'video/webm'
        ];

        $builder
            ->add('background', FileType::class, [
                'mapped' => false,
                'label' => "Fond de la section",
                'required' => !$isEdit,
                'attr' => [
                    'class' => 'hidden',
                    'accept' => implode(", ", $acceptedMimeTypes),
                ],
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['image/webp'],
                        'mimeTypesMessage' => "Le fichier envoyé n'est pas valide",
                        'maxHeight' => 1920,
                        'maxWidth' => 1080,
                        'minRatio' => 9 / 16,
                        'maxRatio' => 9 / 16,
                    ])
                ],
            ])
            ->add('widgetBody', EntityType::class, [
                'required' => false,
                'mapped' => false,
                'expanded' => true,
                'class' => Widget::class,
                'placeholder' => $this->getIcon('x-mark'),
                'label_html' => true,
                'choice_label' => fn(Widget $widget) => $this->getIcon($widget->getIcon()),
                'choices' => $this->widgetRepository->findByDisplay("widgetBody"),
                'data' => $isEdit && $swipe->getWidgetBody() ? $swipe->getWidgetBody()->getWidget() : null,
                'attr' => [
                    'class' => "flex flex-row gap-y-4",
                ],
            ])
            ->add('widgetFooter', EntityType::class, [
                'required' => false,
                'mapped' => false,
                'expanded' => true,
                'class' => Widget::class,
                'placeholder' => $this->getIcon('x-mark'),
                'label_html' => true,
                'choice_label' => fn(Widget $widget) => $this->getIcon($widget->getIcon()),
                'choices' => $this->widgetRepository->findByDisplay("widgetFooter"),
                'data' => $isEdit && $swipe->getWidgetFooter() ? $swipe->getWidgetFooter()->getWidget() : null,
                'attr' => [
                    'class' => "flex flex-row gap-y-4",
                ],
            ]);


        $builder
            ->addEventListener(
                FormEvents::POST_SET_DATA,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    $data = $event->getData();

                    if (!$data) {
                        return;
                    }

                    foreach ($this->widgetFields as $widgetType) {
                        $widgetData = [];

                        if ($form->getConfig()->getOption('data') && $form->getConfig()->getOption('data')->getId()) {
                            $getter = 'get' . ucfirst($widgetType);
                            foreach ($form->getConfig()->getOption('data')->$getter()->getWidgetData() as $dataValue) {
                                $widgetData[$dataValue->getDataName()] = $dataValue->getDataValue();
                            }
                        }

                        $this->setupSpecificWidgetField($form, $widgetType, $form->getConfig()->getOption($widgetType . 'Value'), $widgetData);
                    }
                })
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    $data = $event->getData();
                    $form = $event->getForm();

                    if (!$data) {
                        return;
                    }

                    /**
                     * Background
                     */
                    if (isset($data['background']) && !empty($data['background'])) {
                        $swipeImage = new SwipeImage();
                        $swipeImage->setAuthor($this->security->getUser());
                        $swipeImage->setBackgroundName($data['background']);
                        $swipeImage->setBackgroundFile($data['background']);
                        $swipeImage->setAlt("A Swipe background");

                        $form->getData()->setBackground($swipeImage);

                        $data['background'] = $swipeImage->getBackgroundFile();
                        $event->setData($data);
                    }


                    foreach ($this->widgetFields as $widgetType) {
                        $this->setupSpecificWidgetField($form, $widgetType, $form->getConfig()->getOption($widgetType . 'Value'));
                    }
                })
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $data = $event->getData();
                    $form = $event->getForm();

                    foreach ($this->widgetFields as $field) {
                        $widgetType = $form->get($field)->getData();

                        if ($widgetType instanceof Widget) {
                            $widgetSwipe = new WidgetSwipe();
                            $widgetSwipe->setWidget($widgetType);

                            $widgetsData = $form->getExtraData()[$field . 'Data'] ?? $form->get($field . 'Data')->getData();

                            foreach ($widgetsData as $dataName => $dataValue) {
                                $widgetData = new WidgetData();

                                $widgetData->setWidget($widgetType);
                                $widgetData->setDataName($dataName);
                                $widgetData->setDataValue($dataValue);
                                $widgetSwipe->addWidgetData($widgetData);
                            }

                            $setter = 'set' . ucfirst($field);
                            $data->$setter($widgetSwipe);
                        }
                    }
                });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Swipe::class,
            'allow_extra_fields' => true,
            'widgetBodyValue' => null,
            'widgetFooterValue' => null,
        ]);
    }

    private function getIcon($icon): false|array|string
    {
        return Heroicons::get($icon, 'solid', [
            'class' => 'hover:text-swipe-800 w-6 h-6 fill-current cursor-pointer',
        ]);
    }

    private function setupSpecificWidgetField(FormInterface $form, ?string $widgetType, ?string $option, ?array $autocomplete_data = null)
    {
        if ($widgetType === null) {
            return;
        }

        $widgetValue = $form->get($widgetType)->getData()?->getName() ?? $option;

        if ($widgetValue === 'text') {
            $form->add($widgetType . 'Data', TextWidgetType::class, [
                'mapped' => false,
                'autocomplete_data' => $autocomplete_data ?? [],
            ]);
        } elseif ($widgetValue === 'button') {
            $form->add($widgetType . 'Data', ButtonWidgetType::class, [
                'mapped' => false,
                'autocomplete_data' => $autocomplete_data ?? [],
            ]);
        }
    }
}
