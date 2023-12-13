<?php

/*
 * Plugin Name: HTDEV test task custom post type
 * Description: Регистрирует свой тип записи 'Books'
 * Author:      Marat Zalyalov
 * Version:     0.1
 *
 * Text Domain: htdev-custom-post
 * Domain Path: /languages
 *
 * License:     GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 */


if( !function_exists( 'htdev_cpt_activate' ) && !function_exists( 'htdev_cpt_deactivate' ) && !function_exists( 'htdev_cpt_uninstall' ) ) {
    function htdev_cpt_activate() {
        htdev_cpt_init();
        flush_rewrite_rules();
    }

    function htdev_cpt_deactivate() {
        flush_rewrite_rules();
    }

    function htdev_cpt_uninstall() {
        unregister_taxonomy( 'books_author' );
        unregister_taxonomy( 'books_genre' );
        unregister_post_type( 'books' );
        // по-хорошему нужно удалить записи из БД - в проде так и сделаем
        // вывести алерт и чекбокс, а уже потом или удалять или нет
    }

    register_activation_hook( __FILE__, 'htdev_cpt_activate' );
    register_deactivation_hook( __FILE__, 'htdev_cpt_deactivate' );
    register_uninstall_hook( __FILE__, 'htdev_cpt_uninstall' );
} else {
    echo "Plugin 'HTDEV custom post' init functions conflict with other plugins!";
    die;
}


if( !function_exists( 'htdev_cpt_init' ) ) {
    add_action( 'init', 'htdev_cpt_init' );
    function htdev_cpt_init() {
        $labels = array(
            'name' => 'Книги',
            'singular_name' => 'Книга',
            'add_new' => 'Добавить книгу',
            'add_new_item' => 'Добавить книгу',
            'edit_item' => 'Редактировать книгу',
            'new_item' => 'Новая книга',
            'all_items' => 'Все книги',
            'search_items' => 'Искать книги',
            'not_found' =>  'Книг по заданным критериям не найдено.',
            'not_found_in_trash' => 'В корзине нет книг.',
            'menu_name' => 'Книги'
        );
        $args = array(
            'labels' => $labels,
            'description' => 'Book custom post',
            'public' => true,
            'rewrite' => array( 'slug' => 'books' ),
            'publicly_queryable' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-book',
            'menu_position' => 5,
            'supports' => array( 'title', 'editor' ),
            'show_in_rest' => true
        );
        register_post_type( 'books', $args );

        // Регистрируем таксономию 'Автор'
        register_taxonomy( 'books_author', 'books', array(
            'label' => 'Автор',
            'hierarchical' => false,
            'rewrite' => array( 'slug' => 'author' ) )
        );

        // Регистрируем таксономию 'Жанр'
        register_taxonomy( 'books_genre', 'books', array(
            'label' => 'Жанр',
            'hierarchical' => true,
            'rewrite' => array( 'slug' => 'genre' ) )
        );
    }
}