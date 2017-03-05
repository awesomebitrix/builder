<?php namespace Kitrix\Builder;

use Bitrix\Main\Result;
use Kitrix\Builder\Fields\IconPicker;
use Kitrix\Config\Admin\FormHelper;
use Kitrix\Config\ConfRegistry;
use Kitrix\Config\Fields\Checkbox;
use Kitrix\Config\Fields\Input;
use Kitrix\Config\Fields\Select;
use Kitrix\Config\Fields\Textarea;
use Kitrix\MVC\Admin\Controller;
use Kitrix\MVC\Router;

class generateController extends Controller
{
    const M_VENDOR_NAME = "vendor_name";
    const M_PLUGIN_NAME = "plugin_name";

    const V_TITLE = "conf_name";
    const V_DESC = "conf_desc";
    const V_ICON = "conf_icon";
    const V_LICENSE = "conf_license";

    const V_AUTHOR_NAME = "conf_author_name";
    const V_AUTHOR_EMAIL = "conf_author_email";

    public function generate()
    {
        $tabs = $this->prepareFormTabs();

        // process save
        // ---------------
        if ($_REQUEST['save'])
        {
            $status = $this->validateForm($_REQUEST);

            if (!$status->isSuccess()) {
                $this->set('error', new \CAdminMessage($status->getErrorMessages()));
            }
        }

        // render
        // ---------------

        $this->set('tabs', $tabs);
        $this->set('post_url', Router::getInstance()->generateLinkTo('kitrix_builder_generate'));
    }

    private function prepareFormTabs()
    {
        // Namespace && code
        // =======================
        $tabMain = [
            ConfRegistry::makeField(Input::class, 'vendor_name')
                ->setTitle("Префикс разработчика (vendor name):")
                ->setDefaultValue("")
                ->setHelpText("
                    Ваш плагин будет доступен под этим неймспейсом.<br>
                    К примеру для префикса <b>kitrix</b>, неймспейс будет таким: <b>Kitrix</b>.
                    Для <b>universe_acme</b> - <b>UniverseAcme</b>
                "),

            ConfRegistry::makeField(Input::class, 'plugin_name')
                ->setTitle("Код плагина (plugin name):")
                ->setDefaultValue("")
                ->setHelpText("
                    Префикс и код вместе образуют полное название плагина,
                    к примеру <b>kitrix</b> + <b>core</b> = \\Kitrix\\Core,
                "),

            ConfRegistry::makeField(Input::class, 'ktrx-gen-plug-name')
                ->setTitle("Класс плагина который будет создан:")
                ->setReadonly(true)
                ->setDefaultValue("..укажите префикс и название..")
        ];

        // Visual
        // =======================

        $tabVisual = [
            ConfRegistry::makeField(Input::class, 'conf_name')
                ->setTitle("Название:")
                ->setDefaultValue(""),

            ConfRegistry::makeField(Textarea::class, 'conf_desc')
                ->setTitle("Короткое описание (1-2 предложения):")
                ->setDefaultValue(""),

            ConfRegistry::makeField(IconPicker::class, 'conf_icon')
                ->setTitle("Иконка:")
                ->setDefaultValue("fa-cube"),

            ConfRegistry::makeField(Select::class, 'conf_license')
                ->setTitle("Лицензия:")
                ->setOptions([
                    'MIT' => 'MIT (свободная)',
                    'Apache-2.0' => 'Apache (2.0)',
                    'GPL-3.0+' => 'GPL (3.0+)',
                    'LGPL-3.0+' => 'LGPL (3.0+)',
                    'Commercial' => 'Комерческая (закрытая)',
                ])
                ->setDefaultValue("MIT"),
        ];

        // Author
        // =======================

        $tabAuthor = [
            ConfRegistry::makeField(Input::class, 'conf_author_name')
                ->setTitle("Автор:")
                ->setDefaultValue(""),

            ConfRegistry::makeField(Input::class, 'conf_author_email')
                ->setTitle("Email:")
                ->setDefaultValue(""),

            ConfRegistry::makeField(Input::class, 'conf_author_type')
                ->setTitle("")
                ->setDefaultValue("")
                ->setReadonly(true)
                ->setHelpText("
                    Добавить больше авторов, или установить доп. поля
                    вы сможете вручную через файл composer.json.
                    Описание файла можно посмотреть тут:
                    
                    <a href='https://getcomposer.org/doc/04-schema.md#authors'>
                        https://getcomposer.org/doc/04-schema.md#authors
                    </a>
                "),
        ];

        // Generator Settings
        // =======================

        $tabGenerators = [
            ConfRegistry::makeField(Checkbox::class, 'generator_base')
                ->setTitle("Базовая конфигурация:")
                ->setHelpText("
                    Генерирует основную структуру плагина, и минимальную
                    чистую кодовую базу
                ")
                ->setDefaultValue(true)
                ->setDisabled(true),

            ConfRegistry::makeField(Checkbox::class, 'generator_mvc')
                ->setTitle("+ Пример MVC, routing:")
                ->setHelpText("
                    <ul>
                        <li>URL routing (обычный, wildcards)</li>
                        <li>Админ страницы для роутов</li>
                        <li>Передача параметров из ссылки в контроллер</li>
                        <li>Пункты в админ. меню</li>  
                        <li>Шаблонизатор, рендер данных</li>  
                        <li>Request/Response API</li> 
                    </ul>
                "),

            ConfRegistry::makeField(Checkbox::class, 'generator_assets')
                ->setTitle("+ Пример Assets (css, js):")
                ->setHelpText("
                    Создает тестовые css, js файлы и подключает к плагину. 
                    Ваши ассеты будут подгружены в админ. панеле \"Битрикс\"
                "),

            ConfRegistry::makeField(Checkbox::class, 'generator_doc')
                ->setTitle("+ Пример документации к плагину:")
                ->setHelpText("
                    Создаст README.md файл, который будет стандартным
                    файлом описания плагина в любой системе контроля
                    версий (github, bitbucket, ..), в системе пакетов
                    (packagist), а также документацию можно будет
                    посмотреть в менеджере плагинов (kitrix).
                "),

            ConfRegistry::makeField(Checkbox::class, 'generator_api')
                ->setTitle("
                    + Пример использования API других плагинов:
                ")
                ->setHelpText("
                    Как именно следует использовать открытое API сторонних
                    kitrix плагинов, на примере плагина \"Конфигуратор\",
                    добавляет свои настройки для плагина которые можно будет
                    получить через API в коде, а также создает свой собственный
                    виджет (widget field type).
                "),
        ];

        $tabs = [
            'main' => [
                'title' => 'Namespace и код',
                'widgets' => FormHelper::getWidgetsFromFields($tabMain)
            ],
            'visual' => [
                'title' => 'Описание',
                'widgets' => FormHelper::getWidgetsFromFields($tabVisual)
            ],
            'author' => [
                'title' => 'Авторство',
                'widgets' => FormHelper::getWidgetsFromFields($tabAuthor)
            ],
            'generators' => [
                'title' => 'Генераторы',
                'widgets' => FormHelper::getWidgetsFromFields($tabGenerators)
            ],
        ];

        return $tabs;
    }

    /**
     * @return Result
     */
    private function validateForm($data)
    {
        $result = new Result();

        return $result;
    }
}