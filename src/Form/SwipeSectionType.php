<?php

namespace App\Form;

use App\Entity\Swipe;
use App\Entity\SwipeImage;
use App\Entity\Widget;
use App\Entity\WidgetData;
use App\Entity\WidgetSwipe;
use App\Form\Widgets\Admin\ButtonWidgetType;
use App\Form\Widgets\Admin\EmailWidgetType;
use App\Form\Widgets\Admin\NewsletterWidgetType;
use App\Form\Widgets\Admin\TextWidgetType;
use App\Repository\WidgetRepository;
use App\Service\FileTypeService;
use App\Service\LamialeProcess;
use Defr\PhpMimeType\MimeType;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use MarcW\Heroicons\Heroicons;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class SwipeSectionType extends AbstractType
{
    public function __construct(
        private readonly WidgetRepository $widgetRepository,
        private readonly Security         $security,
        private readonly LamialeProcess   $lamialeProcess,
        private readonly UploaderHelper   $uploaderHelper,
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
            ...FileTypeService::$videoMimeTypes,
            ...FileTypeService::$imageMimeTypes,
        ];

        $builder
            ->add('background', FileType::class, [
                'mapped' => false,
                'label' => "Fond de la section",
                'required' => !$isEdit,
                'attr' => [
                    'class' => 'sr-only',
                    'accept' => implode(", ", $acceptedMimeTypes),
                ],
                'data' => $isEdit ? $swipe->getBackground()->getBackgroundFile() : null,
                'constraints' => [
                    new File([
                        'maxSize' => '1G',
                        'mimeTypes' => $acceptedMimeTypes,
                        'mimeTypesMessage' => "Le fichier envoyé est trop volumineux ou n'est pas un fichier vidéo ou image valide.",
                    ])
                ],
            ])
            ->add('widgetBody', EntityType::class, [
                'required' => false,
                'mapped' => false,
                'expanded' => true,
                'class' => Widget::class,
                'placeholder' => $this->getIcon('x-mark', "Supprimer le widget"),
                'label_html' => true,
                'choice_label' => fn(Widget $widget) => $this->getIcon($widget->getIcon(), $widget->getDescription(), $widget->getIcon() === "pencil-square" ? [
                    'data-introjs-target' => "element",
                    'data-title' => "Éditer le contenu",
                    'data-intro' => "Cliquez sur l'icône avec le crayon pour ajouter un texte au milieu de votre section et modifier sa couleur !",
                    'data-step' => 5,
                    'data-action' => "click->introjs#close",
                ] : null),
                'choices' => $this->widgetRepository->findByDisplay("widgetBody"),
                'data' => $isEdit && $swipe->getWidgetBody() ? $swipe->getWidgetBody()->getWidget() : null,
                'attr' => [
                    'class' => "flex flex-row overflow-x-auto pb-2",
                ],
            ])
            ->add('widgetFooter', EntityType::class, [
                'required' => false,
                'mapped' => false,
                'expanded' => true,
                'class' => Widget::class,
                'placeholder' => $this->getIcon('x-mark', "Supprimer le widget"),
                'label_html' => true,
                'choice_label' => fn(Widget $widget) => $this->getIcon($widget->getIcon(), $widget->getDescription()),
                'choices' => $this->widgetRepository->findByDisplay("widgetFooter"),
                'data' => $isEdit && $swipe->getWidgetFooter() ? $swipe->getWidgetFooter()->getWidget() : null,
                'attr' => [
                    'class' => "flex flex-row overflow-x-auto pb-2",
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
                            if ($form->getConfig()->getOption('data')->$getter()) {
                                foreach ($form->getConfig()->getOption('data')->$getter()->getWidgetData() as $dataValue) {
                                    $widgetData[$dataValue->getDataName()] = $dataValue->getDataValue();
                                }
                            }
                        }

                        $this->setupSpecificWidgetField($form, $widgetType, $form->getConfig()->getOption($widgetType . 'Value'), $widgetData);
                    }
                })
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    // Récupération des données soumises et du formulaire
                    $data = $event->getData();
                    $form = $event->getForm();
                    $formData = $form->getData();

                    // Si aucune donnée n'est soumise, on ne fait rien
                    if (!$data) {
                        return;
                    }

                    /**
                     * Traitement du champ 'background' (fond)
                     */
                    if (!empty($data['background'])) {
                        // Récupération ou création de l'entité SwipeImage
                        $swipeImage = $formData->getBackground() ?? new SwipeImage();

                        // Traitement du fichier soumis
                        if (in_array(MimeType::get($data['background']->getClientOriginalName()), FileTypeService::$imageMimeTypes)) {
                            $swipeImage->setBackgroundName($data['background']); // Nom du fond
                            $swipeImage->setBackgroundFile($data['background']); // Nom du fond
                        } else if (in_array(MimeType::get($data['background']->getClientOriginalName()), FileTypeService::$videoMimeTypes)) {
                            $videoProcess = $this->lamialeProcess->get($data['background']);
                            if ($videoProcess instanceof Exception || $videoProcess instanceof GuzzleException || $videoProcess === null) {
                                $swipeImage->setBackgroundName(null); // Nom du fond
                                $swipeImage->setThumbnailName(null); // Nom du fond
                            } else {
                                $swipeImage->setBackgroundName($videoProcess['background']); // Nom du fond
                                $swipeImage->setThumbnailName($videoProcess['thumbnail']); // Nom du fond
                            }
                        } else {
                            return;
                        }

                        // Configuration de l'entité SwipeImage avec les données soumises
                        $swipeImage->setAuthor($this->security->getUser()); // Définition de l'auteur
                        $swipeImage->setAlt("A Swipe background"); // Texte alternatif pour l'image
                        // Mise à jour de l'entité principale avec le SwipeImage modifié
                        $form->getData()->setBackground($swipeImage);

                        // Mise à jour des données du formulaire avec le fichier modifié
                        $data['background'] = $swipeImage->getBackgroundFile();
                        $event->setData($data);
                    }

                    // Boucle sur chaque champ de widget spécifique
                    foreach ($this->widgetFields as $widgetType) {
                        // Configuration des champs de widget spécifiques
                        $this->setupSpecificWidgetField($form, $widgetType, $form->getConfig()->getOption($widgetType . 'Value'));
                    }
                })
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $data = $event->getData();
                    $form = $event->getForm();
                    $formData = $form->getData();

                    foreach ($this->widgetFields as $field) {
                        $wsGetter = 'get' . ucfirst($field);
                        $wsSetter = 'set' . ucfirst($field);
                        $widgetType = $form->get($field)->getData();

                        if ($widgetType instanceof Widget) {
                            $widgetSwipe = $formData->$wsGetter() ?? new WidgetSwipe();
                            $widgetSwipe->setWidget($widgetType);
                            if ($field === 'widgetBody') {
                                $widgetSwipe->setSwipeBody($data);
                            } elseif ($field === 'widgetFooter') {
                                $widgetSwipe->setSwipeFooter($data);
                            }

                            $widgetsData = $form->getExtraData()[$field . 'Data'] ?? $form->get($field . 'Data')->getData();

                            foreach ($widgetsData as $dataName => $dataValue) {
                                if ($dataValue === null) {
                                    continue;
                                }

                                if ($formData->$wsGetter()) {
                                    $widgetData = $formData->$wsGetter()->getSingleWidgetData($dataName);
                                } else {
                                    $widgetData = new WidgetData();
                                }

                                $widgetData->setWidget($widgetType);
                                $widgetData->setDataName($dataName);
                                $widgetData->setDataValue($dataValue);
                                $widgetSwipe->addWidgetData($widgetData);
                            }

                            $data->$wsSetter($widgetSwipe);
                        } else {
                            $formData->$wsSetter(null);
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

    private function getIcon($icon, $content, array $classes = []): false|array|string
    {
        $tippy = !empty($content) ? 'data-controller="tippy" data-tippy-text-value="' . $content . '"' : null;

        return '<div class="mr-1 p-7 rounded-xl border border-gray-400 cursor-pointer text-gray-800 hover:text-swipe-800" ' . $tippy . '>' .
            Heroicons::get($icon, 'solid', [
                'class' => 'w-6 h-6 fill-current',
                ...$classes,
            ])
            . '</div>';
    }

    private function setupSpecificWidgetField(FormInterface $form, ?string $widgetType, ?string $option, ?array $autocomplete_data = null): void
    {
        if ($widgetType === null) {
            return;
        }

        $widgetValue = $form->get($widgetType)->getData()?->getName() ?? $option;
        $widgetTypes = [
            'text' => TextWidgetType::class,
            'button' => ButtonWidgetType::class,
            'email' => EmailWidgetType::class,
            'newsletter' => NewsletterWidgetType::class,
        ];

        if (array_key_exists($widgetValue, $widgetTypes)) {
            $form->add($widgetType . 'Data', $widgetTypes[$widgetValue], [
                'mapped' => false,
                'autocomplete_data' => $autocomplete_data ?? [],
                'allow_extra_fields' => true,
            ]);
        }
    }
}
