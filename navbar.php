    <nav role="navigation" class="navbar navbar-default">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" data-target=".navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <img src="/images/logo.png" style="max-width:45px" class="img-rounded" alt="Rounded Image">
            <a href="#" class="navbar-brand">Absence Tracking System</a>
        </div>

        <?php 
        if (isset($_SESSION['userID'])) 
        {
            $employee = RetrieveEmployeeByID($_SESSION['userID']); ?>
        
        <div class="nav navbar-nav">
            <ul class=""navbar-nav>
                <li><a href="index.php">Home</a></li>
            </ul>
        </div>
        
        <div id="navbarCollapse" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li>Logged in as <?php echo $employee[EMP_NAME]; ?></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <?php } ?>
    </nav>
        