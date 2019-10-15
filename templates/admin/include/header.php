<div id="adminHeader">
    <h2>Widget News Admin</h2>
    <p>You are logged in as <b><?php echo htmlspecialchars( $_SESSION['username']) ?></b>.
        <a href="admin.php?action=listArticles">Редактирование статей</a>
        <a href="admin.php?action=listCategories">Редактирование категорий</a>
        <a href="admin.php?action=listUsers">Редактирование пользователей</a>
        <a href="admin.php?action=logout"?>Выход</a>
    </p>
</div>
