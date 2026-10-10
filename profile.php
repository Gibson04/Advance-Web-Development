<?php
require_once 'common.php';
page_header('Student Profile');
?>
<section class="profile-layout">
    <div class="profile-photo-panel">
        <img class="profile-photo" src="img/myphoto.jpeg" alt="Profile photo of Gibson Chong Chee Yii">
    </div>
    <div class="profile-info-panel">
        <p class="eyebrow">Student Profile</p>
        <h1>Gibson Chong Chee Yii</h1>
        <dl class="profile-list">
            <div>
                <dt>Student ID</dt>
                <dd>102779580</dd>
            </div>
            <div>
                <dt>Student Email</dt>
                <dd>102779580@students.swinburne.edu.my</dd>
            </div>
        </dl>
        <div class="integrity-box">
            <h2>Academic Integrity Declaration</h2>
            <p>I declare that this assignment is my individual work. I have not worked collaboratively, nor have I copied from any other student's work or from any other source except where due acknowledgement is made explicitly in the text.</p>
        </div>
        <div class="actions">
            <a class="button" href="index.php">Home</a>
            <a class="button" href="about.php">About</a>
        </div>
    </div>
</section>
<?php page_footer(); ?>