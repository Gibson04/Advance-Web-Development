<?php
require_once 'common.php';
page_header('Register Account');
?>
<section class="form-panel">
    <h1>Create Account</h1>
    <form action="process_registration.php" method="post">
        <label>First name <input type="text" name="first_name"></label>
        <label>Last name <input type="text" name="last_name"></label>
        <label>Date of birth <input type="date" name="dob"></label>
        <label>Gender
            <select name="gender"><option selected>Female</option><option>Male</option></select>
        </label>
        <label>Email <input type="text" name="email"></label>
        <label>Hometown <input type="text" name="hometown"></label>
        <label>Password <input type="text" name="password"></label>
        <label>Confirm password <input type="text" name="confirm_password"></label>
        <div class="actions"><button class="button primary" type="submit">Register</button><button class="button" type="reset">Reset</button></div>
    </form>
</section>
<?php page_footer(); ?>
