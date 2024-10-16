<?php
if ($_SESSION['username'] !== 'admin'){
    header("Location: admin.php");
}
include "templates/include/header.php";
include "templates/admin/include/header.php"; ?>
    <h1>All users </h1>
    <?php if (isset($results['errorMessage'])) { ?>
            <div class="errorMessage"><?php 
                                          echo $results['errorMessage'] ?></div>
    <?php } ?>
    
    <?php if (isset($results['statusMessage'])){?>
    <div class="statusMessage"><?php echo $results['statusMessage']?></div>
    <?php } ?>

    <table>
        <tr>
            <th>User</th>
            <th>Password</th>
            <th>Active</th>
        </tr>    
       <?php foreach ($results['users'] as $user){ ?>
        <tr onclick="location='admin.php?action=editUser&amp;userLogin=<?php
                                                          echo $user->login?>'">
            
        <td>
            <?=$user->login?>
        </td>    
        
        <td>
            <?=$user->password?>
        </td> 
        
        <td>
           <?php
           if ($user->active){
               echo 'Active';
           }else {
               echo 'Not active';
           }
           ?>
        </td> 
        </tr>
       <?php } ?>
    </table>
    <p><?php echo $results['totalRows']?> user <?php 
             echo ($results['totalRows'] != 1)? 's' : '' ?>in total.</p>
    <p><a href="admin.php?action=newUser">Add a New User</a></p>
<?php include "templates/include/footer.php" ?>       
