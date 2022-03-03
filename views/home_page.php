<?php

require_once("header.php");

?>
<div class="container">
    <h2>Happy delivery subscription</h2>
    <p></p>
    <div class="forms">
        <div class="form">
            <form method="post" action="<?= URL . 'subscribe' ?>">
                <h3>Subscribe our amazing products/services now. </h3>
                <h3>Fill in the subscription form:</h3>
                <p>Username: <input type="text" name="username" value="<?= $_POST['username'] ?? '' ?>" required> </p>
                <p>Password: <input type="password" name="password" value="" required></p>
                <p>Address: <input type="text" name="address" value="" required> </p>

                <p> Select subscription type: <select name="subscription_plan" class="" required>
                        <option value="0">Select subscription</option>
                        <option value="weekly">
                            weekly
                        </option>
                        <option value="fortnightly">
                            fortnightly
                        </option>
                    </select></p>

                <p>Select delivery day: <select name="delivery_day" class="" required>
                        <option value="0">Select day</option>
                        <option value="monday">monday</option>
                        <option value="tuesday">tuesday</option>
                        <option value="wednesday">wednesday</option>
                        <option value="thursday">thursday</option>
                        <option value="friday">friday</option>
                        <option value="saturday">saturday</option>
                        <option value="sunday">sunday</option>
                    </select></p>

                <p><input type="submit" value="Subscribe" name="subscribe"></p>
            </form>
        </div>
        <div class="form">
            <form method="post" action="<?= URL . 'delivery_calendar' ?>">
                <h3>Pleace login, if You want to see your delivery calendar</h3>
                <p>Username: <input type="text" name="username" value="<?= $_POST['username'] ?? '' ?>" required> </p>
                <p>Password: <input type="password" name="password" value="" required></p>

                <p><input type="submit" value="Login" name="login"></p>
            </form>
        </div>
    </div>
</div>
<?php

require_once("footer.php");

?>