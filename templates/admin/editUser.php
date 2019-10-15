<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
<!--        массив $results<?php echo "<pre>";
print_r($results);echo "<pre>"; ?>массив $data:<?php echo "<pre>";
print_r($data);echo "<pre>";?> -->

<h1><?php echo $results['pageTitle']?></h1>

<form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
    <!-- Обработка формы будет направлена файлу admin.php ф-ции newUser либо editUser в зависимости от formAction, сохранённого в result-е -->
    <input type="hidden" name="userId" value="<?php echo $results['user']->id ?>"/>

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

    <ul>

        <li>
            <label for="name">Имя пользователя</label>
            <input type="text" name="name" id="name" placeholder="Имя пользователя" required autofocus maxlength="10" value="<?php echo htmlspecialchars( $results['user']->name )?>" />
        </li>

        <li>
            <label for="pass">Пароль</label>
            <input type="text" name="pass" id="pass" placeholder="Введите пароль для данного пользователя" required maxlength="10"><?php echo htmlspecialchars( $results['user']->pass )?></input>
        </li>
        <li>
            <label for="group">Активен</label>
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1"
                <?php
                if ($results['user']->group != 0){
                    echo " checked";
                }
                ?>>
            </input>
        </li>

    </ul>

    <div class="buttons">
        <input type="submit" name="saveChanges" value="Save Changes" />
        <input type="submit" formnovalidate name="cancel" value="Cancel" />
    </div>

</form>

<?php if ( $results['user']->id ) { ?>
    <p><a href="admin.php?action=deleteUser&amp;userId=<?php echo $results['user']->id ?>" onclick="return confirm('Удалить данного пользователя?')">Удалить пользователя</a></p>
<?php } ?>

<?php include "templates/include/footer.php" ?>

