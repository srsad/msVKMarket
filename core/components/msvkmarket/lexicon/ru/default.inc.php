<?php
include_once 'setting.inc.php';

$_lang['msvkmarket'] = 'msVKMarket';
$_lang['msvkmarket_menu_desc'] = 'Пример расширения для разработки.';
$_lang['msvkmarket_intro_msg'] = 'Вы можете выделять сразу несколько предметов при помощи Shift или Ctrl.';

$_lang['msvkmarket_success'] = 'Успешно';
$_lang['msvkmarket_items'] = 'Предметы';
$_lang['msvkmarket_item_id'] = 'Id';
$_lang['msvkmarket_item_name'] = 'Название';
$_lang['msvkmarket_item_description'] = 'Описание';
$_lang['msvkmarket_item_active'] = 'Активно';
$_lang['msvkmarket_item_detailed_import'] = 'Подробная синхронизация';
$_lang['msvkmarket_item_fast_import'] = 'Быстрая синхронизация';
$_lang['msvkmarket_items_import_start'] = 'Импорт начат';
$_lang['msvkmarket_items_import_end'] = 'Импорт завершен';

$_lang['msvkmarke_process_log'] = '[[+step]]/[[+count]]  [[[+left]]] - ([[+id]]) | [[+action]] [[+pagetitle]]';
$_lang['msvkmarket_items_import_add'] = '<i class="icon icon-plus" title="Добавлен"></i>';
$_lang['msvkmarket_items_import_upd'] = '<i class="icon icon-refresh" title="Обновлен"></i>';
$_lang['msvkmarket_items_import_skp'] = '<i class="icon icon-low-vision red" title="Позиция пропущенна, см. опция msvkm_default_ststus"></i>';

$_lang['msvkmarket_export'] = 'Экспорт';
$_lang['msvkmarket_groups'] = 'Группы';
$_lang['msvkmarket_compilation'] = 'Подборки';

$_lang['msvkmarket_compilation'] = 'Подборки';

$_lang['msvkmarket_tree_update'] = 'Обновить';
$_lang['msvkmarket_item_export'] = 'Экспорт';
$_lang['msvkmarket_tree_expand'] = 'Раскрыть';
$_lang['msvkmarket_tree_collapse'] = 'Скрыть';
$_lang['msvkmarket_tree_select_all'] = 'Выбрать всё';
$_lang['msvkmarket_tree_clear_all'] = 'Отчистить всё';


$_lang['msvkmarket_item_create'] = 'Создать предмет';

$_lang['msvkmarket_item_grid_name'] = 'Имя';
$_lang['msvkmarket_item_grid_img'] = 'Картинка';
$_lang['msvkmarket_item_grid_status'] = 'Статус';
$_lang['msvkmarket_item_grid_public'] = 'Публикация';
$_lang['msvkmarket_item_grid_date'] = 'Дата';



$_lang['msvkmarket_group_intro_msg'] = 'Настройки групп';
$_lang['msvkmarket_group_create'] = 'Добавить группу';
$_lang['msvkmarket_group_name'] = 'Имя группы';
$_lang['msvkmarket_group_id'] = 'id группы';
$_lang['msvkmarket_group_app_id'] = 'app id';
$_lang['msvkmarket_group_skey'] = 'Секретный ключ';
$_lang['msvkmarket_group_token'] = 'Токен';
$_lang['msvkmarket_group_select'] = 'Выберите группу';

$_lang['msvkmarket_group_err_name'] = 'Укажите имя группы.';
$_lang['msvkmarket_group_err_not_id'] = 'Не найдена группа в данным id.';
$_lang['msvkmarket_group_err_group_id'] = 'Укажите id группы.';
$_lang['msvkmarket_group_err_app_id'] = 'Укажите app_id группы.';
$_lang['msvkmarket_group_err_secretkey'] = 'Укажите секретный ключь.';
$_lang['msvkmarket_group_err_token'] = 'Введите токен.';
$_lang['msvkmarket_group_err_ae'] = 'Элеммент с таким параметром уже существует.';

$_lang['msvkmarket_manager_on'] = 'Включен';
$_lang['msvkmarket_status'] = 'Статус';

$_lang['msvkmarket_item_update'] = 'Изменить';
$_lang['msvkmarket_item_enable'] = 'Включить';
$_lang['msvkmarket_items_enable'] = 'Включить';
$_lang['msvkmarket_item_disable'] = 'Отключить';
$_lang['msvkmarket_items_disable'] = 'Отключить';
$_lang['msvkmarket_item_remove'] = 'Удалить';
$_lang['msvkmarket_items_remove'] = 'Удалить';
$_lang['msvkmarket_item_remove_confirm'] = 'Вы уверены, что хотите удалить?';
$_lang['msvkmarket_items_remove_confirm'] = 'Вы уверены, что хотите удалить?';
$_lang['msvkmarket_item_active'] = 'Включено';

$_lang['msvkmarket_item_err_name'] = 'Вы должны указать имя Предмета.';
$_lang['msvkmarket_item_err_ae'] = 'Предмет с таким именем уже существует.';

$_lang['msvkmarket_item_err_nf'] = 'Элемент не найден.';
$_lang['msvkmarket_item_err_ns'] = 'Элемент не указан.';

$_lang['msvkmarket_group_err_nf'] = 'Группа не найдена.';
$_lang['msvkmarket_group_err_ns'] = 'Группа не указана.';

$_lang['msvkmarket_item_err_remove'] = 'Ошибка при удалении Предмета.';
$_lang['msvkmarket_item_err_save'] = 'Ошибка при сохранении Предмета.';

$_lang['msvkmarket_grid_search'] = 'Поиск';
$_lang['msvkmarket_grid_actions'] = 'Действия';

$_lang['msvkmarket_compilation_intro_msg'] = 'Настройка подборок групп';
$_lang['msvkmarket_compilation_create'] = 'Создать подборку';
$_lang['msvkmarket_compilation_group_name'] = 'Группа';
$_lang['msvkmarket_compilation_export'] = 'Экспорт подборок';
$_lang['msvkmarket_compilation_export_response'] = 'Группа: [[+name]] <br>
                                                    Всего подборок: [[+count]] <br>
                                                    Экспортированно подборок: [[+export]]<br>
                                                    <span class="blue">----------------------------</span><br>';

$_lang['msvkmarket_compialtion_err_name'] = 'Укажите имя подборки';
$_lang['msvkmarket_compialtion_err_ae'] = 'Такая подборка в этой группе уже есть';
$_lang['msvkmarket_compialtion_err_group_id'] = 'Укажите группу';
$_lang['msvkmarket_compilation_err_ns'] = 'Подборка не найдена.';

$_lang['msvkmarket_compilation_create_album_error'] = 'Ошибка при создании подборки.';
$_lang['msvkmarket_compilation_create_album_error_album_id'] = 'Не удалось создать подборку!';
$_lang['msvkmarket_compilation_create_album_error_log'] = 'Ошибка при создании подборки, проверте логи!';
$_lang['msvkmarket_compilation_create_album_error_name'] = 'Не указанно имя подборки или id группы.';
$_lang['msvkmarket_compilation_create_album_error_id'] = 'Не указанно id подборки или группа.';
$_lang['msvkmarket_compilation_remove_album_error'] = 'Ошибка при удалении подборки - [[+msg]]';

$_lang['msvkmarket_connect_error'] = 'Ошибка при подключении к VK, событие [[+action]]!';
$_lang['msvkmarket_export_album_error'] = 'Ошибка экспорта ';
$_lang['msvkmarket_error_response'] = 'Ошибка разбора ответа';
$_lang['msvkmarket_console_start'] = 'Консоль запущенна';
$_lang['msvkmarket_console_end'] = 'Конец';


// _el_ - error log
$_lang['msvkmarket_el_empty_item_id'] = 'Отстствует market_item_id в ответе, не удалось импортировать позицию!';




