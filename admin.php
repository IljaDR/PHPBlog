<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/blog/logic/includes.php';
$isLoggedIn = false;
//If the user is logged in, he will see his own blog
if(isset($_COOKIE["Token"])){
    $isLoggedIn = true;
    $user = UserDB::getUserByToken($_COOKIE["Token"]);
}
if(!UserDB::checkIfAdmin($_COOKIE["Token"])){
    header("location:index.php");
}

if(!isset($user)){
    header("location:index.php");
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
                <a id="username">Admin page</a>
            </b></h1>
        <?php
        if($isLoggedIn) {
            ?>
            <button type="button" id="logoutBtn" class="accountBtn">LOG OUT
            </button>
            <?php
            if(!isset($_GET['id'])){
                ?>
                <button onclick="window.location.href = 'blog.php'" class="accountBtn">MY BLOG
                </button>
                <button type="button" id="createpostBtn" class="accountBtn">CREATE POST
                </button>
                <?php
            }
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
        <div class="w3-col l8 s12">
            <!-- Blog entry -->
            <?php
            $blogpostList = BlogpostDB::getBlogposts($user);
            if($blogpostList) {
                foreach ($blogpostList as $bp) {
                    ?>
                    <div class="w3-card-4 w3-margin w3-white">
                        <img src="uploads/<?php echo $bp->picturePath ?>"
                             alt="<?php echo $bp->alt ?>"
                             title="<?php echo $bp->alt ?>"
                             style="width:100%">
                        <div class="w3-container">
                            <h3><b><?php echo $bp->title ?></b></h3>
                            <h5><span class="w3-opacity"><?php echo fixDate($bp->date) ?></span></h5>
                        </div>

                        <div class="w3-container">
                            <p><?php echo shortText($bp->text) ?></p>
                            <div class="w3-row">
                                <div class="w3-col m8 s12">
                                    <p>
                                        <a href="blogdetail.php?id=<?php echo $bp->blogpostID ?>"
                                           class="w3-button w3-padding-large w3-white w3-border"><b>READ MORE »</b>
                                        </a>
                                    </p>
                                </div>
                                <div class="w3-col m4 w3-hide-small">
                                    <p>
                                        <a style="float:right; background-color: red; border: red;"
                                           href="logic/deletePost.php?id=<?php echo $bp->blogpostID ?>" type="submit" class="btn btn-primary">Delete post</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php
                }
            }
            else {
                ?>
                <div class="w3-card-4 w3-margin w3-white">
                    <div class="w3-container">
                        <h3><b>This is where your blogposts will appear!</b></h3>
                        <h5><span class="w3-opacity">It seems like all blogposts have been removed from this site</span></h5>
                    </div>
                </div>
            <?php }?>
            <!-- END BLOG ENTRIES -->
        </div>

        <!-- Introduction menu -->
        <div class="w3-col l4">
            <!-- About Card -->
            <div class="w3-card w3-margin w3-margin-top">
                <div class="w3-container w3-white">
                    <form id="addCategory" action="logic/addCategory.php" method="POST">
                        <div class="form-group">
                            <label style="margin-top: 10px">Add Category</label>
                            <input type="text" class="form-control" name="category" placeholder="Category name" id="categoryInput" autocomplete="off">
                            <div class="warning"></div>
                            <input style="margin-top: 5px" type="submit" class="btn btn-primary" value="Add"/>
                        </div>
                    </form>
                </div>
            </div>
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
