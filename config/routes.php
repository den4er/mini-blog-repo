<?php

// массов с маршрутами
return array(

    'news/([a-z]+)' => 'news/categoryList/$1', // росмотр списка новостей по категории
    'news/([a-z]+)/([0-9]+)' => 'news/categoryItem/$1/$2', // просмотр одной новости по категориям

    'news/([0-9]+)' => 'news/detail/$1',         // NewsController & actionView - просмотр одной новости
    'news' => 'news/index',                 // NewsController & actionIndex - список новостей
    '' => 'index/index',                    // главная страница

);