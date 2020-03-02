<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/blog/logic/includes.php';

$isLoggedIn = false;
if(isset($_COOKIE["Token"])){
    $isLoggedIn = true;
    $user = UserDB::getUserByToken($_COOKIE["Token"]);
}
if(!$isLoggedIn){
header("location: blog.php");
}
if(isset($_GET['error'])){
    switch($_GET['error']){
        case 1:
            $errorMessage = "Please upload a valid image.";
            break;
        case 2:
            $errorMessage = "Max image size is 5MB.";
            break;
        case 3:
            $errorMessage = "Only JPG, JPEG, PNG and GIF are allowed.";
            break;
    }
}

?>

<!--HTML and CSS copied and abridged from https://www.w3schools.com/w3css/tryit.asp?filename=tryw3css_templates_blog -->
<!DOCTYPE html>
<html>
<head>
    <title>Blog</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheet/stylesheet.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script src="script/script.js"></script>
</head>
<body class="w3-light-grey">

<!-- w3-content defines a container for fixed size centered content,
and is wrapped around the whole page content, except for the footer in this example -->
<div class="w3-content" style="max-width:1400px; padding-top: 40px">
    <!-- Header -->
    <header class="w3-container w3-center"
            style="position: sticky; top: 0; padding: 5px; margin-bottom: 40px; background-color: #f1f1f1; z-index: 50">
        <h1 style="display: inline-block"><b>
                <a href="blog.php?id=<?php echo $user->userID ?>" id="username"><?php echo strtoupper($user->username) . "'S BLOG"; ?></a>
            </b></h1>
        <?php
        if($isLoggedIn) {
            if(UserDB::checkIfAdmin($_COOKIE['Token'])){
            ?>
                <button onclick="window.location.href = 'admin.php'" type="button" class="accountBtn">ADMIN
                </button>
                <?php } ?>
            <button onclick="window.location.href = 'blog.php'" class="accountBtn">MY BLOG
            </button>
            <button type="button" id="logoutBtn" class="accountBtn">LOG OUT
            </button>
        <?php
        }
        else {
            ?>
            <button type="button" data-toggle="modal" data-target="#signupModal" id="signupBtn" class="accountBtn">
                SIGN UP
            </button>
            <button type="button" data-toggle="modal" data-target="#loginModal" id="loginBtn" class="accountBtn">LOG
                IN
            </button>
            <?php
        }
        ?>
    </header>


    <!-- Grid -->
    <div class="w3-row">

        <!-- Blog entries -->
        <div class="w3-col  s12">
            <!-- Blog entry -->
            <div class="w3-card-4 w3-margin w3-white">
                <div class="w3-container">
                    <h3><b>Make a new blogpost</b></h3>
                    <form id="makepostForm" action="logic/addpost.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" maxlength="80" class="form-control" name="title"
                                   id="commentTitle" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Text</label>
                            <textarea form="makepostForm" minlength="200" maxlength="2000" type="text" class="form-control"
                                      name="text" id="commentText" required></textarea>
                        </div>
                        <div class="warning"></div>
                        <select form="makepostForm" name="category" required>
                            <option value="" selected disabled hidden>Choose a category</option>
                            <?php
                            $categories = CategoryDB::getCategories();
                            foreach($categories as $category){
                                echo '<option value="' . $category->categoryID . '">' . $category->categoryName . '</option>';
                            }
                            ?>
                        </select>
                        <br>
                        <br>
                        <input type="file" name="image" accept="image/png, image/jpg, image/jpeg, image/gif">
                        <div class="warning"<?php if(isset($errorMessage)){echo 'style="visibility: visible"';} ?>><?php if(isset($errorMessage)){echo $errorMessage;} ?></div>
                        <hr>
                        <input style="margin-bottom: 15px" type="submit" class="btn btn-primary"
                               value="Submit post" required/>
                    </form>
                </div>
            </div>
            <hr>
            <!-- END BLOG ENTRIES -->
        </div>
        <!-- END GRID -->
    </div><br>
    <!-- END w3-content -->
</div>

<?php
include 'footer.php';
?>

</body>
</html>