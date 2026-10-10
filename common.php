<?php
session_start();

const USER_DIR = __DIR__ . '/data/User';
const USER_FILE = USER_DIR . '/user.txt';
const BOOKING_FILE = __DIR__ . '/data/bookings.txt';
const PASSWORD_PATTERN = '/^(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/';

function h($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function ensure_data_store() {
    if (!is_dir(USER_DIR)) {
        mkdir(USER_DIR, 0777, true);
    }
    if (!is_dir(__DIR__ . '/data')) {
        mkdir(__DIR__ . '/data', 0777, true);
    }
}

function parse_record($line) {
    $record = [];
    foreach (explode('|', trim($line)) as $part) {
        $pair = explode(':', $part, 2);
        if (count($pair) === 2) {
            $record[$pair[0]] = $pair[1];
        }
    }
    return $record;
}

function read_users() {
    if (!file_exists(USER_FILE)) {
        return [];
    }
    $lines = file(USER_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return array_map('parse_record', $lines ?: []);
}

function find_user_by_email($email) {
    foreach (read_users() as $user) {
        if (isset($user['Email']) && strtolower($user['Email']) === strtolower($email)) {
            return $user;
        }
    }
    return null;
}

function require_login() {
    if (empty($_SESSION['user_email'])) {
        header('Location: main_menu.php?login=required');
        exit;
    }
}

function current_user() {
    return empty($_SESSION['user_email']) ? null : find_user_by_email($_SESSION['user_email']);
}

function facilities() {
    return [
        'Study Spaces' => [
            ['title' => 'Quiet Study Pod', 'desc' => 'Private booth for focused solo work.', 'img' => 'img/space-study.svg'],
            ['title' => 'Group Study Room A', 'desc' => 'Whiteboard room for small teams.', 'img' => 'img/space-group.svg'],
            ['title' => 'Library Discussion Room', 'desc' => 'Low-noise meeting room near library resources.', 'img' => 'img/space-library.svg'],
            ['title' => 'Late Study Lounge', 'desc' => 'Flexible seating for evening study.', 'img' => 'img/space-lounge.svg'],
            ['title' => 'Presentation Practice Room', 'desc' => 'Screen and podium for rehearsals.', 'img' => 'img/space-presentation.svg'],
            ['title' => 'Peer Tutoring Corner', 'desc' => 'Compact table setup for mentoring.', 'img' => 'img/space-tutoring.svg'],
        ],
        'Event Spaces' => [
            ['title' => 'Seminar Room', 'desc' => 'Classroom seating for workshops.', 'img' => 'img/space-seminar.svg'],
            ['title' => 'Club Meeting Room', 'desc' => 'Round-table room for societies.', 'img' => 'img/space-club.svg'],
            ['title' => 'Innovation Hub', 'desc' => 'Open collaboration venue for showcases.', 'img' => 'img/space-innovation.svg'],
            ['title' => 'Auditorium', 'desc' => 'Large venue for guest talks.', 'img' => 'img/space-auditorium.svg'],
            ['title' => 'Outdoor Courtyard', 'desc' => 'Open-air space for campus events.', 'img' => 'img/space-courtyard.svg'],
            ['title' => 'Exhibition Booth Zone', 'desc' => 'Pop-up area for project displays.', 'img' => 'img/space-exhibition.svg'],
        ],
        'Specialist Facilities' => [
            ['title' => 'Computer Lab', 'desc' => 'PC lab for coding and design work.', 'img' => 'img/space-lab.svg'],
            ['title' => 'Media Studio', 'desc' => 'Recording room for audio and video.', 'img' => 'img/space-media.svg'],
            ['title' => 'Makerspace Bench', 'desc' => 'Workbench for prototyping.', 'img' => 'img/space-maker.svg'],
            ['title' => 'Design Crit Room', 'desc' => 'Pin-up walls for visual reviews.', 'img' => 'img/space-design.svg'],
            ['title' => 'Sports Court', 'desc' => 'Indoor court for student activities.', 'img' => 'img/space-court.svg'],
            ['title' => 'Wellbeing Room', 'desc' => 'Calm room for peer-support sessions.', 'img' => 'img/space-wellbeing.svg'],
        ],
    ];
}

function activities() {
    return [
        ['type' => 'Study Booking', 'title' => 'Group Study Room A', 'datetime' => '2026-10-14 10:00', 'price' => 'Free', 'venue' => 'Library Level 2'],
        ['type' => 'Workshop Space', 'title' => 'Seminar Room', 'datetime' => '2026-10-15 14:00', 'price' => 'RM 20', 'venue' => 'Block G, Room 203'],
        ['type' => 'Media Session', 'title' => 'Media Studio', 'datetime' => '2026-10-16 09:30', 'price' => 'RM 35', 'venue' => 'Creative Hub'],
        ['type' => 'Event Venue', 'title' => 'Innovation Hub', 'datetime' => '2026-10-18 13:00', 'price' => 'RM 50', 'venue' => 'Innovation Centre'],
        ['type' => 'Sports Booking', 'title' => 'Sports Court', 'datetime' => '2026-10-19 17:00', 'price' => 'RM 15', 'venue' => 'Sports Complex'],
    ];
}

function contributions() {
    return [
        'coding-night' => ['title' => 'Coding Night Setup', 'contributor' => 'Computing Club', 'related' => 'Computer Lab', 'media' => 'img/community-coding.svg', 'desc' => 'A collaborative programming night hosted for first-year students.'],
        'design-showcase' => ['title' => 'Design Showcase', 'contributor' => 'Design Society', 'related' => 'Innovation Hub', 'media' => 'img/community-design.svg', 'desc' => 'Student posters and prototypes displayed during project week.'],
        'debate-prep' => ['title' => 'Debate Practice', 'contributor' => 'Debate Club', 'related' => 'Presentation Practice Room', 'media' => 'img/community-debate.svg', 'desc' => 'A room setup used for speaking practice and peer feedback.'],
    ];
}

function page_header($title) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo h($title); ?> | CampusSpace</title>
    <link rel="stylesheet" href="style/site.css">
</head>
<body>
<header class="topbar">
    <a class="brand" href="index.php">CampusSpace</a>
    <nav>
        <a href="main_menu.php">Menu</a>
        <a href="catalog.php">Facilities</a>
        <a href="activities.php">Bookings</a>
        <a href="community.php">Community</a>
        
        <a href="profile.php">Student Profile</a>
<?php if (!empty($_SESSION['user_email'])): ?>
            <a href="update_profile.php">View Profile</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="registration.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
<main>
<?php
}

function page_footer() {
?>
</main>
<footer class="footer">
    <p>&copy; 2026 CampusSpace. Assignment prototype for COS30020.</p>
</footer>
</body>
</html>
<?php
}
?>
