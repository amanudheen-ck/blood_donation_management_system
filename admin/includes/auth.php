<?php
// Shared bootstrap for every admin page: session, DB, CSRF, helpers.
session_start();
require_once __DIR__ . '/../../db_connect.php';

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function require_admin() {
    if (empty($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }
}

function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

// Call at the top of every POST handler.
function verify_csrf() {
    if (empty($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
        http_response_code(400);
        die("Invalid request (CSRF check failed).");
    }
}

function flash($msg = null, $type = 'success') {
    if ($msg !== null) {
        $_SESSION['flash'] = [$msg, $type];
        return;
    }
    if (!empty($_SESSION['flash'])) {
        [$m, $t] = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return "<div class='alert alert-" . e($t) . "'>" . e($m) . "</div>";
    }
    return '';
}

const BLOOD_TYPES = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
const REQUEST_STATUSES = ['Pending', 'Approved', 'Rejected'];
