
<?php 
	$phpself = explode("/", "{$_SERVER['PHP_SELF']}");
	$script = $phpself[count($phpself) - 1];

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item <?php if($script == 'index.php'){ echo ' active'; } ?>">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item <?php if($script == 'orders.php'){ echo ' active'; } ?>">
                <a class="nav-link" href="orders.php">View orders</a>
            </li>
            <li class="nav-item <?php if($script == 'create-order.php'){ echo ' active'; } ?>">
                <a class="nav-link" href="create-order.php">Create an order</a>
            </li>
            <li class="nav-item <?php if($script == 'view-customers.php'){ echo ' active'; } ?>">
                <a class="nav-link" href="view-customers.php">View customers</a>
            </li>
            <li class="nav-item <?php if($script == 'create-customer.php'){ echo ' active'; } ?>">
                <a class="nav-link" href="create-customer.php">Add a cusomer</a>
            </li>
        </ul>
    </div>
</nav>  