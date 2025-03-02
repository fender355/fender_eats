<?php include('partials/menu.php'); ?>

        <!-- Main Content Section Starts -->
        <div class = "main-content">
            <div class = "wrapper">
                <br><br>
                <h1 class = "text-margin"> Dashboard </h1>
                <br>

                <?php 
                    if(isset($_SESSION['login'])) {
                        echo $_SESSION['login'];
                        unset($_SESSION['login']);
                    }
                ?>

                <br>

                <div class = "col-4">
                    <h1>5</h1> 
                    Categories
                </div>

                <div class = "col-4">
                    <h1>5</h1>
                    Categories
                </div>

                <div class = "col-4">
                    <h1>5</h1>
                    Categories
                </div>

                <div class = "col-4">
                    <h1>5</h1>
                    Categories
                </div>

                <div class="clearfix"></div>
                <br><br>
            </div>
        </div>
        <!-- Main Content Section Ends --> 

<?php include('partials/footer.php'); ?>