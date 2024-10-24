<div id="adminHeader">
    <h2>Widget News Admin</h2>
    <p>You are logged in as <b><?php echo htmlspecialchars( $_SESSION['username']) ?></b>.
        <a href="admin.php?action=listArticles">Edit Articles</a> 
        <a href="admin.php?action=listCategories">Edit Categories</a>
         <?php if ($_SESSION['username'] === 'admin') { ?>
                <a href="admin.php?action=listUsers">Edit User</a> 
        <?php } ?>
        <a href="admin.php?action=listSubcategories">Edit Subcategories</a>
        <a href="admin.php?action=logout"?>Log Out</a>
        
            
        
        
    </p>
</div>
