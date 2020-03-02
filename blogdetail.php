<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/blog/logic/includes.php';
if($_GET['id'])
    $bp = BlogpostDB::getBlogpostByID($_GET['id']);
else
    header("location:index.php");
$isLoggedIn = false;
if(isset($_COOKIE["Token"])){
    $isLoggedIn = true;
    $user = UserDB::getUserByToken($_COOKIE["Token"]);
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
                <a href="blog.php?id=<?php echo $bp->userID ?>" id="username"><?php echo strtoupper(UserDB::getUserByID($bp->userID)->username) . "'S BLOG"; ?></a>
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
        <div class="w3-col 18 s12" style="width:66%">
            <!-- Blog entry -->
                <div class="w3-card-4 w3-margin w3-white">
                    <img src="uploads/<?php echo $bp->picturePath ?>"
                         alt="<?php echo $bp->alt ?>"
                         title="<?php echo $bp->alt ?>"
                         style="width:100%">
                    <div class="w3-container">
                        <h3><b><?php echo $bp->title ?></b></h3>
                        <h4><span class="w3-opacity"><?php echo fixDate($bp->date) ?></span></h4>
                    </div>

                    <div class="w3-container">
                        <p><?php echo $bp->text ?></p>
                    </div>
                    <div class="w3-container">
                        <hr style="margin-top: 50px;">
                        <h3>Comments</h3>
                        <hr>
                    </div>
                    <?php
                    $commentList = CommentDB::getCommentsByBlogpost($bp);
                    foreach($commentList as $comment) {
                        $commentUser = UserDB::getUserByID($comment->userID);
                        ?>
                        <div class="w3-container">
                            <h5 class="commentTitle"><?php echo $comment->title ?></h5>
                            <p class="commentInfo">by <a style="color: blue" href="blog.php?id=<?php echo $commentUser->userID ?>"><?php echo $commentUser->username ?></a><?php echo ", " . fixDate($comment->date) ?></p>
                            <p><?php echo $comment->text ?></p>
                            <hr>
                        </div>
                        <?php
                    }
                    if($isLoggedIn) {
                        ?>
                        <div class="w3-container">
                            <form id="commentForm" action="logic/postComment.php" method="POST">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" maxlength="80" class="form-control" name="title"
                                           id="commentTitle" required autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label>Comment</label>
                                    <textarea form="commentForm" maxlength="2000" type="text" class="form-control"
                                              name="text" id="commentText" required></textarea>
                                </div>
                                <input type="text" style="display: none" name="blogID"
                                       value="<?php echo $bp->blogpostID ?>">
                                <input type="text" style="display: none" name="userID"
                                       value="<?php echo $user->userID ?>">
                                <div class="warning"></div>
                                <input style="margin-bottom: 15px" type="submit" class="btn btn-primary"
                                       value="Submit comment"/>
                            </form>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <hr>
            <!-- END BLOG ENTRIES -->
        </div>
        <div class="w3-col l4">
            <div class="w3-card w3-margin">
                <div class="w3-container w3-padding">
                    <h4>Similar posts</h4>
                </div>
                <ul class="w3-ul w3-hoverable w3-white">
                    <?php
                    $blogpostList = BlogpostDB::getPostsByCategory($bp->categoryID,$bp->blogpostID);
                    if($blogpostList)
                        foreach($blogpostList as $bpc) {
                            ?>
                            <li class="w3-padding-16">
                                <a href="blogdetail.php?id=<?php echo $bpc->blogpostID ?>">
                                <img src="uploads/<?php echo $bpc->picturePath ?>"
                                     alt="<?php echo $bpc->alt ?>" class="w3-left w3-margin-right"
                                     style="max-width:70px; height: 50px; object-fit: cover;"
                                     title="<?php echo $bpc->alt ?>">
                                <span class="w3-large"><?php echo $bpc->title ?></span><br>
                                <span><?php echo shorterText($bpc->text) ?></span>
                                </a>
                            </li>
                            <?php
                        }
                    ?>
                </ul>
            </div>
            <hr>

            <!-- Categories -->
            <!-- AJAX call not implemented due to a lack of time -->
            <div class="w3-card w3-margin">
                <div class="w3-container w3-padding">
                    <h4>Categories</h4>
                </div>
                <div class="w3-container w3-white" style="padding-top: 25px">
                    <p>
                        <?php
                        $categories = BlogpostDB::getCategoriesByUser(UserDB::getUserByID($bp->userID));

                        if($categories)
                            foreach ($categories as $c) {
                                ?>
                                <a class="w3-tag w3-black w3-margin-bottom"><?php echo $c ?></a>
                                <?php
                            }
                        ?>
                    </p>
                </div>
            </div>

            <!-- Archive -->
            <!-- AJAX call not implemented due to a lack of time -->
            <div class="w3-card w3-margin">
                <div class="w3-container w3-padding">
                    <h4>Archive</h4>
                </div>
                <div class="w3-container w3-white" style="padding-top: 25px">
                    <p>
                        <?php
                        $archive = BlogpostDB::getArchiveArray($bp->userID);
                        if($archive)
                            foreach ($archive as $a) {
                                ?>
                                <a class="w3-tag w3-black w3-margin-bottom"><?php echo $a ?></a>
                                <?php
                            }
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- END GRID -->
    </div><br>

    <!-- END w3-content -->
        <!-- Modal copied from https://getbootstrap.com/docs/4.0/components/modal/ -->
        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="loginForm" action="logic/login.php" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Log in</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Email address</label>
                                <input type="email" class="form-control" name="mail" placeholder="Enter email" id="loginMail">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Password"id="loginPass">
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="check">
                                <label class="form-check-label" for="exampleCheck1">Stay logged in</label>
                            </div>
                            <div class="warning"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Log in"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="signupForm" action="logic/signup.php" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Sign up</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" name="username" placeholder="Username" id="signupUser">
                            </div>
                            <div class="form-group">
                                <label>Email address</label>
                                <input type="email" class="form-control" name="mail" placeholder="Enter email" id="signupMail">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Password" id="signupPass1">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Password" id="signupPass2">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" class="form-control" name="description" placeholder="Description" id="signupdescription">
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="check">
                                <label class="form-check-label" for="exampleCheck1">Stay logged in</label>
                            </div>
                            <div class="warning"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Sign up"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>

<?php
include 'footer.php';
?>


</body>
</html>
