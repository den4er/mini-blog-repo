<?php
 return [
   'news/([a-z]+)' => 'news/category/$1', // NewsController метод actionCategory - новости по категории
   'news/([0-9]+)' => 'news/view/$1', // NewsController метод actionView - новость по ID
   'news' => 'news/index', // NewsController метод actionIndex - список новостей
   '' => 'index/index',
 ];