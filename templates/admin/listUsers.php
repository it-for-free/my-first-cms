<?php include "templates/include/header.php" ?>
	<?php include "templates/admin/include/header.php" ?>
	  
            <h1>Список пользователей</h1>
	  
	<?php if ( isset( $results['errorMessage'] ) ) { ?>
	        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
	<?php } ?>
	  
	  
	<?php if ( isset( $results['statusMessage'] ) ) { ?>
	        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
	<?php } ?>
	  
            <table>
                <tr>
                    <th>Пользователь</th>
                </tr>

        <?php foreach ( $results['users'] as $user ) { ?>

                <tr onclick="location='admin.php?action=editUser&amp;userId=<?php echo $user->id?>'">
                    <td>
                        <?php echo $user->name?>
                    </td>
                </tr>

        <?php } ?>

            </table>

            <p>Всего <?php echo $results['totalRows']?> Пользовател<?php echo ( $results['totalRows'] != 1 ) ? 'ей' : 'ь' ?> </p>

            <p><a href="admin.php?action=newUser">Добавить пользователя</a></p>
	  
	<?php include "templates/include/footer.php" ?>