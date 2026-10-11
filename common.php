<?php
session_start();

const USER_DIR = __DIR__ . '/data/User';
const USER_FILE = USER_DIR . '/user.txt';
const BOOKING_FILE = __DIR__ . '/data/bookings.txt';
const CONTRIBUTION_FILE = __DIR__ . '/data/contributions.txt';
const PASSWORD_PATTERN = '/^(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/';

function h($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function ensure_data_store() {
    if (!is_dir(__DIR__ . '/data')) mkdir(__DIR__ . '/data', 0777, true);
    if (!is_dir(USER_DIR)) mkdir(USER_DIR, 0777, true);
}

function parse_record($line) {
    $record = [];
    foreach (explode('|', trim($line)) as $part) {
        $pair = explode(':', $part, 2);
        if (count($pair) === 2) $record[$pair[0]] = $pair[1];
    }
    return $record;
}

function read_users() {
    if (!file_exists(USER_FILE)) return [];
    $lines = file(USER_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return array_map('parse_record', $lines ?: []);
}

function find_user_by_email($email) {
    foreach (read_users() as $user) {
        if (isset($user['Email']) && strtolower($user['Email']) === strtolower($email)) return $user;
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

function read_bookings_for_email($email) {
    if (!file_exists(BOOKING_FILE)) return [];

    $matches = [];
    $lines = file(BOOKING_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines ?: [] as $index => $line) {
        $booking = parse_record($line);
        if (strtolower($booking['Email'] ?? '') === strtolower($email)) {
            $booking['_line'] = $index;
            $matches[] = $booking;
        }
    }

    return $matches;
}

function remove_booking_for_email($email, $lineIndex) {
    if (!file_exists(BOOKING_FILE) || !is_numeric($lineIndex)) return false;

    $lineIndex = (int) $lineIndex;
    $lines = file(BOOKING_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!isset($lines[$lineIndex])) return false;

    $booking = parse_record($lines[$lineIndex]);
    if (strtolower($booking['Email'] ?? '') !== strtolower($email)) return false;

    unset($lines[$lineIndex]);
    $content = $lines ? implode(PHP_EOL, $lines) . PHP_EOL : '';
    file_put_contents(BOOKING_FILE, $content);

    return true;
}

function facilities() {
    return [
        'Study Spaces' => [
            'intro' => 'Focused rooms for individual study, group assignments, peer tutoring and presentation practice.',
            'items' => [
                ['title' => 'Quiet Study Pod', 'desc' => 'Private booth for focused individual study between classes.', 'capacity' => '1 person', 'equipment' => 'Desk, power outlet, reading light', 'img' => 'img/facility-study-pod.png'],
                ['title' => 'Group Study Room A', 'desc' => 'Small teamwork room for assignments and revision sessions.', 'capacity' => '6 people', 'equipment' => 'Whiteboard, display screen, power outlets', 'img' => 'img/facility-group-study.png'],
                ['title' => 'Library Discussion Room', 'desc' => 'Low-noise discussion room close to library resources.', 'capacity' => '8 people', 'equipment' => 'Round table, whiteboard, Wi-Fi', 'img' => 'img/facility-library-discussion.png'],
                ['title' => 'Late Study Lounge', 'desc' => 'Flexible evening study area for students who need longer access.', 'capacity' => '20 people', 'equipment' => 'Shared tables, charging points, soft seating', 'img' => 'img/facility-study-lounge.png'],
                ['title' => 'Presentation Practice Room', 'desc' => 'Rehearsal space for speeches, pitches and group presentations.', 'capacity' => '10 people', 'equipment' => 'Projector, podium, timer display', 'img' => 'img/facility-presentation-room.png'],
                ['title' => 'Peer Tutoring Corner', 'desc' => 'Compact setup for mentoring, consultation and peer support.', 'capacity' => '4 people', 'equipment' => 'Small table, marker board, laptop stand', 'img' => 'img/facility-tutoring-corner.png'],
            ],
        ],
        'Event Spaces' => [
            'intro' => 'Larger spaces for club meetings, talks, showcases, exhibitions and campus activities.',
            'items' => [
                ['title' => 'Seminar Room', 'desc' => 'Classroom-style venue for workshops and guest sessions.', 'capacity' => '35 people', 'equipment' => 'Projector, speaker desk, microphones', 'img' => 'img/facility-seminar-room.png'],
                ['title' => 'Club Meeting Room', 'desc' => 'Round-table room for student society planning and meetings.', 'capacity' => '18 people', 'equipment' => 'Conference table, TV display, whiteboard', 'img' => 'img/facility-club-room.png'],
                ['title' => 'Innovation Hub', 'desc' => 'Open collaboration venue for project showcases and hackathons.', 'capacity' => '60 people', 'equipment' => 'Movable tables, display screens, pin-up boards', 'img' => 'img/facility-innovation-hub.png'],
                ['title' => 'Auditorium', 'desc' => 'Large venue for formal talks, launches and competitions.', 'capacity' => '180 people', 'equipment' => 'Stage, AV booth, fixed seating', 'img' => 'img/facility-auditorium.png'],
                ['title' => 'Outdoor Courtyard', 'desc' => 'Open-air space for fairs, pop-up booths and casual events.', 'capacity' => '100 people', 'equipment' => 'Outdoor power points, booth space, lighting', 'img' => 'img/facility-courtyard.png'],
                ['title' => 'Exhibition Booth Zone', 'desc' => 'Display area for posters, prototypes and society recruitment.', 'capacity' => '12 booths', 'equipment' => 'Booth tables, poster stands, power access', 'img' => 'img/facility-exhibition-zone.png'],
            ],
        ],
        'Specialist Facilities' => [
            'intro' => 'Purpose-built rooms for technical, creative, sporting and wellbeing activities.',
            'items' => [
                ['title' => 'Computer Lab', 'desc' => 'PC lab for coding classes, software practice and digital projects.', 'capacity' => '30 people', 'equipment' => 'Desktop PCs, projector, lab network', 'img' => 'img/facility-computer-lab.png'],
                ['title' => 'Media Studio', 'desc' => 'Recording room for video presentations, podcasts and interviews.', 'capacity' => '5 people', 'equipment' => 'Camera tripod, lights, audio recorder', 'img' => 'img/facility-media-studio.png'],
                ['title' => 'Makerspace Bench', 'desc' => 'Workbench area for prototyping, model building and repairs.', 'capacity' => '8 people', 'equipment' => 'Workbench, hand tools, storage trays', 'img' => 'img/facility-makerspace.png'],
                ['title' => 'Design Crit Room', 'desc' => 'Review room for design pin-ups, sketches and feedback sessions.', 'capacity' => '16 people', 'equipment' => 'Pin-up wall, movable stools, display monitor', 'img' => 'img/facility-design-crit.png'],
                ['title' => 'Sports Court', 'desc' => 'Indoor court for recreational student games and club training.', 'capacity' => '24 people', 'equipment' => 'Court markings, score board, basic sports gear', 'img' => 'img/facility-sports-court.png'],
                ['title' => 'Wellbeing Room', 'desc' => 'Calm room for peer support, mindfulness and small wellbeing events.', 'capacity' => '8 people', 'equipment' => 'Soft seating, privacy screen, ambient lighting', 'img' => 'img/facility-wellbeing-room.png'],
            ],
        ],
    ];
}

function booking_category_details() {
    return [
        'Study Spaces' => ['type' => 'Study Space Booking', 'price' => 'Free', 'venue' => 'Library and Learning Commons'],
        'Event Spaces' => ['type' => 'Event Space Booking', 'price' => 'RM 35', 'venue' => 'Campus Event Zone'],
        'Specialist Facilities' => ['type' => 'Specialist Facility Booking', 'price' => 'RM 25', 'venue' => 'Specialist Facilities Block'],
    ];
}

function bookable_spaces() {
    $categoryDetails = booking_category_details();

    $spaces = [];
    foreach (facilities() as $category => $group) {
        foreach ($group['items'] as $item) {
            $spaces[] = [
                'type' => $categoryDetails[$category]['type'],
                'title' => $item['title'],
                'datetime' => 'Selected by user during booking',
                'price' => $categoryDetails[$category]['price'],
                'venue' => $categoryDetails[$category]['venue'],
            ];
        }
    }

    return $spaces;
}

function find_bookable_space($title) {
    foreach (bookable_spaces() as $space) {
        if (strtolower($space['title']) === strtolower($title)) return $space;
    }
    return null;
}

function contributions() {
    return [
        'coding-night' => ['title' => 'Coding Night Setup', 'contributor' => 'Computing Club', 'related' => 'Computer Lab', 'media' => 'img/community-coding.png', 'desc' => 'A collaborative programming night hosted for first-year students.'],
        'design-showcase' => ['title' => 'Design Showcase', 'contributor' => 'Design Society', 'related' => 'Innovation Hub', 'media' => 'img/community-design.png', 'desc' => 'Student posters and prototypes displayed during project week.'],
        'debate-prep' => ['title' => 'Debate Practice', 'contributor' => 'Debate Club', 'related' => 'Presentation Practice Room', 'media' => 'img/community-debate.png', 'desc' => 'A room setup used for speaking practice and peer feedback.'],
    ];
}

function read_contributions() {
    if (!file_exists(CONTRIBUTION_FILE)) {
        return contributions();
    }

    $items = [];
    $lines = file(CONTRIBUTION_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines ?: [] as $line) {
        $record = parse_record($line);
        if (!empty($record['ID'])) {
            $items[$record['ID']] = [
                'title' => $record['Title'] ?? '',
                'contributor' => $record['Contributor'] ?? '',
                'related' => $record['Related'] ?? '',
                'media' => $record['Media'] ?? 'img/space.svg',
                'desc' => $record['Description'] ?? '',
            ];
        }
    }

    return $items ?: contributions();
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
        <a href="index.php">Home</a>
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
<footer class="footer"><p>&copy; 2026 CampusSpace. Assignment prototype for COS30020.</p></footer>
</body>
</html>
<?php
}
?>
