<?php include "templates/include/header.php" ?>
	<?php include "templates/admin/include/header.php" ?>
<!--        <?php echo "<pre>";
            print_r($results);
            print_r($data);
        echo "<pre>"; ?> Данные о массиве $results и типе формы передаются корректно-->
	  
            <h1>Список пользователей</h1>
	  
	<?php if ( isset( $results['errorMessage'] ) ) { ?>
	        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
	<?php } ?>
	  
	  
	<?php if ( isset( $results['statusMessage'] ) ) { ?>
	        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
	<?php } ?>
	  
            <table>
                <tr>
                    <th>Пользователь</th><th>Активность</th>
                </tr>

        <?php foreach ( $results['users'] as $user ) { ?>

                <tr onclick="location='admin.php?action=editUser&amp;id=<?php echo $user->id?>'">
                    <td>
                        <?php echo $user->name?>
                    </td>
                    <td>
                        <?php 
                          if(($user->groupId) != 1) {
                            echo 'не активен';
                          }
                          else{
                            echo 'активен';
                          }
                        ?>    
                    </td>
                </tr>

        <?php } ?>

            </table>

            <p>Всего <?php echo $results['totalRows']?> Пользовател<?php echo ( $results['totalRows'] != 1 ) ? 'ей' : 'ь' ?> </p>

            <p><a href="admin.php?action=newUser">Добавить пользователя</a></p>
	  
	<?php include "templates/include/footer.php" ?>
