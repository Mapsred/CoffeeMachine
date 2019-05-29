<?php
require_once(__DIR__ . "/../vendor/autoload.php");
session_start();

use App\CoffeeMachine;
use App\Products\AbstractProduct;

$coffeeMachine = $_SESSION['coffee_machine'] ?? new CoffeeMachine();

if (!isset($_SESSION['coffee_machine'])) {
    $_SESSION['coffee_machine'] = $coffeeMachine;
}

if (isset($_POST['money_add'])) {
    $coffeeMachine->addMoney($_POST['money']);

    redirect();
}

if (isset($_POST['refund'])) {
    $money = $coffeeMachine->refundMoney();
    $_SESSION['tmp_message'] = sprintf("Refunded %s$", $money);

    redirect();
}

if (isset($_POST['product'])) {
    if (isset($_POST['sugar'])) {
        $coffeeMachine->addSugar($_POST['sugar']);
    }
    if (isset($_POST['milk'])) {
        $coffeeMachine->addMilk($_POST['milk']);
    }

    try {
        $coffeeMachine->selectProduct($_POST['product']);
    } catch (Exception $e) {
        $_SESSION['tmp_message'] = $e->getMessage();
    }

    redirect();
}


function redirect()
{
    header("Location: http://demo.develop");
    exit;
}

if (isset($_SESSION['tmp_message'])) {
    $tmp_message = $_SESSION['tmp_message'];
    unset($_SESSION['tmp_message']);
}

?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CoffeeMachine</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
    <h5 class="my-0 mr-md-auto font-weight-normal">Office</h5>
</div>

<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">Coffee Machine</h1>
    <p class="lead">Here is your virtual coffee machine, enjoy !</p>
    <?php
    if (isset($tmp_message)) {
        echo "<p>$tmp_message</p>";
    }
    ?>
</div>

<div class="container">
    <form method="post">

        <div class="card-deck mb-3 text-center">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Sugar</h4>
                </div>
                <div class="card-body">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sugar" id="sugarRadio1"
                               value="1">
                        <label class="form-check-label" for="sugarRadio1">1</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sugar" id="sugarRadio2"
                               value="2">
                        <label class="form-check-label" for="sugarRadio2">2</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sugar" id="sugarRadio3"
                               value="3">
                        <label class="form-check-label" for="sugarRadio3">3</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sugar" id="sugarRadio4"
                               value="4">
                        <label class="form-check-label" for="sugarRadio4">4</label>
                    </div>
                </div>
            </div>
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Milk</h4>
                </div>
                <div class="card-body">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="milk" id="milkRadio1" value="1">
                        <label class="form-check-label" for="milkRadio1">1</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="milk" id="milkRadio2" value="2">
                        <label class="form-check-label" for="milkRadio2">2</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="milk" id="milkRadio3" value="3">
                        <label class="form-check-label" for="milkRadio3">3</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="milk" id="milkRadio4" value="4">
                        <label class="form-check-label" for="milkRadio4">4</label>
                    </div>
                </div>
            </div>
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Money</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="money" class="sr-only">Money</label>
                        <h1 class="card-title pricing-card-title"><?= $coffeeMachine->getMoney() ?>$</h1>
                        <input type="number" id="money" class="form-control" name="money" min="1" value="1">
                        <button type="submit" class="btn btn-block btn-primary mt-3" name="money_add">Add</button>
                        <button type="submit" class="btn btn-lg btn-block btn-primary mt-3" name="refund">Refund
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-deck mb-3 text-center">
            <?php
            foreach ($coffeeMachine->getProducts() as $product) {
                $name = str_replace("App\\Products\\", "", $product);
                ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal"><?= $name ?></h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title"><?= $product::PRICE ?>$</h1>
                        <button type="submit" class="btn btn-lg btn-block btn-primary" name="product"
                                value="<?= $product ?>">
                            Buy
                        </button>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>

        <?php
        if (null !== $orders = $coffeeMachine->getOrders()) {

            ?>
            <div class="card-deck mb-3 text-center">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Orders</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Type</th>
                                <th>Sugar</th>
                                <th>Milk</th>
                                <th>Date</th>
                            </tr>
                            <?php
                            /** @var AbstractProduct $order */
                            foreach ($orders as $order) {
                                ?>
                                <tr>
                                    <td><?= str_replace("App\\Products\\", "", get_class($order)) ?></td>
                                    <td><?= $order->getSugar() ?></td>
                                    <td><?= $order->getMilk() ?></td>
                                    <td><?= $order->getCreationDate()->format("Y-m-d H:i:s") ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>

                    </div>
                </div>
            </div>

            <?php
        }
        ?>
    </form>

</div>


<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>
