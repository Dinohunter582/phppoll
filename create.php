<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

if (!empty($_POST)) {
    // check post data
    // check if all variables have been filled
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $stmt = $pdo->prepare('INSERT INTO polls (title, description) VALUES (?, ?)');
 // make new record in poll table
    $stmt->execute([ $title, $description ]);
   // thos will get you last id inserted
    $poll_id = $pdo->lastInsertId();
        // turn multiline string into an array so they can be saved to "poll_answers" table
    $answers = isset($_POST['answers']) ? explode(PHP_EOL, $_POST['answers']) : '';
    foreach($answers as $answer) {
        // alert user answer feild is empty and continue
        if (empty($answer)) continue;
        //add answers to the polls
        $stmt = $pdo->prepare('INSERT INTO poll_answers (poll_id, title) VALUES (?, ?)');
        $stmt->execute([ $poll_id, $answer ]);
    }
    //output message
    $msg = 'Created Successfully!';
}
?>

<?=template_header('Create Poll')?>

<div class="content update">
    <h2>Create Poll</h2>
    <form action="create.php" method="post">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" placeholder="Title" required>
        <label for="description">Description</label>
        <input type="text" name="description" id="description" placeholder="Description">
        <label for="answers">Answers (per line)</label>
        <textarea name="answers" id="answers" placeholder="Description" required></textarea>
        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>