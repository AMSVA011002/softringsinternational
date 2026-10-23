<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/json; charset=UTF-8');

$action = (string) ($_GET['action'] ?? $_POST['action'] ?? '');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

function json_reply(int $code, array $data): never
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($action === 'status' && $method === 'GET') {
    $branches = get_branches();
    $openList = [];
    foreach ($branches as $b) {
        $st = get_branch_live_status($b);
        if ($st['is_open']) {
            $openList[] = $b['city'];
        }
    }
    json_reply(200, [
        'ok' => true,
        'colombo_time' => date('h:i:s A'),
        'colombo_date' => date('D, d M Y'),
        'open_count' => count($openList),
        'total_branches' => count($branches),
        'open_cities' => $openList,
    ]);
}

if ($action === 'branches' && $method === 'GET') {
    $province = trim((string) ($_GET['province'] ?? 'all'));
    $query = mb_strtolower(trim((string) ($_GET['q'] ?? '')));
    $result = [];

    foreach (get_branches() as $b) {
        if ($province !== 'all' && strcasecmp($b['province'], $province) !== 0) {
            continue;
        }
        if ($query !== '') {
            $haystack = mb_strtolower($b['name'] . ' ' . $b['city'] . ' ' . $b['address'] . ' ' . implode(' ', $b['services']));
            if (!str_contains($haystack, $query)) {
                continue;
            }
        }
        $b['live_status'] = get_branch_live_status($b);
        $result[] = $b;
    }

    json_reply(200, [
        'ok' => true,
        'count' => count($result),
        'branches' => $result,
    ]);
}

if ($action === 'ticket' && $method === 'GET') {
    $ref = trim((string) ($_GET['ref'] ?? ''));
    if ($ref === '') {
        json_reply(400, ['ok' => false, 'error' => 'Please enter a valid reference code (e.g. SFT-2026-XXXXX).']);
    }
    $record = find_reference_record($ref);
    if ($record === null) {
        json_reply(404, [
            'ok' => false,
            'error' => 'No inquiry or HR application matched reference "' . strtoupper($ref) . '". Please verify the code and try again.',
        ]);
    }
    json_reply(200, [
        'ok' => true,
        'record' => $record,
    ]);
}

if ($action === 'contact' && $method === 'POST') {
    $raw = file_get_contents('php://input');
    $input = !empty($_POST) ? $_POST : (json_decode((string) $raw, true) ?: []);

    if (!verify_csrf((string) ($input['csrf_token'] ?? ''))) {
        json_reply(403, ['ok' => false, 'error' => 'Security session expired. Please refresh the page and submit again.']);
    }

    $fullName = trim((string) ($input['full_name'] ?? ''));
    $companyName = trim((string) ($input['company_name'] ?? ''));
    $inquiryType = trim((string) ($input['inquiry_type'] ?? 'General Corporate Inquiry'));
    $phone = trim((string) ($input['phone'] ?? ''));
    $email = trim((string) ($input['email'] ?? ''));
    $branch = trim((string) ($input['preferred_branch'] ?? 'Colombo Corporate Office — Maharagama'));
    $message = trim((string) ($input['message'] ?? ''));

    if (mb_strlen($fullName) < 2) {
        json_reply(422, ['ok' => false, 'error' => 'Please enter your full name.']);
    }
    if (!preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
        json_reply(422, ['ok' => false, 'error' => 'Please provide a valid Sri Lankan or international contact number.']);
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        json_reply(422, ['ok' => false, 'error' => 'Please provide a valid business or personal email address.']);
    }
    if (mb_strlen($message) < 10) {
        json_reply(422, ['ok' => false, 'error' => 'Please include brief details of your requirement (at least 10 characters).']);
    }

    $saved = save_corporate_inquiry([
        'inquiry_type' => $inquiryType,
        'full_name' => $fullName,
        'company_name' => $companyName,
        'phone' => $phone,
        'email' => $email,
        'preferred_branch' => $branch,
        'message' => $message,
    ]);

    json_reply(200, [
        'ok' => true,
        'message' => 'Thank you. Your inquiry has been logged with the Softrings International Corporate Desk.',
        'ref_code' => $saved['ref_code'],
        'status' => $saved['status'],
        'created_at' => $saved['created_at'],
    ]);
}

if ($action === 'apply' && $method === 'POST') {
    $raw = file_get_contents('php://input');
    $input = !empty($_POST) ? $_POST : (json_decode((string) $raw, true) ?: []);

    if (!verify_csrf((string) ($input['csrf_token'] ?? ''))) {
        json_reply(403, ['ok' => false, 'error' => 'Security session expired. Please refresh the page and submit again.']);
    }

    $jobCode = trim((string) ($input['job_code'] ?? 'SFT-GEN'));
    $jobTitle = trim((string) ($input['job_title'] ?? 'General Talent Pool'));
    $fullName = trim((string) ($input['full_name'] ?? ''));
    $phone = trim((string) ($input['phone'] ?? ''));
    $email = trim((string) ($input['email'] ?? ''));
    $district = trim((string) ($input['district'] ?? 'Colombo District'));
    $experience = trim((string) ($input['experience_years'] ?? '0-1 Years'));
    $summary = trim((string) ($input['summary'] ?? ''));

    if (mb_strlen($fullName) < 2) {
        json_reply(422, ['ok' => false, 'error' => 'Please enter your full name.']);
    }
    if (!preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
        json_reply(422, ['ok' => false, 'error' => 'Please provide a valid contact phone number.']);
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        json_reply(422, ['ok' => false, 'error' => 'Please provide a valid email address.']);
    }
    if (mb_strlen($summary) < 12) {
        json_reply(422, ['ok' => false, 'error' => 'Please include a short summary of your qualifications or experience.']);
    }

    $saved = save_career_application([
        'job_code' => $jobCode,
        'job_title' => $jobTitle,
        'full_name' => $fullName,
        'phone' => $phone,
        'email' => $email,
        'district' => $district,
        'experience_years' => $experience,
        'summary' => $summary,
    ]);

    json_reply(200, [
        'ok' => true,
        'message' => 'Application recorded with the Softrings Human Resources Department.',
        'ref_code' => $saved['ref_code'],
        'status' => $saved['status'],
        'created_at' => $saved['created_at'],
    ]);
}

json_reply(400, ['ok' => false, 'error' => 'Unsupported API action.']);
