<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
<!--        <?php echo "<pre>";
            print_r($results);
            print_r($data);
        echo "<pre>"; ?> Данные о массиве $results и типе формы передаются корректно-->

        <h1><?php echo $results['pageTitle']?></h1>

        <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
            <input type="hidden" name="articleId" value="<?php echo $results['article']->id ?>">

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

            <ul>

              <li>
                <label for="title">Article Title</label>
                <input type="text" name="title" id="title" placeholder="Name of the article" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['article']->title )?>" />
              </li>

              <li>
                <label for="summary">Article Summary</label>
                <textarea name="summary" id="summary" placeholder="Brief description of the article" required maxlength="1000" style="height: 5em;"><?php echo htmlspecialchars( $results['article']->summary )?></textarea>
              </li>

              <li>
                <label for="content">Article Content</label>
                <textarea name="content" id="content" placeholder="The HTML content of the article" required maxlength="100000" style="height: 30em;"><?php echo htmlspecialchars( $results['article']->content )?></textarea>
              </li>

              <li>
                <label for="categoryId">Article Category</label>
                <select name="categoryId">
                  <option value="0"<?php echo !$results['article']->categoryId ? " selected" : ""?>>(none)</option>
                <?php foreach ( $results['categories'] as $category ) { ?>
                  <option value="<?php echo $category->id?>"<?php echo ( $category->id == $results['article']->categoryId ) ? " selected" : ""?>><?php echo htmlspecialchars( $category->name )?></option>
                <?php } ?>
                </select>
              </li>

              <li>
                <label for="publicationDate">Publication Date</label>
                <input type="date" name="publicationDate" id="publicationDate" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo $results['article']->publicationDate ? date( "Y-m-d", $results['article']->publicationDate ) : "" ?>" />
              </li>
              <li>
                  <label for="active">Active</label>
                  <input type="checkbox" name="active" value="true"
                     <?php 
                        if ($results['article']->active != 0){
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

    <?php if ($results['article']->id) { ?>
          <p><a href="admin.php?action=deleteArticle&amp;articleId=<?php echo $results['article']->id ?>" onclick="return confirm('Delete This Article?')">
                  Delete This Article
              </a>
          </p>
    <?php } ?>
	  
<?php include "templates/include/footer.php" ?>

              