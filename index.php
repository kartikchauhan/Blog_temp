<?php

require_once'Core/init.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>
      Home
    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="keywords" content="blog, technology, code, program, alorithms"/>
    <meta name="description" content="We emphaisze on solving problems">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
    <style type="text/css">
        /* no added transitions for safari, mozilla, safari and other browsers*/
        .slider
        {
            z-index: -1;
        }
        nav
        {
            border-bottom: 1px white solid;
        }
        input[type="search"]
        {
            height: 64px !important; /* or height of nav */
        }
        #secondary-content
        {
            position: relative;
            top: 100vh;
        }
        #write-blog
        {
            position: relative;
            top: -50%;
            z-index: 3;
        }
        .ghost-button
        {
            display: inline-block;
            width: 200px;
            padding: 8px;
            color: #fff;
            border: 2px solid #fff;
            text-align: center;
            outline: none;
            text-decoration: none;
        }
        .ghost-button:hover, .ghost-button:active
        {
            background-color: #fff;
            color: #000;
            transition: background-color 0.3s ease-in, color 0.3s ease-in;
        }
        .blockquote
        {
            font-size: 12px;
        }
        .description
        {
            font-size: 12px;
        }
        ._token
        {
            display: none;
        }
        a
        {
            text-decoration: none;
            color: none;
        }
    </style>
</head>
<body>

    <?php
        include'header.html';
    ?>

    <div class="slider fullscreen" data-indicators="false">
        <ul class="slides">
            <li>
                <img src="Includes/images/map2.jpg">
                <div class="caption left-align">
                    <h3 class="light white-text">History doesn't repeats itself,<br>but it does rhyme.</h3>
                </div>
            </li>
            <li>
                <div class="caption right-align">
                    <h1 class="light white-text">First solve the problem.<br>Then, write the code.</h1>
                </div>
                <img src="Includes/images/sublime_text.jpeg"> <!-- random image -->
            </li>
            <li>
                <div class="caption center-align">
                    <h4 class="light white-text">Art speaks where words are unable to explain.</h4>
                </div>
                <img src="Includes/images/art1.jpg">
            </li>
            <li>
                <img src="Includes/images/music2.jpg">
                <div class="caption right-align">
                    <h5 class="light grey-text text-lighten-3">Where words fail, Music speaks.</h5>
                </div>
            </li>
            <li>
                <div class="caption left-align">
                    <h4 class="light white-text">Science is the poetry of<br>reality.</h4>
                </div>
                <img src="Includes/images/science.jpg"> <!-- random image -->
            </li>            
        </ul>
        <div id="write-blog" class="center-align">
            <a class="ghost-button" href="">WRITE A BLOG</a>
        </div>
    </div>
    <div id="secondary-content">
            <div class="row">
                <div id='_token' class="_token" data-attribute="<?php echo Token::generate(); ?>"></div>
                <div class="col s8">
                    <h5 class="center-align">Recent Blogs</h5>
                    <div class="content" id="content">
                        <?php
                            $blogs = DB::getInstance()->sort('blogs', array('created_on', 'DESC'));
                            $num_blogs = $blogs->count();
                            $num_pages = ceil($num_blogs/1);
                            if($num_blogs)  // show blogs if there are any, otherwise show message 'No blogs'
                            {
                                if($blogs = $blogs->fetchRecords(1))
                                {
                                    foreach($blogs as $blog)
                                    {
                                        $date=strtotime($blog->created_on); // changing the format of timestamp fetched from the database, converting it to milliseconds
                                        echo 
                                        "<div class='row'>
                                            <div class='col s2'>
                                                <blockquote>".
                                                    date('M', $date)."<br>".
                                                    date('Y d', $date).
                                                "</blockquote>
                                            </div>
                                            <div class='col s10'>
                                                <h5><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h5>
                                                <h6>".ucfirst($blog->description)."</h6><br>
                                                <div class='row'>
                                                    <div class='measure-count' data-attribute='{$blog->id}'>
                                                        <div class='col s1'>
                                                            <i class='fa fa-eye fa-2x' aria-hidden='true' style='color:grey'></i>
                                                        </div>
                                                        <div class='col s1'>
                                                            {$blog->views}
                                                        </div>
                                                        <div class='col s1 offset-s1'>
                                                            <i class='fa fa-thumbs-up fa-2x' aria-hidden='true' style='color:grey'></i>
                                                        </div>
                                                        <div class='col s1'>
                                                            {$blog->likes}
                                                        </div>
                                                        <div class='col s1 offset-s1'>
                                                            <i class='fa fa-thumbs-down fa-2x' aria-hidden='true' style='color:grey'></i>
                                                        </div>
                                                        <div class='col s1'>
                                                            {$blog->dislikes}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='divider'></div>
                                            </div>
                                        </div>";
                                    }
                                }
                            }
                            else
                            {
                                echo "<div class='section center-align'>No blogs yet. <a href='write_blogs.php'>Write the very first blog.</div>";
                            }
                        ?>
                    </div>
                    <div class=" section center-align">
                        <ul class="pagination">
                            <li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
                            <?php 
                                for($x = 1; $x <= $num_pages; $x++)
                                {
                                    if($x == 1)
                                    {
                                        echo "<li class='waves-effect pagination active'><a href='#' class='blog-pagination'>".$x."</a></li>";
                                    }
                                    else
                                    {
                                        echo "<li class='waves-effect pagination'><a href='#' class='blog-pagination'>".$x."</a></li>";
                                    }
                                }
                            ?>
                            <li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col s4">
                    <div class="section">
                        <h5 class="center-align">Recommended Blogs</h5>
                    </div>
                    <?php
                        $blogs = DB::getInstance()->sort('blogs', array('views', 'DESC'));
                        if($blogs->count())
                        {
                            if($blogs = $blogs->fetchRecords(5))
                            {
                                foreach($blogs as $blog)
                                {
                                    $date=strtotime($blog->created_on); // changing the format of timestamp fetched from the database, converting it to milliseconds
                                    echo 
                                    "<div class='row'>
                                        <div class='col s2'>
                                            <blockquote class='blockquote'>".
                                                date('M', $date)."<br>".
                                                date('Y d', $date).
                                            "</blockquote>
                                        </div>
                                        <div class='col s10'>
                                            <h6><a class='views' data-attribute='{$blog->views}' href='".Config::get('url/endpoint')."/view_blog.php?blog_id={$blog->id}'".">".ucfirst($blog->title)."</a></h6>
                                            <p class='description'>".ucfirst($blog->description)."</p><br>
                                            <div class='row'>
                                                <div class='measure-count' data-attribute='{$blog->id}'>
                                                    <div class='col s1'>
                                                        <i class='fa fa-eye fa-lg' aria-hidden='true' style='color:grey'></i>
                                                    </div>
                                                    <div class='col s1'>
                                                        {$blog->views}
                                                    </div>
                                                    <div class='col s1 offset-s2'>
                                                        <i class='fa fa-thumbs-up fa-lg' aria-hidden='true' style='color:grey'></i>
                                                    </div>
                                                    <div class='col s1'>
                                                        {$blog->likes}
                                                    </div>
                                                    <div class='col s1 offset-s2'>
                                                        <i class='fa fa-thumbs-down fa-lg' aria-hidden='true' style='color:grey'></i>
                                                    </div>
                                                    <div class='col s1'>
                                                        {$blog->dislikes}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                                }
                            }
                        }
                        else
                        {
                            echo 
                            "<h6 class='center-align'>No blogs yet</h6>";
                        }
                    ?>
                </div>
            </div>
    </div>

    <script src="Includes/js/jquery.min.js"></script>
    <script src="https://use.fontawesome.com/17e854d5bf.js"></script>
    <script type="text/javascript" src="Includes/js/materialize.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.slider').slider();

            $('.blog-pagination').click(function(e){
                e.preventDefault();
                var page_id = $(this).html();
                var _token = $('#_token').attr('data-attribute');

                $.ajax({
                    type: 'POST',
                    url: 'pagination_backend.php',
                    data: {page_id: page_id, _token: _token},
                    cache: false,
                    success: function(response)
                    {
                        var response = JSON.parse(response);
                        console.log(response);
                        console.log(response._token);
                        $('#_token').attr('data-attribute', response._token);
                        $('.content').html(response.content);
                    }
                });
            });

            // $('.views').click(function(e){
            //     e.preventDefault();
            //     var blog_id = getBlogId(this);
            //     var _token = getToken();

            // });


            // $('.likes, .dislikes').click(function(e){
            //     e.preventDefault();
            //     var object = $(this);
                
            //     var blog_id = getBlogId(this);
            //     var _token = getToken();
            //     var count = $(this).attr('data-attribute');
            //     var className = getClassName(this);

            //     $.ajax({
            //         type: 'POST',
            //         url: 'blog_attributes.php',
            //         data: {blog_id: blog_id, _token: _token, field: className, count: count},
            //         cache: false,
            //         success: function(response)
            //         {
            //             var response = JSON.parse(response);
            //             console.log(response);
            //             $('#_token').attr('data-attribute', response._token);
            //             if(response.error_status)
            //             {
            //                 consol.log(response.error);
            //                 Materialize.toast(response.error, 5000, 'red');
            //                 return false;
            //             }
            //             else
            //             {
            //                 $(object).attr('data-attribute', response.count);
            //                 console.log(response.count);
            //                 console.log($(object).parent().next().text(response.count));
            //             }
            //         }
            //     });
            // });
   
            // function getToken()
            // {
            //     return $('#_token').attr('data-attribute');
            // } 

            // function getBlogId(object)
            // {
            //     return $(object).parent().parent().attr('data-attribute');
            // }

            // function getClassName(object)
            // {
            //     var className = $(object).attr('class');
            //     if(className === 'likes')
            //     {
            //         className = 'likes';
            //     }
            //     else if(className === 'dislikes')
            //     {
            //         className = 'dislikes';
            //     }

            //     return className;

            // }

        });
    </script>
</body>
</html>