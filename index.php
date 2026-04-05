<?php

//phpinfo(); die();

require("config.php");

try {
    initApplication();
} catch (Exception $e) { 
    $results['errorMessage'] = $e->getMessage();
    require(CmsConfig::$template_path . "/viewErrorPage.php");
}


function initApplication()
{
    $action = isset($_GET['action']) ? $_GET['action'] : "";

    switch ($action) {
        case 'archive':
          archive();
          break;
        case 'viewArticle':
          viewArticle();
          break;
        default:
          homepage();
    }
}

function archive() 
{
    $results = [];
    
    $categoryId = ( isset( $_GET['categoryId'] ) && $_GET['categoryId'] ) ? (int)$_GET['categoryId'] : null;
    
    $results['category'] = Category::getById( $categoryId );
    
    $data = Article::getList( 100000, $results['category'] ? $results['category']->id : null );
    
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    
    $data = Category::getList();
    $results['categories'] = array();
    
    foreach ( $data['results'] as $category ) {
        $results['categories'][$category->id] = $category;
    }
    
    $results['pageHeading'] = $results['category'] ?  $results['category']->name : "Article Archive";
    $results['pageTitle'] = $results['pageHeading'] . " | Widget News";
    
    require( CmsConfig::$template_path . "/archive.php" );
}

/**
 * Загрузка страницы с конкретной статьёй
 * 
 * @return null
 */
function viewArticle() 
{   
    if ( !isset($_GET["articleId"]) || !$_GET["articleId"] ) {
      homepage();
      return;
    }

    $results = array();
    $articleId = (int)$_GET["articleId"];
    $results['article'] = Article::getById($articleId);
    
    if (!$results['article']) {
        throw new Exception("Статья с id = $articleId не найдена");
    }
    
    $results['category'] = Category::getById($results['article']->categoryId);
    $results['pageTitle'] = $results['article']->title . " | Простая CMS";
    
    require(CmsConfig::$template_path . "/viewArticle.php");
}

/**
 * Вывод домашней ("главной") страницы сайта
 */
function homepage() 
{
    $results = array(); // $const->homepage_num_articles
    $data = Article::getList(CmsConfig::$homepage_num_articles);
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    
    $data = Category::getList();
    $results['categories'] = array();
    
//    echo "<pre>";
//    print_r($data);
//    echo "</pre>";
//    die();
    
    require(CmsConfig::$template_path . "/homepage.php");
    
}
