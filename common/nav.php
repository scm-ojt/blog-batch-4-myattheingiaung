<link rel="stylesheet" href="../css/style.css">
<section class="header">
    <nav class="nav clearfix">
        <div class="logo">
            <h1>Blog</h1>
        </div>
        <ul class="menu">
            <li class="list"><a class="" href="../post/index.php">Post</a></li>
            <li class="list"><a class="" href="../category/index.php">Category</a></li>
            <li class="list" class="dropdown"><a class=""><i class="fa-solid fa-user"></i><?php if(isset($_SESSION['user']['name'])){ echo $_SESSION['user']['name']; } ?></a>
            <ul>
                <li class="list"><a class="" href="../login/login.php">Login</a></li>
                <li class="list"><a class="" href="../login/logout.php">Logout</a></li>
            </ul>        
            </li>
        </ul>
    </nav>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    let activePage = location.href;
    let navLinks = document.querySelectorAll('nav a').forEach(link => {
        if(link.href.includes(`${activePage}`)){
            link.classList.add('active');
        } 
    })
</script>


