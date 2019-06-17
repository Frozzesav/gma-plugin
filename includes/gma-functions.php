<?php

/*
 * Добавляем новое меню в Админ Консоль
 */
 
// Добавляем новую ссылку в меню Админ Консоли
add_action('admin_menu','gma_add_my_admin_link');

function gma_add_my_admin_link()
{
 add_menu_page(
 'My First Page', // Название страниц (Title)
 'Gma Plugin', // Текст ссылки в меню
 'manage_options', // Требование к возможности видеть ссылку
 plugin_dir_path(__FILE__) . '/gma-admin-page.php' // 'slug' - файл отобразится по нажатию на ссылку
 );
}
