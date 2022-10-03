<link rel="stylesheet" href="../css/style.css">
<section class="header">
    <nav class="nav clearfix">
        <div class="logo">
            <h1>Blog</h1>
        </div>
        <ul class="menu">
            <li><a class="active" href="#">Home</a></li>
            <li><a href="../post/index.php">Post</a></li>
            <li><a href="../category/index.php">Category</a></li>
            <li class="dropdown"><a href="#"><?php if(isset($_SESSION['user']['name'])){ echo $_SESSION['user']['name']; } ?></a>
            <ul>
                <li><a href="../login/login.php">Login</a></li>
                <li><a href="../login/logout.php">Logout</a></li>
            </ul>        
            </li>
        </ul>
    </nav>
</section>
