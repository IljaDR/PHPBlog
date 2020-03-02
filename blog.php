<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/blog/logic/includes.php';
$isLoggedIn = false;
//If the user is logged in, he will see his own blog
if(isset($_COOKIE["Token"])){
    $isLoggedIn = true;
    $user = UserDB::getUserByToken($_COOKIE["Token"]);
}
//If the user is logged in, but he goes to a blog.php?id= page, he will see someone else's page instead
if(isset($_GET['id'])){
    $user = UserDB::getUserByID($_GET['id']);
    if(isset($_COOKIE["Token"])){
        //If user lands on his own page with a blog.php?id= url, he will be redirected to the default blog.php page
        if(UserDB::getUserByToken($_COOKIE["Token"])->userID == $user->userID){
            header("location:blog.php");
        }
    }
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
            if(!isset($_GET['id'])){
                ?>
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
                $blogpostList = BlogpostDB::getBlogpostByUser($user);
                if($blogpostList) {
                    foreach ($blogpostList as $bp) {
                        ?>
                        <div class="w3-card-4 w3-margin w3-white" title="<?php echo CommentDB::getCommentAmount($bp) ?>">
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
                                        <span class="w3-padding-large w3-right">
                                            <b>Comments  </b>
                                            <span class="w3-tag"><?php echo CommentDB::getCommentAmount($bp) ?></span>
                                        </span>
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
                    <h5><span class="w3-opacity">Start writing some blogposts :)</span></h5>
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
                    <h4><b><?php echo $user->username ?></b></h4>
                    <p><?php echo $user->description ?></p>
                </div>
            </div>
            <hr>

            <!-- Posts -->
            <div class="w3-card w3-margin">
                <div class="w3-container w3-padding">
                    <h4>Most popular posts by <?php echo $user->username ?></h4>
                </div>
                <ul class="w3-ul w3-hoverable w3-white">
                    <?php
                    $blogpostList = BlogpostDB::getMostPopularPostsFromUser($user);
                    if($blogpostList)
                        foreach($blogpostList as $bp) {
                            ?>
                            <li class="w3-padding-16">
                                <a href="blogdetail.php?id=<?php echo $bp->blogpostID ?>">
                                <img src="uploads/<?php echo $bp->picturePath ?>"
                                     alt="<?php echo $bp->alt ?>" class="w3-left w3-margin-right"
                                     style="max-width:70px; height: 50px; object-fit: cover;"
                                     title="<?php echo $bp->alt ?>">
                                <span class="w3-large"><?php echo $bp->title ?></span><br>
                                <span><?php echo shorterText($bp->text) ?></span>
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
                    $categories = BlogpostDB::getCategoriesByUser($user);
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
                        $archive = BlogpostDB::getArchiveArray($user->userID);
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
            <!-- END Introduction Menu -->
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
                            <input type="password" class="form-control" name="password" placeholder="Password" id="loginPass">
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
