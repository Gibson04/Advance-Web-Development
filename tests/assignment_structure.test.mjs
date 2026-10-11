import assert from "node:assert/strict";
import { existsSync, readFileSync } from "node:fs";
import { join } from "node:path";

const root = process.cwd();
const files = [
  "index.php",
  "main_menu.php",
  "catalog.php",
  "activities.php",
  "community.php",
  "community_detail.php",
  "profile.php",
  "update_profile.php",
  "registration.php",
  "process_registration.php",
  "activity_reg.php",
  "login.php",
  "about.php",
];

for (const file of files) {
  assert.ok(existsSync(join(root, file)), `${file} should exist in the project root`);
}

for (const dir of ["style", "img", "data"]) {
  assert.ok(existsSync(join(root, dir)), `${dir}/ should exist`);
}

const index = readFileSync(join(root, "index.php"), "utf8");
assert.match(index, /\$_SESSION\[['"]user_email['"]\]/, "index.php hero actions should respond to logged-in state");
assert.match(index, /logout\.php/, "index.php should offer logout when a user is logged in");
assert.match(index, /facility-.*\.png/, "index.php should use real facility photos for the hero image");
assert.doesNotMatch(index, /hero-.*\.svg/, "index.php should not use the old SVG hero image set");

const mainMenu = readFileSync(join(root, "main_menu.php"), "utf8");
for (const target of ["catalog.php", "activities.php", "community.php", "smart_feature.php"]) {
  assert.ok(mainMenu.includes(target), `main_menu.php should link to ${target}`);
}

const registration = readFileSync(join(root, "registration.php"), "utf8");
assert.match(registration, /method=["']post["']/i, "registration form should use POST");
assert.match(registration, /name=["']email["']/i, "registration form should include email");
assert.doesNotMatch(registration, /type=["']email["']/i, "registration email should be text input");

const processRegistration = readFileSync(join(root, "process_registration.php"), "utf8");
assert.match(processRegistration, /preg_match/, "process_registration.php should validate fields");
assert.match(processRegistration, /PASSWORD_PATTERN/, "process_registration.php should enforce password complexity");
assert.match(processRegistration, /Email:/, "process_registration.php should save labelled email field");
assert.match(processRegistration, /\|/, "process_registration.php should use pipe-delimited records");

assert.ok(existsSync(join(root, "img", "profile.jpeg")), "profile photo should be stored in img/profile.jpeg");

const profile = readFileSync(join(root, "profile.php"), "utf8");
assert.match(profile, /img\/profile\.jpeg/, "profile.php should use the supplied profile photo");

const updateProfile = readFileSync(join(root, "update_profile.php"), "utf8");
assert.match(updateProfile, /img\/profile\.jpeg/, "update_profile.php should show the supplied profile photo");
assert.match(updateProfile, /My Bookings/, "update_profile.php should include a My Bookings section");
assert.match(updateProfile, /read_bookings_for_email/, "update_profile.php should read bookings for the logged-in user");
assert.match(updateProfile, /No bookings yet/, "update_profile.php should handle users with no bookings");
assert.match(updateProfile, /name=["']delete_booking["']/i, "update_profile.php should allow users to delete a booking");
assert.match(updateProfile, /Delete Booking/, "update_profile.php should show a delete booking button");
assert.match(updateProfile, /remove_booking_for_email/, "update_profile.php should delete bookings through the shared helper");

const activity = readFileSync(join(root, "activity_reg.php"), "utf8");
for (const field of ["first_name", "last_name", "contact", "email", "activity_datetime", "activity_title"]) {
  assert.ok(activity.includes(`name="${field}"`) || activity.includes(`name='${field}'`), `activity_reg.php should include ${field}`);
}
assert.match(activity, /name=["']payment_method["']/i, "activity_reg.php should include payment method");
assert.match(activity, /PaymentMethod:/, "activity_reg.php should save payment method");
assert.match(activity, /Price:/, "activity_reg.php should save booking price");
assert.match(activity, /Pay at Counter/, "activity_reg.php should offer Pay at Counter");
assert.match(activity, /id=["']payment_method["']/i, "payment method select should be addressable by JavaScript");
assert.match(activity, /id=["']summary-payment-method["']/i, "booking summary payment method should be addressable by JavaScript");
assert.match(activity, /addEventListener\(["']change["']/, "payment method changes should update the booking summary");
assert.match(activity, /class=["']datetime-field["']/i, "date and time input should use dedicated styling");

const css = readFileSync(join(root, "style/site.css"), "utf8");
assert.match(css, /datetime-field/, "site CSS should style the date and time field");
assert.match(css, /::-webkit-calendar-picker-indicator/, "site CSS should improve the date picker indicator");
assert.match(css, /button\.danger/, "site CSS should style destructive buttons");

const activities = readFileSync(join(root, "activities.php"), "utf8");
assert.match(activities, /<th>Price<\/th>/, "activities.php should label booking prices as Price");

const catalog = readFileSync(join(root, "catalog.php"), "utf8");
assert.match(catalog, /<strong>Price:<\/strong>/, "catalog.php should display each space price");

const common = readFileSync(join(root, "common.php"), "utf8");
assert.doesNotMatch(common, /RM\s+\d+\s*-\s*RM\s+\d+/, "bookable prices should be fixed prices, not ranges");
assert.match(common, /img\/community-coding\.png/, "coding contribution should use a real bitmap image");
assert.match(common, /img\/community-design\.png/, "design contribution should use a real bitmap image");
assert.match(common, /img\/community-debate\.png/, "debate contribution should use a real bitmap image");

for (const image of ["community-coding.png", "community-design.png", "community-debate.png"]) {
  assert.ok(existsSync(join(root, "img", image)), `${image} should exist in img/`);
}
assert.match(common, /function remove_booking_for_email/, "common.php should provide a safe booking removal helper");

for (const file of ["activities.php", "activity_reg.php", "catalog.php", "common.php"]) {
  const content = readFileSync(join(root, file), "utf8");
  assert.doesNotMatch(content, /n[e]tt/i, `${file} should use the plain Price label`);
}

const about = readFileSync(join(root, "about.php"), "utf8");
assert.match(about, /phpversion\(\)/, "about.php should output PHP version with phpversion()");
