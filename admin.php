<?php

require("config.php");
session_start();
$action = isset($_GET['action']) ? $_GET['action'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

if ($action != "login" && $action != "logout" && !$username) {
    login();
    exit;
}

switch ($action) {
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;
    case 'newArticle':
        newArticle();
        break;
    case 'editArticle':
        editArticle();
        break;
    case 'deleteArticle':
        deleteArticle();
        break;
    case 'listCategories':
        listCategories();
        break;
    case 'newCategory':
        newCategory();
        break;
    case 'editCategory':
        editCategory();
        break;
    case 'deleteCategory':
        deleteCategory();
        break;
    case 'listUsers':
        listUsers();
        break;
    case 'newUser':
        newUser();
        break;
    case 'editUser':
        editUser();
        break;
    case 'deleteUser':
        deleteUser();
        break;
    case 'listSubcategories':
        listSubcategories();
        break;
    case 'newSubcategory':
        newSubcategory();
        break;
    case 'editSubcategory':
        editSubcategory();
        break;
    case 'deleteSubcategory':
        deleteSubcategory();
        break;
    default:
        listArticles();
}

/**
 * Авторизация пользователя (админа) -- установка значения в сессию
 */
function login() {

    $results = array();
    $results['pageTitle'] = "Admin Login | Widget News";

    if (isset($_POST['login'])) {

        // Пользователь получает форму входа: попытка авторизировать пользователя

        if ($_POST['username'] == ADMIN_USERNAME 
                && $_POST['password'] == ADMIN_PASSWORD) {

          // Вход прошел успешно: создаем сессию и перенаправляем на страницу администратора
          $_SESSION['username'] = ADMIN_USERNAME;
          header( "Location: admin.php");

        }elseif($active = testMatches($_POST)){
            if($active == 'active'){
                $_SESSION['username'] = $_POST['username'];
                header("Location: admin.php");
            }else {
                 $results['errorMessage'] = "Ваша учетная запись не активна ";
                 require(TEMPLATE_PATH . "/admin/loginForm.php");
            }
        } else {

          // Ошибка входа: выводим сообщение об ошибке для пользователя
          $results['errorMessage'] = "Неправильный пароль, попробуй ещё раз.";
          require( TEMPLATE_PATH . "/admin/loginForm.php" );
        }

    } else {

      // Пользователь еще не получил форму: выводим форму
      require(TEMPLATE_PATH . "/admin/loginForm.php");
    }

}


function logout() {
    unset( $_SESSION['username'] );
    header( "Location: admin.php" );
}


function newArticle() {

    $results = array();
    $results['pageTitle'] = "New Article";
    $results['formAction'] = "newArticle";

    if (isset($_POST['saveChanges'])) {
        if ($_POST['subcategoryId'] != null) {
            $category = Subcategory::getById($_POST['subcategoryId'])->category;
            if ($category != $_POST['categoryId']) {
                $results['errorMessage'] = "Данная подкатегория не принадлежит выбранной категории";
                $data = Category::getList();
                $results['categories'] = $data['results'];
                $inf = Subcategory::getList();
                $results['subcategories'] = $inf['results'];
                require(TEMPLATE_PATH . "/admin/editArticle.php");
            }
        } else {
            // Сохраняем новую статью
            $article = new Article();
            $article->storeFormValues($_POST);
            $article->insert();
            header("Location: admin.php?status=changesSaved");
            return;  // Прерываем выполнение, чтобы не было дальнейшего кода
        }
    } elseif (isset($_POST['cancel'])) {
        // Пользователь сбросил результаты редактирования
        header("Location: admin.php");
        return;
    } else {
        // Пользователь еще не получил форму редактирования
        $results['article'] = new Article;
        $$data = Category::getList();
        $results['categories'] = $data['results'];
        $inf = Subcategory::getList();
        $results['subcategories'] = $inf['results'];   
        require(TEMPLATE_PATH . "/admin/editArticle.php");
    }
}

function getCategoriesAndSubcategories() {
    $data = Category::getList();
    $results['categories'] = $data['results'];
    $inf = Subcategory::getList();
    $results['subcategories'] = $inf['results'];
    return $results;
}



/**
 * Редактирование статьи
 * 
 * @return null
 */
function editArticle() {
	  
    $results = array();
    $results['pageTitle'] = "Edit Article";
    $results['formAction'] = "editArticle";

    if (isset($_POST['saveChanges'])) {

        // Пользователь получил форму редактирования статьи: сохраняем изменения
        if ( !$article = Article::getById( (int)$_POST['articleId'] ) ) {
            header( "Location: admin.php?error=articleNotFound" );
            return;
        }

        $article->storeFormValues( $_POST );
        $article->update();
        header( "Location: admin.php?status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // Пользователь отказался от результатов редактирования: возвращаемся к списку статей
        header( "Location: admin.php" );
    } else {

        // Пользвоатель еще не получил форму редактирования: выводим форму
        $results['article'] = Article::getById((int)$_GET['articleId']);
        $data = Category::getList();
        $results['categories'] = $data['results'];
        $inf = Subcategory::getList();
        $results['subcategories'] = $inf['results']; 
        require(TEMPLATE_PATH . "/admin/editArticle.php");
    }

}


function deleteArticle() {

    if ( !$article = Article::getById( (int)$_GET['articleId'] ) ) {
        header( "Location: admin.php?error=articleNotFound" );
        return;
    }

    $article->delete();
    header( "Location: admin.php?status=articleDeleted" );
}


function listArticles() {
    $results = array();
    
    $data = Article::getList();
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    
    $data = Category::getList();
    $results['categories'] = array();
    foreach ($data['results'] as $category) { 
        $results['categories'][$category->id] = $category;
    
    }
    $inf = Subcategory::getList();
    $results['subcategories'] = array();
    foreach ($inf['results'] as $subcategory)
                     $results['subcategories'][$subcategory->id] = $subcategory;
    
    $results['pageTitle'] = "Все статьи";

    if (isset($_GET['error'])) { // вывод сообщения об ошибке (если есть)
        if ($_GET['error'] == "articleNotFound") 
            $results['errorMessage'] = "Error: Article not found.";
    }

    if (isset($_GET['status'])) { // вывод сообщения (если есть)
        if ($_GET['status'] == "changesSaved") {
            $results['statusMessage'] = "Your changes have been saved.";
        }
        if ($_GET['status'] == "articleDeleted")  {
            $results['statusMessage'] = "Article deleted.";
        }
    }

    require(TEMPLATE_PATH . "/admin/listArticles.php" );
}

function listCategories() {
    $results = array();
    $data = Category::getList();
    $results['categories'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = "Article Categories";

    if ( isset( $_GET['error'] ) ) {
        if ( $_GET['error'] == "categoryNotFound" ) $results['errorMessage'] = "Error: Category not found.";
        if ( $_GET['error'] == "categoryContainsArticles" ) $results['errorMessage'] = "Error: Category contains articles. Delete the articles, or assign them to another category, before deleting this category.";
    }

    if ( isset( $_GET['status'] ) ) {
        if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
        if ( $_GET['status'] == "categoryDeleted" ) $results['statusMessage'] = "Category deleted.";
    }

    require( TEMPLATE_PATH . "/admin/listCategories.php" );
}
	  
	  
function newCategory() {

    $results = array();
    $results['pageTitle'] = "New Article Category";
    $results['formAction'] = "newCategory";

    if ( isset( $_POST['saveChanges'] ) ) {

        // User has posted the category edit form: save the new category
        $category = new Category;
        $category->storeFormValues( $_POST );
        $category->insert();
        header( "Location: admin.php?action=listCategories&status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // User has cancelled their edits: return to the category list
        header( "Location: admin.php?action=listCategories" );
    } else {

        // User has not posted the category edit form yet: display the form
        $results['category'] = new Category;
        require( TEMPLATE_PATH . "/admin/editCategory.php" );
    }

}


function editCategory() {

    $results = array();
    $results['pageTitle'] = "Edit Article Category";
    $results['formAction'] = "editCategory";

    if ( isset( $_POST['saveChanges'] ) ) {

        // User has posted the category edit form: save the category changes

        if ( !$category = Category::getById( (int)$_POST['categoryId'] ) ) {
          header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
          return;
        }

        $category->storeFormValues( $_POST );
        $category->update();
        header( "Location: admin.php?action=listCategories&status=changesSaved" );

    } elseif ( isset( $_POST['cancel'] ) ) {

        // User has cancelled their edits: return to the category list
        header( "Location: admin.php?action=listCategories" );
    } else {

        // User has not posted the category edit form yet: display the form
        $results['category'] = Category::getById( (int)$_GET['categoryId'] );
        require( TEMPLATE_PATH . "/admin/editCategory.php" );
    }

}


function deleteCategory() {

    if ( !$category = Category::getById( (int)$_GET['categoryId'] ) ) {
        header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
        return;
    }

    $articles = Article::getList( 1000000, $category->id );
    $subcategory = Subcategory::getList(1, $category->id);

    if ( $articles['totalRows'] > 0 ) {
        header( "Location: admin.php?action=listCategories&error=categoryContainsArticles" );
        return;
    }

    $category->delete();
    header( "Location: admin.php?action=listCategories&status=categoryDeleted" );
}
function listUsers(){
    $results = array();
    $data = User::getList();
    $results['users'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = "Users";
    
    if (isset($_GET['error'])) {
        if ($_GET['error'] == "userNotFound") 
                            $results['errorMessage'] = "Error: User not found.";
    }
    if (isset($_GET['status'])) {
        if ($_GET['status'] == "changesSaved") 
                    $results['statusMessage'] = "Your changes have been saved.";
        if ($_GET['status'] == "userDeleted") 
                                    $results['statusMessage'] = "User deleted.";
    }
    require(TEMPLATE_PATH . "/admin/listUsers.php");      
}
function newUser(){
    $results = array();
    $results['pageTitle'] = "New user";
    $results['formAction'] = "newUser";
    
    if(isset($_POST['saveChanges'])){
        $user = new User($_POST);
        if ($user->insert($_POST)){
            header("Location: admin.php?action=newUser&error=loginExists");
        }else {
            header("Location: admin.php?action=listUsers&status=changesSaved");
        }
    } elseif(isset($_POST['cancel'])){
        header("Location: admin.php?action=listUsers");
    }else {
         if (isset($_GET['error'])) {
            if ($_GET['error'] == 'loginExists') {
                $results['errorMessage'] = 'Логин занят';
            }
        }
        $results['user'] = new User;
        require(TEMPLATE_PATH . "/admin/editUser.php");
    }
}
function editUser(){
    $results = array();
    $results['pageTitle'] = "Edit user";
    $results['formAction'] = "editUser";
    
    if (isset($_POST['saveChanges'])){
        $user = new User($_POST);
        
        if ($user->update($_POST)){
            header("Location: admin.php?action=editUser&error=loginExists&userLogin" . $_POST['userLogin']);
        }else{
            header("Location: admin.php?action=listUsers&status=changesSaved");
        }
    }elseif (isset ($_POST['cancel'])){
        header("Location: admin.php?action=listUsers");
    }else {
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 'loginExists') {
                $results['errorMessage'] = 'Логин занят';
            }
        }
         $results['user'] = User::getByLogin($_GET['userLogin']);
        require(TEMPLATE_PATH . "/admin/editUser.php");
    }
}
function deleteUser()
{
    if (!$user = User::getByLogin($_GET['userLogin'])) {
        header("Location: admin.php?action=listUsers&error=userNotFound");
        return;
    }
    $user->delete();
    header("Location: admin.php?action=listUsers&status=userDeleted"); 
}
function listSubcategories(){
    $results = array();
    $data = Subcategory::getList();
    $results['subcategories'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = "Article Subcategory";
    if (isset( $_GET['error'])) {
        if ($_GET['error'] == "subcategoryNotFound") 
                        $results['errorMessage'] = "Error: Subcategory not found.";
        if ($_GET['error'] == "subcategoryContainsArticles")   
            $results['errorMessage'] = "Error: Subcategory contains articles. "
                . "Delete the articles, or assign them to another subcategory, "
                . "before deleting this subcategory.";
    }
     if (isset($_GET['status'])) {
        if ($_GET['status'] == "changesSaved") 
                    $results['statusMessage'] = "Your changes have been saved.";
        if ($_GET['status'] == "SubcategoryDeleted") 
                             $results['statusMessage'] = "Subcategory deleted.";
    }
    require(TEMPLATE_PATH . "/admin/listSubcategory.php");
}
function newSubcategory(){
    $results = array ();
    $results['pageTitle'] = "New Acticle Subcategory";
    $results['formAction'] = "newSubcategory";
    if (isset($_POST['saveChanges'])){
        $subcategory = new Subcategory;
        $subcategory->storeFormValues($_POST);
        $subcategory->insert();
        header("Location: admin.php?action=listSubcategory&status=changesSaved");
    }elseif (isset($_POST['cancel'])){
        header("Location:admin.php?action=listSubcategory");
    }else {
        $data = Category::getList();
        $results['categories'] = $data['results'];
        $results['subcategory'] = new Subcategory();
        require (TEMPLATE_PATH . "/admin/editSubcategory.php");
        }
}
function editSubcategory() {
    $results = array();
    $results['pageTitle'] = "Edit Article Subcategory"; 
    $results['formAction'] = "editSubcategory";
    
    if (isset($_POST['saveChanges'])) {
        if (!$subcategory = Subcategory::getById((int)$_POST['subcategoryId'])) {
            header("Location: admin.php?action=listSubcategory&error=subcategoryNotFound"); 
            return;
        }

        $subcategory->storeFormValues($_POST);
        $subcategory->update();
        header("Location: admin.php?action=listSubcategory&status=changesSaved");
    } elseif (isset($_POST['cancel'])) {
        header("Location: admin.php?action=listSubcategory&status=changesSaved");
    } else {
        $data = Category::getList();
        $results['categories'] = $data['results'];
        $results['subcategory'] = Subcategory::getById((int)$_GET['subcategoryId']);
        require(TEMPLATE_PATH . "/admin/editSubcategory.php");
    }
}
function deleteSubcategory() 
{
    if (!$subcategory = Subcategory::getById((int)$_GET['subcategoryId'])) {
        header("Location: admin.php?action=listSubcategories&error=subcategoryNotFound");
        return;
    }
    $articles = Article::getList(1000000, null, false, $subcategory->id);
    if ($articles['totalRows'] > 0) {
        header("Location: admin.php?action=listSubcategories&error=subcategoryContainsArticles");
        return;
    }
    $subcategory->delete();
    header("Location: admin.php?action=listSubcategories&status=subcategoryDeleted");
}
function testMatches($param){
    $conn = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
    $sql = "SELECT password, active FROM users WHERE login = :login";
    $st = $conn->prepare($sql);
    $st->bindValue(":login", $param['username'],PDO::PARAM_STR);
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    
    if ($param['password'] === $row['password']){
        if($row['active'] == 1){
            return 'active';
        }else {
            return 'noActive';
        }
    }
    return false;
}