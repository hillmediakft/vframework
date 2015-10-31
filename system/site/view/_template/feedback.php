<?php
$feedback_success = Message::get('success');
$feedback_error = Message::get('error');
$feedback_info = Message::get('info');
$feedback_warning = Message::get('warning');

// echo out positive messages
if (!empty($feedback_success)) {
    foreach ($feedback_success as $feedback) {
        echo '<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button">×</button>'.$feedback.'</div>';
    }
}

// echo out negative messages
if (!empty($feedback_error)) {
    foreach ($feedback_error as $feedback) {
        echo '<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button">×</button>'.$feedback.'</div>';
    }
}

// echo out notice messages
if (!empty($feedback_info)) {
    foreach ($feedback_info as $feedback) {
        echo '<div class="alert alert-info"><button class="close" data-dismiss="alert" type="button">×</button>'.$feedback.'</div>';
    }
}

// echo out notice messages
if (!empty($feedback_warning)) {
    foreach ($feedback_warning as $feedback) {
        echo '<div class="alert alert-warning"><button class="close" data-dismiss="alert" type="button">×</button>'.$feedback.'</div>';
    }
}

Message::clear();
?>