<?php 
// показываем только админу
if ($_SESSION['username'] !== 'admin') {
    header("Location: admin.php");
}
include "templates/include/header.php";
include "templates/admin/include/header.php" ?>
        <h1><?php echo $results['pageTitle']?></h1>
        <form action="admin.php?action=<?php 
                                   echo $results['formAction']?>" method="post">
            <input type="hidden" name="userLogin" value="<?php 
                                               echo $results['user']->login ?>">
    <?php if (isset($results['errorMessage'])) { ?>
            <div class="errorMessage"><?php 
                                          echo $results['errorMessage'] ?></div>
    <?php } ?>
            <ul>
              <li>
                <label for="title">User Login</label>
                <input type="text" name="login" id="login" 
                       placeholder="Login" 
                       required autofocus maxlength="25"/>
              </li>
              <li>
                <label for="title">User Password</label>
                <input type="text" name="password" id="password" 
                       placeholder="Password" 
                       required autofocus maxlength="25" />
              </li>
              
              <li>
                  <label for="checkActivity">Active</label>
                  <input type="checkbox" name="active" value="1" 
                         id="checkboxActivity"
                  <?php
                        if($results['user']->active == 1) {
                            echo 'checked = "checked"';
                        }
                  ?>
                  >
              </li>
            </ul>
            <div class="buttons">
              <input type="submit" name="saveChanges" value="Save Changes" />
              <input type="submit" formnovalidate name="cancel" value="Cancel" />
            </div>
        </form>
    <?php if ($results['user']->login) { ?>
          <p><a href="admin.php?action=deleteUser&amp;userLogin=<?php 
            echo $results['user']->login ?>" 
            onclick="return confirm('Delete This User?')">
                  Delete This User
          </a></p>
    <?php } ?>
	  
<?php include "templates/include/footer.php" ?>